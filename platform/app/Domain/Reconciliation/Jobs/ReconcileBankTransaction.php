<?php

namespace App\Domain\Reconciliation\Jobs;

use App\Domain\Banking\Models\BankTransaction;
use App\Domain\Reconciliation\Services\ReconciliationDecisionService;
use App\Domain\Reconciliation\Services\ReconciliationEngine;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Queue\SerializesModels;

class ReconcileBankTransaction implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 5;

    public function __construct(
        public readonly string $bankTransactionId,
        public readonly ?string $correlationId = null,
    ) {
        $this->onQueue('reconciliation');
    }

    /** @return list<object> */
    public function middleware(): array
    {
        return [(new WithoutOverlapping('reconcile:'.$this->bankTransactionId))->releaseAfter(10)->expireAfter(180)->shared()];
    }

    public function handle(ReconciliationEngine $engine, ReconciliationDecisionService $decisions): void
    {
        $transaction = BankTransaction::query()->withoutGlobalScopes()->findOrFail($this->bankTransactionId);
        if ($transaction->status !== 'posted') {
            return;
        }

        $case = $engine->evaluate($transaction, $this->correlationId);
        if (! $case->auto_eligible || ! config('openfinance.reconciliation.auto_enabled')) {
            return;
        }

        $candidate = $case->candidates()->orderByDesc('score')->firstOrFail();
        $decisions->decide(
            case: $case,
            action: 'match',
            payload: ['allocations' => [[
                'erp_title_id' => $candidate->erp_title_id,
                'amount_minor' => $candidate->suggested_amount_minor,
            ]]],
            expectedVersion: (int) $case->version,
            idempotencyKey: 'auto:'.$case->getKey().':v'.$case->version,
            source: 'automatic',
        );
    }
}
