<?php

namespace App\Domain\Identity\Models;

use App\Domain\Shared\Models\DomainModel;
use App\Domain\Shared\Models\OrganizationOwned;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

/**
 * @property string $id
 * @property string $organization_id
 * @property string|null $company_id
 * @property list<string> $scopes
 * @property list<string>|null $allowed_ips
 * @property CarbonImmutable|null $expires_at
 * @property CarbonImmutable|null $revoked_at
 */
class ApiClient extends DomainModel
{
    use OrganizationOwned;

    protected function casts(): array
    {
        return [
            'scopes' => 'array',
            'allowed_ips' => 'array',
            'last_used_at' => 'datetime',
            'expires_at' => 'datetime',
            'revoked_at' => 'datetime',
        ];
    }

    /** @return BelongsTo<Organization, $this> */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    /** @return BelongsTo<Company, $this> */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function can(string $scope): bool
    {
        return in_array('*', $this->scopes ?? [], true) || in_array($scope, $this->scopes ?? [], true);
    }

    /**
     * @param  array<string,mixed>  $attributes
     * @return array{client:self,token:string}
     */
    public static function issue(array $attributes): array
    {
        $plain = 'ofp_'.Str::random(64);
        $client = self::query()->create([
            ...$attributes,
            'token_prefix' => substr($plain, 0, 16),
            'token_hash' => hash('sha256', $plain),
        ]);

        return ['client' => $client, 'token' => $plain];
    }
}
