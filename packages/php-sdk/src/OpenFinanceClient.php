<?php

namespace Sabba\OpenFinance\Sdk;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\RequestOptions;

final readonly class OpenFinanceClient
{
    private Client $http;

    public function __construct(
        string $baseUrl,
        private string $token,
        int $timeoutSeconds = 15,
        ?HandlerStack $handler = null,
    )
    {
        $this->http = new Client([
            'base_uri' => rtrim($baseUrl, '/').'/api/v1/',
            'timeout' => $timeoutSeconds,
            'connect_timeout' => 5,
            'http_errors' => false,
            ...($handler ? ['handler' => $handler] : []),
        ]);
    }

    /** @param list<array<string,mixed>> $accounts */
    public function pushAccounts(string $companyId, string $erpConnectionId, array $accounts, string $idempotencyKey): array
    {
        return $this->request('POST', 'erp/accounts/bulk', [], $idempotencyKey, [
            'company_id' => $companyId, 'erp_connection_id' => $erpConnectionId, 'items' => $accounts,
        ]);
    }

    /** @param list<array<string,mixed>> $titles */
    public function pushTitles(string $companyId, string $erpConnectionId, array $titles, string $idempotencyKey): array
    {
        return $this->request('POST', 'erp/titles/bulk', [], $idempotencyKey, [
            'company_id' => $companyId, 'erp_connection_id' => $erpConnectionId, 'items' => $titles,
        ]);
    }

    /** @param array<string,mixed> $filters */
    public function bankTransactions(array $filters = []): array
    {
        return $this->request('GET', 'bank-transactions', $filters);
    }

    /** @param array<string,mixed> $filters */
    public function reconciliations(array $filters = []): array
    {
        return $this->request('GET', 'reconciliations', $filters);
    }

    /**
     * @param array<string,mixed> $filters
     * @return iterable<array<string,mixed>>
     */
    public function paginateBankTransactions(array $filters = []): iterable
    {
        yield from $this->paginate('bank-transactions', $filters);
    }

    /**
     * @param array<string,mixed> $filters
     * @return iterable<array<string,mixed>>
     */
    public function paginateReconciliations(array $filters = []): iterable
    {
        yield from $this->paginate('reconciliations', $filters);
    }

    /** @param array<string,mixed> $payload */
    public function decideReconciliation(string $id, string $action, int $expectedVersion, array $payload, string $idempotencyKey): array
    {
        return $this->request('POST', "reconciliations/{$id}/decisions", [], $idempotencyKey, [
            'action' => $action, 'expected_version' => $expectedVersion, 'payload' => $payload,
        ]);
    }

    /** @param list<string> $liquidationIds */
    public function confirmDecision(string $decisionId, array $liquidationIds, string $idempotencyKey): array
    {
        return $this->request('POST', "reconciliation-decisions/{$decisionId}/confirm", [], $idempotencyKey, [
            'erp_result' => ['liquidation_ids' => $liquidationIds],
        ]);
    }

    public function rejectDecision(string $decisionId, string $reason, string $idempotencyKey): array
    {
        return $this->request('POST', "reconciliation-decisions/{$decisionId}/reject", [], $idempotencyKey, [
            'erp_result' => ['reason' => $reason],
        ]);
    }

    /** @param array<string,mixed> $charge */
    public function createPix(array $charge, string $idempotencyKey): array
    {
        return $this->request('POST', 'pix/charges', [], $idempotencyKey, $charge);
    }

    /** @param array<string,mixed> $boleto */
    public function createBoleto(array $boleto, string $idempotencyKey): array
    {
        return $this->request('POST', 'boletos', [], $idempotencyKey, $boleto);
    }

    public function refreshReceivable(string $receivableId, string $idempotencyKey): array
    {
        return $this->request('POST', "receivables/{$receivableId}/refresh", [], $idempotencyKey);
    }

    public function refundPix(string $receivableId, int $amountMinor, string $idempotencyKey, ?string $externalTransactionId = null): array
    {
        return $this->request('POST', "pix/charges/{$receivableId}/refunds", [], $idempotencyKey, array_filter([
            'amount_minor' => $amountMinor,
            'external_transaction_id' => $externalTransactionId,
        ], fn (mixed $value): bool => $value !== null));
    }

    /** @param array<string,mixed> $changes */
    public function updateBoleto(string $receivableId, array $changes, string $idempotencyKey): array
    {
        return $this->request('PATCH', "boletos/{$receivableId}", [], $idempotencyKey, $changes);
    }

    public function cancelBoleto(string $receivableId, string $idempotencyKey): array
    {
        return $this->request('POST', "boletos/{$receivableId}/cancel", [], $idempotencyKey);
    }

    /**
     * @param array<string,mixed> $filters
     * @return iterable<array<string,mixed>>
     */
    private function paginate(string $uri, array $filters): iterable
    {
        do {
            $page = $this->request('GET', $uri, $filters);

            foreach ($page['data'] ?? [] as $item) {
                if (is_array($item)) {
                    yield $item;
                }
            }

            $cursor = $page['next_cursor'] ?? null;
            if (is_string($cursor) && $cursor !== '') {
                $filters['cursor'] = $cursor;
            }
        } while (is_string($cursor) && $cursor !== '');
    }

    /** @param array<string,mixed> $query @param array<string,mixed>|null $json */
    private function request(string $method, string $uri, array $query = [], ?string $idempotencyKey = null, ?array $json = null): array
    {
        $headers = ['Accept' => 'application/json', 'Authorization' => 'Bearer '.$this->token];
        if ($idempotencyKey) {
            $headers['Idempotency-Key'] = $idempotencyKey;
        }
        $response = $this->http->request($method, $uri, array_filter([
            RequestOptions::HEADERS => $headers,
            RequestOptions::QUERY => $query,
            RequestOptions::JSON => $json,
        ], fn (mixed $value): bool => $value !== null && $value !== []));
        $decoded = json_decode((string) $response->getBody(), true);
        $decoded = is_array($decoded) ? $decoded : [];

        if ($response->getStatusCode() >= 400) {
            throw new OpenFinanceException((string) ($decoded['message'] ?? 'OpenFinance Platform rejeitou a operação.'), $response->getStatusCode(), $decoded);
        }

        return $decoded;
    }
}
