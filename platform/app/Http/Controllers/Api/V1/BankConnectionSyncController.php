<?php

namespace App\Http\Controllers\Api\V1;

use App\Domain\Banking\Jobs\SyncBankConnection;
use App\Domain\Banking\Models\BankConnection;
use App\Http\Controllers\Api\V1\Concerns\ResolvesApiContext;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BankConnectionSyncController extends Controller
{
    use ResolvesApiContext;

    public function __invoke(Request $request, BankConnection $bankConnection): JsonResponse
    {
        $data = $request->validate([
            'from' => ['nullable', 'date', 'after_or_equal:'.now('UTC')->subDays(90)->toDateString()],
            'to' => ['nullable', 'date', 'after_or_equal:from'],
        ]);
        $client = $request->attributes->get('api_client');
        $this->assertOrganization($request, $bankConnection->organization_id);
        abort_if($client->company_id && $client->company_id !== $bankConnection->company_id, 403);
        SyncBankConnection::dispatch((string) $bankConnection->getKey(), $data['from'] ?? null, $data['to'] ?? null);

        return response()->json(['data' => ['status' => 'queued']], 202);
    }
}
