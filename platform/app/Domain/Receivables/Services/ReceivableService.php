<?php

namespace App\Domain\Receivables\Services;

use App\Domain\Banking\Models\BankConnection;
use App\Domain\ERP\Models\ErpTitle;
use App\Domain\Receivables\Jobs\CreateProviderReceivable;
use App\Domain\Receivables\Models\Receivable;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

final class ReceivableService
{
    /** @param array<string,mixed> $attributes */
    public function create(
        string $kind,
        ErpTitle $title,
        BankConnection $connection,
        string $idempotencyKey,
        array $attributes,
    ): Receivable {
        return DB::transaction(function () use ($kind, $title, $connection, $idempotencyKey, $attributes): Receivable {
            $existing = Receivable::query()->withoutGlobalScopes()
                ->where('organization_id', $connection->organization_id)
                ->where('idempotency_key', $idempotencyKey)
                ->first();
            if ($existing) {
                return $existing;
            }

            if ($title->organization_id !== $connection->organization_id
                || $title->company_id !== $connection->company_id
                || ! in_array($title->type, ['receivable', 'receive'], true)
                || $title->status !== 'open') {
                throw new UnprocessableEntityHttpException('A cobrança exige um título a receber aberto, da mesma empresa e conexão.');
            }

            $amount = (int) ($attributes['amount_minor'] ?? $title->open_amount_minor);
            if ($amount <= 0 || $amount > (int) $title->open_amount_minor) {
                throw new UnprocessableEntityHttpException('O valor deve ser positivo e não pode superar o saldo aberto do título.');
            }

            $receivable = Receivable::query()->withoutGlobalScopes()->create([
                'organization_id' => $connection->organization_id,
                'company_id' => $connection->company_id,
                'bank_connection_id' => $connection->getKey(),
                'erp_title_id' => $title->getKey(),
                'kind' => $kind,
                'subtype' => $attributes['subtype'] ?? null,
                'idempotency_key' => $idempotencyKey,
                'status' => 'pending',
                'amount_minor' => $amount,
                'currency' => $attributes['currency'] ?? $title->currency,
                'reference' => $attributes['reference'] ?? $title->external_id,
                'due_at' => $attributes['due_at'] ?? $title->due_at,
                'metadata' => [
                    'payer' => $attributes['payer'] ?? [],
                    'options' => $attributes['options'] ?? [],
                ],
            ]);

            CreateProviderReceivable::dispatch((string) $receivable->getKey(), (string) $connection->getKey())->afterCommit();

            return $receivable;
        }, 3);
    }
}
