<?php

namespace Tests\Feature;

use App\Domain\Identity\Models\ApiClient;
use App\Domain\Identity\Models\Organization;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiClientAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_api_tokens_are_hashed_and_scoped(): void
    {
        $organization = Organization::query()->create(['name' => 'Org', 'slug' => 'org']);
        $issued = ApiClient::issue([
            'organization_id' => $organization->id,
            'name' => 'ERP',
            'scopes' => ['banking:read'],
        ]);

        $this->assertNotSame($issued['token'], $issued['client']->token_hash);
        $this->withToken($issued['token'])->getJson('/api/v1/bank-transactions')->assertOk();
        $this->withToken($issued['token'])
            ->withHeader('Idempotency-Key', 'test-key')
            ->postJson('/api/v1/pix/charges', [])
            ->assertForbidden();
    }
}
