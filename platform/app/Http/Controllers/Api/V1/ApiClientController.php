<?php

namespace App\Http\Controllers\Api\V1;

use App\Domain\Identity\Models\ApiClient;
use App\Domain\Identity\Models\Company;
use App\Http\Controllers\Api\V1\Concerns\ResolvesApiContext;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApiClientController extends Controller
{
    use ResolvesApiContext;

    public function index(): JsonResponse
    {
        return response()->json(['data' => ApiClient::query()->latest()->get()]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'company_id' => ['nullable', 'uuid'],
            'scopes' => ['required', 'array', 'min:1'],
            'scopes.*' => ['string', 'max:100'],
            'allowed_ips' => ['nullable', 'array'],
            'allowed_ips.*' => ['ip'],
            'expires_at' => ['nullable', 'date', 'after:now'],
        ]);
        $actor = $request->attributes->get('api_client');
        if (! empty($data['company_id'])) {
            abort_unless(Company::query()->withoutGlobalScopes()
                ->where('organization_id', $actor->organization_id)
                ->whereKey($data['company_id'])
                ->exists(), 422, 'A empresa não pertence à organização.');
            $this->assertCompany($request, $data['company_id']);
        }
        $issued = ApiClient::issue(['organization_id' => $actor->organization_id, ...$data]);

        return response()->json(['data' => [
            'client' => $issued['client'],
            'token' => $issued['token'],
            'warning' => 'Este token será exibido apenas agora.',
        ]], 201);
    }

    public function rotate(Request $request, ApiClient $apiClient): JsonResponse
    {
        $this->assertOrganization($request, $apiClient->organization_id);
        $apiClient->forceFill(['revoked_at' => now('UTC')])->save();
        $issued = ApiClient::issue([
            'organization_id' => $apiClient->organization_id,
            'company_id' => $apiClient->company_id,
            'name' => $apiClient->name,
            'scopes' => $apiClient->scopes,
            'allowed_ips' => $apiClient->allowed_ips,
            'expires_at' => $apiClient->expires_at,
        ]);

        return response()->json(['data' => ['client' => $issued['client'], 'token' => $issued['token']]], 201);
    }
}
