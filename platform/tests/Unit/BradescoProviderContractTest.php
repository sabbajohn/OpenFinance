<?php

namespace Tests\Unit;

use DateInterval;
use DateTimeImmutable;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;
use Psr\SimpleCache\CacheInterface;
use Sabba\OpenFinance\Bradesco\BradescoHttpClient;
use Sabba\OpenFinance\Bradesco\BradescoProvider;
use Sabba\OpenFinance\Core\DTO\ConnectionContext;
use Sabba\OpenFinance\Core\DTO\Money;
use Sabba\OpenFinance\Core\DTO\PixReceiptQuery;
use Sabba\OpenFinance\Core\DTO\ReceivableCommand;

class BradescoProviderContractTest extends TestCase
{
    public function test_it_authenticates_with_form_credentials_without_exposing_the_token(): void
    {
        $history = [];
        $handler = HandlerStack::create(new MockHandler([$this->fixtureResponse('oauth-token.json')]));
        $handler->push(Middleware::history($history));
        $client = new BradescoHttpClient(new BradescoArrayPsrCache, handler: $handler);

        $result = $client->testAuthentication($this->context(), 'pix');

        $this->assertSame('Bearer', $result['token_type']);
        $this->assertSame(3599, $result['expires_in']);
        $this->assertSame([], $result['scope']);
        $this->assertArrayNotHasKey('access_token', $result);
        $this->assertSame('', $history[0]['request']->getHeaderLine('Authorization'));
        parse_str((string) $history[0]['request']->getBody(), $body);
        $this->assertSame('client_credentials', $body['grant_type']);
        $this->assertSame('bradesco-client', $body['client_id']);
        $this->assertSame('bradesco-secret', $body['client_secret']);
        $this->assertArrayNotHasKey('scope', $body);
    }

    public function test_it_creates_reads_and_refunds_pix_using_bradesco_paths(): void
    {
        $history = [];
        $handler = HandlerStack::create(new MockHandler([
            $this->fixtureResponse('oauth-token.json'),
            $this->fixtureResponse('pix-created.json'),
            $this->fixtureResponse('pix-paid.json'),
            $this->fixtureResponse('pix-refund.json'),
        ]));
        $handler->push(Middleware::history($history));
        $provider = new BradescoProvider(new BradescoHttpClient(new BradescoArrayPsrCache, handler: $handler));
        $context = $this->context();

        $created = $provider->createPix($context, new ReceivableCommand(
            idempotencyKey: 'fixture-idempotency',
            reference: 'ERP12345678901234567890123',
            amount: new Money(1234),
            dueAt: null,
        ));
        $paid = $provider->getPix($context, 'ERP12345678901234567890123');
        $refund = $provider->refundPix(
            $context,
            'E237202608130000000000000000001',
            'REFUND123',
            new Money(500),
        );

        $this->assertSame('ERP12345678901234567890123', $created->externalId);
        $this->assertSame(1234, $created->amount->minor);
        $this->assertSame('000201010212bradescofixture', $created->copyAndPaste);
        $this->assertSame('paid', $paid->status);
        $this->assertSame('2026-08-13T12:01:00+00:00', $paid->paidAt?->format(DATE_ATOM));
        $this->assertSame('REFUND123', $refund->externalId);
        $this->assertSame(500, $refund->amount->minor);

        $this->assertSame('PUT', $history[1]['request']->getMethod());
        $this->assertSame('/v2/cob/ERP12345678901234567890123', $history[1]['request']->getUri()->getPath());
        $this->assertStringContainsString('"valor":{"original":"12.34","modalidadeAlteracao":0}', (string) $history[1]['request']->getBody());
        $this->assertStringContainsString('"calendario":{"expiracao":3600}', (string) $history[1]['request']->getBody());
        $this->assertStringContainsString('"chave":"financeiro@example.test"', (string) $history[1]['request']->getBody());
        $this->assertSame('GET', $history[2]['request']->getMethod());
        $this->assertSame('/v2/cob/ERP12345678901234567890123', $history[2]['request']->getUri()->getPath());
        $this->assertSame('/v2/pix/E237202608130000000000000000001/devolucao/REFUND123', $history[3]['request']->getUri()->getPath());
        $this->assertStringContainsString('"valor":"5.00"', (string) $history[3]['request']->getBody());
    }

