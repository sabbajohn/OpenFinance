<?php

namespace Tests\Feature;

use App\Domain\Identity\Models\Organization;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_to_the_login_page()
    {
        $response = $this->get(route('dashboard'));
        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_users_can_visit_the_dashboard()
    {
        $organization = Organization::query()->create(['name' => 'Org', 'slug' => 'org']);
        $user = User::factory()->withTwoFactor()->create();
        $user->organizations()->attach($organization, ['role' => 'owner', 'accepted_at' => now()]);
        $user->forceFill(['current_organization_id' => $organization->id])->save();
        $this->actingAs($user);

        $response = $this->get(route('dashboard'));
        $response->assertOk();
    }

    public function test_users_can_browse_the_platform_before_enabling_two_factor()
    {
        $organization = Organization::query()->create(['name' => 'Org', 'slug' => 'org']);
        $user = User::factory()->create();
        $user->organizations()->attach($organization, ['role' => 'owner', 'accepted_at' => now()]);
        $user->forceFill(['current_organization_id' => $organization->id])->save();

        $this->actingAs($user)
            ->get(route('bank-connections.index'))
            ->assertOk();
    }

    public function test_sensitive_changes_still_require_two_factor()
    {
        $organization = Organization::query()->create(['name' => 'Org', 'slug' => 'org']);
        $user = User::factory()->create();
        $user->organizations()->attach($organization, ['role' => 'owner', 'accepted_at' => now()]);
        $user->forceFill(['current_organization_id' => $organization->id])->save();

        $this->actingAs($user)
            ->post(route('companies.store'), [
                'legal_name' => 'Empresa Bloqueada Ltda',
                'tax_id' => '12345678000190',
            ])
            ->assertRedirect(route('security.edit'));

        $this->assertDatabaseMissing('companies', ['tax_id' => '12345678000190']);
    }
}
