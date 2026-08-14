<?php

namespace App\Domain\Events\Models;

use App\Domain\Shared\Models\DomainModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $id
 * @property string|null $organization_id
 * @property string|null $company_id
 * @property string|null $raw_payload_id
 * @property string $event_type
 * @property string $status
 * @property string|null $correlation_id
 */
class InboxEvent extends DomainModel
{
    protected function casts(): array
    {
        return [
            'received_at' => 'immutable_datetime',
            'processed_at' => 'immutable_datetime',
        ];
    }

    /** @return BelongsTo<RawPayload, $this> */
    public function rawPayload(): BelongsTo
    {
        return $this->belongsTo(RawPayload::class, 'raw_payload_id');
    }
}
