<?php

namespace App\Domain\Events\Services;

use App\Domain\Events\Jobs\ProcessInboxEvent;
use App\Domain\Events\Models\InboxEvent;
use Illuminate\Database\UniqueConstraintViolationException;

class InboxService
{
    public function __construct(private readonly RawPayloadStore $payloads) {}

    /** @param array<string,mixed> $payload */
    public function receive(
        string $source,
        string $eventType,
        string $idempotencyKey,
        array $payload,
        ?string $organizationId = null,
        ?string $companyId = null,
        ?string $correlationId = null,
    ): InboxEvent {
        if ($existing = InboxEvent::query()->where('idempotency_key', $idempotencyKey)->first()) {
            return $existing;
        }

        $raw = $this->payloads->store($payload, $organizationId, $source);

        try {
            $event = InboxEvent::query()->create([
                'organization_id' => $organizationId,
                'company_id' => $companyId,
                'raw_payload_id' => $raw->getKey(),
                'source' => $source,
                'event_type' => $eventType,
                'idempotency_key' => $idempotencyKey,
                'status' => 'received',
                'correlation_id' => $correlationId,
                'received_at' => now('UTC'),
            ]);
        } catch (UniqueConstraintViolationException) {
            return InboxEvent::query()->where('idempotency_key', $idempotencyKey)->firstOrFail();
        }

        ProcessInboxEvent::dispatch($event->getKey())->afterCommit();

        return $event;
    }
}
