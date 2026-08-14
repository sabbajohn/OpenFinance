<?php

namespace App\Domain\Reconciliation\Jobs;

use App\Domain\Reconciliation\Models\ReconciliationCase;
use App\Domain\Reconciliation\Services\ReconciliationEngine;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Queue\SerializesModels;

class ReevaluateCompanyReconciliations implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public readonly string $organizationId,
        public readonly string $companyId,
    ) {
        $this->onQueue('reconciliation');
    }

    /** @return list<object> */
    public function middleware(): array
    {
        return [(new WithoutOverlapping('reconciliation-company:'.$this->companyId))->releaseAfter(15)->expireAfter(300)->shared()];
    }

    public function handle(ReconciliationEngine $engine): void
    {
        ReconciliationCase::query()->withoutGlobalScopes()
            ->where('organization_id', $this->organizationId)
            ->where('company_id', $this->companyId)
            ->where('status', 'open')
            ->with('transaction')
            ->orderBy('created_at')
            ->limit(1000)
            ->get()
            ->each(fn (ReconciliationCase $case) => $engine->evaluate($case->transaction));
    }
}
