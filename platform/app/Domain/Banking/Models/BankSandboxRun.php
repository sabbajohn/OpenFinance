<?php

namespace App\Domain\Banking\Models;

use App\Domain\Shared\Models\DomainModel;
use App\Domain\Shared\Models\OrganizationOwned;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BankSandboxRun extends DomainModel
{
    use OrganizationOwned;

    protected function casts(): array
    {
        return [
            'steps' => 'array',
            'summary' => 'array',
            'started_at' => 'immutable_datetime',
            'finished_at' => 'immutable_datetime',
        ];
    }

    /** @return BelongsTo<BankConnection, $this> */
    public function connection(): BelongsTo
    {
        return $this->belongsTo(BankConnection::class, 'bank_connection_id');
    }

    /** @return BelongsTo<User, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
