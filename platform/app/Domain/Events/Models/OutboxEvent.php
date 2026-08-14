<?php

namespace App\Domain\Events\Models;

use App\Domain\Shared\Models\DomainModel;
use Carbon\CarbonImmutable;

/**
 * @property string $id
 * @property string|null $organization_id
 * @property string|null $company_id
 * @property string $aggregate_type
 * @property string $aggregate_id
 * @property string $event_type
 * @property int $schema_version
 * @property string|null $correlation_id
 * @property array<string,mixed> $payload
 * @property string $status
 * @property int $attempts
 * @property CarbonImmutable $available_at
 * @property CarbonImmutable|null $published_at
 */
class OutboxEvent extends DomainModel
{
    protected function casts(): array
    {
        return [
            'payload' => 'array',
            'available_at' => 'immutable_datetime',
            'published_at' => 'immutable_datetime',
        ];
    }
}
