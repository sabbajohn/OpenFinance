<?php

namespace App\Domain\Receivables\Services;

use App\Domain\Receivables\Jobs\ProcessReceivableOperation;
use App\Domain\Receivables\Models\Receivable;
use App\Domain\Receivables\Models\ReceivableOperation;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

final class ReceivableOperationService
{
    /** @param array<string,mixed> $payload */
    public function request(Receivable $receivable, string $action, string $idempotencyKey, array $payload = []): ReceivableOperation
    {
        return DB::transaction(function () use ($receivable, $action, $idempotencyKey, $payload): ReceivableOperation {
            $existing = ReceivableOperation::query()->withoutGlobalScopes()
                ->where('organization_id', $receivable->organization_id)
                ->where('idempotency_key', $idempotencyKey)
                ->first();
            if ($existing) {
                return $existing;
            }

            $this->validateOperation($receivable, $action, $payload);
            $operation = ReceivableOperation::query()->withoutGlobalScopes()->create([
                'organization_id' => $receivable->organization_id,
                'company_id' => $receivable->company_id,
                'receivable_id' => $receivable->getKey(),
                'action' => $action,
                'idempotency_key' => $idempotencyKey,
                'status' => 'pending',
                'payload' => $payload,
            ]);
            ProcessReceivableOperation::dispatch(
                (string) $operation->getKey(),
                (string) $receivable->bank_connection_id,
                $receivable->kind,
            )->afterCommit();

            return $operation;
        }, 3);
    }

    /** @param array<string,mixed> $payload */
    private function validateOperation(Receivable $receivable, string $action, array $payload): void
    {
        if (! $receivable->provider_external_id) {
            throw new UnprocessableEntityHttpException('A cobrança ainda não foi criada no banco.');
        }

        if ($action === 'refund') {
            $amount = (int) ($payload['amount_minor'] ?? 0);
            if ($receivable->kind !== 'pix' || $receivable->status !== 'paid' || $amount < 1 || $amount > $receivable->amount_minor) {
                throw new UnprocessableEntityHttpException('A devolução exige Pix pago e valor válido.');
            }
        }

        if (in_array($action, ['update', 'cancel'], true)
            && ($receivable->kind !== 'boleto' || in_array($receivable->status, ['paid', 'cancelled', 'refunded'], true))) {
            throw new UnprocessableEntityHttpException('Este boleto não admite a operação solicitada.');
        }
    }
}
