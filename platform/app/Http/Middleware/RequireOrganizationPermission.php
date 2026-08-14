<?php

namespace App\Http\Middleware;

use App\Domain\Identity\Enums\OrganizationPermission;
use App\Support\OrganizationContext;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequireOrganizationPermission
{
    public function __construct(private readonly OrganizationContext $context) {}

    public function handle(Request $request, Closure $next, string $permission): Response
    {
        $required = OrganizationPermission::tryFrom($permission);
        abort_if($required === null, 500, 'Permissão de organização inválida.');

        $role = $request->user()?->roleFor($this->context->get());
        abort_unless($role?->allows($required), 403, 'Seu perfil não permite acessar este recurso.');

        return $next($request);
    }
}
