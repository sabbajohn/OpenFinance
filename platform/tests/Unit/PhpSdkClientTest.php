<?php

namespace Tests\Unit;

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Sabba\OpenFinance\Sdk\OpenFinanceClient;
use Sabba\OpenFinance\Sdk\OpenFinanceException;

class PhpSdkClientTest extends TestCase
{
    public function test_it_paginates_and_sends_authentication_and_idempotency_headers(): void
    {
        $history = [];
        $handler = HandlerStack::create(new MockHandler([
            $this->jsonResponse(['data' => [['id' => 'transaction-1']], 'next_cursor' => 'cursor-2']),
            $this->jsonResponse(['data' => [['id' => 'transaction-2']], 'next_cursor' => null]),
            $this->jsonResponse(['data' => ['id' => 'pix-1']], 201),
        ]));
        $handler->push(Middleware::history($history));
        $client = new OpenFinanceClient('https://platform.fixture.test', 'opaque-token', handler: $handler);

        $items = iterator_to_array($client->paginateBankTransactions(['company_id' => 'company-1']));
        $this->assertSame(['transaction-1', 'transaction-2'], array_column($items, 'id'));
        $this->assertSame('cursor-2', $history[1]['request']->getUri()->getQuery() !== ''
            ? $this->query($history[1]['request']->getUri()->getQuery())['cursor']
            : null);

        $client->createPix(['company_id' => 'company-1', 'amount_minor' => 1234], 'pix-idempotency');
        $request = $history[2]['request'];
        $this->assertSame('Bearer opaque-token', $request->getHeaderLine('Authorization'));
        $this->assertSame('pix-idempotency', $request->getHeaderLine('Idempotency-Key'));
        $this->assertStringContainsString('"amount_minor":1234', (string) $request->getBody());
    }

    public function test_it_exposes_platform_conflicts_as_typed_exceptions(): void
    {
        $handler = HandlerStack::create(new MockHandler([
            $this->jsonResponse(['message' => 'Decisão concorrente.', 'current_version' => 3], 409),
        ]));
        $client = new OpenFinanceClient('https://platform.fixture.test', 'opaque-token', handler: $handler);

        try {
            $client->decideReconciliation('case-1', 'settle', 2, [], 'decision-idempotency');
            $this->fail('A resposta 409 deveria gerar OpenFinanceException.');
        } catch (OpenFinanceException $exception) {
            $this->assertSame(409, $exception->status);
            $this->assertSame(3, $exception->details['current_version']);
        }
    }

    /** @param array<string,mixed> $body */
    private function jsonResponse(array $body, int $status = 200): Response
    {
        return new Response($status, ['Content-Type' => 'application/json'], json_encode($body, JSON_THROW_ON_ERROR));
    }

    /** @return array<string,string> */
    private function query(string $query): array
    {
        parse_str($query, $parsed);

        return array_map('strval', $parsed);
    }
}
