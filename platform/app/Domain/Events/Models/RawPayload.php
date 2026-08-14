<?php

namespace App\Domain\Events\Models;

use App\Domain\Shared\Models\DomainModel;

/**
 * @property string $id
 * @property string|null $organization_id
 * @property string $disk
 * @property string|null $path
 * @property string $status
 * @property string|null $encrypted_blob
 */
class RawPayload extends DomainModel
{
    protected $hidden = ['encrypted_blob'];

    protected function casts(): array
    {
        return [
            'stored_at' => 'immutable_datetime',
            'expires_at' => 'immutable_datetime',
        ];
    }
}
