<?php

namespace Tests\Feature;

use App\Domain\Banking\Models\BankConnection;
use App\Domain\Identity\Models\Company;
use App\Domain\Identity\Models\Organization;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class OrganizationAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_navigation_permissions_are_shared_for_an_auditor(): void
    {
        [$organization, $auditor] = $this->member('auditor');

        $this->actingAs($auditor)
            ->get(route('dashboard'))
            ->assertInertia(fn (Assert $page) => $page
                ->where('organization.role', 'auditor')
                ->where('organization.role_label', 'Auditor')
                ->where('organization.permissions', fn ($permissions): bool => $permissions->contains('financial.view')
                    && ! $permissions->contains('financial.operate')
                    && ! $permissions->contains('members.manage')));

        $this->actingAs($auditor)->get(route('bank-connections.index'))->assertOk();
        $this->actingAs($auditor)->get(route('members.index'))->assertForbidden();
        $this->actingAs($auditor)->post(route('companies.store'), [
            'legal_name' => 'Empresa sem permissão',
            'tax_id' => '60320001000145',
        ])->assertForbidden();

        $this->assertDatabaseMissing('companies', ['organization_id' => $organization->getKey()]);
    }

    public function test_operator_can_operate_but_cannot_manage_members_or_bank_credentials(): void
    {
        [$organization, $operator] = $this->member('operator', true);
        $company = Company::query()->withoutGlobalScopes()->create([
            'organization_id' => $organization->getKey(),
            'legal_name' => 'Sabba Sistemas Ltda',
            'tax_id' => '60320001000145',
        ]);
        $connection = BankConnection::query()->withoutGlobalScopes()->create([
            'organization_id' => $organization->getKey(),
            'company_id' => $company->getKey(),
            'provider' => 'bradesco',
            'name' => 'Bradesco Sandbox',
            'environment' => 'sandbox',
            'status' => 'active',
            'capabilities' => ['pix.refund'],
        ]);

        $this->actingAs($operator)->get(route('members.index'))->assertForbidden();
        $this->actingAs($operator)->post(route('bank-connections.store'))->assertForbidden();
        $this->actingAs($operator)->post(route('bank-connections.sync', $connection))->assertRedirect();
    }

    public function test_admin_cannot_grant_owner_and_last_owner_is_protected(): void
    {
        Notification::fake();
        [$organization, $owner] = $this->member('owner', true);
        $admin = User::factory()->withTwoFactor()->create();
        $admin->organizations()->attach($organization, ['role' => 'admin', 'accepted_at' => now()]);
        $admin->forceFill(['current_organization_id' => $organization->getKey()])->save();

        $this->actingAs($admin)->post(route('members.invitations.store'), [
            'email' => 'novo-proprietario@example.test',
            'role' => 'owner',
        ])->assertSessionHasErrors('role');

        $this->actingAs($owner)->patch(route('members.role.update', $owner), [
            'role' => 'admin',
        ])->assertStatus(422);

        $this->assertSame('owner', $owner->fresh()?->roleFor($organization)?->value);
    }

    /** @return array{Organization,User} */
    private function member(string $role, bool $withTwoFactor = false): array
    {
        $organization = Organization::query()->create([
            'name' => 'Sabba Sistemas',
            'slug' => 'sabba-sistemas-'.bin2hex(random_bytes(3)),
        ]);
        $factory = User::factory();
        $user = ($withTwoFactor ? $factory->withTwoFactor() : $factory)->create();
        $user->organizations()->attach($organization, ['role' => $role, 'accepted_at' => now()]);
        $user->forceFill(['current_organization_id' => $organization->getKey()])->save();

        return [$organization, $user];
    }
}
