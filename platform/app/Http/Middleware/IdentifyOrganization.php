<?php

namespace App\Http\Middleware;

use App\Support\OrganizationContext;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IdentifyOrganization
{
    public function __construct(private readonly OrganizationContext $context) {}

    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        $organization = $user?->currentOrganization;

        if ($user && (! $organization || ! $user->organizations()->whereKey($organization->getKey())->exists())) {
            $organization = $user->organizations()->orderBy('organizations.name')->first();

            if ($organization) {
                $user->forceFill(['current_organization_id' => $organization->getKey()])->save();
            }
        }

        abort_unless($organization !== null, 403, 'Usuário sem organização ativa.');
        $this->context->set($organization);

        try {
            return $next($request);
        } finally {
            $this->context->clear();
        }
    }
}
