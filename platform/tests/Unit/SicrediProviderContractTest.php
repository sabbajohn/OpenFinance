<?php

namespace Tests\Unit;

use DateInterval;
use DateTimeImmutable;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Psr\SimpleCache\CacheInterface;
use Sabba\OpenFinance\Core\DTO\ConnectionContext;
use Sabba\OpenFinance\Core\DTO\Money;
use Sabba\OpenFinance\Core\DTO\PixReceiptQuery;
use Sabba\OpenFinance\Core\DTO\ReceivableCommand;
use Sabba\OpenFinance\Core\DTO\TransactionQuery;
use Sabba\OpenFinance\Core\Enums\TransactionDirection;
use Sabba\OpenFinance\Sicredi\SicrediHttpClient;
use Sabba\OpenFinance\Sicredi\SicrediProvider;
use Sabba\OpenFinance\Sicredi\SicrediProviderException;

class SicrediProviderContractTest extends TestCase
{
    public function test_it_uses_http_basic_and_returns_safe_authentication_diagnostics(): void
    {
        $history = [];
        $mock = new MockHandler([
            new Response(200, ['Content-Type' => 'application/json'], json_encode([
                'access_token' => 'sensitive-token',
                'expires_in' => 3599,
                'token_type' => 'Bearer',
                'scope' => 'cob.read cob.write',
            ], JSON_THROW_ON_ERROR)),
        ]);
        $handler = HandlerStack::create($mock);
        $handler->push(Middleware::history($history));
        $client = new SicrediHttpClient(new ArrayPsrCache, handler: $handler);

        $result = $client->testAuthentication($this->context(), 'pix');

        $this->assertSame('Bearer', $result['token_type']);
        $this->assertSame(3599, $result['expires_in']);
        $this->assertSame(['cob.read', 'cob.write'], $result['scope']);
        $this->assertArrayNotHasKey('access_token', $result);

        $request = $history[0]['request'];
        $this->assertSame('Basic '.base64_encode('fixture-client:fixture-secret'), $request->getHeaderLine('Authorization'));
        parse_str((string) $request->getBody(), $body);
        $this->assertSame('client_credentials', $body['grant_type']);
        $this->assertArrayNotHasKey('client_id', $body);
        $this->assertArrayNotHasKey('client_secret', $body);
    }

