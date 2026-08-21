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
use Inertia\Testing\AssertableInertia as Assert;
use Psr\SimpleCache\CacheInterface;
use Sabba\OpenFinance\Sicredi\SicrediHttpClient;
use Tests\TestCase;

class SicrediConnectionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config()->set('openfinance.sicredi.pix.environments.sandbox', [
            'label' => 'Homologação',
            'base_url' => 'https://sicredi.fixture.test/api/v2/',
            'token_url' => 'https://sicredi.fixture.test/oauth/token',
        ]);
        config()->set('openfinance.sicredi.boleto.environments.sandbox', [
            'label' => 'Sandbox',
            'base_url' => 'https://sicredi.fixture.test/sb/cobranca/boleto/v1/',
            'token_url' => 'https://sicredi.fixture.test/sb/auth/openapi/token',
        ]);
    }

    public function test_an_administrator_can_save_and_test_sicredi_credentials(): void
    {
        [$user, $company] = $this->administrator();
        [$certificate, $privateKey] = $this->certificatePair();

        $this->actingAs($user)->post(route('bank-connections.store'), [
            'company_id' => $company->getKey(),
            'name' => 'Sicredi homologação',
            'environment' => 'sandbox',
            'capabilities' => ['pix.immediate', 'pix.refund', 'webhooks'],
            'client_id' => 'fixture-client',
            'client_secret' => 'fixture-secret',
            'pix_key' => 'financeiro@example.test',
            'webhook_secret' => 'fixture-webhook-secret-123456',
            'certificate' => UploadedFile::fake()->createWithContent('aplicacao.cer', $certificate),
            'private_key' => UploadedFile::fake()->createWithContent('aplicacao.key', $privateKey),
        ])->assertRedirect();

        $connection = BankConnection::query()->withoutGlobalScopes()->sole();
        $this->assertSame('draft', $connection->status);
        $this->assertSame(['pix.immediate', 'pix.refund', 'webhooks'], $connection->capabilities);
        $this->assertSame('fixture-client', data_get($connection->encrypted_credentials, 'products.pix.client_id'));
        $this->assertSame('fixture-webhook-secret-123456', data_get($connection->encrypted_credentials, 'webhook_secret'));
        $this->assertSame('cob.read cob.write pix.read pix.write webhook.read webhook.write', data_get($connection->encrypted_credentials, 'products.pix.scope'));
        $this->assertStringNotContainsString('fixture-secret', (string) $connection->getRawOriginal('encrypted_credentials'));

        $this->actingAs($user)
            ->get(route('bank-connections.index'))
            ->assertInertia(fn (Assert $page) => $page
                ->component('Platform/BankConnections')
                ->has('connections', 1)
                ->where('connections.0.product', 'pix')
                ->where('connections.0.credential_hint', '••••client')
                ->missing('connections.0.encrypted_credentials')
                ->missing('connections.0.client_secret'));

        $history = [];
        $handler = HandlerStack::create(new MockHandler([
            new Response(200, ['Content-Type' => 'application/json'], json_encode([
                'access_token' => 'fixture-access-token',
                'expires_in' => 3599,
                'token_type' => 'Bearer',
                'scope' => 'cob.read cob.write pix.read pix.write webhook.read webhook.write',
            ], JSON_THROW_ON_ERROR)),
        ]));
        $handler->push(Middleware::history($history));
        $this->app->singleton(
            SicrediHttpClient::class,
            fn ($app) => new SicrediHttpClient($app->make(CacheInterface::class), handler: $handler),
        );

        $this->actingAs($user)
            ->post(route('bank-connections.test', $connection))
            ->assertRedirect();

        $connection->refresh();
        $this->assertSame('active', $connection->status);
        $this->assertNull($connection->last_error);
        $this->assertSame('passed', data_get($connection->sync_settings, 'last_connection_test.status'));
        $this->assertSame('Basic '.base64_encode('fixture-client:fixture-secret'), $history[0]['request']->getHeaderLine('Authorization'));

        [$replacementCertificate, $replacementPrivateKey] = $this->certificatePair();
        $this->actingAs($user)->post(route('bank-connections.update', $connection), [
            '_method' => 'PATCH',
            'company_id' => $company->getKey(),
            'name' => 'Sicredi credenciais renovadas',
            'environment' => 'sandbox',
            'capabilities' => ['pix.immediate'],
            'client_id' => 'replacement-client',
            'client_secret' => 'replacement-secret',
            'pix_key' => 'financeiro@example.test',
            'webhook_secret' => null,
            'certificate' => UploadedFile::fake()->createWithContent('renovado.cer', $replacementCertificate),
            'private_key' => UploadedFile::fake()->createWithContent('renovado.key', $replacementPrivateKey),
        ])->assertRedirect();

        $connection->refresh();
        $this->assertSame('draft', $connection->status);
        $this->assertSame('Sicredi credenciais renovadas', $connection->name);
        $this->assertSame('replacement-client', data_get($connection->encrypted_credentials, 'products.pix.client_id'));
    }

    public function test_an_administrator_can_save_and_test_sicredi_billing_without_mtls(): void
    {
        [$user, $company] = $this->administrator();

        $this->actingAs($user)->post(route('bank-connections.store'), [
            'product' => 'boleto',
            'company_id' => $company->getKey(),
            'name' => 'Sicredi Cobrança Sandbox',
            'environment' => 'sandbox',
            'capabilities' => ['boleto.normal', 'boleto.hybrid'],
            'x_api_key' => 'fixture-x-api-key',
            'beneficiary_code' => '12345',
            'cooperative_code' => '6789',
            'branch_code' => '03',
            'access_code' => 'teste123',
        ])->assertRedirect()->assertSessionHasNoErrors();

        $connection = BankConnection::query()->withoutGlobalScopes()->sole();
        $this->assertSame(['boleto.normal', 'boleto.hybrid'], $connection->capabilities);
        $this->assertNull($connection->certificate_expires_at);
        $this->assertSame('boleto', data_get($connection->sync_settings, 'product'));
        $this->assertSame('123456789', data_get($connection->encrypted_credentials, 'products.boleto.username'));
        $this->assertSame('fixture-x-api-key', data_get($connection->encrypted_credentials, 'products.boleto.api_key'));
        $this->assertStringNotContainsString('fixture-x-api-key', (string) $connection->getRawOriginal('encrypted_credentials'));

        $this->actingAs($user)
            ->get(route('bank-connections.index'))
            ->assertInertia(fn (Assert $page) => $page
                ->where('connections.0.product', 'boleto')
                ->where('connections.0.configured', true)
                ->where('connections.0.credential_hint', '••••pi-key')
                ->where('providers.0.products.0.value', 'boleto')
                ->where('presets.sicredi.boleto.sandbox.token_url', 'https://sicredi.fixture.test/sb/auth/openapi/token'));

        $history = [];
        $handler = HandlerStack::create(new MockHandler([
            new Response(200, ['Content-Type' => 'application/json'], json_encode([
                'access_token' => 'fixture-billing-token',
                'expires_in' => 300,
                'token_type' => 'Bearer',
                'scope' => 'cobranca profile email',
            ], JSON_THROW_ON_ERROR)),
        ]));
        $handler->push(Middleware::history($history));
        $this->app->singleton(
            SicrediHttpClient::class,
            fn ($app) => new SicrediHttpClient($app->make(CacheInterface::class), handler: $handler),
        );

        $this->actingAs($user)
            ->post(route('bank-connections.test', $connection))
            ->assertRedirect();

        $connection->refresh();
        $this->assertSame('active', $connection->status);
        $this->assertSame('fixture-x-api-key', $history[0]['request']->getHeaderLine('x-api-key'));
        $this->assertSame('COBRANCA', $history[0]['request']->getHeaderLine('context'));
        $this->assertSame('', $history[0]['request']->getHeaderLine('Authorization'));
        parse_str((string) $history[0]['request']->getBody(), $tokenBody);
        $this->assertSame('password', $tokenBody['grant_type']);
        $this->assertSame('123456789', $tokenBody['username']);
        $this->assertSame('teste123', $tokenBody['password']);
        $this->assertSame('cobranca', $tokenBody['scope']);
    }

    public function test_a_pix_key_is_required_when_sicredi_pix_charges_are_enabled(): void
    {
        [$user, $company] = $this->administrator();
        [$certificate, $privateKey] = $this->certificatePair();

        $this->actingAs($user)->post(route('bank-connections.store'), [
            'product' => 'pix',
            'company_id' => $company->getKey(),
            'name' => 'Sicredi Pix sem chave',
            'environment' => 'sandbox',
            'capabilities' => ['pix.immediate'],
            'client_id' => 'fixture-client',
            'client_secret' => 'fixture-secret',
            'certificate' => UploadedFile::fake()->createWithContent('aplicacao.cer', $certificate),
            'private_key' => UploadedFile::fake()->createWithContent('aplicacao.key', $privateKey),
        ])->assertSessionHasErrors('pix_key');

        $this->assertDatabaseCount('bank_connections', 0);
    }

    public function test_a_mismatched_private_key_is_rejected(): void
    {
        [$user, $company] = $this->administrator();
        [$certificate] = $this->certificatePair();
        [, $otherPrivateKey] = $this->certificatePair();

        $this->actingAs($user)->post(route('bank-connections.store'), [
            'company_id' => $company->getKey(),
            'name' => 'Sicredi inválido',
            'environment' => 'sandbox',
            'capabilities' => ['pix.immediate'],
            'client_id' => 'fixture-client',
            'client_secret' => 'fixture-secret',
            'pix_key' => 'financeiro@example.test',
            'certificate' => UploadedFile::fake()->createWithContent('aplicacao.cer', $certificate),
            'private_key' => UploadedFile::fake()->createWithContent('outra.key', $otherPrivateKey),
        ])->assertSessionHasErrors('private_key');

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
        $csr = openssl_csr_new(['commonName' => 'sicredi-test.local'], $privateKey, ['digest_alg' => 'sha256']);
        $this->assertNotFalse($csr);
        $certificate = openssl_csr_sign($csr, null, $privateKey, 30, ['digest_alg' => 'sha256']);
        $this->assertNotFalse($certificate);
        $this->assertTrue(openssl_x509_export($certificate, $certificatePem));
        $this->assertTrue(openssl_pkey_export($privateKey, $privateKeyPem));

        return [$certificatePem, $privateKeyPem];
    }
}
