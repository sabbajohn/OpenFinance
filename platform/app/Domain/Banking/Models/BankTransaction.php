<?php

namespace App\Domain\Banking\Models;

use App\Domain\Reconciliation\Models\ReconciliationCase;
use App\Domain\Shared\Models\DomainModel;
use App\Domain\Shared\Models\OrganizationOwned;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property string $id
 * @property string $organization_id
 * @property string $company_id
 * @property string $bank_connection_id
 * @property string $bank_account_id
 * @property string|null $external_id
 * @property string $direction
 * @property string $status
 * @property int $amount_minor
 * @property string $currency
 * @property CarbonImmutable $occurred_at
 * @property CarbonImmutable $observed_at
 * @property string|null $description
 * @property string|null $counterparty_name
 * @property string|null $counterparty_tax_id_hash
 * @property array<string,string|null>|null $identifiers
 * @property array<string,mixed>|null $metadata
 * @property int $version
 */
class BankTransaction extends DomainModel
{
    use OrganizationOwned;

    protected function casts(): array
    {
        return [
            'occurred_at' => 'immutable_datetime',
            'observed_at' => 'immutable_datetime',
            'deleted_at' => 'immutable_datetime',
            'identifiers' => 'array',
            'metadata' => 'array',
        ];
    }

    /** @return BelongsTo<BankAccount, $this> */
    public function account(): BelongsTo
    {
        return $this->belongsTo(BankAccount::class, 'bank_account_id');
    }

    /** @return BelongsTo<BankConnection, $this> */
    public function connection(): BelongsTo
    {
        return $this->belongsTo(BankConnection::class, 'bank_connection_id');
    }

    /** @return HasOne<ReconciliationCase, $this> */
    public function reconciliationCase(): HasOne
    {
        return $this->hasOne(ReconciliationCase::class);
    }
}
