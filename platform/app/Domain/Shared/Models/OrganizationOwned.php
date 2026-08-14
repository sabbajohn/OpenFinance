<?php

namespace App\Domain\Shared\Models;

use App\Support\OrganizationContext;
use Illuminate\Database\Eloquent\Builder;

trait OrganizationOwned
{
    protected static function bootOrganizationOwned(): void
    {
        static::creating(function (DomainModel $model): void {
            if (! $model->getAttribute('organization_id') && app(OrganizationContext::class)->has()) {
                $model->setAttribute('organization_id', app(OrganizationContext::class)->id());
            }
        });

        static::addGlobalScope('organization', function (Builder $builder): void {
            $context = app(OrganizationContext::class);

            if ($context->has()) {
                $builder->where($builder->qualifyColumn('organization_id'), $context->id());
            }
        });
    }
}