    public function test_it_normalizes_sicredi_fixtures_and_serializes_money_exactly(): void
    {
        $history = [];
        $mock = new MockHandler([
            $this->fixtureResponse('oauth-token.json'),
            $this->fixtureResponse('accounts.json'),
            $this->fixtureResponse('transactions.json'),
            $this->fixtureResponse('oauth-token.json'),
            $this->fixtureResponse('pix-created.json'),
            $this->fixtureResponse('pix-received-list.json'),
            $this->fixtureResponse('pix-received.json'),
        ]);
        $handler = HandlerStack::create($mock);
        $handler->push(Middleware::history($history));
        $provider = new SicrediProvider(new SicrediHttpClient(new ArrayPsrCache, handler: $handler));
        $context = $this->context();

        $accounts = iterator_to_array($provider->accounts($context));
        $this->assertCount(1, $accounts);
        $this->assertSame('account-1', $accounts[0]->externalId);
        $this->assertSame('******7890', $accounts[0]->numberMasked);

        $page = $provider->transactions(new TransactionQuery(
            context: $context,
            accountExternalId: 'account-1',
            from: new DateTimeImmutable('2026-08-01T00:00:00Z'),
            to: new DateTimeImmutable('2026-08-12T23:59:59Z'),
        ));
        $this->assertSame('next-page', $page->nextCursor);
        $this->assertSame(1025, $page->transactions[0]->amount->minor);
        $this->assertSame(TransactionDirection::Credit, $page->transactions[0]->direction);
        $this->assertSame('E74820260812000000001', $page->transactions[0]->identifiers['end_to_end_id']);

        $pix = $provider->createPix($context, new ReceivableCommand(
            idempotencyKey: 'fixture-idempotency',
            reference: 'erp-title-1',
            amount: new Money(1234),
            dueAt: null,
        ));
        $this->assertSame('1524069360e472ac7e91b11f7ee72d1c', $pix->externalId);
        $this->assertSame('active', $pix->status);
        $this->assertSame(1234, $pix->amount->minor);
        $this->assertSame('000201010212fixture', $pix->copyAndPaste);

        $pixRequest = $history[4]['request'];
        $this->assertSame('PUT', $pixRequest->getMethod());
        $this->assertSame('/cob/1524069360e472ac7e91b11f7ee72d1c', $pixRequest->getUri()->getPath());
        $this->assertStringContainsString('"original":"12.34"', (string) $pixRequest->getBody());
        $this->assertStringContainsString('"modalidadeAlteracao":0', (string) $pixRequest->getBody());
        $this->assertStringContainsString('"chave":"financeiro@example.test"', (string) $pixRequest->getBody());
        $this->assertSame('Bearer fixture-access-token', $pixRequest->getHeaderLine('Authorization'));

        $received = $provider->receivedPix(new PixReceiptQuery(
            context: $context,
            from: new DateTimeImmutable('2026-08-01T00:00:00Z'),
            to: new DateTimeImmutable('2026-08-12T23:59:59Z'),
            payerTaxId: '12.345.678/0001-90',
        ));
        $this->assertSame('1', $received->nextCursor);
        $this->assertCount(1, $received->transactions);
        $this->assertSame('E74820260812000000000000001', $received->transactions[0]->externalId);
        $this->assertSame(1234, $received->transactions[0]->amount->minor);
        $this->assertSame('1524069360e472ac7e91b11f7ee72d1c', $received->transactions[0]->identifiers['txid']);
        $this->assertSame('12345678000190', $received->transactions[0]->counterpartyTaxId);

        $receivedRequest = $history[5]['request'];
        $this->assertSame('/pix', $receivedRequest->getUri()->getPath());
        parse_str($receivedRequest->getUri()->getQuery(), $receivedQuery);
        $this->assertSame('12345678000190', $receivedQuery['cnpj']);
        $this->assertSame('0', $receivedQuery['paginacao_paginaAtual'] ?? $receivedQuery['paginacao']['paginaAtual'] ?? null);

        $byId = $provider->receivedPixById($context, 'E74820260812000000000000001');
        $this->assertSame('E74820260812000000000000001', $byId->externalId);
        $this->assertSame('Cliente Fixture', $byId->counterpartyName);
        $this->assertSame('/pix/E74820260812000000000000001', $history[6]['request']->getUri()->getPath());
    }

    public function test_it_exposes_sicredi_problem_details_without_leaking_tokens(): void
    {
        $mock = new MockHandler([
            $this->fixtureResponse('oauth-token.json'),
            new Response(403, ['Content-Type' => 'application/problem+json'], json_encode([
                'type' => 'https://pix.bcb.gov.br/api/v2/error/AcessoNegado',
                'title' => 'Acesso Negado',
                'status' => 403,
                'detail' => 'Certificado incompatível com a credencial.',
            ], JSON_THROW_ON_ERROR)),
        ]);
        $client = new SicrediHttpClient(new ArrayPsrCache, handler: HandlerStack::create($mock));

        try {
            $client->request($this->context(), 'pix', 'GET', '/cob/fixture');
            $this->fail('A resposta 403 deveria lançar uma exceção.');
        } catch (SicrediProviderException $exception) {
            $this->assertSame('Certificado incompatível com a credencial.', $exception->getMessage());
            $this->assertSame(403, $exception->responseStatus);
            $this->assertSame('https://pix.bcb.gov.br/api/v2/error/AcessoNegado', $exception->providerCode);
            $this->assertStringNotContainsString('fixture-access-token', $exception->getMessage());
        }
    }

