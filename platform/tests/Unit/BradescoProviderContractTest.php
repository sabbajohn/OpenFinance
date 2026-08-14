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
use Sabba\OpenFinance\Bradesco\BradescoProviderException;
use Sabba\OpenFinance\Core\DTO\ConnectionContext;
use Sabba\OpenFinance\Core\DTO\Money;
use Sabba\OpenFinance\Core\DTO\ReceivableCommand;

class BradescoProviderContractTest extends TestCase
{
    public function test_it_authenticates_with_http_basic_without_exposing_the_token(): void
    {
        $history = [];
        $handler = HandlerStack::create(new MockHandler([$this->fixtureResponse('oauth-token.json')]));
        $handler->push(Middleware::history($history));
        $client = new BradescoHttpClient(new BradescoArrayPsrCache, handler: $handler);

        $result = $client->testAuthentication($this->context(), 'pix');

        $this->assertSame('Bearer', $result['token_type']);
        $this->assertSame(3599, $result['expires_in']);
        $this->assertSame(['cob.read', 'cob.write', 'pix.read', 'pix.write'], $result['scope']);
        $this->assertArrayNotHasKey('access_token', $result);
        $this->assertSame(
            'Basic '.base64_encode('bradesco-client:bradesco-secret'),
            $history[0]['request']->getHeaderLine('Authorization'),
        );
        parse_str((string) $history[0]['request']->getBody(), $body);
        $this->assertSame('client_credentials', $body['grant_type']);
        $this->assertArrayNotHasKey('client_id', $body);
        $this->assertArrayNotHasKey('client_secret', $body);
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
            reference: 'ERP123',
            amount: new Money(1234),
            dueAt: null,
        ));
        $paid = $provider->getPix($context, 'ERP123');
        $refund = $provider->refundPix(
            $context,
            'E237202608130000000000000000001',
            'REFUND123',
            new Money(500),
        );

        $this->assertSame('ERP123', $created->externalId);
        $this->assertSame(1234, $created->amount->minor);
        $this->assertSame('000201010212bradescofixture', $created->copyAndPaste);
        $this->assertSame('paid', $paid->status);
        $this->assertSame('2026-08-13T12:01:00+00:00', $paid->paidAt?->format(DATE_ATOM));
        $this->assertSame('REFUND123', $refund->externalId);
        $this->assertSame(500, $refund->amount->minor);

        $this->assertSame('PUT', $history[1]['request']->getMethod());
        $this->assertSame('/cob/ERP123', $history[1]['request']->getUri()->getPath());
        $this->assertStringContainsString('"original":"12.34"', (string) $history[1]['request']->getBody());
        $this->assertStringContainsString('"chave":"financeiro@example.test"', (string) $history[1]['request']->getBody());
        $this->assertSame('GET', $history[2]['request']->getMethod());
        $this->assertSame('/cob/ERP123', $history[2]['request']->getUri()->getPath());
        $this->assertSame('/pix/E237202608130000000000000000001/devolucao/REFUND123', $history[3]['request']->getUri()->getPath());
        $this->assertStringContainsString('"valor":"5.00"', (string) $history[3]['request']->getBody());
    }

    public function test_it_rejects_due_pix_and_requires_webhook_verification(): void
    {
        $provider = new BradescoProvider(new BradescoHttpClient(new BradescoArrayPsrCache));

        $this->expectException(BradescoProviderException::class);
        $provider->createPix($this->context(), new ReceivableCommand(
            idempotencyKey: 'fixture-idempotency',
            reference: 'ERP123',
            amount: new Money(1234),
            dueAt: new DateTimeImmutable('2026-08-20'),
        ));
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
                        'scope' => 'cob.read cob.write pix.read pix.write',
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
