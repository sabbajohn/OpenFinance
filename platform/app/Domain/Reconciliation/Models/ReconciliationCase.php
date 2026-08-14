<?php

namespace App\Domain\Reconciliation\Models;

use App\Domain\Banking\Models\BankTransaction;
use App\Domain\Shared\Models\DomainModel;
use App\Domain\Shared\Models\OrganizationOwned;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string $id
 * @property string $organization_id
 * @property string $company_id
 * @property string $bank_transaction_id
 * @property string $status
 * @property int $version
 * @property bool $auto_eligible
 */
class ReconciliationCase extends DomainModel
{
    use OrganizationOwned;

    protected function casts(): array
    {
        return [
            'auto_eligible' => 'boolean',
            'resolved_at' => 'immutable_datetime',
        ];
    }

    /** @return BelongsTo<BankTransaction, $this> */
    public function transaction(): BelongsTo
    {
        return $this->belongsTo(BankTransaction::class, 'bank_transaction_id');
    }

    /** @return HasMany<ReconciliationCandidate, $this> */
    public function candidates(): HasMany
    {
        return $this->hasMany(ReconciliationCandidate::class);
    }

    /** @return HasMany<ReconciliationDecision, $this> */
    public function decisions(): HasMany
    {
        return $this->hasMany(ReconciliationDecision::class);
    }
}