    public function test_it_creates_due_pix_with_the_bradesco_contract(): void
    {
        $history = [];
        $handler = HandlerStack::create(new MockHandler([
            $this->fixtureResponse('oauth-token.json'),
            $this->fixtureResponse('pix-due-created.json'),
            $this->fixtureResponse('pix-due-paid.json'),
        ]));
        $handler->push(Middleware::history($history));
        $provider = new BradescoProvider(new BradescoHttpClient(new BradescoArrayPsrCache, handler: $handler));

        $result = $provider->createPix($this->context(), new ReceivableCommand(
            idempotencyKey: 'fixture-idempotency',
            reference: 'ERP98765432109876543210987',
            amount: new Money(4567),
            dueAt: new DateTimeImmutable('2026-08-20'),
            payer: ['cpf' => '12345678901', 'nome' => 'Cliente Teste'],
        ));
        $paid = $provider->getPix($this->context(), 'ERP98765432109876543210987', 'due');

        $this->assertSame('ERP98765432109876543210987', $result->externalId);
        $this->assertSame(4567, $result->amount->minor);
        $this->assertSame('000201010212bradescoduefixture', $result->copyAndPaste);
        $this->assertSame('/v2/cobv/ERP98765432109876543210987', $history[1]['request']->getUri()->getPath());
        $body = json_decode((string) $history[1]['request']->getBody(), true, flags: JSON_THROW_ON_ERROR);
        $this->assertSame(['dataDeVencimento' => '2026-08-20', 'validadeAposVencimento' => 30], $body['calendario']);
        $this->assertSame(['original' => '45.67'], $body['valor']);
        $this->assertSame(['cpf' => '12345678901', 'nome' => 'Cliente Teste'], $body['devedor']);
        $this->assertSame('GET', $history[2]['request']->getMethod());
        $this->assertSame('/v2/cobv/ERP98765432109876543210987', $history[2]['request']->getUri()->getPath());
        $this->assertSame('paid', $paid->status);
        $this->assertSame('2026-08-14T15:01:00+00:00', $paid->paidAt?->format(DATE_ATOM));
    }

    public function test_it_lists_and_reads_received_pix_for_reconciliation(): void
    {
        $history = [];
        $handler = HandlerStack::create(new MockHandler([
            $this->fixtureResponse('oauth-token.json'),
            $this->fixtureResponse('pix-received-list.json'),
            $this->fixtureResponse('pix-received.json'),
        ]));
        $handler->push(Middleware::history($history));
        $provider = new BradescoProvider(new BradescoHttpClient(new BradescoArrayPsrCache, handler: $handler));
        $context = $this->context();

        $page = $provider->receivedPix(new PixReceiptQuery(
            context: $context,
            from: new DateTimeImmutable('2026-08-13T00:00:00.000Z'),
            to: new DateTimeImmutable('2026-08-14T23:59:59.000Z'),
            limit: 1,
            hasTxid: true,
        ));
        $receipt = $provider->receivedPixById($context, 'E237202608140000000000000000004');

        $this->assertCount(1, $page->transactions);
        $this->assertSame('1', $page->nextCursor);
        $this->assertSame('E237202608140000000000000000003', $page->transactions[0]->externalId);
        $this->assertSame(1234, $page->transactions[0]->amount->minor);
        $this->assertSame('credit', $page->transactions[0]->direction->value);
        $this->assertSame('Cliente Pagador', $page->transactions[0]->counterpartyName);
        $this->assertSame('12345678000190', $page->transactions[0]->counterpartyTaxId);
        $this->assertSame('ERP12345678901234567890123', $page->transactions[0]->identifiers['txid']);
        $this->assertSame('/v2/pix', $history[1]['request']->getUri()->getPath());
        parse_str($history[1]['request']->getUri()->getQuery(), $query);
        $this->assertSame('2026-08-13T00:00:00.000Z', $query['inicio']);
        $this->assertSame('2026-08-14T23:59:59.000Z', $query['fim']);
        $this->assertSame('0', $query['paginacao_paginaAtual'] ?? $query['paginacao.paginaAtual'] ?? null);
        $this->assertSame('1', $query['paginacao_itensPorPagina'] ?? $query['paginacao.itensPorPagina'] ?? null);
        $this->assertSame('1', $query['txIdPresente']);
        $this->assertSame(45, $history[1]['options']['timeout']);
        $this->assertSame('/v2/pix/E237202608140000000000000000004', $history[2]['request']->getUri()->getPath());
        $this->assertSame(4567, $receipt->amount->minor);
        $this->assertSame('12345678901', $receipt->counterpartyTaxId);
    }