    public function test_it_creates_reads_refunds_and_filters_sicredi_pix_with_the_official_contract(): void
    {
        $history = [];
        $handler = HandlerStack::create(new MockHandler([
            $this->fixtureResponse('oauth-token.json'),
            new Response(201, ['Content-Type' => 'application/json'], json_encode([
                'txid' => 'ERP98765432109876543210987',
                'status' => 'ATIVA',
                'valor' => ['original' => '45.67'],
                'pixCopiaECola' => '000201010212sicrediduefixture',
            ], JSON_THROW_ON_ERROR)),
            new Response(200, ['Content-Type' => 'application/json'], json_encode([
                'txid' => 'ERP98765432109876543210987',
                'status' => 'CONCLUIDA',
                'valor' => ['original' => '45.67'],
                'pix' => [['horario' => '2026-08-21T18:00:00Z']],
            ], JSON_THROW_ON_ERROR)),
            new Response(201, ['Content-Type' => 'application/json'], json_encode([
                'id' => 'REFUND123',
                'status' => 'EM_PROCESSAMENTO',
                'valor' => '5.00',
            ], JSON_THROW_ON_ERROR)),
            new Response(200, ['Content-Type' => 'application/json'], json_encode([
                'parametros' => ['paginacao' => ['paginaAtual' => 0, 'quantidadeDePaginas' => 1]],
                'pix' => [],
            ], JSON_THROW_ON_ERROR)),
        ]));
        $handler->push(Middleware::history($history));
        $provider = new SicrediProvider(new SicrediHttpClient(new ArrayPsrCache, handler: $handler));
        $context = $this->context();

        $created = $provider->createPix($context, new ReceivableCommand(
            idempotencyKey: 'fixture-due-idempotency',
            reference: 'ERP98765432109876543210987',
            amount: new Money(4567),
            dueAt: new DateTimeImmutable('2026-09-20'),
            payer: ['cnpj' => '12.ABC.678/0001-DE', 'nome' => 'Empresa Teste'],
        ));
        $paid = $provider->getPix($context, 'ERP98765432109876543210987', 'due');
        $refund = $provider->refundPix(
            $context,
            'E748202608210000000000000000001',
            'REFUND123',
            new Money(500),
        );
        $provider->receivedPix(new PixReceiptQuery(
            context: $context,
            from: new DateTimeImmutable('2026-08-21T00:00:00Z'),
            to: new DateTimeImmutable('2026-08-21T23:59:59Z'),
            payerTaxId: '12.ABC.678/0001-DE',
        ));

        $this->assertSame('active', $created->status);
        $this->assertSame('000201010212sicrediduefixture', $created->copyAndPaste);
        $this->assertSame('paid', $paid->status);
        $this->assertSame('2026-08-21T18:00:00+00:00', $paid->paidAt?->format(DATE_ATOM));
        $this->assertSame('REFUND123', $refund->externalId);
        $this->assertSame('pending', $refund->status);

        $this->assertSame('PUT', $history[1]['request']->getMethod());
        $this->assertSame('/cobv/ERP98765432109876543210987', $history[1]['request']->getUri()->getPath());
        $duePayload = json_decode((string) $history[1]['request']->getBody(), true, flags: JSON_THROW_ON_ERROR);
        $this->assertSame(['dataDeVencimento' => '2026-09-20', 'validadeAposVencimento' => 30], $duePayload['calendario']);
        $this->assertSame(['original' => '45.67'], $duePayload['valor']);
        $this->assertSame(['cnpj' => '12ABC6780001DE', 'nome' => 'Empresa Teste'], $duePayload['devedor']);
        $this->assertSame('GET', $history[2]['request']->getMethod());
        $this->assertSame('/cobv/ERP98765432109876543210987', $history[2]['request']->getUri()->getPath());
        $this->assertSame('/pix/E748202608210000000000000000001/devolucao/REFUND123', $history[3]['request']->getUri()->getPath());
        $this->assertStringContainsString('"valor":"5.00"', (string) $history[3]['request']->getBody());
        parse_str($history[4]['request']->getUri()->getQuery(), $receivedQuery);
        $this->assertSame('12ABC6780001DE', $receivedQuery['cnpj']);
        $this->assertSame('2026-08-21T00:00:00.000+00:00', $receivedQuery['inicio']);
        $this->assertSame('2026-08-21T23:59:59.000+00:00', $receivedQuery['fim']);
    }

