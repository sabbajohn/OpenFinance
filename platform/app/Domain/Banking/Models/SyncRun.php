<?php

namespace App\Domain\Banking\Models;

use App\Domain\Shared\Models\DomainModel;
use App\Domain\Shared\Models\OrganizationOwned;

class SyncRun extends DomainModel
{
    use OrganizationOwned;

    protected function casts(): array
    {
        return [
            'checkpoint' => 'array',
            'started_at' => 'immutable_datetime',
            'finished_at' => 'immutable_datetime',
        ];
    }
}