    public function test_it_omits_optional_pagination_on_the_default_first_page(): void
    {
        $history = [];
        $handler = HandlerStack::create(new MockHandler([
            $this->fixtureResponse('oauth-token.json'),
            $this->fixtureResponse('pix-received-list-last.json'),
        ]));
        $handler->push(Middleware::history($history));
        $provider = new BradescoProvider(new BradescoHttpClient(new BradescoArrayPsrCache, handler: $handler));

        $provider->receivedPix(new PixReceiptQuery(
            context: $this->context(),
            from: new DateTimeImmutable('2024-10-02T00:00:00.000Z'),
            to: new DateTimeImmutable('2024-10-03T10:06:00.000Z'),
        ));

        parse_str($history[1]['request']->getUri()->getQuery(), $query);
        $this->assertSame('2024-10-02T00:00:00.000Z', $query['inicio']);
        $this->assertSame('2024-10-03T10:06:00.000Z', $query['fim']);
        $this->assertArrayNotHasKey('paginacao_paginaAtual', $query);
        $this->assertArrayNotHasKey('paginacao_itensPorPagina', $query);
    }

    public function test_it_manages_a_normal_bradesco_boleto(): void
    {
        $history = [];
        $handler = HandlerStack::create(new MockHandler([
            $this->fixtureResponse('oauth-token.json'),
            $this->jsonResponse([
                'nuTituloGerado' => '12345678901',
                'cdBarras' => '23790000000000000000000000000000000000000000',
                'linhaDigitavel' => '23790.00000 00000.000000 00000.000000 0 000000001234',
                'vlTitulo' => 1234,
            ]),
            $this->jsonResponse([
                'nossoNumero' => '12345678901',
                'statusTitulo' => 'LIQUIDADO',
                'vlTitulo' => 1234,
                'dataPagamento' => '14/08/2026',
            ]),
            $this->jsonResponse(['statusTitulo' => 'ATIVO']),
            $this->jsonResponse(['statusTitulo' => 'BAIXADO']),
        ]));
        $handler->push(Middleware::history($history));
        $provider = new BradescoProvider(new BradescoHttpClient(new BradescoArrayPsrCache, handler: $handler));
        $context = $this->context();

        $created = $provider->createBoleto($context, new ReceivableCommand(
            idempotencyKey: 'fixture-boleto-normal',
            reference: 'ERP-BOL-123',
            amount: new Money(1234),
            dueAt: new DateTimeImmutable('2026-08-30'),
            payer: $this->boletoPayer(),
            options: ['subtype' => 'normal', 'our_number' => '12345678901'],
        ));
        $paid = $provider->getBoleto($context, $created->externalId, 'normal');
        $provider->updateBoleto($context, $created->externalId, ['due_at' => '2026-09-10'], 'normal');
        $cancelled = $provider->cancelBoleto($context, $created->externalId, 'normal');

        $this->assertSame('12345678901', $created->externalId);
        $this->assertSame(1234, $created->amount->minor);
        $this->assertNotNull($created->barcode);
        $this->assertNotNull($created->digitableLine);
        $this->assertSame('paid', $paid->status);
        $this->assertSame('2026-08-14', $paid->paidAt?->format('Y-m-d'));
        $this->assertSame('cancelled', $cancelled->status);

        $this->assertSame('/boleto/cobranca-registro/v1/cobranca', $history[1]['request']->getUri()->getPath());
        $createdBody = json_decode((string) $history[1]['request']->getBody(), true, flags: JSON_THROW_ON_ERROR);
        $this->assertSame(12345678, $createdBody['nuCPFCNPJ']);
        $this->assertSame(1, $createdBody['filialCPFCNPJ']);
        $this->assertSame(90, $createdBody['ctrlCPFCNPJ']);
        $this->assertSame(9, $createdBody['idProduto']);
        $this->assertSame(123400000001234567, $createdBody['nuNegociacao']);
        $this->assertSame('30.08.2026', $createdBody['dtVencimentoTitulo']);
        $this->assertSame('Rua das Flores', $createdBody['logradouroPagador']);
        $this->assertSame('/boleto/cobranca-consulta/v1/consultar', $history[2]['request']->getUri()->getPath());
        $this->assertSame('PUT', $history[3]['request']->getMethod());
        $updatedBody = json_decode((string) $history[3]['request']->getBody(), true, flags: JSON_THROW_ON_ERROR);
        $this->assertSame(10092026, $updatedBody['dadosTitulo']['vencimento']['dataVencimento']);
        $this->assertSame('/boleto/cobranca-baixa/v1/baixar', $history[4]['request']->getUri()->getPath());
        $cancelBody = json_decode((string) $history[4]['request']->getBody(), true, flags: JSON_THROW_ON_ERROR);
        $this->assertSame(57, $cancelBody['codigoBaixa']);
        $this->assertSame(12341234567, $cancelBody['negociacao']);
    }