    public function test_it_authenticates_and_creates_a_sicredi_billing_boleto(): void
    {
        $history = [];
        $mock = new MockHandler([
            new Response(200, ['Content-Type' => 'application/json'], json_encode([
                'access_token' => 'fixture-billing-token',
                'expires_in' => 300,
                'token_type' => 'Bearer',
                'scope' => 'cobranca',
            ], JSON_THROW_ON_ERROR)),
            new Response(201, ['Content-Type' => 'application/json'], json_encode([
                'txid' => 'sandbox-default-txid',
                'qrCode' => 'sandbox-default-qr-code',
                'linhaDigitavel' => '74891125110061420512803153351030188640000050000',
                'codigoBarras' => '74891886400000500001125100614205120315335103',
                'nossoNumero' => '251006142',
            ], JSON_THROW_ON_ERROR)),
        ]);
        $handler = HandlerStack::create($mock);
        $handler->push(Middleware::history($history));
        $provider = new SicrediProvider(new SicrediHttpClient(new ArrayPsrCache, handler: $handler));

        $boleto = $provider->createBoleto($this->billingContext(), new ReceivableCommand(
            idempotencyKey: 'fixture-boleto',
            reference: 'NF123456',
            amount: new Money(50000),
            dueAt: new DateTimeImmutable('2026-09-10'),
            payer: ['cpf' => '12345678909', 'nome' => 'Cliente Fixture'],
            options: ['subtype' => 'hybrid'],
        ));

        $this->assertSame('251006142', $boleto->externalId);
        $this->assertSame('74891125110061420512803153351030188640000050000', $boleto->digitableLine);
        $this->assertSame('sandbox-default-qr-code', $boleto->copyAndPaste);

        $tokenRequest = $history[0]['request'];
        $this->assertSame('fixture-x-api-key', $tokenRequest->getHeaderLine('x-api-key'));
        $this->assertSame('COBRANCA', $tokenRequest->getHeaderLine('context'));
        parse_str((string) $tokenRequest->getBody(), $tokenBody);
        $this->assertSame('password', $tokenBody['grant_type']);
        $this->assertSame('123456789', $tokenBody['username']);

        $createRequest = $history[1]['request'];
        $this->assertSame('Bearer fixture-billing-token', $createRequest->getHeaderLine('Authorization'));
        $this->assertSame('fixture-x-api-key', $createRequest->getHeaderLine('x-api-key'));
        $this->assertSame('6789', $createRequest->getHeaderLine('cooperativa'));
        $this->assertSame('03', $createRequest->getHeaderLine('posto'));
        $payload = json_decode((string) $createRequest->getBody(), true, flags: JSON_THROW_ON_ERROR);
        $this->assertSame('12345', $payload['codigoBeneficiario']);
        $this->assertSame('HIBRIDO', $payload['tipoCobranca']);
        $this->assertSame('500.00', $payload['valor']);
        $this->assertSame('PESSOA_FISICA', $payload['pagador']['tipoPessoa']);
    }

    public function test_it_renews_sicredi_billing_access_with_the_refresh_token(): void
    {
        $history = [];
        $mock = new MockHandler([
            new Response(200, ['Content-Type' => 'application/json'], json_encode([
                'access_token' => 'first-access-token',
                'refresh_token' => 'first-refresh-token',
                'expires_in' => 300,
                'refresh_expires_in' => 1800,
                'token_type' => 'Bearer',
                'scope' => 'cobranca profile email',
            ], JSON_THROW_ON_ERROR)),
            new Response(200, ['Content-Type' => 'application/json'], json_encode([
                'nossoNumero' => '251006142',
                'valorNominal' => 500,
                'situacao' => 'EM_CARTEIRA',
            ], JSON_THROW_ON_ERROR)),
            new Response(200, ['Content-Type' => 'application/json'], json_encode([
                'access_token' => 'renewed-access-token',
                'refresh_token' => 'renewed-refresh-token',
                'expires_in' => 300,
                'refresh_expires_in' => 1800,
                'token_type' => 'Bearer',
                'scope' => 'cobranca profile email',
            ], JSON_THROW_ON_ERROR)),
            new Response(200, ['Content-Type' => 'application/json'], json_encode([
                'nossoNumero' => '251006142',
                'valorNominal' => 500,
                'situacao' => 'EM_CARTEIRA',
            ], JSON_THROW_ON_ERROR)),
        ]);
        $handler = HandlerStack::create($mock);
        $handler->push(Middleware::history($history));
        $cache = new ArrayPsrCache;
        $provider = new SicrediProvider(new SicrediHttpClient($cache, handler: $handler));
        $context = $this->billingContext();

        $boleto = $provider->getBoleto($context, '251006142');
        $this->assertSame('251006142', $boleto->externalId);
        $this->assertSame('active', $boleto->status);
        $this->assertSame(50000, $boleto->amount->minor);

        $tokenCacheKey = 'sicredi:token:'.hash('sha256', implode('|', [
            $context->connectionId,
            'boleto',
            '123456789',
            'https://sicredi.fixture.test/sb/auth/openapi/token',
        ]));
        $cache->delete($tokenCacheKey.':access');

        $provider->getBoleto($context, '251006142');

        parse_str((string) $history[2]['request']->getBody(), $refreshBody);
        $this->assertSame('refresh_token', $refreshBody['grant_type']);
        $this->assertSame('first-refresh-token', $refreshBody['refresh_token']);
        $this->assertArrayNotHasKey('username', $refreshBody);
        $this->assertArrayNotHasKey('password', $refreshBody);
        $this->assertArrayNotHasKey('scope', $refreshBody);
        $this->assertSame('fixture-x-api-key', $history[2]['request']->getHeaderLine('x-api-key'));
        $this->assertSame('COBRANCA', $history[2]['request']->getHeaderLine('context'));
        $this->assertSame('Bearer renewed-access-token', $history[3]['request']->getHeaderLine('Authorization'));
    }

