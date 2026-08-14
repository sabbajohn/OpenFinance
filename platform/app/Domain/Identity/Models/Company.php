<?php

namespace App\Domain\Identity\Models;

use App\Domain\Banking\Models\BankConnection;
use App\Domain\Shared\Models\DomainModel;
use App\Domain\Shared\Models\OrganizationOwned;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string $id
 * @property string $organization_id
 */
class Company extends DomainModel
{
    use OrganizationOwned;

    /** @return BelongsTo<Organization, $this> */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    /** @return HasMany<BankConnection, $this> */
    public function bankConnections(): HasMany
    {
        return $this->hasMany(BankConnection::class);
    }
}
