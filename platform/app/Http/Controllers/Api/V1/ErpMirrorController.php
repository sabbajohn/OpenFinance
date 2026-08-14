<?php

namespace App\Http\Controllers\Api\V1;

use App\Domain\Banking\Models\BankAccount;
use App\Domain\ERP\Models\ErpConnection;
use App\Domain\ERP\Models\ErpFinancialAccount;
use App\Domain\ERP\Models\ErpTitle;
use App\Domain\Reconciliation\Jobs\ReevaluateCompanyReconciliations;
use App\Http\Controllers\Api\V1\Concerns\ResolvesApiContext;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ErpMirrorController extends Controller
{
    use ResolvesApiContext;

    public function accounts(Request $request): JsonResponse
    {
        $data = $request->validate([
            'company_id' => ['required', 'uuid'],
            'erp_connection_id' => ['required', 'uuid'],
            'items' => ['required', 'array', 'max:1000'],
            'items.*.external_id' => ['required', 'string', 'max:255'],
            'items.*.name' => ['required', 'string', 'max:255'],
            'items.*.currency' => ['sometimes', 'string', 'size:3'],
            'items.*.status' => ['sometimes', 'in:active,inactive'],
            'items.*.bank_account_id' => ['nullable', 'uuid'],
            'items.*.metadata' => ['sometimes', 'array'],
        ]);
        $companyId = (string) $data['company_id'];
        $this->assertCompany($request, $companyId);
        $connection = ErpConnection::query()->findOrFail((string) $data['erp_connection_id']);
        $this->assertOrganization($request, $connection->organization_id);
        abort_unless($connection->company_id === $companyId, 422, 'Integração ERP não pertence à empresa.');

        $items = DB::transaction(fn () => collect((array) $data['items'])->map(function (array $item) use ($connection, $companyId) {
            if (! empty($item['bank_account_id'])) {
                abort_unless(BankAccount::query()
                    ->whereKey((string) $item['bank_account_id'])
                    ->where('company_id', $companyId)
                    ->exists(), 422, 'Conta bancária mapeada não pertence à empresa.');
            }

            return ErpFinancialAccount::query()->updateOrCreate(
                ['erp_connection_id' => $connection->getKey(), 'external_id' => $item['external_id']],
                [
                    'organization_id' => $connection->organization_id,
                    'company_id' => $companyId,
                    'name' => $item['name'],
                    'currency' => strtoupper($item['currency'] ?? 'BRL'),
                    'status' => $item['status'] ?? 'active',
                    'bank_account_id' => $item['bank_account_id'] ?? null,
                    'metadata' => $item['metadata'] ?? [],
                ],
            );
        }));

        return response()->json(['data' => ['processed' => $items->count()]], 202);
    }

    public function titles(Request $request): JsonResponse
    {
        $data = $request->validate([
            'company_id' => ['required', 'uuid'],
            'erp_connection_id' => ['required', 'uuid'],
            'items' => ['required', 'array', 'max:1000'],
            'items.*.external_id' => ['required', 'string', 'max:255'],
            'items.*.external_version' => ['nullable', 'string', 'max:255'],
            'items.*.financial_account_external_id' => ['nullable', 'string', 'max:255'],
            'items.*.type' => ['required', 'in:receivable,payable,receive,pay'],
            'items.*.status' => ['required', 'in:open,paid,cancelled'],
            'items.*.description' => ['required', 'string', 'max:500'],
            'items.*.amount_minor' => ['required', 'integer', 'min:0'],
            'items.*.open_amount_minor' => ['required', 'integer', 'min:0'],
            'items.*.currency' => ['sometimes', 'string', 'size:3'],
            'items.*.document_number' => ['nullable', 'string', 'max:255'],
            'items.*.issued_at' => ['nullable', 'date'],
            'items.*.due_at' => ['nullable', 'date'],
            'items.*.counterparty_name' => ['nullable', 'string', 'max:255'],
            'items.*.counterparty_tax_id' => ['nullable', 'string', 'max:32'],
            'items.*.identifiers' => ['sometimes', 'array'],
            'items.*.metadata' => ['sometimes', 'array'],
            'items.*.source_updated_at' => ['nullable', 'date'],
        ]);
        $companyId = (string) $data['company_id'];
        $this->assertCompany($request, $companyId);
        $connection = ErpConnection::query()->findOrFail((string) $data['erp_connection_id']);
        $this->assertOrganization($request, $connection->organization_id);
        abort_unless($connection->company_id === $companyId, 422, 'Integração ERP não pertence à empresa.');

        $items = DB::transaction(fn () => collect((array) $data['items'])->map(function (array $item) use ($connection, $companyId) {
            $accountId = empty($item['financial_account_external_id']) ? null : ErpFinancialAccount::query()
                ->where('erp_connection_id', $connection->getKey())
                ->where('external_id', $item['financial_account_external_id'])
                ->value('id');
            $taxId = preg_replace('/\D+/', '', (string) ($item['counterparty_tax_id'] ?? ''));

            return ErpTitle::query()->updateOrCreate(
                ['erp_connection_id' => $connection->getKey(), 'external_id' => $item['external_id']],
                [
                    'organization_id' => $connection->organization_id,
                    'company_id' => $companyId,
                    'erp_financial_account_id' => $accountId,
                    'external_version' => $item['external_version'] ?? null,
                    'type' => $item['type'],
                    'status' => $item['status'],
                    'document_number' => $item['document_number'] ?? null,
                    'description' => $item['description'],
                    'amount_minor' => $item['amount_minor'],
                    'open_amount_minor' => $item['open_amount_minor'],
                    'currency' => strtoupper($item['currency'] ?? 'BRL'),
                    'issued_at' => $item['issued_at'] ?? null,
                    'due_at' => $item['due_at'] ?? null,
                    'counterparty_name' => $item['counterparty_name'] ?? null,
                    'counterparty_tax_id_hash' => $taxId ? hash_hmac('sha256', $taxId, (string) config('app.key')) : null,
                    'identifiers' => $item['identifiers'] ?? [],
                    'metadata' => $item['metadata'] ?? [],
                    'source_updated_at' => $item['source_updated_at'] ?? null,
                ],
            );
        }));
        ReevaluateCompanyReconciliations::dispatch($connection->organization_id, $companyId)->afterCommit();

        return response()->json(['data' => ['processed' => $items->count()]], 202);
    }
}
