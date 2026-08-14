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
use Psr\SimpleCache\CacheInterface;
use Sabba\OpenFinance\Bradesco\BradescoHttpClient;
use Tests\TestCase;

class BankSandboxTest extends TestCase
{
    use RefreshDatabase;

    public function test_developer_can_run_a_sanitized_authentication_suite(): void
    {
        [$user, $connection] = $this->sandboxConnection('developer');
        $this->mockBradesco([$this->oauthResponse()]);

        $this->actingAs($user)->post(route('sandbox.runs.store'), [
            'bank_connection_id' => $connection->getKey(),
            'suite' => 'authentication',
        ])->assertRedirect();

        $this->assertDatabaseHas('bank_sandbox_runs', [
            'bank_connection_id' => $connection->getKey(),
            'user_id' => $user->getKey(),
            'suite' => 'authentication',
            'environment' => 'sandbox',
            'status' => 'passed',
        ]);
        $stored = (string) $this->app['db']->table('bank_sandbox_runs')->value('steps');
        $this->assertStringContainsString('mtls_oauth2', $stored);
        $this->assertStringNotContainsString('fixture-access-token', $stored);
        $this->assertStringNotContainsString('bradesco-secret', $stored);
    }

    public function test_pix_receipts_suite_records_read_only_steps(): void
    {
        [$user, $connection] = $this->sandboxConnection('operator', true);
        $history = [];
        $this->mockBradesco([
            $this->oauthResponse(),
            $this->oauthResponse(),
            new Response(
                200,
                ['Content-Type' => 'application/json'],
                (string) file_get_contents(__DIR__.'/../Fixtures/Bradesco/pix-received-list.json'),
            ),
        ], $history);

        $this->actingAs($user)->post(route('sandbox.runs.store'), [
            'bank_connection_id' => $connection->getKey(),
            'suite' => 'pix_receipts',
        ])->assertRedirect();

        $run = $this->app['db']->table('bank_sandbox_runs')->first();
        $this->assertSame('passed', $run->status);
        $this->assertStringContainsString('pix_receipts', (string) $run->steps);
        $this->assertStringContainsString('items_found', (string) $run->summary);
        parse_str($history[2]['request']->getUri()->getQuery(), $query);
        $this->assertSame('2024-10-02T00:00:00.000Z', $query['inicio']);
        $this->assertSame('2024-10-03T10:06:00.000Z', $query['fim']);
        $this->assertArrayNotHasKey('paginacao_paginaAtual', $query);
        $this->assertArrayNotHasKey('paginacao_itensPorPagina', $query);
    }

    public function test_sandbox_runner_rejects_a_production_connection(): void
    {
        [$user, $connection] = $this->sandboxConnection('developer');
        $connection->forceFill(['environment' => 'production'])->save();

        $this->actingAs($user)->post(route('sandbox.runs.store'), [
            'bank_connection_id' => $connection->getKey(),
            'suite' => 'authentication',
        ])->assertStatus(422);

        $this->assertDatabaseCount('bank_sandbox_runs', 0);
    }

    /** @return array{User,BankConnection} */
    private function sandboxConnection(string $role, bool $withTwoFactor = false): array
    {
        $organization = Organization::query()->create(['name' => 'Sabba Sistemas', 'slug' => 'sabba-sistemas']);
        $company = Company::query()->withoutGlobalScopes()->create([
            'organization_id' => $organization->getKey(),
            'legal_name' => 'Sabba Sistemas Ltda',
            'tax_id' => '60320001000145',
        ]);
        [$certificate, $privateKey] = $this->certificatePair();
        $connection = BankConnection::query()->withoutGlobalScopes()->create([
            'organization_id' => $organization->getKey(),
            'company_id' => $company->getKey(),
            'provider' => 'bradesco',
            'name' => 'Bradesco Sandbox',
            'environment' => 'sandbox',
            'status' => 'active',
            'capabilities' => ['pix.immediate', 'pix.due', 'pix.refund'],
            'encrypted_credentials' => [
                'products' => [
                    'pix' => [
                        'base_url' => 'https://openapisandbox.prebanco.com.br/',
                        'token_url' => 'https://openapisandbox.prebanco.com.br/auth/server/oauth/token',
                        'client_id' => 'bradesco-client',
                        'client_secret' => 'bradesco-secret',
                        'certificate_pem' => $certificate,
                        'private_key_pem' => $privateKey,
                        'paths' => ['receipts' => '/v2/pix'],
                    ],
                ],
            ],
        ]);
        $factory = User::factory();
        $user = ($withTwoFactor ? $factory->withTwoFactor() : $factory)->create();
        $user->organizations()->attach($organization, ['role' => $role, 'accepted_at' => now()]);
        $user->forceFill(['current_organization_id' => $organization->getKey()])->save();

        return [$user, $connection];
    }

    /** @param list<Response> $responses */
    private function mockBradesco(array $responses, ?array &$history = null): void
    {
        $history = [];
        $handler = HandlerStack::create(new MockHandler($responses));
        $handler->push(Middleware::history($history));
        $this->app->singleton(
            BradescoHttpClient::class,
            fn ($app) => new BradescoHttpClient($app->make(CacheInterface::class), handler: $handler),
        );
    }

    private function oauthResponse(): Response
    {
        return new Response(200, ['Content-Type' => 'application/json'], json_encode([
            'access_token' => 'fixture-access-token',
            'expires_in' => 3599,
            'token_type' => 'Bearer',
        ], JSON_THROW_ON_ERROR));
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
}