    private function fixtureResponse(string $name): Response
    {
        return new Response(
            200,
            ['Content-Type' => 'application/json'],
            (string) file_get_contents(__DIR__.'/../Fixtures/Sicredi/'.$name),
        );
    }

    private function context(): ConnectionContext
    {
        $product = [
            'base_url' => 'https://sicredi.fixture.test/',
            'token_url' => 'https://sicredi.fixture.test/oauth/token',
            'client_id' => 'fixture-client',
            'client_secret' => 'fixture-secret',
        ];

        return new ConnectionContext(
            connectionId: '019ff716-1d34-7151-b320-f34e87ca26ab',
            companyId: '019ff716-1d34-7151-b320-f34e87ca26ac',
            environment: 'sandbox',
            credentials: [
                'default_pix_key' => 'financeiro@example.test',
                'products' => ['accounts' => $product, 'pix' => $product],
            ],
        );
    }

    private function billingContext(): ConnectionContext
    {
        return new ConnectionContext(
            connectionId: '019ff716-1d34-7151-b320-f34e87ca26ab',
            companyId: '019ff716-1d34-7151-b320-f34e87ca26ac',
            environment: 'sandbox',
            credentials: [
                'products' => [
                    'boleto' => [
                        'base_url' => 'https://sicredi.fixture.test/sb/cobranca/boleto/v1/',
                        'token_url' => 'https://sicredi.fixture.test/sb/auth/openapi/token',
                        'grant_type' => 'password',
                        'username' => '123456789',
                        'password' => 'teste123',
                        'scope' => 'cobranca',
                        'api_key' => 'fixture-x-api-key',
                        'token_headers' => ['context' => 'COBRANCA'],
                        'headers' => ['cooperativa' => '6789', 'posto' => '03'],
                        'beneficiary_code' => '12345',
                        'paths' => ['boletos' => '/boletos'],
                    ],
                ],
            ],
        );
    }
}

final class ArrayPsrCache implements CacheInterface
{
    /** @var array<string,mixed> */
    private array $items = [];

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->items[$key] ?? $default;
    }

    public function set(string $key, mixed $value, DateInterval|int|null $ttl = null): bool
    {
        $this->items[$key] = $value;

        return true;
    }

    public function delete(string $key): bool
    {
        unset($this->items[$key]);

        return true;
    }

    public function clear(): bool
    {
        $this->items = [];

        return true;
    }

    public function getMultiple(iterable $keys, mixed $default = null): iterable
    {
        foreach ($keys as $key) {
            yield $key => $this->get((string) $key, $default);
        }
    }

    public function setMultiple(iterable $values, DateInterval|int|null $ttl = null): bool
    {
        foreach ($values as $key => $value) {
            $this->set((string) $key, $value, $ttl);
        }

        return true;
    }

    public function deleteMultiple(iterable $keys): bool
    {
        foreach ($keys as $key) {
            $this->delete((string) $key);
        }

        return true;
    }

    public function has(string $key): bool
    {
        return array_key_exists($key, $this->items);
    }
}
