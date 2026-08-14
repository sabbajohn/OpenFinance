<?php

namespace Tests\Feature;

use App\Domain\Banking\Models\BankConnection;
use App\Domain\ERP\Models\ErpConnection;
use App\Domain\ERP\Models\ErpTitle;
use App\Domain\Identity\Models\Company;
use App\Domain\Identity\Models\Organization;
use App\Domain\Receivables\Models\Receivable;
use App\Domain\Receivables\Services\ReceivableOperationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Tests\TestCase;

class ReceivableOperationTest extends TestCase
{
    use RefreshDatabase;

    public function test_pix_refund_operation_is_idempotent(): void
    {
        Queue::fake();
        $receivable = $this->receivable('pix', 'paid');
        $service = app(ReceivableOperationService::class);

        $first = $service->request($receivable, 'refund', 'refund-1', ['amount_minor' => 500]);
        $second = $service->request($receivable, 'refund', 'refund-1', ['amount_minor' => 500]);

        $this->assertTrue($first->is($second));
        $this->assertDatabaseCount('receivable_operations', 1);
    }

    public function test_unpaid_pix_cannot_be_refunded(): void
    {
        $this->expectException(UnprocessableEntityHttpException::class);
        app(ReceivableOperationService::class)->request(
            $this->receivable('pix', 'active'),
            'refund',
            'refund-invalid',
            ['amount_minor' => 500],
        );
    }

    private function receivable(string $kind, string $status): Receivable
    {
        $organization = Organization::query()->create(['name' => 'Org', 'slug' => 'org-'.uniqid()]);
        $company = Company::query()->create([
            'organization_id' => $organization->id,
            'legal_name' => 'Company',
            'tax_id' => (string) random_int(10000000000000, 99999999999999),
        ]);
        $connection = BankConnection::query()->withoutGlobalScopes()->create([
            'organization_id' => $organization->id,
            'company_id' => $company->id,
            'provider' => 'sicredi',
            'name' => 'Sicredi',
            'status' => 'active',
            'capabilities' => ['pix', 'boleto'],
        ]);
        $erp = ErpConnection::query()->withoutGlobalScopes()->create([
            'organization_id' => $organization->id,
            'company_id' => $company->id,
            'name' => 'ERP',
            'status' => 'active',
        ]);
        $title = ErpTitle::query()->withoutGlobalScopes()->create([
            'organization_id' => $organization->id,
            'company_id' => $company->id,
            'erp_connection_id' => $erp->id,
            'external_id' => 'title-1',
            'type' => 'receivable',
            'status' => 'open',
            'description' => 'Venda',
            'amount_minor' => 1000,
            'open_amount_minor' => 1000,
        ]);

        return Receivable::query()->withoutGlobalScopes()->create([
            'organization_id' => $organization->id,
            'company_id' => $company->id,
            'bank_connection_id' => $connection->id,
            'erp_title_id' => $title->id,
            'kind' => $kind,
            'provider_external_id' => 'provider-1',
            'idempotency_key' => 'create-1',
            'status' => $status,
            'amount_minor' => 1000,
        ]);
    }
}
