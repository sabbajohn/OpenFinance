<?php

namespace App\Domain\ERP\Models;

use App\Domain\Shared\Models\DomainModel;
use App\Domain\Shared\Models\OrganizationOwned;

/**
 * @property string $id
 * @property string $organization_id
 * @property string $company_id
 */
class ErpConnection extends DomainModel
{
    use OrganizationOwned;

    protected $hidden = ['encrypted_webhook_secret'];

    protected function casts(): array
    {
        return [
            'encrypted_webhook_secret' => 'encrypted',
            'last_synced_at' => 'datetime',
        ];
    }
}
