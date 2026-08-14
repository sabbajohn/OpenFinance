<?php

namespace App\Domain\Events\Jobs;

use App\Domain\Events\Models\EventLedger;
use App\Domain\Events\Models\OutboxEvent;
use App\Domain\Events\Models\WebhookDelivery;
use App\Domain\Events\Models\WebhookEndpoint;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class PublishOutboxBatch implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct()
    {
        $this->onQueue('erp-delivery');
    }

    public function handle(): void
    {
        OutboxEvent::query()
            ->where('status', 'pending')
            ->where('available_at', '<=', now('UTC'))
            ->orderBy('available_at')
            ->limit(250)
            ->pluck('id')
            ->each(fn (string $id) => $this->publish($id));
    }

    private function publish(string $id): void
    {
        $deliveryIds = DB::transaction(function () use ($id): array {
            $event = OutboxEvent::query()->lockForUpdate()->find($id);
            if (! $event || $event->status === 'published') {
                return [];
            }

            EventLedger::query()->firstOrCreate(
                ['id' => $event->getKey()],
                [
                    'organization_id' => $event->organization_id,
                    'company_id' => $event->company_id,
                    'event_type' => $event->event_type,
                    'aggregate_type' => $event->aggregate_type,
                    'aggregate_id' => $event->aggregate_id,
                    'schema_version' => $event->schema_version,
                    'payload' => $event->payload,
                    'correlation_id' => $event->correlation_id,
                    'occurred_at' => $event->created_at,
                ],
            );

            $endpoints = WebhookEndpoint::query()
                ->where('organization_id', $event->organization_id)
                ->where('status', 'active')
                ->where(fn ($query) => $query->whereNull('company_id')->orWhere('company_id', $event->company_id))
                ->get()
                ->filter(fn (WebhookEndpoint $endpoint): bool => in_array('*', $endpoint->events, true)
                    || in_array($event->event_type, $endpoint->events, true));

            $deliveryIds = $endpoints->map(function (WebhookEndpoint $endpoint) use ($event): string {
                $delivery = WebhookDelivery::query()->firstOrCreate([
                    'organization_id' => $event->organization_id,
                    'webhook_endpoint_id' => $endpoint->getKey(),
                    'outbox_event_id' => $event->getKey(),
                ], [
                    'status' => 'pending',
                    'next_attempt_at' => now('UTC'),
                ]);

                return (string) $delivery->getKey();
            })->all();

            $event->forceFill([
                'status' => 'published',
                'published_at' => now('UTC'),
                'attempts' => $event->attempts + 1,
                'last_error' => null,
            ])->save();

            return $deliveryIds;
        });

        foreach ($deliveryIds as $deliveryId) {
            DeliverWebhook::dispatch($deliveryId);
        }
    }
}
