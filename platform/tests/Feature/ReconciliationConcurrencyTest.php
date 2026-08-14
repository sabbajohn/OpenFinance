<?php

namespace Tests\Feature;

use App\Domain\Banking\Models\BankAccount;
use App\Domain\Banking\Models\BankConnection;
use App\Domain\Banking\Services\BankTransactionIngestor;
use App\Domain\ERP\Models\ErpConnection;
use App\Domain\ERP\Models\ErpFinancialAccount;
use App\Domain\ERP\Models\ErpTitle;
use App\Domain\Identity\Models\Company;
use App\Domain\Identity\Models\Organization;
use App\Domain\Reconciliation\Services\ReconciliationDecisionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Tests\TestCase;

class ReconciliationConcurrencyTest extends TestCase
{
    use RefreshDatabase;

    public function test_exact_match_is_eligible_but_only_one_decision_wins(): void
    {
        config()->set('openfinance.reconciliation.auto_enabled', false);
        $organization = Organization::query()->create(['name' => 'Org', 'slug' => 'org']);
        $company = Company::query()->create(['organization_id' => $organization->id, 'legal_name' => 'Company', 'tax_id' => '12345678000199']);
        $connection = BankConnection::query()->withoutGlobalScopes()->create([
            'organization_id' => $organization->id, 'company_id' => $company->id, 'provider' => 'sicredi',
            'name' => 'Sicredi', 'status' => 'active', 'capabilities' => ['transactions'],
        ]);
        $account = BankAccount::query()->withoutGlobalScopes()->create([
            'organization_id' => $organization->id, 'company_id' => $company->id,
            'bank_connection_id' => $connection->id, 'provider_account_id' => 'account-1',
        ]);
        $erp = ErpConnection::query()->withoutGlobalScopes()->create([
            'organization_id' => $organization->id, 'company_id' => $company->id, 'name' => 'ERP', 'status' => 'active',
        ]);
        $erpAccount = ErpFinancialAccount::query()->withoutGlobalScopes()->create([
            'organization_id' => $organization->id, 'company_id' => $company->id, 'erp_connection_id' => $erp->id,
            'bank_account_id' => $account->id, 'external_id' => 'cash-1', 'name' => 'Banco',
        ]);
        $title = ErpTitle::query()->withoutGlobalScopes()->create([
            'organization_id' => $organization->id, 'company_id' => $company->id, 'erp_connection_id' => $erp->id,
            'erp_financial_account_id' => $erpAccount->id, 'external_id' => '42', 'type' => 'receivable',
            'status' => 'open', 'description' => 'Venda 42', 'amount_minor' => 15000, 'open_amount_minor' => 15000,
            'identifiers' => ['txid' => 'exact-42'],
        ]);

        $transaction = app(BankTransactionIngestor::class)->ingest([
            'connection_id' => $connection->id,
            'account_id' => $account->id,
            'transaction' => [
                'external_id' => 'bank-42', 'type' => 'pix', 'direction' => 'credit', 'status' => 'posted',
                'amount_minor' => 15000, 'currency' => 'BRL', 'occurred_at' => now()->toIso8601String(),
                'observed_at' => now()->toIso8601String(), 'description' => 'PIX venda 42',
                'identifiers' => ['txid' => 'exact-42'],
            ],
        ]);
        $case = $transaction->reconciliationCase()->firstOrFail();
        $this->assertTrue($case->auto_eligible);

        $service = app(ReconciliationDecisionService::class);
        $decision = $service->decide($case, 'match', [
            'allocations' => [['erp_title_id' => $title->id, 'amount_minor' => 15000]],
        ], 1, 'decision-one', 'hub');
        $this->assertSame('pending_erp', $decision->status);

        $this->expectException(ConflictHttpException::class);
        $service->decide($case, 'match', [
            'allocations' => [['erp_title_id' => $title->id, 'amount_minor' => 15000]],
        ], 1, 'decision-two', 'erp');
    }
}
