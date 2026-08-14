<?php

namespace App\Http\Middleware;

use App\Domain\Audit\Models\AuditLog;
use App\Domain\Identity\Models\ApiClient;
use App\Support\OrganizationContext;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuditRequest
{
    public function __construct(private readonly OrganizationContext $context) {}

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        if (! $request->isMethodSafe() && $this->context->has()) {
            $client = $request->attributes->get('api_client');
            $companyId = $client instanceof ApiClient ? $client->company_id : $request->input('company_id');
            AuditLog::query()->create([
                'organization_id' => $this->context->id(),
                'company_id' => $companyId,
                'user_id' => $request->user()?->getKey(),
                'api_client_id' => $client instanceof ApiClient ? $client->getKey() : null,
                'action' => ($request->route()?->getName() ?: $request->method().' '.$request->path()),
                'subject_type' => null,
                'subject_id' => null,
                'ip_hash' => hash_hmac('sha256', (string) $request->ip(), (string) config('app.key')),
                'metadata' => ['method' => $request->method(), 'status' => $response->getStatusCode()],
                'occurred_at' => now('UTC'),
            ]);
        }

        return $response;
    }
}
