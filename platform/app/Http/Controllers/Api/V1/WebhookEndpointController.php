<?php

namespace App\Http\Controllers\Api\V1;

use App\Domain\Events\Jobs\DeliverWebhook;
use App\Domain\Events\Models\WebhookDelivery;
use App\Domain\Events\Models\WebhookEndpoint;
use App\Domain\Identity\Models\Company;
use App\Http\Controllers\Api\V1\Concerns\ResolvesApiContext;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class WebhookEndpointController extends Controller
{
    use ResolvesApiContext;

    public function index(): JsonResponse
    {
        return response()->json(['data' => WebhookEndpoint::query()->latest()->get()]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'company_id' => ['nullable', 'uuid'],
            'name' => ['required', 'string', 'max:255'],
            'url' => ['required', 'url:http,https', 'max:2048'],
            'events' => ['required', 'array', 'min:1'],
            'events.*' => ['string', 'max:128'],
        ]);
        $client = $request->attributes->get('api_client');
        if (! empty($data['company_id'])) {
            $this->assertCompany($request, $data['company_id']);
            abort_unless(Company::query()->withoutGlobalScopes()
                ->where('organization_id', $client->organization_id)
                ->whereKey($data['company_id'])
                ->exists(), 422, 'A empresa não pertence à organização.');
        }
        $secret = 'whsec_'.Str::random(64);
        $endpoint = WebhookEndpoint::query()->create([
            'organization_id' => $client->organization_id,
            ...$data,
            'encrypted_secret' => $secret,
            'status' => 'active',
        ]);

        return response()->json(['data' => [
            'endpoint' => $endpoint,
            'secret' => $secret,
            'warning' => 'Este segredo será exibido apenas agora.',
        ]], 201);
    }

    public function replay(Request $request, WebhookDelivery $delivery): JsonResponse
    {
        $this->assertOrganization($request, $delivery->organization_id);
        $delivery->forceFill(['status' => 'pending', 'next_attempt_at' => now('UTC'), 'last_error' => null])->save();
        DeliverWebhook::dispatch((string) $delivery->getKey());

        return response()->json(['data' => ['status' => 'queued']], 202);
    }
}
