<?php

namespace App\Domain\Events\Jobs;

use App\Domain\Events\Models\OutboxEvent;
use App\Domain\Events\Models\WebhookDelivery;
use App\Domain\Events\Models\WebhookEndpoint;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Throwable;

class DeliverWebhook implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 12;

    public function __construct(public readonly string $deliveryId)
    {
        $this->onQueue('erp-delivery');
    }

    /** @return list<int> */
    public function backoff(): array
    {
        return [10, 30, 120, 300, 900, 1800, 3600];
    }

    public function handle(): void
    {
        $delivery = WebhookDelivery::query()->findOrFail($this->deliveryId);
        if ($delivery->status === 'delivered') {
            return;
        }

        $endpoint = WebhookEndpoint::query()->findOrFail($delivery->webhook_endpoint_id);
        $event = OutboxEvent::query()->findOrFail($delivery->outbox_event_id);
        $timestamp = (string) now('UTC')->getTimestamp();
        $body = json_encode([
            'id' => $event->getKey(),
            'type' => $event->event_type,
            'schema_version' => $event->schema_version,
            'occurred_at' => $event->created_at->toIso8601String(),
            'organization_id' => $event->organization_id,
            'company_id' => $event->company_id,
            'correlation_id' => $event->correlation_id,
            'data' => $event->payload,
        ], JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        $signature = hash_hmac('sha256', $timestamp.'.'.$delivery->getKey().'.'.$body, $endpoint->encrypted_secret);

        $delivery->forceFill(['attempts' => $delivery->attempts + 1])->save();

        try {
            $response = Http::timeout((int) config('openfinance.webhooks.timeout_seconds'))
                ->withBody($body, 'application/json')
                ->withHeaders([
                    'User-Agent' => 'OpenFinance-Platform/1.0',
                    'X-OpenFinance-Delivery' => $delivery->getKey(),
                    'X-OpenFinance-Timestamp' => $timestamp,
                    'X-OpenFinance-Signature' => 'sha256='.$signature,
                ])
                ->post($endpoint->url);

            if (! $response->successful()) {
                throw new \RuntimeException('ERP webhook returned HTTP '.$response->status());
            }

            $delivery->forceFill([
                'status' => 'delivered',
                'response_status' => $response->status(),
                'delivered_at' => now('UTC'),
                'last_error' => null,
            ])->save();
            $endpoint->forceFill(['last_success_at' => now('UTC')])->save();
        } catch (Throwable $exception) {
            $attempt = max(1, $this->attempts());
            $baseDelay = $this->backoff()[min($attempt - 1, count($this->backoff()) - 1)];
            $delay = $baseDelay + random_int(0, max(1, (int) ($baseDelay * 0.25)));
            $delivery->forceFill([
                'status' => $attempt >= $this->tries ? 'failed' : 'retrying',
                'last_error' => mb_substr($exception->getMessage(), 0, 4000),
                'next_attempt_at' => now('UTC')->addSeconds($delay),
            ])->save();
            $endpoint->forceFill(['last_failure_at' => now('UTC')])->save();

            if ($attempt >= $this->tries) {
                throw $exception;
            }

            $this->release($delay);
        }
    }
}
