<?php

namespace Database\Seeders;

use App\Domain\Identity\Models\Company;
use App\Domain\Identity\Models\Organization;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if (app()->isProduction()) {
            $this->command->warn('O seed de demonstração não é executado em produção.');

            return;
        }

        $this->call(LocalAdminSeeder::class);

        $organization = Organization::query()
            ->where('slug', 'sabba-sistemas')
            ->firstOrFail();
        Company::query()->firstOrCreate(
            [
                'organization_id' => $organization->getKey(),
                'tax_id' => '00000000000191',
            ],
            [
                'legal_name' => 'Empresa Demonstração Ltda',
                'trade_name' => 'Empresa Demonstração',
            ],
        );

        $this->call(DemoBankingSeeder::class);
    }
}
