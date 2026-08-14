<?php

namespace App\Domain\ERP\Models;

use App\Domain\Shared\Models\DomainModel;
use App\Domain\Shared\Models\OrganizationOwned;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $id
 * @property string $organization_id
 * @property string $company_id
 * @property string $erp_connection_id
 * @property string|null $erp_financial_account_id
 * @property string $external_id
 * @property string $type
 * @property string $status
 * @property string $description
 * @property string|null $document_number
 * @property int $amount_minor
 * @property int $open_amount_minor
 * @property string $currency
 * @property CarbonImmutable|null $due_at
 * @property string|null $counterparty_tax_id_hash
 * @property array<string,string|null>|null $identifiers
 */
class ErpTitle extends DomainModel
{
    use OrganizationOwned;

    protected function casts(): array
    {
        return [
            'issued_at' => 'date',
            'due_at' => 'date',
            'source_updated_at' => 'immutable_datetime',
            'identifiers' => 'array',
            'metadata' => 'array',
        ];
    }

    /** @return BelongsTo<ErpFinancialAccount, $this> */
    public function financialAccount(): BelongsTo
    {
        return $this->belongsTo(ErpFinancialAccount::class, 'erp_financial_account_id');
    }
}
