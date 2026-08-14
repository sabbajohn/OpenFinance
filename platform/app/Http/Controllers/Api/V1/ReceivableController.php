<?php

namespace App\Http\Controllers\Api\V1;

use App\Domain\Banking\Models\BankConnection;
use App\Domain\ERP\Models\ErpTitle;
use App\Domain\Receivables\Models\Receivable;
use App\Domain\Receivables\Services\ReceivableOperationService;
use App\Domain\Receivables\Services\ReceivableService;
use App\Http\Controllers\Api\V1\Concerns\ResolvesApiContext;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReceivableController extends Controller
{
    use ResolvesApiContext;

    public function storePix(Request $request, ReceivableService $service): JsonResponse
    {
        return $this->store($request, $service, 'pix');
    }

    public function storeBoleto(Request $request, ReceivableService $service): JsonResponse
    {
        return $this->store($request, $service, 'boleto');
    }

    public function show(Request $request, Receivable $receivable): JsonResponse
    {
        $client = $this->apiClient($request);
        $this->assertOrganization($request, $receivable->organization_id);
        abort_if($client->company_id && $client->company_id !== $receivable->company_id, 403);

        return response()->json(['data' => $receivable->load('operations')]);
    }

    public function refresh(Request $request, Receivable $receivable, ReceivableOperationService $service): JsonResponse
    {
        return $this->operate($request, $receivable, $service, 'refresh');
    }

    public function refundPix(Request $request, Receivable $receivable, ReceivableOperationService $service): JsonResponse
    {
        $payload = $request->validate([
            'amount_minor' => ['required', 'integer', 'min:1'],
            'refund_id' => ['nullable', 'string', 'max:64'],
            'external_transaction_id' => ['nullable', 'string', 'max:255'],
        ]);

        return $this->operate($request, $receivable, $service, 'refund', $payload);
    }

    public function updateBoleto(Request $request, Receivable $receivable, ReceivableOperationService $service): JsonResponse
    {
        $payload = $request->validate([
            'amount_minor' => ['sometimes', 'integer', 'min:1'],
            'due_at' => ['sometimes', 'date'],
            'payer' => ['sometimes', 'array'],
            'options' => ['sometimes', 'array'],
        ]);
        abort_if($payload === [], 422, 'Informe ao menos um campo para alterar.');

        return $this->operate($request, $receivable, $service, 'update', $payload);
    }

    public function cancelBoleto(Request $request, Receivable $receivable, ReceivableOperationService $service): JsonResponse
    {
        return $this->operate($request, $receivable, $service, 'cancel');
    }

    private function store(Request $request, ReceivableService $service, string $kind): JsonResponse
    {
        $data = $request->validate([
            'erp_title_id' => ['required', 'uuid'],
            'bank_connection_id' => ['required', 'uuid'],
            'amount_minor' => ['nullable', 'integer', 'min:1'],
            'currency' => ['nullable', 'string', 'size:3'],
            'reference' => ['nullable', 'string', 'max:255'],
            'due_at' => ['nullable', 'date'],
            'subtype' => ['nullable', 'in:immediate,due,normal,hybrid'],
            'payer' => ['sometimes', 'array'],
            'options' => ['sometimes', 'array'],
        ]);
        $title = ErpTitle::query()->findOrFail((string) $data['erp_title_id']);
        $connection = BankConnection::query()->findOrFail((string) $data['bank_connection_id']);
        $this->assertOrganization($request, $title->organization_id);
        $this->assertOrganization($request, $connection->organization_id);
        $this->assertCompany($request, $title->company_id);

        $receivable = $service->create(
            kind: $kind,
            title: $title,
            connection: $connection,
            idempotencyKey: $request->attributes->get('idempotency_key'),
            attributes: $data,
        );

        return response()->json(['data' => $receivable], 202);
    }

    /** @param array<string,mixed> $payload */
    private function operate(
        Request $request,
        Receivable $receivable,
        ReceivableOperationService $service,
        string $action,
        array $payload = [],
    ): JsonResponse {
        $this->assertOrganization($request, $receivable->organization_id);
        $this->assertCompany($request, $receivable->company_id);
        $operation = $service->request(
            $receivable,
            $action,
            (string) $request->attributes->get('idempotency_key'),
            $payload,
        );

        return response()->json(['data' => $operation], 202);
    }
}
