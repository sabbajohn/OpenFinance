<?php

namespace App\Domain\Events\Models;

use App\Domain\Shared\Models\DomainModel;
use App\Domain\Shared\Models\OrganizationOwned;

/**
 * @property string $id
 * @property string $organization_id
 * @property string|null $company_id
 * @property string $url
 * @property string $encrypted_secret
 * @property list<string> $events
 */
class WebhookEndpoint extends DomainModel
{
    use OrganizationOwned;

    protected $hidden = ['encrypted_secret'];

    protected function casts(): array
    {
        return [
            'encrypted_secret' => 'encrypted',
            'events' => 'array',
            'last_success_at' => 'datetime',
            'last_failure_at' => 'datetime',
        ];
    }
}
