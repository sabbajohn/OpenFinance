<?php

namespace App\Domain\Reconciliation\Services;

use App\Domain\ERP\Models\ErpTitle;
use App\Domain\Events\Services\OutboxService;
use App\Domain\Identity\Models\ApiClient;
use App\Domain\Reconciliation\Models\ReconciliationCase;
use App\Domain\Reconciliation\Models\ReconciliationDecision;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

final readonly class ReconciliationDecisionService
{
    public function __construct(private OutboxService $outbox) {}

    /** @param array<string,mixed> $payload */
    public function decide(
        ReconciliationCase $case,
        string $action,
        array $payload,
        int $expectedVersion,
        string $idempotencyKey,
        string $source,
        User|ApiClient|null $actor = null,
    ): ReconciliationDecision {
        return DB::transaction(function () use ($case, $action, $payload, $expectedVersion, $idempotencyKey, $source, $actor): ReconciliationDecision {
            $locked = ReconciliationCase::query()->withoutGlobalScopes()->lockForUpdate()->findOrFail((string) $case->getKey());
            $existing = ReconciliationDecision::query()->withoutGlobalScopes()
                ->where('organization_id', $locked->organization_id)
                ->where('idempotency_key', $idempotencyKey)
                ->first();
            if ($existing) {
                return $existing;
            }

            if ($locked->status !== 'open' || (int) $locked->version !== $expectedVersion) {
                throw new ConflictHttpException('A conciliação já recebeu outra decisão ou sua versão está desatualizada.');
            }

            if (! in_array($action, ['match', 'split', 'partial', 'adjust', 'classify', 'ignore', 'reverse'], true)) {
                throw new UnprocessableEntityHttpException('Ação de conciliação inválida.');
            }

            $payload = $this->enrichAllocations($locked, $payload);

            $status = 'pending_erp';
            $decision = ReconciliationDecision::query()->withoutGlobalScopes()->create([
                'organization_id' => $locked->organization_id,
                'company_id' => $locked->company_id,
                'reconciliation_case_id' => $locked->getKey(),
                'requested_by_user_id' => $actor instanceof User ? $actor->getKey() : null,
                'requested_by_api_client_id' => $actor instanceof ApiClient ? $actor->getKey() : null,
                'source' => $source,
                'action' => $action,
                'expected_version' => $expectedVersion,
                'idempotency_key' => $idempotencyKey,
                'status' => $status,
                'payload' => $payload,
                'confirmed_at' => null,
            ]);

            $locked->forceFill([
                'status' => 'pending_erp',
                'version' => $locked->version + 1,
                'resolved_at' => null,
            ])->save();

            $this->outbox->forModel('reconciliation.decision.created', $decision, $this->payload($decision, $locked));

            return $decision;
        }, 3);
    }

    /** @param array<string,mixed> $erpResult */
    public function confirm(ReconciliationDecision $decision, array $erpResult): ReconciliationDecision
    {
        return $this->complete($decision, 'confirmed', $erpResult);
    }

    /** @param array<string,mixed> $erpResult */
    public function reject(ReconciliationDecision $decision, array $erpResult): ReconciliationDecision
    {
        return $this->complete($decision, 'rejected', $erpResult);
    }

    /** @param array<string,mixed> $erpResult */
    private function complete(ReconciliationDecision $decision, string $status, array $erpResult): ReconciliationDecision
    {
        return DB::transaction(function () use ($decision, $status, $erpResult): ReconciliationDecision {
            $locked = ReconciliationDecision::query()->withoutGlobalScopes()->lockForUpdate()->findOrFail((string) $decision->getKey());
            if (in_array($locked->status, ['confirmed', 'rejected', 'reversed', 'conflict'], true)) {
                return $locked;
            }

            $case = ReconciliationCase::query()->withoutGlobalScopes()->lockForUpdate()->findOrFail((string) $locked->reconciliation_case_id);
            $locked->forceFill([
                'status' => $status,
                'erp_result' => $erpResult,
                'confirmed_at' => $status === 'confirmed' ? now('UTC') : null,
                'rejected_at' => $status === 'rejected' ? now('UTC') : null,
            ])->save();
            $case->forceFill([
                'status' => $status === 'confirmed' ? 'resolved' : 'open',
                'version' => $case->version + 1,
                'resolved_at' => $status === 'confirmed' ? now('UTC') : null,
            ])->save();

            $this->outbox->forModel("reconciliation.decision.{$status}", $locked, $this->payload($locked, $case));

            return $locked;
        }, 3);
    }

    /** @return array<string,mixed> */
    private function payload(ReconciliationDecision $decision, ReconciliationCase $case): array
    {
        $transaction = $case->transaction()->first();

        return [
            'decision_id' => $decision->getKey(),
            'reconciliation_id' => $case->getKey(),
            'action' => $decision->action,
            'status' => $decision->status,
            'expected_version' => $decision->expected_version,
            'case_version' => $case->version,
            'payload' => $decision->payload,
            'erp_result' => $decision->erp_result,
            'bank_transaction' => $transaction ? [
                'id' => $transaction->getKey(),
                'external_id' => $transaction->external_id,
                'bank_account_id' => $transaction->bank_account_id,
                'direction' => $transaction->direction,
                'amount_minor' => $transaction->amount_minor,
                'currency' => $transaction->currency,
                'occurred_at' => $transaction->occurred_at->toIso8601String(),
                'description' => $transaction->description,
                'counterparty_name' => $transaction->counterparty_name,
                'identifiers' => $transaction->identifiers,
            ] : null,
        ];
    }

    /**
     * @param  array<string,mixed>  $payload
     * @return array<string,mixed>
     */
    private function enrichAllocations(ReconciliationCase $case, array $payload): array
    {
        $allocations = $payload['allocations'] ?? [];
        if (! is_array($allocations)) {
            throw new UnprocessableEntityHttpException('As alocações da decisão são inválidas.');
        }

        $allowedTitleIds = $case->candidates()->pluck('erp_title_id')->map(fn ($id) => (string) $id)->all();
        $payload['allocations'] = collect($allocations)->map(function (array $allocation) use ($case, $allowedTitleIds): array {
            $titleId = (string) ($allocation['erp_title_id'] ?? '');
            if (! in_array($titleId, $allowedTitleIds, true)) {
                throw new UnprocessableEntityHttpException('A decisão contém um título que não pertence aos candidatos do caso.');
            }
            $title = ErpTitle::query()->withoutGlobalScopes()->findOrFail($titleId);
            if ($title->company_id !== $case->company_id) {
                throw new UnprocessableEntityHttpException('Título de outra empresa na decisão.');
            }

            return [
                ...$allocation,
                'erp_title_external_id' => $title->external_id,
                'amount_minor' => (int) ($allocation['amount_minor'] ?? $title->open_amount_minor),
            ];
        })->all();

        return $payload;
    }
}
