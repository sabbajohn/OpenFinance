<?php

namespace App\Domain\Banking\Services;

use App\Domain\Banking\Models\BankAccount;
use App\Domain\Banking\Models\BankConnection;
use App\Domain\Banking\Models\BankTransaction;
use App\Domain\Events\Services\OutboxService;
use App\Domain\Reconciliation\Jobs\ReconcileBankTransaction;
use Illuminate\Support\Facades\DB;

final readonly class BankTransactionIngestor
{
    public function __construct(private OutboxService $outbox) {}

    /** @param array<string,mixed> $payload */
    public function ingest(array $payload, ?string $rawPayloadId = null, ?string $correlationId = null): BankTransaction
    {
        $transaction = DB::transaction(function () use ($payload, $rawPayloadId, $correlationId): BankTransaction {
            $connection = BankConnection::query()->withoutGlobalScopes()->findOrFail((string) $payload['connection_id']);
            $account = BankAccount::query()->withoutGlobalScopes()->findOrFail((string) $payload['account_id']);
            $canonical = (array) $payload['transaction'];
            $fingerprint = $this->fingerprint($connection, $canonical);

            $transaction = BankTransaction::query()->withoutGlobalScopes()
                ->where('bank_connection_id', $connection->getKey())
                ->where('fingerprint', $fingerprint)
                ->lockForUpdate()
                ->first();

            $attributes = [
                'organization_id' => $connection->organization_id,
                'company_id' => $connection->company_id,
                'bank_connection_id' => $connection->getKey(),
                'bank_account_id' => $account->getKey(),
                'raw_payload_id' => $rawPayloadId,
                'external_id' => $canonical['external_id'] ?? null,
                'fingerprint' => $fingerprint,
                'type' => $canonical['type'] ?? 'other',
                'direction' => $canonical['direction'],
                'status' => $canonical['status'] ?? 'posted',
                'amount_minor' => (int) $canonical['amount_minor'],
                'currency' => $canonical['currency'] ?? 'BRL',
                'occurred_at' => $canonical['occurred_at'],
                'observed_at' => $canonical['observed_at'] ?? now('UTC'),
                'description' => $canonical['description'] ?? null,
                'counterparty_name' => $canonical['counterparty_name'] ?? null,
                'counterparty_tax_id_hash' => $this->taxIdHash($canonical['counterparty_tax_id'] ?? null),
                'identifiers' => $canonical['identifiers'] ?? [],
                'metadata' => $canonical['metadata'] ?? [],
                'deleted_at' => ($canonical['status'] ?? null) === 'deleted' ? now('UTC') : null,
            ];

            if (! $transaction) {
                $transaction = BankTransaction::query()->withoutGlobalScopes()->create([...$attributes, 'version' => 1]);
                $eventType = 'bank.transaction.created';
            } else {
                $changed = collect($attributes)->except(['raw_payload_id', 'observed_at'])->contains(
                    fn (mixed $value, string $key): bool => $transaction->getAttribute($key) != $value,
                );
                if (! $changed) {
                    return $transaction;
                }

                $transaction->forceFill([...$attributes, 'version' => $transaction->version + 1])->save();
                $eventType = in_array($transaction->status, ['reversed', 'deleted'], true)
                    ? 'bank.transaction.reversed'
                    : 'bank.transaction.updated';
            }

            $this->outbox->forModel($eventType, $transaction, $this->eventPayload($transaction), $correlationId);

            return $transaction;
        }, 3);

        if ($transaction->status === 'posted') {
            ReconcileBankTransaction::dispatch((string) $transaction->getKey(), $correlationId);
        }

        return $transaction;
    }

    /** @param array<string,mixed> $canonical */
    private function fingerprint(BankConnection $connection, array $canonical): string
    {
        if (! empty($canonical['external_id'])) {
            return hash('sha256', implode('|', [
                'external-v1', $connection->provider, $connection->getKey(), 'bank_transaction', $canonical['external_id'],
            ]));
        }

        return hash('sha256', json_encode([
            'version' => 1,
            'connection' => $connection->getKey(),
            'account' => $canonical['account_external_id'] ?? null,
            'direction' => $canonical['direction'] ?? null,
            'amount' => (int) ($canonical['amount_minor'] ?? 0),
            'currency' => $canonical['currency'] ?? 'BRL',
            'occurred_at' => $canonical['occurred_at'] ?? null,
            'description' => mb_strtolower(trim((string) ($canonical['description'] ?? ''))),
            'identifiers' => $canonical['identifiers'] ?? [],
        ], JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    }

    private function taxIdHash(mixed $taxId): ?string
    {
        $digits = preg_replace('/\D+/', '', (string) $taxId);

        return $digits ? hash_hmac('sha256', $digits, (string) config('app.key')) : null;
    }

    /** @return array<string,mixed> */
    private function eventPayload(BankTransaction $transaction): array
    {
        return [
            'bank_transaction_id' => $transaction->getKey(),
            'bank_account_id' => $transaction->bank_account_id,
            'external_id' => $transaction->external_id,
            'direction' => $transaction->direction,
            'status' => $transaction->status,
            'amount_minor' => $transaction->amount_minor,
            'currency' => $transaction->currency,
            'occurred_at' => $transaction->occurred_at->toIso8601String(),
            'description' => $transaction->description,
            'counterparty_name' => $transaction->counterparty_name,
            'identifiers' => $transaction->identifiers,
            'version' => $transaction->version,
        ];
    }
}