    public function test_it_creates_and_updates_a_hybrid_bradesco_boleto_with_its_txid(): void
    {
        $history = [];
        $handler = HandlerStack::create(new MockHandler([
            $this->fixtureResponse('oauth-token.json'),
            $this->jsonResponse([
                'status10' => '0',
                'ctitloCobrCdent' => '87654321098',
                'codBarras10' => '23790000000000000000000000000000000000000000',
                'linhaDig10' => '23790.00000 00000.000000 00000.000000 0 000000004567',
                'wqrcdPdraoMercd' => '000201010212bradescoboletohibrido',
                'iconcPgtoSpi' => 'BRADESCO-HYBRID-TXID',
                'valMoeda10' => 4567,
            ]),
            $this->jsonResponse(['statusTitulo' => 'ATIVO']),
        ]));
        $handler->push(Middleware::history($history));
        $provider = new BradescoProvider(new BradescoHttpClient(new BradescoArrayPsrCache, handler: $handler));
        $context = $this->context();

        $created = $provider->createBoleto($context, new ReceivableCommand(
            idempotencyKey: 'fixture-boleto-hybrid',
            reference: 'ERP-BOL-456',
            amount: new Money(4567),
            dueAt: new DateTimeImmutable('2026-09-05'),
            payer: $this->boletoPayer(),
            options: ['subtype' => 'hybrid', 'our_number' => '87654321098'],
        ));
        $provider->updateBoleto($context, $created->externalId, [
            'due_at' => '2026-09-12',
            'provider_metadata' => $created->metadata,
        ], 'hybrid');

        $this->assertSame('000201010212bradescoboletohibrido', $created->copyAndPaste);
        $this->assertSame('active', $created->status);
        $this->assertSame(4567, $created->amount->minor);
        $this->assertSame('/boleto-hibrido/cobranca-registro/v1/gerarBoleto', $history[1]['request']->getUri()->getPath());
        $createdBody = json_decode((string) $history[1]['request']->getBody(), true, flags: JSON_THROW_ON_ERROR);
        $this->assertSame('4567', $createdBody['vnmnalTitloCobr']);
        $this->assertSame('S', $createdBody['cindcdCobrMisto']);
        $this->assertSame('POST', $history[2]['request']->getMethod());
        $this->assertSame('/boleto-hibrido/cobranca-alteracao/v1/alteraBoletoConsulta', $history[2]['request']->getUri()->getPath());
        $this->assertSame('BRADESCO-HYBRID-TXID', $history[2]['request']->getHeaderLine('txId'));
        $updatedBody = json_decode((string) $history[2]['request']->getBody(), true, flags: JSON_THROW_ON_ERROR);
        $this->assertSame(12092026, $updatedBody['dadosTitulo']['dataVencimento']);
    }

