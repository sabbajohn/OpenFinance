<?php

namespace App\Http\Controllers\Api\V1;

use App\Domain\Reconciliation\Models\ReconciliationDecision;
use App\Domain\Reconciliation\Services\ReconciliationDecisionService;
use App\Http\Controllers\Api\V1\Concerns\ResolvesApiContext;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReconciliationDecisionController extends Controller
{
    use ResolvesApiContext;

    public function confirm(Request $request, ReconciliationDecision $decision, ReconciliationDecisionService $service): JsonResponse
    {
        $data = $request->validate([
            'erp_result' => ['required', 'array'],
            'erp_result.liquidation_ids' => ['required', 'array', 'min:1'],
            'erp_result.liquidation_ids.*' => ['string', 'max:255'],
        ]);
        $this->authorizeClient($request, $decision);

        return response()->json(['data' => $service->confirm($decision, $data['erp_result'])]);
    }

    public function reject(Request $request, ReconciliationDecision $decision, ReconciliationDecisionService $service): JsonResponse
    {
        $data = $request->validate([
            'erp_result' => ['required', 'array'],
            'erp_result.reason' => ['required', 'string', 'max:1000'],
        ]);
        $this->authorizeClient($request, $decision);

        return response()->json(['data' => $service->reject($decision, $data['erp_result'])]);
    }

    private function authorizeClient(Request $request, ReconciliationDecision $decision): void
    {
        $client = $request->attributes->get('api_client');
        $this->assertOrganization($request, $decision->organization_id);
        abort_if($client->company_id && $client->company_id !== $decision->company_id, 403);
    }
}
