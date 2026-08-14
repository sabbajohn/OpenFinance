<?php

namespace App\Http\Middleware;

use App\Support\OrganizationContext;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequireTwoFactorForSensitiveRole
{
    public function __construct(private readonly OrganizationContext $context) {}

    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if ($user === null) {
            abort(401);
        }

        // A autenticação forte protege comandos e alterações, mas não deve
        // impedir que o usuário conheça e navegue pela plataforma.
        if ($request->isMethodSafe()) {
            return $next($request);
        }

        $role = $user->roleFor($this->context->get());

        if ($role?->requiresTwoFactor() && ! $user->two_factor_confirmed_at) {
            if ($request->expectsJson()) {
                abort(403, 'Ative a autenticação em dois fatores para executar esta operação.');
            }

            return redirect()->route('security.edit')
                ->with('error', 'Ative a autenticação em dois fatores para executar esta operação.');
        }

        return $next($request);
    }
}
