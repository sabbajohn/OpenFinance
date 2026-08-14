<?php

namespace App\Domain\Events\Models;

use App\Domain\Shared\Models\DomainModel;
use App\Domain\Shared\Models\OrganizationOwned;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $id
 * @property string $organization_id
 * @property string $webhook_endpoint_id
 * @property string $outbox_event_id
 * @property string $status
 * @property int $attempts
 */
class WebhookDelivery extends DomainModel
{
    use OrganizationOwned;

    protected function casts(): array
    {
        return [
            'next_attempt_at' => 'immutable_datetime',
            'delivered_at' => 'immutable_datetime',
        ];
    }

    /** @return BelongsTo<WebhookEndpoint, $this> */
    public function endpoint(): BelongsTo
    {
        return $this->belongsTo(WebhookEndpoint::class, 'webhook_endpoint_id');
    }
}
