<?php

namespace App\Http\Middleware;

use App\Domain\Identity\Models\ApiClient;
use App\Support\OrganizationContext;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateApiClient
{
    public function __construct(private readonly OrganizationContext $context) {}

    public function handle(Request $request, Closure $next): Response
    {
        $plain = (string) $request->bearerToken();
        $client = $plain === '' ? null : ApiClient::query()
            ->withoutGlobalScopes()
            ->where('token_hash', hash('sha256', $plain))
            ->with('organization')
            ->first();

        if (! $client || $client->revoked_at || ($client->expires_at && $client->expires_at->isPast())) {
            return new JsonResponse(['message' => 'Token de integração inválido.'], 401);
        }

        if ($client->allowed_ips && ! in_array($request->ip(), $client->allowed_ips, true)) {
            return new JsonResponse(['message' => 'Origem não autorizada para este cliente.'], 403);
        }

        $this->context->set($client->organization);
        $request->attributes->set('api_client', $client);
        $client->forceFill(['last_used_at' => now()])->saveQuietly();

        try {
            return $next($request);
        } finally {
            $this->context->clear();
        }
    }
}
