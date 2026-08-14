<?php

namespace App\Domain\Events\Jobs;

use App\Domain\Banking\Services\BankTransactionIngestor;
use App\Domain\Events\Models\InboxEvent;
use App\Domain\Events\Services\RawPayloadStore;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Queue\SerializesModels;
use Throwable;

class NormalizeInboxBankTransaction implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 8;

    public function __construct(public readonly string $eventId)
    {
        $this->onQueue('normalization');
    }

    /** @return list<object> */
    public function middleware(): array
    {
        return [(new WithoutOverlapping('normalize-inbox:'.$this->eventId))->releaseAfter(10)->expireAfter(180)->shared()];
    }

    /** @return list<int> */
    public function backoff(): array
    {
        return [5, 15, 60, 300, 900];
    }

    public function handle(RawPayloadStore $payloads, BankTransactionIngestor $transactions): void
    {
        $event = InboxEvent::query()->findOrFail($this->eventId);
        if ($event->status === 'processed') {
            return;
        }

        $payload = $payloads->readJson($event->rawPayload()->firstOrFail());
        $transactions->ingest($payload, $event->raw_payload_id, $event->correlation_id);
        $event->forceFill(['status' => 'processed', 'processed_at' => now('UTC'), 'error' => null])->save();
    }

    public function failed(Throwable $exception): void
    {
        InboxEvent::query()->whereKey($this->eventId)->update([
            'status' => 'failed',
            'error' => mb_substr($exception->getMessage(), 0, 4000),
        ]);
    }
}
