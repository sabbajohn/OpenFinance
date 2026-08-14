<?php

namespace Database\Seeders;

use App\Domain\Banking\Models\BankAccount;
use App\Domain\Banking\Models\BankConnection;
use App\Domain\Identity\Models\Company;
use App\Domain\Identity\Models\Organization;
use Illuminate\Database\Seeder;
use Sabba\OpenFinance\Core\Enums\Capability;

class DemoBankingSeeder extends Seeder
{
    public function run(): void
    {
        if (app()->isProduction()) {
            return;
        }

        $organization = Organization::query()->where('slug', 'sabba-sistemas')->firstOrFail();
        $company = Company::query()->withoutGlobalScopes()
            ->where('organization_id', $organization->getKey())
            ->where('tax_id', '00000000000191')
            ->firstOrFail();

        $connection = BankConnection::query()->withoutGlobalScopes()->updateOrCreate(
            [
                'organization_id' => $organization->getKey(),
                'company_id' => $company->getKey(),
                'provider' => 'sicredi',
                'name' => 'Sicredi Sandbox — dados simulados',
                'environment' => 'sandbox',
            ],
            [
                // A conexão é apenas o agrupador das contas simuladas; sem
                // credenciais, ela não deve entrar no polling automático.
                'status' => 'draft',
                'capabilities' => [
                    Capability::Accounts->value,
                    Capability::Balances->value,
                    Capability::Transactions->value,
                    Capability::PixImmediate->value,
                    Capability::PixDue->value,
                    Capability::PixRefund->value,
                    Capability::BoletoNormal->value,
                    Capability::BoletoHybrid->value,
                    Capability::Webhooks->value,
                ],
                'sync_settings' => [
                    'interval_minutes' => 15,
                    'overlap_days' => 3,
                    'demo' => true,
                ],
            ],
        );

        $observedAt = now('UTC')->startOfMinute();
        $accounts = [
            [
                'provider_account_id' => 'demo-checking-001',
                'type' => 'checking',
                'branch' => '0710',
                'number_masked' => '**** 4521-9',
                'available_balance_minor' => 17_932_780,
                'current_balance_minor' => 18_452_780,
                'metadata' => ['label' => 'Conta Corrente Principal', 'purpose' => 'operating', 'demo' => true],
            ],
            [
                'provider_account_id' => 'demo-collection-001',
                'type' => 'checking',
                'branch' => '0710',
                'number_masked' => '**** 8890-3',
                'available_balance_minor' => 4_825_420,
                'current_balance_minor' => 4_825_420,
                'metadata' => ['label' => 'Conta Cobrança e Pix', 'purpose' => 'receivables', 'demo' => true],
            ],
        ];

        foreach ($accounts as $account) {
            BankAccount::query()->withoutGlobalScopes()->updateOrCreate(
                [
                    'bank_connection_id' => $connection->getKey(),
                    'provider_account_id' => $account['provider_account_id'],
                ],
                [
                    'organization_id' => $organization->getKey(),
                    'company_id' => $company->getKey(),
                    'type' => $account['type'],
                    'bank_code' => '748',
                    'branch' => $account['branch'],
                    'number_masked' => $account['number_masked'],
                    'currency' => 'BRL',
                    'available_balance_minor' => $account['available_balance_minor'],
                    'current_balance_minor' => $account['current_balance_minor'],
                    'balance_observed_at' => $observedAt,
                    'status' => 'active',
                    'metadata' => $account['metadata'],
                ],
            );
        }
    }
}
