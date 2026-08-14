<?php

namespace App\Domain\Events\Jobs;

use App\Domain\Events\Models\InboxEvent;
use App\Domain\Events\Services\RawPayloadStore;
use App\Domain\Receivables\Services\SicrediWebhookHandler;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;

class ProcessInboxEvent implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 8;

    public function __construct(public readonly string $eventId)
    {
        $this->onQueue('webhooks-critical');
    }

    /** @return list<int> */
    public function backoff(): array
    {
        return [5, 15, 60, 300, 900];
    }

    public function handle(
        RawPayloadStore $payloads,
        SicrediWebhookHandler $sicrediWebhooks,
    ): void {
        $event = InboxEvent::query()->findOrFail($this->eventId);
        if ($event->status === 'processed') {
            return;
        }

        if ($event->event_type === 'bank.transaction.observed') {
            $event->forceFill(['status' => 'normalizing', 'error' => null])->save();
            NormalizeInboxBankTransaction::dispatch((string) $event->getKey());

            return;
        }

        $payload = $payloads->readJson($event->rawPayload()->firstOrFail());
        if ($event->event_type === 'sicredi.webhook') {
            $sicrediWebhooks->handle($event, $payload);
        }

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
