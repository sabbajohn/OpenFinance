<?php

namespace Tests\Feature;

use App\Domain\Banking\Models\BankAccount;
use App\Domain\Banking\Models\BankConnection;
use App\Domain\Identity\Models\Company;
use App\Domain\Identity\Models\Organization;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class DatabaseSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_local_bootstrap_is_idempotent_and_does_not_require_dev_factories(): void
    {
        $this->seed(DatabaseSeeder::class);

        $user = User::query()->firstOrFail();
        $user->forceFill([
            'password' => Hash::make('changed-password'),
            'remember_token' => 'remember-me',
            'two_factor_secret' => 'secret',
            'two_factor_recovery_codes' => 'codes',
            'two_factor_confirmed_at' => now(),
        ])->save();

        $this->seed(DatabaseSeeder::class);

        $this->assertDatabaseCount(Organization::class, 1);
        $this->assertDatabaseCount(Company::class, 1);
        $this->assertDatabaseCount(User::class, 1);
        $this->assertDatabaseCount(BankConnection::class, 1);
        $this->assertDatabaseCount(BankAccount::class, 2);
        $this->assertDatabaseHas(BankConnection::class, [
            'provider' => 'sicredi',
            'name' => 'Sicredi Sandbox — dados simulados',
            'environment' => 'sandbox',
            'status' => 'draft',
        ]);

        $this->assertDatabaseHas(BankAccount::class, [
            'provider_account_id' => 'demo-checking-001',
            'bank_code' => '748',
            'number_masked' => '**** 4521-9',
            'available_balance_minor' => 17_932_780,
            'current_balance_minor' => 18_452_780,
            'status' => 'active',
        ]);
        $this->assertDatabaseHas(BankAccount::class, [
            'provider_account_id' => 'demo-collection-001',
            'number_masked' => '**** 8890-3',
            'available_balance_minor' => 4_825_420,
            'current_balance_minor' => 4_825_420,
            'status' => 'active',
        ]);

        $user->refresh();
        $this->assertTrue(Hash::check('password', $user->password));
        $this->assertNull($user->remember_token);
        $this->assertNull($user->two_factor_secret);
        $this->assertNull($user->two_factor_recovery_codes);
        $this->assertNull($user->two_factor_confirmed_at);
        $this->assertSame('owner', $user->organizations()->firstOrFail()->pivot->role);
    }
}