    public function test_it_verifies_the_secret_injected_by_the_mtls_edge(): void
    {
        $provider = new BradescoProvider(new BradescoHttpClient(new BradescoArrayPsrCache));
        $context = $this->context(['webhook_secret' => 'fixture-webhook-secret']);

        $this->assertTrue($provider->verify(
            $context,
            new ServerRequest('POST', '/webhook', ['Authorization' => 'Bearer fixture-webhook-secret']),
        ));
        $this->assertFalse($provider->verify(
            $context,
            new ServerRequest('POST', '/webhook', ['Authorization' => 'Bearer invalid']),
        ));
    }

    private function fixtureResponse(string $name): Response
    {
        return new Response(
            200,
            ['Content-Type' => 'application/json'],
            (string) file_get_contents(__DIR__.'/../Fixtures/Bradesco/'.$name),
        );
    }

    /** @param array<string,mixed> $body */
    private function jsonResponse(array $body): Response
    {
        return new Response(200, ['Content-Type' => 'application/json'], json_encode($body, JSON_THROW_ON_ERROR));
    }

    /** @return array<string,string> */
    private function boletoPayer(): array
    {
        return [
            'nome' => 'Cliente Teste',
            'cnpj' => '98765432000110',
            'endereco' => 'Rua das Flores',
            'numero' => '123',
            'complemento' => 'Sala 4',
            'bairro' => 'Centro',
            'cidade' => 'Sao Paulo',
            'uf' => 'SP',
            'cep' => '01001000',
            'email' => 'financeiro@cliente.test',
        ];
    }

    /** @param array<string,mixed> $extraCredentials */
    private function context(array $extraCredentials = []): ConnectionContext
    {
        return new ConnectionContext(
            connectionId: '019ff716-1d34-7151-b320-f34e87ca26ab',
            companyId: '019ff716-1d34-7151-b320-f34e87ca26ac',
            environment: 'sandbox',
            credentials: [
                'default_pix_key' => 'financeiro@example.test',
                'products' => [
                    'pix' => [
                        'base_url' => 'https://bradesco.fixture.test/',
                        'token_url' => 'https://bradesco.fixture.test/auth/server/oauth/token',
                        'client_id' => 'bradesco-client',
                        'client_secret' => 'bradesco-secret',
                        'scope' => '',
                    ],
                    'boleto' => [
                        'base_url' => 'https://bradesco.fixture.test/',
                        'token_url' => 'https://bradesco.fixture.test/auth/server-mtls/v2/token',
                        'client_id' => 'bradesco-client',
                        'client_secret' => 'bradesco-secret',
                        'scope' => '',
                        'beneficiary_tax_id' => '12345678000190',
                        'product_code' => '9',
                        'negotiation_number' => '123400000001234567',
                    ],
                ],
                ...$extraCredentials,
            ],
        );
    }
}

final class BradescoArrayPsrCache implements CacheInterface
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
