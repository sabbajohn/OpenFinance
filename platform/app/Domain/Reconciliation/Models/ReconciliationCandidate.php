<?php

namespace App\Domain\Reconciliation\Models;

use App\Domain\ERP\Models\ErpTitle;
use App\Domain\Shared\Models\DomainModel;
use App\Domain\Shared\Models\OrganizationOwned;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $id
 * @property string $erp_title_id
 * @property int $score
 * @property int $suggested_amount_minor
 */
class ReconciliationCandidate extends DomainModel
{
    use OrganizationOwned;

    protected function casts(): array
    {
        return ['signals' => 'array'];
    }

    /** @return BelongsTo<ErpTitle, $this> */
    public function title(): BelongsTo
    {
        return $this->belongsTo(ErpTitle::class, 'erp_title_id');
    }

    /** @return BelongsTo<ReconciliationCase, $this> */
    public function reconciliationCase(): BelongsTo
    {
        return $this->belongsTo(ReconciliationCase::class);
    }
}
