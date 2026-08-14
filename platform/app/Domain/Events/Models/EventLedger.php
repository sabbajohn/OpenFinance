<?php

namespace App\Domain\Events\Models;

use App\Domain\Shared\Models\DomainModel;

class EventLedger extends DomainModel
{
    protected $table = 'event_ledger';

    protected function casts(): array
    {
        return [
            'payload' => 'array',
            'occurred_at' => 'immutable_datetime',
        ];
    }
}
