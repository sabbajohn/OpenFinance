<?php

namespace App\Domain\Banking\Models;

use App\Domain\Identity\Models\Company;
use App\Domain\Shared\Models\DomainModel;
use App\Domain\Shared\Models\OrganizationOwned;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string $id
 * @property string $organization_id
 * @property string $company_id
 * @property string $provider
 * @property string $environment
 * @property string $status
 * @property list<string> $capabilities
 * @property array<string,mixed>|null $encrypted_credentials
 * @property array<string,mixed>|null $sync_settings
 * @property CarbonImmutable|null $certificate_expires_at
 * @property CarbonImmutable|null $last_synced_at
 * @property int $version
 */
class BankConnection extends DomainModel
{
    use OrganizationOwned;

    protected $hidden = ['encrypted_credentials'];

    protected function casts(): array
    {
        return [
            'capabilities' => 'array',
            'encrypted_credentials' => 'encrypted:array',
            'sync_settings' => 'array',
            'certificate_expires_at' => 'datetime',
            'last_synced_at' => 'datetime',
        ];
    }

    /** @return BelongsTo<Company, $this> */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /** @return HasMany<BankAccount, $this> */
    public function accounts(): HasMany
    {
        return $this->hasMany(BankAccount::class);
    }

    /** @return HasMany<BankSandboxRun, $this> */
    public function sandboxRuns(): HasMany
    {
        return $this->hasMany(BankSandboxRun::class);
    }
}
