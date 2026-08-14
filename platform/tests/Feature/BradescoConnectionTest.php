<?php

namespace Tests\Feature;

use App\Domain\Banking\Models\BankConnection;
use App\Domain\Identity\Models\Company;
use App\Domain\Identity\Models\Organization;
use App\Models\User;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Queue;
use Inertia\Testing\AssertableInertia as Assert;
use Psr\SimpleCache\CacheInterface;
use Sabba\OpenFinance\Bradesco\BradescoHttpClient;
use Tests\TestCase;

class BradescoConnectionTest extends TestCase
{
    use RefreshDatabase;

    public function test_an_administrator_can_save_and_test_bradesco_credentials(): void
    {
        [$user, $company] = $this->administrator();
        [$certificate, $privateKey] = $this->certificatePair();

        $this->actingAs($user)->post(route('bank-connections.store'), [
            'provider' => 'bradesco',
            'company_id' => $company->getKey(),
            'name' => 'Bradesco homologação',
            'environment' => 'sandbox',
            'capabilities' => ['pix.immediate', 'pix.refund', 'webhooks'],
            'client_id' => 'bradesco-client',
            'client_secret' => 'bradesco-secret',
            'pix_key' => 'financeiro@example.test',
            'webhook_secret' => 'fixture-edge-secret-123456',
            'certificate' => UploadedFile::fake()->createWithContent('aplicacao.cer', $certificate),
            'private_key' => UploadedFile::fake()->createWithContent('aplicacao.key', $privateKey),
        ])->assertRedirect();

        $connection = BankConnection::query()->withoutGlobalScopes()->sole();
        $this->assertSame('bradesco', $connection->provider);
        $this->assertSame('draft', $connection->status);
        $this->assertSame(['pix.immediate', 'pix.refund', 'webhooks'], $connection->capabilities);
        $this->assertSame('bradesco-client', data_get($connection->encrypted_credentials, 'products.pix.client_id'));
        $this->assertSame('cob.read cob.write pix.read pix.write webhook.read webhook.write', data_get($connection->encrypted_credentials, 'products.pix.scope'));
        $this->assertSame('fixture-edge-secret-123456', data_get($connection->encrypted_credentials, 'webhook_secret'));
        $this->assertSame('https://qrpix-h.bradesco.com.br/', data_get($connection->encrypted_credentials, 'products.pix.base_url'));
        $this->assertStringNotContainsString('bradesco-secret', (string) $connection->getRawOriginal('encrypted_credentials'));

        $this->actingAs($user)
            ->get(route('bank-connections.index'))
            ->assertInertia(fn (Assert $page) => $page
                ->component('Platform/BankConnections')
                ->where('connections.0.provider', 'bradesco')
                ->where('connections.0.client_id_hint', '••••client')
                ->where('connections.0.webhook_url', route('bank-webhooks.pix.receive', $connection))
                ->where('providers.1.value', 'bradesco')
                ->where('presets.bradesco.sandbox.token_url', 'https://qrpix-h.bradesco.com.br/auth/server/oauth/token'));

        $history = [];
        $handler = HandlerStack::create(new MockHandler([
            new Response(200, ['Content-Type' => 'application/json'], json_encode([
                'access_token' => 'fixture-access-token',
                'expires_in' => 3599,
                'token_type' => 'Bearer',
                'scope' => 'cob.read cob.write pix.read pix.write',
            ], JSON_THROW_ON_ERROR)),
        ]));
        $handler->push(Middleware::history($history));
        $this->app->singleton(
            BradescoHttpClient::class,
            fn ($app) => new BradescoHttpClient($app->make(CacheInterface::class), handler: $handler),
        );

        $this->actingAs($user)
            ->post(route('bank-connections.test', $connection))
            ->assertRedirect();

        $connection->refresh();
        $this->assertSame('active', $connection->status);
        $this->assertSame('passed', data_get($connection->sync_settings, 'last_connection_test.status'));
        $this->assertSame(
            'Basic '.base64_encode('bradesco-client:bradesco-secret'),
            $history[0]['request']->getHeaderLine('Authorization'),
        );

        Queue::fake();
        $this->postJson(route('bank-webhooks.pix.receive', $connection), [
            'pix' => [[
                'endToEndId' => 'E237202608130000000000000000001',
                'txid' => 'ERP123',
                'valor' => '12.34',
                'horario' => '2026-08-13T12:01:00Z',
            ]],
        ], ['Authorization' => 'Bearer fixture-edge-secret-123456'])
            ->assertStatus(202)
            ->assertJsonPath('data.status', 'accepted');
        $this->assertDatabaseHas('inbox_events', [
            'source' => 'bradesco',
            'event_type' => 'bradesco.webhook',
            'status' => 'received',
        ]);
    }

    public function test_bradesco_rejects_capabilities_not_covered_by_the_enabled_product(): void
    {
        [$user, $company] = $this->administrator();
        [$certificate, $privateKey] = $this->certificatePair();

        $this->actingAs($user)->post(route('bank-connections.store'), [
            'provider' => 'bradesco',
            'company_id' => $company->getKey(),
            'name' => 'Bradesco inválido',
            'environment' => 'sandbox',
            'capabilities' => ['pix.due'],
            'client_id' => 'bradesco-client',
            'client_secret' => 'bradesco-secret',
            'certificate' => UploadedFile::fake()->createWithContent('aplicacao.cer', $certificate),
            'private_key' => UploadedFile::fake()->createWithContent('aplicacao.key', $privateKey),
        ])->assertSessionHasErrors('capabilities.0');

        $this->assertDatabaseCount('bank_connections', 0);
    }

    /** @return array{User,Company} */
    private function administrator(): array
    {
        $organization = Organization::query()->create(['name' => 'Org', 'slug' => 'org']);
        $company = Company::query()->withoutGlobalScopes()->create([
            'organization_id' => $organization->getKey(),
            'legal_name' => 'Empresa Teste Ltda',
            'tax_id' => '12345678000190',
        ]);
        $user = User::factory()->withTwoFactor()->create();
        $user->organizations()->attach($organization, ['role' => 'owner', 'accepted_at' => now()]);
        $user->forceFill(['current_organization_id' => $organization->getKey()])->save();

        return [$user, $company];
    }

    /** @return array{string,string} */
    private function certificatePair(): array
    {
        $privateKey = openssl_pkey_new([
            'digest_alg' => 'sha256',
            'private_key_bits' => 2048,
            'private_key_type' => OPENSSL_KEYTYPE_RSA,
        ]);
        $this->assertNotFalse($privateKey);
        $csr = openssl_csr_new(['commonName' => 'bradesco-test.local'], $privateKey, ['digest_alg' => 'sha256']);
        $this->assertNotFalse($csr);
        $certificate = openssl_csr_sign($csr, null, $privateKey, 30, ['digest_alg' => 'sha256']);
        $this->assertNotFalse($certificate);
        $this->assertTrue(openssl_x509_export($certificate, $certificatePem));
        $this->assertTrue(openssl_pkey_export($privateKey, $privateKeyPem));

        return [$certificatePem, $privateKeyPem];
    }
}
