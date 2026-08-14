<?php

namespace App\Http\Middleware;

use App\Domain\Identity\Models\ApiClient;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequireApiScope
{
    public function handle(Request $request, Closure $next, string ...$scopes): Response
    {
        /** @var ApiClient|null $client */
        $client = $request->attributes->get('api_client');

        if (! $client || collect($scopes)->doesntContain(fn (string $scope): bool => $client->can($scope))) {
            return new JsonResponse(['message' => 'Token sem o escopo necessário.'], 403);
        }

        return $next($request);
    }
}
