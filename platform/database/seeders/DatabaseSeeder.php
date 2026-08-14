<?php

namespace Database\Seeders;

use App\Domain\Identity\Models\Company;
use App\Domain\Identity\Models\Organization;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

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

        $organization = Organization::query()->firstOrCreate(
            ['slug' => 'sabba-sistemas'],
            ['name' => 'Sabba Sistemas'],
        );
        $user = User::query()->firstOrCreate(
            ['email' => 'admin@openfinance.local'],
            [
                'name' => 'Administrador OpenFinance',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
            ],
        );
        $user->organizations()->syncWithoutDetaching([
            $organization->getKey() => ['role' => 'owner', 'accepted_at' => now()],
        ]);
        $user->forceFill(['current_organization_id' => $organization->getKey()])->save();
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
