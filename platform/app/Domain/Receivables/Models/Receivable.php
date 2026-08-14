<?php

namespace App\Domain\Receivables\Models;

use App\Domain\Shared\Models\DomainModel;
use App\Domain\Shared\Models\OrganizationOwned;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string $id
 * @property string $organization_id
 * @property string $company_id
 * @property string $bank_connection_id
 * @property string $erp_title_id
 * @property string $kind
 * @property string|null $subtype
 * @property string $status
 * @property string|null $provider_external_id
 * @property string $idempotency_key
 * @property string|null $reference
 * @property int $amount_minor
 * @property string $currency
 * @property CarbonImmutable|null $due_at
 * @property CarbonImmutable|null $paid_at
 * @property CarbonImmutable|null $cancelled_at
 * @property array<string,mixed>|null $metadata
 * @property int $version
 * @property string|null $copy_and_paste
 * @property string|null $barcode
 * @property string|null $digitable_line
 */
class Receivable extends DomainModel
{
    use OrganizationOwned;

    protected function casts(): array
    {
        return [
            'due_at' => 'date',
            'paid_at' => 'immutable_datetime',
            'cancelled_at' => 'immutable_datetime',
            'metadata' => 'array',
        ];
    }

    /** @return HasMany<ReceivableOperation, $this> */
    public function operations(): HasMany
    {
        return $this->hasMany(ReceivableOperation::class);
    }
}
