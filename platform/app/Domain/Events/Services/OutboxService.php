<?php

namespace App\Domain\Events\Services;

use App\Domain\Events\Models\OutboxEvent;
use Illuminate\Database\Eloquent\Model;

class OutboxService
{
    /** @param array<string,mixed> $payload */
    public function record(
        string $eventType,
        string $aggregateType,
        string $aggregateId,
        array $payload,
        ?string $organizationId,
        ?string $companyId,
        ?string $correlationId = null,
    ): OutboxEvent {
        return OutboxEvent::query()->create([
            'organization_id' => $organizationId,
            'company_id' => $companyId,
            'aggregate_type' => $aggregateType,
            'aggregate_id' => $aggregateId,
            'event_type' => $eventType,
            'schema_version' => 1,
            'correlation_id' => $correlationId,
            'payload' => $payload,
            'status' => 'pending',
            'available_at' => now('UTC'),
        ]);
    }

    /** @param array<string,mixed> $payload */
    public function forModel(string $eventType, Model $model, array $payload, ?string $correlationId = null): OutboxEvent
    {
        return $this->record(
            eventType: $eventType,
            aggregateType: $model->getMorphClass(),
            aggregateId: (string) $model->getKey(),
            payload: $payload,
            organizationId: $model->getAttribute('organization_id'),
            companyId: $model->getAttribute('company_id'),
            correlationId: $correlationId,
        );
    }
}
