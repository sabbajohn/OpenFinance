<?php

namespace App\Domain\Events\Jobs;

use App\Domain\Banking\Models\BankTransaction;
use App\Domain\Events\Models\InboxEvent;
use App\Domain\Receivables\Jobs\CreateProviderReceivable;
use App\Domain\Receivables\Jobs\ProcessReceivableOperation;
use App\Domain\Receivables\Models\Receivable;
use App\Domain\Receivables\Models\ReceivableOperation;
use App\Domain\Reconciliation\Jobs\ReconcileBankTransaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RecoverProcessingPipelines implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct()
    {
        $this->onQueue('maintenance');
    }

    public function handle(): void
    {
        InboxEvent::query()
            ->whereIn('status', ['received', 'normalizing'])
            ->where('updated_at', '<=', now('UTC')->subMinutes(5))
            ->orderBy('updated_at')
            ->limit(500)
            ->get(['id', 'status'])
            ->each(function (InboxEvent $event): void {
                $event->status === 'normalizing'
                    ? NormalizeInboxBankTransaction::dispatch((string) $event->getKey())
                    : ProcessInboxEvent::dispatch((string) $event->getKey());
            });

        BankTransaction::query()->withoutGlobalScopes()
            ->where('status', 'posted')
            ->where('created_at', '<=', now('UTC')->subMinutes(5))
            ->whereDoesntHave('reconciliationCase')
            ->orderBy('created_at')
            ->limit(500)
            ->pluck('id')
            ->each(fn (string $id) => ReconcileBankTransaction::dispatch($id));

        Receivable::query()->withoutGlobalScopes()
            ->whereIn('status', ['pending', 'failed'])
            ->whereNull('provider_external_id')
            ->where('updated_at', '<=', now('UTC')->subMinutes(5))
            ->limit(250)
            ->get(['id', 'bank_connection_id'])
            ->each(fn (Receivable $receivable) => CreateProviderReceivable::dispatch(
                (string) $receivable->getKey(),
                (string) $receivable->bank_connection_id,
            ));

        ReceivableOperation::query()->withoutGlobalScopes()
            ->whereIn('status', ['pending', 'processing', 'retrying'])
            ->where('updated_at', '<=', now('UTC')->subMinutes(5))
            ->limit(250)
            ->with('receivable:id,bank_connection_id,kind')
            ->get()
            ->each(fn (ReceivableOperation $operation) => ProcessReceivableOperation::dispatch(
                (string) $operation->getKey(),
                (string) $operation->receivable->bank_connection_id,
                (string) $operation->receivable->kind,
            ));
    }
}
