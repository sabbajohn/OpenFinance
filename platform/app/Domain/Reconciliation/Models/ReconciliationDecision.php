<?php

namespace App\Domain\Reconciliation\Models;

use App\Domain\Shared\Models\DomainModel;
use App\Domain\Shared\Models\OrganizationOwned;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $id
 * @property string $organization_id
 * @property string $company_id
 * @property string $reconciliation_case_id
 * @property string $action
 * @property string $status
 * @property int $expected_version
 * @property array<string,mixed> $payload
 * @property array<string,mixed>|null $erp_result
 */
class ReconciliationDecision extends DomainModel
{
    use OrganizationOwned;

    protected function casts(): array
    {
        return [
            'payload' => 'array',
            'erp_result' => 'array',
            'confirmed_at' => 'immutable_datetime',
            'rejected_at' => 'immutable_datetime',
        ];
    }

    /** @return BelongsTo<ReconciliationCase, $this> */
    public function reconciliationCase(): BelongsTo
    {
        return $this->belongsTo(ReconciliationCase::class);
    }
}
