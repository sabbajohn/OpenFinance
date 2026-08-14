<?php

namespace App\Http\Controllers\Api\V1;

use App\Domain\Reconciliation\Models\ReconciliationCase;
use App\Domain\Reconciliation\Services\ReconciliationDecisionService;
use App\Http\Controllers\Api\V1\Concerns\ResolvesApiContext;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReconciliationController extends Controller
{
    use ResolvesApiContext;

    public function index(Request $request): JsonResponse
    {
        $client = $request->attributes->get('api_client');
        $items = ReconciliationCase::query()
            ->when($client->company_id, fn ($query) => $query->where('company_id', $client->company_id))
            ->when($request->string('status')->isNotEmpty(), fn ($query) => $query->where('status', $request->string('status')))
            ->with(['transaction', 'candidates.title'])
            ->latest()
            ->cursorPaginate(min(100, max(1, $request->integer('per_page', 50))));

        return response()->json($items);
    }

    public function decide(Request $request, ReconciliationCase $reconciliation, ReconciliationDecisionService $decisions): JsonResponse
    {
        $data = $request->validate([
            'action' => ['required', 'in:match,split,partial,adjust,classify,ignore,reverse'],
            'expected_version' => ['required', 'integer', 'min:1'],
            'payload' => ['sometimes', 'array'],
        ]);
        $client = $request->attributes->get('api_client');
        $this->assertOrganization($request, $reconciliation->organization_id);
        abort_if($client->company_id && $client->company_id !== $reconciliation->company_id, 403);

        $decision = $decisions->decide(
            case: $reconciliation,
            action: $data['action'],
            payload: $data['payload'] ?? [],
            expectedVersion: $data['expected_version'],
            idempotencyKey: $request->attributes->get('idempotency_key'),
            source: 'erp',
            actor: $client,
        );

        return response()->json(['data' => $decision], 202);
    }
}
