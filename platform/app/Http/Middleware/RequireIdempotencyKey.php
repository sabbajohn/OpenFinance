<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequireIdempotencyKey
{
    public function handle(Request $request, Closure $next): Response
    {
        $key = trim((string) $request->header('Idempotency-Key'));
        if ($key === '' || strlen($key) > 200) {
            return new JsonResponse(['message' => 'Envie um Idempotency-Key válido (até 200 caracteres).'], 422);
        }

        $request->attributes->set('idempotency_key', $key);

        return $next($request);
    }
}
