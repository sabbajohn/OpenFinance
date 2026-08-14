<?php

namespace App\Domain\Banking\Models;

use App\Domain\Shared\Models\DomainModel;
use App\Domain\Shared\Models\OrganizationOwned;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string $id
 * @property string $organization_id
 * @property string $company_id
 * @property string $bank_connection_id
 * @property string $provider_account_id
 * @property int|null $available_balance_minor
 * @property int|null $current_balance_minor
 * @property CarbonImmutable|null $balance_observed_at
 * @property array<string,mixed>|null $metadata
 */
class BankAccount extends DomainModel
{
    use OrganizationOwned;

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
            'balance_observed_at' => 'datetime',
        ];
    }

    /** @return BelongsTo<BankConnection, $this> */
    public function connection(): BelongsTo
    {
        return $this->belongsTo(BankConnection::class, 'bank_connection_id');
    }

    /** @return HasMany<BankTransaction, $this> */
    public function transactions(): HasMany
    {
        return $this->hasMany(BankTransaction::class);
    }
}
