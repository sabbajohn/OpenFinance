<?php

namespace Tests\Feature;

use App\Domain\Banking\Jobs\SyncBankConnection;
use App\Domain\Banking\Models\BankConnection;
use App\Domain\Banking\Models\BankTransaction;
use App\Domain\Banking\Services\BankProviderRegistry;
use App\Domain\Banking\Services\BankTransactionIngestor;
use App\Domain\Banking\Services\ConnectionContextFactory;
use App\Domain\Events\Jobs\NormalizeInboxBankTransaction;
use App\Domain\Events\Models\InboxEvent;
use App\Domain\Events\Services\InboxService;
use App\Domain\Events\Services\RawPayloadStore;
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
use Sabba\OpenFinance\Bradesco\BradescoProvider;
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
            'capabilities' => ['pix.immediate', 'pix.due', 'pix.refund', 'webhooks'],
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
        $this->assertSame(['pix.immediate', 'pix.due', 'pix.refund', 'webhooks'], $connection->capabilities);
        $this->assertSame('bradesco-client', data_get($connection->encrypted_credentials, 'products.pix.client_id'));
        $this->assertSame('', data_get($connection->encrypted_credentials, 'products.pix.scope'));
        $this->assertSame(45, data_get($connection->encrypted_credentials, 'products.pix.receipts_timeout_seconds'));
        $this->assertSame('fixture-edge-secret-123456', data_get($connection->encrypted_credentials, 'webhook_secret'));
        $this->assertSame('https://openapisandbox.prebanco.com.br/', data_get($connection->encrypted_credentials, 'products.pix.base_url'));
        $this->assertSame('/v2/cob/{txid}', data_get($connection->encrypted_credentials, 'products.pix.paths.charge'));
        $this->assertSame('/v2/cobv/{txid}', data_get($connection->encrypted_credentials, 'products.pix.paths.due_charge'));
        $this->assertSame('/v2/pix', data_get($connection->encrypted_credentials, 'products.pix.paths.receipts'));
        $this->assertSame('/v2/pix/{endToEndId}', data_get($connection->encrypted_credentials, 'products.pix.paths.receipt'));
        $this->assertStringNotContainsString('bradesco-secret', (string) $connection->getRawOriginal('encrypted_credentials'));

        $this->actingAs($user)
            ->get(route('bank-connections.index'))
            ->assertInertia(fn (Assert $page) => $page
                ->component('Platform/BankConnections')
                ->where('connections.0.provider', 'bradesco')
                ->where('connections.0.credential_hint', '••••client')
                ->where('connections.0.can_sync', true)
                ->where('connections.0.webhook_url', route('bank-webhooks.pix.receive', $connection))
                ->where('providers.1.value', 'bradesco')
                ->where('providers.1.products.0.contract', 'Cobrança v1.7.2 e Cobrança com QR Code v1.8.3')
                ->where('providers.1.products.1.contract', 'Pix - geração de QR Code v1.2.3')
                ->where('presets.bradesco.pix.sandbox.token_url', 'https://openapisandbox.prebanco.com.br/auth/server/oauth/token'));

        $history = [];
        $handler = HandlerStack::create(new MockHandler([
            new Response(200, ['Content-Type' => 'application/json'], json_encode([
                'access_token' => 'fixture-access-token',
                'expires_in' => 3599,
                'token_type' => 'Bearer',
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
        $this->assertSame('', $history[0]['request']->getHeaderLine('Authorization'));
        parse_str((string) $history[0]['request']->getBody(), $tokenBody);
        $this->assertSame('bradesco-client', $tokenBody['client_id']);
        $this->assertSame('bradesco-secret', $tokenBody['client_secret']);

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

        $this->actingAs($user)
            ->post(route('bank-connections.sync', $connection))
            ->assertRedirect();
        Queue::assertPushed(
            SyncBankConnection::class,
            fn (SyncBankConnection $job): bool => $job->connectionId === (string) $connection->getKey(),
        );

        $syncHistory = [];
        $syncHandler = HandlerStack::create(new MockHandler([
            $this->fixtureResponse('oauth-token.json'),
            $this->fixtureResponse('pix-received-list.json'),
            $this->fixtureResponse('pix-received-list-last.json'),
        ]));
        $syncHandler->push(Middleware::history($syncHistory));
        $this->app->instance(
            BradescoHttpClient::class,
            new BradescoHttpClient($this->app->make(CacheInterface::class), handler: $syncHandler),
        );
        $this->app->forgetInstance(BradescoProvider::class);
        $this->app->forgetInstance(BankProviderRegistry::class);

        (new SyncBankConnection(
            (string) $connection->getKey(),
            '2026-08-13T00:00:00.000Z',
            '2026-08-14T23:59:59.000Z',
        ))->handle(
            $this->app->make(BankProviderRegistry::class),
            $this->app->make(ConnectionContextFactory::class),
            $this->app->make(InboxService::class),
        );

        $this->assertDatabaseHas('bank_accounts', [
            'bank_connection_id' => $connection->getKey(),
            'provider_account_id' => 'pix-received',
            'type' => 'pix',
            'bank_code' => '237',
        ]);
        $this->assertDatabaseHas('sync_runs', [
            'bank_connection_id' => $connection->getKey(),
            'capability' => 'pix.receipts',
            'status' => 'completed',
            'items_seen' => 2,
        ]);
        $this->assertDatabaseHas('inbox_events', [
            'source' => 'bradesco',
            'event_type' => 'bank.transaction.observed',
            'status' => 'received',
        ]);
        $this->assertSame('/v2/pix', $syncHistory[1]['request']->getUri()->getPath());
        $this->assertSame('/v2/pix', $syncHistory[2]['request']->getUri()->getPath());

        $receiptEvents = InboxEvent::query()->withoutGlobalScopes()
            ->where('event_type', 'bank.transaction.observed')
            ->get();
        $this->assertCount(2, $receiptEvents);
        foreach ($receiptEvents as $event) {
            (new NormalizeInboxBankTransaction((string) $event->getKey()))->handle(
                $this->app->make(RawPayloadStore::class),
                $this->app->make(BankTransactionIngestor::class),
            );
        }

        $transactions = BankTransaction::query()->withoutGlobalScopes()->orderBy('occurred_at')->get();
        $this->assertCount(2, $transactions);
        $this->assertSame('E237202608140000000000000000003', $transactions[0]->external_id);
        $this->assertSame('ERP12345678901234567890123', $transactions[0]->identifiers['txid']);
        $this->assertSame(1234, $transactions[0]->amount_minor);
        $this->assertSame('credit', $transactions[0]->direction);

        $this->actingAs($user)
            ->get(route('bank-transactions.index'))
            ->assertInertia(fn (Assert $page) => $page
                ->component('Platform/Index')
                ->has('records', 2)
                ->where('records.1.identifiers.txid', 'ERP12345678901234567890123')
                ->where('records.1.connection.name', 'Bradesco homologação'));
    }

    public function test_an_administrator_can_configure_and_authenticate_bradesco_boleto(): void
    {
        [$user, $company] = $this->administrator();
        [$certificate, $privateKey] = $this->certificatePair();

        $this->actingAs($user)->post(route('bank-connections.store'), [
            'provider' => 'bradesco',
            'product' => 'boleto',
            'company_id' => $company->getKey(),
            'name' => 'Bradesco Cobrança',
            'environment' => 'sandbox',
            'capabilities' => ['boleto.normal', 'boleto.hybrid'],
            'client_id' => 'bradesco-boleto-client',
            'client_secret' => 'bradesco-boleto-secret',
            'wallet_code' => '9',
            'negotiation_number' => '123400000001234567',
            'certificate' => UploadedFile::fake()->createWithContent('aplicacao.cer', $certificate),
            'private_key' => UploadedFile::fake()->createWithContent('aplicacao.key', $privateKey),
        ])->assertRedirect()->assertSessionHasNoErrors();

        $connection = BankConnection::query()->withoutGlobalScopes()->sole();
        $this->assertSame(['boleto.normal', 'boleto.hybrid'], $connection->capabilities);
        $this->assertSame('boleto', data_get($connection->sync_settings, 'product'));
        $this->assertSame('bradesco-boleto-client', data_get($connection->encrypted_credentials, 'products.boleto.client_id'));
        $this->assertSame('12345678000190', data_get($connection->encrypted_credentials, 'products.boleto.beneficiary_tax_id'));
        $this->assertSame('9', data_get($connection->encrypted_credentials, 'products.boleto.product_code'));
        $this->assertSame('123400000001234567', data_get($connection->encrypted_credentials, 'products.boleto.negotiation_number'));
        $this->assertSame(
            'https://openapisandbox.prebanco.com.br/auth/server-mtls/v2/token',
            data_get($connection->encrypted_credentials, 'products.boleto.token_url'),
        );
        $this->assertSame(
            '/boleto-hibrido/cobranca-registro/v1/gerarBoleto',
            data_get($connection->encrypted_credentials, 'products.boleto.paths.hybrid_create'),
        );

        $this->actingAs($user)
            ->get(route('bank-connections.index'))
            ->assertInertia(fn (Assert $page) => $page
                ->where('connections.0.product', 'boleto')
                ->where('connections.0.configured', true)
                ->where('connections.0.credential_hint', '••••client')
                ->where('presets.bradesco.boleto.sandbox.token_url', 'https://openapisandbox.prebanco.com.br/auth/server-mtls/v2/token'));

        $history = [];
        $handler = HandlerStack::create(new MockHandler([
            new Response(200, ['Content-Type' => 'application/json'], json_encode([
                'access_token' => 'fixture-boleto-token',
                'expires_in' => 3599,
                'token_type' => 'Bearer',
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
        $this->assertSame('/auth/server-mtls/v2/token', $history[0]['request']->getUri()->getPath());
        parse_str((string) $history[0]['request']->getBody(), $tokenBody);
        $this->assertSame('bradesco-boleto-client', $tokenBody['client_id']);
        $this->assertSame('bradesco-boleto-secret', $tokenBody['client_secret']);
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
            'capabilities' => ['account.list'],
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
        $certificate = openssl_csr_sign($csr, null, $privateKey, 90, ['digest_alg' => 'sha256']);
        $this->assertNotFalse($certificate);
        $this->assertTrue(openssl_x509_export($certificate, $certificatePem));
        $this->assertTrue(openssl_pkey_export($privateKey, $privateKeyPem));

        return [$certificatePem, $privateKeyPem];
    }

    private function fixtureResponse(string $name): Response
    {
        return new Response(
            200,
            ['Content-Type' => 'application/json'],
            (string) file_get_contents(__DIR__.'/../Fixtures/Bradesco/'.$name),
        );
    }
}
