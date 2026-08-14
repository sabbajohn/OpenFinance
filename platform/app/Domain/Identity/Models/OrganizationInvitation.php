<?php

namespace App\Domain\Identity\Models;

use App\Domain\Shared\Models\DomainModel;
use App\Domain\Shared\Models\OrganizationOwned;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $id
 * @property string $organization_id
 * @property string $email
 * @property string $role
 * @property CarbonImmutable $expires_at
 * @property CarbonImmutable|null $accepted_at
 */
class OrganizationInvitation extends DomainModel
{
    use OrganizationOwned;

    protected $table = 'organization_invitations';

    protected $hidden = ['token_hash'];

    protected function casts(): array
    {
        return [
            'expires_at' => 'immutable_datetime',
            'accepted_at' => 'immutable_datetime',
        ];
    }

    /** @return BelongsTo<Organization, $this> */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }
}
