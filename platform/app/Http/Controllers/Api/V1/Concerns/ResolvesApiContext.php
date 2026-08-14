<?php

namespace App\Http\Controllers\Api\V1\Concerns;

use App\Domain\Identity\Models\ApiClient;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

trait ResolvesApiContext
{
    protected function apiClient(Request $request): ApiClient
    {
        return $request->attributes->get('api_client');
    }

    protected function assertCompany(Request $request, string $companyId): void
    {
        $client = $this->apiClient($request);
        if ($client->company_id && $client->company_id !== $companyId) {
            throw new AccessDeniedHttpException('O cliente de API está restrito a outra empresa.');
        }
    }

    protected function assertOrganization(Request $request, string $organizationId): void
    {
        if ($this->apiClient($request)->organization_id !== $organizationId) {
            throw new AccessDeniedHttpException('O recurso pertence a outra organização.');
        }
    }
}
