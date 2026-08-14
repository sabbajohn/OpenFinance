<?php

namespace App\Domain\Receivables\Models;

use App\Domain\Shared\Models\DomainModel;
use App\Domain\Shared\Models\OrganizationOwned;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $id
 * @property string $organization_id
 * @property string $company_id
 * @property string $receivable_id
 * @property string $action
 * @property string $idempotency_key
 * @property string $status
 * @property array<string,mixed> $payload
 * @property array<string,mixed>|null $provider_result
 * @property int $attempts
 * @property string|null $last_error
 * @property CarbonImmutable|null $completed_at
 */
class ReceivableOperation extends DomainModel
{
    use OrganizationOwned;

    protected function casts(): array
    {
        return [
            'payload' => 'array',
            'provider_result' => 'array',
            'completed_at' => 'immutable_datetime',
        ];
    }

    /** @return BelongsTo<Receivable, $this> */
    public function receivable(): BelongsTo
    {
        return $this->belongsTo(Receivable::class);
    }
}
