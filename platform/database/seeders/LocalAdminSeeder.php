<?php

namespace Database\Seeders;

use App\Domain\Identity\Models\Organization;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class LocalAdminSeeder extends Seeder
{
    public function run(): void
    {
        if (app()->isProduction()) {
            $this->command->warn('O usuário administrativo local não é criado em produção.');

            return;
        }

        $organization = Organization::query()->firstOrCreate(
            ['slug' => 'sabba-sistemas'],
            ['name' => 'Sabba Sistemas'],
        );

        $user = User::query()->updateOrCreate(
            ['email' => 'admin@openfinance.local'],
            [
                'name' => 'Administrador OpenFinance',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'remember_token' => null,
                'two_factor_secret' => null,
                'two_factor_recovery_codes' => null,
                'two_factor_confirmed_at' => null,
                'current_organization_id' => $organization->getKey(),
            ],
        );

        $user->passkeys()->delete();
        $user->organizations()->syncWithoutDetaching([
            $organization->getKey() => ['role' => 'owner', 'accepted_at' => now()],
        ]);
    }
}
