<?php

namespace App\Domain\ERP\Models;

use App\Domain\Shared\Models\DomainModel;
use App\Domain\Shared\Models\OrganizationOwned;

/**
 * @property string $id
 * @property string $organization_id
 * @property string $company_id
 * @property string $erp_connection_id
 * @property string|null $bank_account_id
 */
class ErpFinancialAccount extends DomainModel
{
    use OrganizationOwned;

    protected function casts(): array
    {
        return ['metadata' => 'array'];
    }
}
