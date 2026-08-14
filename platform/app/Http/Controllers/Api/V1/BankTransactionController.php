<?php

namespace App\Http\Controllers\Api\V1;

use App\Domain\Banking\Models\BankTransaction;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BankTransactionController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $filters = $request->validate([
            'company_id' => ['nullable', 'uuid'],
            'bank_account_id' => ['nullable', 'uuid'],
            'status' => ['nullable', 'string'],
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:200'],
        ]);
        $client = $request->attributes->get('api_client');

        $items = BankTransaction::query()
            ->when($client->company_id, fn ($query) => $query->where('company_id', $client->company_id))
            ->when($filters['company_id'] ?? null, fn ($query, $id) => $query->where('company_id', $id))
            ->when($filters['bank_account_id'] ?? null, fn ($query, $id) => $query->where('bank_account_id', $id))
            ->when($filters['status'] ?? null, fn ($query, $status) => $query->where('status', $status))
            ->when($filters['from'] ?? null, fn ($query, $from) => $query->where('occurred_at', '>=', $from))
            ->when($filters['to'] ?? null, fn ($query, $to) => $query->where('occurred_at', '<=', $to))
            ->latest('occurred_at')
            ->cursorPaginate($filters['per_page'] ?? 100);

        return response()->json($items);
    }
}
