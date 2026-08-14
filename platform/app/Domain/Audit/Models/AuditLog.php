<?php

namespace App\Domain\Audit\Models;

use App\Domain\Shared\Models\DomainModel;

class AuditLog extends DomainModel
{
    protected function casts(): array
    {
        return [
            'metadata' => 'array',
            'occurred_at' => 'immutable_datetime',
            'created_at' => 'immutable_datetime',
            'updated_at' => 'immutable_datetime',
        ];
    }
}
