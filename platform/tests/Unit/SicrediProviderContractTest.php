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
use Sabba\OpenFinance\Core\DTO\ReceivableCommand;
use Sabba\OpenFinance\Core\DTO\TransactionQuery;
use Sabba\OpenFinance\Core\Enums\TransactionDirection;
use Sabba\OpenFinance\Sicredi\SicrediHttpClient;
use Sabba\OpenFinance\Sicredi\SicrediProvider;

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
        $this->assertSame('erp-title-1', $pix->externalId);
        $this->assertSame(1234, $pix->amount->minor);
        $this->assertSame('000201010212fixture', $pix->copyAndPaste);

        $pixRequest = $history[4]['request'];
        $this->assertSame('PUT', $pixRequest->getMethod());
        $this->assertStringContainsString('"original":"12.34"', (string) $pixRequest->getBody());
        $this->assertStringContainsString('"chave":"financeiro@example.test"', (string) $pixRequest->getBody());
        $this->assertSame('Bearer fixture-access-token', $pixRequest->getHeaderLine('Authorization'));
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
