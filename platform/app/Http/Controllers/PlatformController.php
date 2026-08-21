<?php

namespace App\Http\Controllers;

use App\Domain\Audit\Models\AuditLog;
use App\Domain\Banking\Jobs\SyncBankConnection;
use App\Domain\Banking\Models\BankAccount;
use App\Domain\Banking\Models\BankConnection;
use App\Domain\Banking\Models\BankTransaction;
use App\Domain\Banking\Models\SyncRun;
use App\Domain\Banking\Services\BradescoConnectionConfigurator;
use App\Domain\Banking\Services\ConnectionContextFactory;
use App\Domain\Banking\Services\SicrediConnectionConfigurator;
use App\Domain\ERP\Models\ErpConnection;
use App\Domain\ERP\Models\ErpTitle;
use App\Domain\Events\Models\OutboxEvent;
use App\Domain\Events\Models\RawPayload;
use App\Domain\Events\Models\WebhookDelivery;
use App\Domain\Identity\Enums\OrganizationPermission;
use App\Domain\Identity\Enums\OrganizationRole;
use App\Domain\Identity\Models\Company;
use App\Domain\Receivables\Models\Receivable;
use App\Domain\Receivables\Services\ReceivableOperationService;
use App\Domain\Receivables\Services\ReceivableService;
use App\Domain\Reconciliation\Models\ReconciliationCase;
use App\Domain\Reconciliation\Services\ReconciliationDecisionService;
use App\Support\OrganizationContext;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;
use Sabba\OpenFinance\Bradesco\BradescoHttpClient;
use Sabba\OpenFinance\Bradesco\BradescoProviderException;
use Sabba\OpenFinance\Core\Enums\Capability;
use Sabba\OpenFinance\Sicredi\SicrediHttpClient;
use Sabba\OpenFinance\Sicredi\SicrediProviderException;
use Throwable;

class PlatformController extends Controller
{
    public function dashboard(OrganizationContext $context): Response
    {
        return Inertia::render('Dashboard', [
            'metrics' => [
                'balance_minor' => BankAccount::query()->sum('current_balance_minor'),
                'transactions_today' => BankTransaction::query()->where('occurred_at', '>=', now('UTC')->startOfDay())->count(),
                'open_reconciliations' => ReconciliationCase::query()->where('status', 'open')->count(),
                'connections_attention' => BankConnection::query()->whereIn('status', ['degraded', 'action_required'])->count(),
                'active_connections' => BankConnection::query()->where('status', 'active')->count(),
            ],
            'recentTransactions' => BankTransaction::query()->latest('occurred_at')->limit(8)->get(),
            'queueHealth' => OutboxEvent::query()->where('organization_id', $context->id())->selectRaw('status, count(*) as total')->groupBy('status')->pluck('total', 'status'),
            'onboarding' => [
                'company' => Company::query()->exists(),
                'bank_connection' => BankConnection::query()->exists(),
                'bank_authenticated' => BankConnection::query()->where('status', 'active')->exists(),
                'erp_connection' => ErpConnection::query()->where('status', 'active')->exists(),
                'two_factor' => request()->user()?->two_factor_confirmed_at !== null,
            ],
            'connectionHealth' => BankConnection::query()
                ->with('company:id,trade_name,legal_name')
                ->latest()
                ->limit(4)
                ->get()
                ->map(fn (BankConnection $connection): array => [
                    'id' => $connection->getKey(),
                    'name' => $connection->name,
                    'provider' => $connection->provider,
                    'company_name' => $connection->company?->trade_name ?: $connection->company?->legal_name,
                    'environment' => $connection->environment,
                    'status' => $connection->status,
                    'last_synced_at' => $connection->last_synced_at,
                    'last_error' => $connection->last_error,
                ]),
        ]);
    }

    public function companies(): Response
    {
        return $this->page('Empresas', 'companies', Company::query()->latest()->get(), [
            ['key' => 'legal_name', 'label' => 'Razão social'],
            ['key' => 'trade_name', 'label' => 'Nome fantasia'],
            ['key' => 'tax_id', 'label' => 'CNPJ'],
            ['key' => 'status', 'label' => 'Status'],
        ]);
    }

    public function bankConnections(Request $request, OrganizationContext $context): Response
    {
        $role = $request->user()->roleFor($context->get());
        $connections = BankConnection::query()
            ->with('company:id,trade_name,legal_name')
            ->withCount('accounts')
            ->latest()
            ->get()
            ->map(function (BankConnection $connection): array {
                $credentials = $connection->encrypted_credentials;
                $settings = is_array($connection->sync_settings) ? $connection->sync_settings : [];
                $product = (string) ($settings['product'] ?? (
                    is_array($credentials) && is_array(data_get($credentials, 'products.boleto'))
                        ? 'boleto'
                        : 'pix'
                ));
                $productCredentials = is_array($credentials)
                    ? data_get($credentials, 'products.'.$product, [])
                    : [];
                $usesSicrediBilling = $connection->provider === 'sicredi' && $product === 'boleto';
                $configured = is_array($productCredentials) && ($usesSicrediBilling
                    ? ! empty($productCredentials['api_key']) && ! empty($productCredentials['username']) && ! empty($productCredentials['password'])
                    : ! empty($productCredentials['client_id']) && ! empty($productCredentials['certificate_pem']));
                $credential = $usesSicrediBilling
                    ? ($productCredentials['api_key'] ?? null)
                    : ($productCredentials['client_id'] ?? null);

                return [
                    'id' => $connection->getKey(),
                    'company_id' => $connection->company_id,
                    'company_name' => $connection->company?->trade_name ?: $connection->company?->legal_name,
                    'name' => $connection->name,
                    'provider' => $connection->provider,
                    'product' => $product,
                    'environment' => $connection->environment,
                    'status' => $connection->status,
                    'capabilities' => $connection->capabilities,
                    'accounts_count' => $connection->accounts_count,
                    'certificate_expires_at' => $connection->certificate_expires_at,
                    'last_synced_at' => $connection->last_synced_at,
                    'last_error' => $connection->last_error,
                    'last_test_at' => $settings['last_connection_test_at'] ?? null,
                    'last_test' => $settings['last_connection_test'] ?? null,
                    'configured' => $configured,
                    'credential_hint' => is_string($credential) && $credential !== ''
                        ? '••••'.substr($credential, -6)
                        : null,
                    'scope' => is_array($productCredentials) ? ($productCredentials['scope'] ?? null) : null,
                    'webhook_url' => in_array(Capability::Webhooks->value, $connection->capabilities ?? [], true)
                        ? route('bank-webhooks.pix.receive', ['bankConnection' => $connection])
                        : null,
                    'can_sync' => in_array(Capability::Accounts->value, $connection->capabilities ?? [], true)
                        || in_array(Capability::PixRefund->value, $connection->capabilities ?? [], true),
                ];
            });

        return Inertia::render('Platform/BankConnections', [
            'access' => [
                'manage' => $role?->allows(OrganizationPermission::ManageBankConnections) ?? false,
                'test' => $role?->allows(OrganizationPermission::RunBankTests) ?? false,
                'sync' => $role?->allows(OrganizationPermission::RunBankSync) ?? false,
            ],
            'connections' => $connections,
            'companies' => Company::query()->select('id', 'trade_name', 'legal_name')->orderBy('trade_name')->get(),
            'providers' => [
                [
                    'value' => 'sicredi',
                    'label' => 'Sicredi',
                    'portal_url' => 'https://developer.sicredi.com.br',
                    'products' => [
                        [
                            'value' => 'boleto',
                            'label' => 'Cobrança',
                            'default_name' => 'Sicredi Cobrança',
                            'documentation_url' => 'https://developers.sicredi.com.br/public/docs/getting-started-billing',
                            'contract' => 'API Cobrança Sicredi',
                            'auth_mode' => 'oauth_password',
                            'capabilities' => [
                                Capability::BoletoNormal->value,
                                Capability::BoletoHybrid->value,
                            ],
                        ],
                        [
                            'value' => 'pix',
                            'label' => 'Pix',
                            'default_name' => 'Sicredi Pix',
                            'documentation_url' => 'https://developers.sicredi.com.br/public/docs/getting-started-pix',
                            'contract' => 'API Pix Sicredi v2.9.0',
                            'auth_mode' => 'mtls_client_credentials',
                            'capabilities' => [
                                Capability::PixImmediate->value,
                                Capability::PixDue->value,
                                Capability::PixRefund->value,
                                Capability::Webhooks->value,
                            ],
                        ],
                    ],
                ],
                [
                    'value' => 'bradesco',
                    'label' => 'Bradesco',
                    'portal_url' => 'https://developers.bradesco.com.br',
                    'products' => [
                        [
                            'value' => 'boleto',
                            'label' => 'Cobrança',
                            'default_name' => 'Bradesco Cobrança',
                            'documentation_url' => 'https://api.bradesco/openapis',
                            'contract' => 'Cobrança v1.7.2 e Cobrança com QR Code v1.8.3',
                            'auth_mode' => 'mtls_client_credentials',
                            'capabilities' => [
                                Capability::BoletoNormal->value,
                                Capability::BoletoHybrid->value,
                            ],
                        ],
                        [
                            'value' => 'pix',
                            'label' => 'Pix',
                            'default_name' => 'Bradesco Pix',
                            'documentation_url' => 'https://developers.bradesco.com.br/productDetails/8a2e88539549335b01954e36ab14699b/version/87757755b0f34076add6c7b9e72f9399/docs',
                            'contract' => 'Pix - geração de QR Code v1.2.3',
                            'auth_mode' => 'mtls_client_credentials',
                            'capabilities' => [
                                Capability::PixImmediate->value,
                                Capability::PixDue->value,
                                Capability::PixRefund->value,
                                Capability::Webhooks->value,
                            ],
                        ],
                    ],
                ],
            ],
            'presets' => [
                'sicredi' => [
                    'boleto' => config('openfinance.sicredi.boleto.environments'),
                    'pix' => config('openfinance.sicredi.pix.environments'),
                ],
                'bradesco' => [
                    'boleto' => config('openfinance.bradesco.boleto.environments'),
                    'pix' => config('openfinance.bradesco.pix.environments'),
                ],
            ],
        ]);
    }

    public function accounts(): Response
    {
        return $this->page('Contas e saldos', 'accounts', BankAccount::query()->with('connection:id,name')->latest()->get(), [
            ['key' => 'connection.name', 'label' => 'Conexão'],
            ['key' => 'number_masked', 'label' => 'Conta'],
            ['key' => 'current_balance_minor', 'label' => 'Saldo atual', 'format' => 'money'],
            ['key' => 'available_balance_minor', 'label' => 'Disponível', 'format' => 'money'],
            ['key' => 'balance_observed_at', 'label' => 'Atualizado'],
        ]);
    }

    public function transactions(): Response
    {
        return $this->page('Transações bancárias', 'transactions', BankTransaction::query()
            ->with('connection:id,name,provider')
            ->latest('occurred_at')
            ->limit(500)
            ->get(), [
                ['key' => 'connection.name', 'label' => 'Conexão'],
                ['key' => 'occurred_at', 'label' => 'Data'],
                ['key' => 'description', 'label' => 'Descrição'],
                ['key' => 'identifiers.txid', 'label' => 'TXID'],
                ['key' => 'amount_minor', 'label' => 'Valor', 'format' => 'money'],
                ['key' => 'status', 'label' => 'Status'],
            ]);
    }

    public function reconciliations(): Response
    {
        return $this->page('Central de conciliação', 'reconciliations', ReconciliationCase::query()
            ->with(['transaction', 'candidates.title'])->latest()->limit(300)->get(), [
                ['key' => 'transaction.description', 'label' => 'Movimento'],
                ['key' => 'transaction.amount_minor', 'label' => 'Valor', 'format' => 'money'],
                ['key' => 'best_score', 'label' => 'Melhor score'],
                ['key' => 'auto_eligible', 'label' => 'Automação'],
                ['key' => 'status', 'label' => 'Status'],
                ['key' => 'version', 'label' => 'Versão'],
            ]);
    }

    public function receivables(string $kind): Response
    {
        abort_unless(in_array($kind, ['pix', 'boleto'], true), 404);

        return $this->page($kind === 'pix' ? 'Cobranças Pix' : 'Boletos', $kind, Receivable::query()->where('kind', $kind)->latest()->get(), [
            ['key' => 'reference', 'label' => 'Referência'],
            ['key' => 'subtype', 'label' => 'Modalidade'],
            ['key' => 'amount_minor', 'label' => 'Valor', 'format' => 'money'],
            ['key' => 'due_at', 'label' => 'Vencimento'],
            ['key' => 'status', 'label' => 'Status'],
        ], [
            'connections' => BankConnection::query()->where('status', 'active')->get([
                'id',
                'company_id',
                'name',
                'provider',
                'capabilities',
            ]),
            'titles' => ErpTitle::query()->whereIn('type', ['receivable', 'receive'])->where('status', 'open')
                ->get(['id', 'company_id', 'external_id', 'description', 'open_amount_minor', 'currency']),
        ]);
    }

    public function erp(): Response
    {
        return $this->page('Integrações ERP', 'erp', ErpConnection::query()->latest()->get(), [
            ['key' => 'name', 'label' => 'Integração'],
            ['key' => 'provider', 'label' => 'ERP'],
            ['key' => 'base_url', 'label' => 'URL'],
            ['key' => 'status', 'label' => 'Status'],
            ['key' => 'last_synced_at', 'label' => 'Última sincronização'],
        ], Company::query()->select('id', 'trade_name', 'legal_name')->get());
    }

    public function webhooks(): Response
    {
        return $this->page('Entregas de webhook', 'webhooks', WebhookDelivery::query()
            ->with('endpoint:id,name,url')->latest()->limit(500)->get(), [
                ['key' => 'endpoint.name', 'label' => 'Endpoint'],
                ['key' => 'status', 'label' => 'Status'],
                ['key' => 'attempts', 'label' => 'Tentativas'],
                ['key' => 'response_status', 'label' => 'HTTP'],
                ['key' => 'created_at', 'label' => 'Criada em'],
            ]);
    }

    public function audit(): Response
    {
        return $this->page('Auditoria', 'audit', AuditLog::query()->latest('occurred_at')->limit(500)->get(), [
            ['key' => 'occurred_at', 'label' => 'Data'],
            ['key' => 'action', 'label' => 'Ação'],
            ['key' => 'user_id', 'label' => 'Usuário'],
            ['key' => 'api_client_id', 'label' => 'Cliente API'],
        ]);
    }

    public function operations(OrganizationContext $context): Response
    {
        return Inertia::render('Platform/Operations', [
            'syncRuns' => SyncRun::query()->latest()->limit(100)->get(),
            'queues' => OutboxEvent::query()->where('organization_id', $context->id())->selectRaw('status, count(*) as total')->groupBy('status')->pluck('total', 'status'),
            'deliveries' => WebhookDelivery::query()->selectRaw('status, count(*) as total')->groupBy('status')->pluck('total', 'status'),
            'rawPayloads' => RawPayload::query()->where('organization_id', $context->id())->selectRaw('status, count(*) as total')->groupBy('status')->pluck('total', 'status'),
        ]);
    }

    public function storeCompany(Request $request, OrganizationContext $context): RedirectResponse
    {
        $this->assertAdministrator($request, $context);
        $data = $request->validate([
            'legal_name' => ['required', 'string', 'max:255'],
            'trade_name' => ['nullable', 'string', 'max:255'],
            'tax_id' => ['required', 'string', 'max:20'],
            'timezone' => ['nullable', 'timezone'],
        ]);
        Company::query()->create([...$data, 'tax_id' => preg_replace('/\D+/', '', $data['tax_id']), 'timezone' => $data['timezone'] ?? 'America/Sao_Paulo']);

        return back()->with('success', 'Empresa criada.');
    }

    public function storeBankConnection(
        Request $request,
        OrganizationContext $context,
        SicrediConnectionConfigurator $sicrediConfigurator,
        BradescoConnectionConfigurator $bradescoConfigurator,
    ): RedirectResponse {
        $this->assertAdministrator($request, $context);
        $data = $this->validateBankConnection($request);
        $company = Company::query()->whereKey($data['company_id'])->firstOrFail();
        $configurationData = [...$data, 'beneficiary_tax_id' => $company->tax_id];
        $configuration = match ($data['provider']) {
            'sicredi' => $sicrediConfigurator->build($configurationData),
            'bradesco' => $bradescoConfigurator->build($configurationData),
            default => abort(422, 'Banco não suportado.'),
        };
        BankConnection::query()->create([
            'company_id' => $data['company_id'],
            'provider' => $data['provider'],
            'name' => $data['name'],
            'environment' => $data['environment'],
            'status' => 'draft',
            'capabilities' => $configuration['capabilities'],
            'encrypted_credentials' => $configuration['credentials'],
            'certificate_expires_at' => $configuration['certificate_expires_at'],
            'sync_settings' => [
                'source' => $data['provider'].'_'.$data['product'],
                'product' => $data['product'],
                'last_connection_test' => ['status' => 'pending'],
            ],
        ]);

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Conexão salva como rascunho. Agora teste a autenticação com o '.$this->providerLabel($data['provider']).'.']);

        return back();
    }

    public function updateBankConnection(
        Request $request,
        BankConnection $bankConnection,
        OrganizationContext $context,
        SicrediConnectionConfigurator $sicrediConfigurator,
        BradescoConnectionConfigurator $bradescoConfigurator,
    ): RedirectResponse {
        $this->assertAdministrator($request, $context);
        abort_unless($bankConnection->organization_id === $context->id(), 403);
        $data = $this->validateBankConnection($request, $bankConnection->provider);
        $company = Company::query()->whereKey($data['company_id'])->firstOrFail();
        $configurationData = [...$data, 'beneficiary_tax_id' => $company->tax_id];
        $configuration = match ($data['provider']) {
            'sicredi' => $sicrediConfigurator->build($configurationData),
            'bradesco' => $bradescoConfigurator->build($configurationData),
            default => abort(422, 'Banco não suportado.'),
        };
        $bankConnection->forceFill([
            'company_id' => $data['company_id'],
            'name' => $data['name'],
            'environment' => $data['environment'],
            'status' => 'draft',
            'capabilities' => $configuration['capabilities'],
            'encrypted_credentials' => $configuration['credentials'],
            'certificate_expires_at' => $configuration['certificate_expires_at'],
            'last_error' => null,
            'sync_settings' => [
                'source' => $data['provider'].'_'.$data['product'],
                'product' => $data['product'],
                'last_connection_test' => ['status' => 'pending'],
            ],
            'version' => $bankConnection->version + 1,
        ])->save();

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Credenciais substituídas. Execute um novo teste de autenticação.']);

        return back();
    }

    public function testBankConnection(
        Request $request,
        BankConnection $bankConnection,
        OrganizationContext $context,
        ConnectionContextFactory $contexts,
        SicrediHttpClient $sicredi,
        BradescoHttpClient $bradesco,
    ): RedirectResponse {
        $this->assertPermission($request, $context, OrganizationPermission::RunBankTests);
        abort_unless($bankConnection->organization_id === $context->id(), 403);

        try {
            $client = match ($bankConnection->provider) {
                'sicredi' => $sicredi,
                'bradesco' => $bradesco,
                default => abort(422, 'Banco não suportado.'),
            };
            $settings = is_array($bankConnection->sync_settings) ? $bankConnection->sync_settings : [];
            $product = (string) ($settings['product'] ?? 'pix');
            $result = $contexts->with(
                $bankConnection,
                fn ($connectionContext): array => $client->testAuthentication($connectionContext, $product),
            );
            $bankConnection->forceFill([
                'status' => 'active',
                'last_error' => null,
                'sync_settings' => [
                    ...$settings,
                    'last_connection_test_at' => now('UTC')->toIso8601String(),
                    'last_connection_test' => ['status' => 'passed', ...$result],
                ],
            ])->save();
            $authentication = $bankConnection->provider === 'sicredi' && $product === 'boleto'
                ? 'OAuth2 e x-api-key validados'
                : 'mTLS e OAuth2 validados';
            Inertia::flash('toast', ['type' => 'success', 'message' => $authentication.' com sucesso no '.$this->providerLabel($bankConnection->provider).'.']);
        } catch (Throwable $exception) {
            $providerError = $this->providerError($exception);
            $message = $providerError['message'];
            $settings = is_array($bankConnection->sync_settings) ? $bankConnection->sync_settings : [];
            $bankConnection->forceFill([
                'status' => 'action_required',
                'last_error' => mb_substr($message, 0, 4000),
                'sync_settings' => [
                    ...$settings,
                    'last_connection_test_at' => now('UTC')->toIso8601String(),
                    'last_connection_test' => [
                        'status' => 'failed',
                        'http_status' => $providerError['http_status'],
                        'provider_code' => $providerError['provider_code'],
                    ],
                ],
            ])->save();
            Inertia::flash('toast', ['type' => 'error', 'message' => $message]);
        }

        return back();
    }

    public function storeErpConnection(Request $request, OrganizationContext $context): RedirectResponse
    {
        $this->assertAdministrator($request, $context);
        $data = $request->validate([
            'company_id' => ['required', 'uuid'],
            'name' => ['required', 'string', 'max:255'],
            'base_url' => ['nullable', 'url'],
            'webhook_url' => ['nullable', 'url'],
            'webhook_secret' => ['nullable', 'string', 'min:24'],
        ]);
        abort_unless(Company::query()->whereKey($data['company_id'])->exists(), 422);
        ErpConnection::query()->create([
            'company_id' => $data['company_id'],
            'provider' => 'simpleslaravel',
            'name' => $data['name'],
            'base_url' => $data['base_url'] ?? null,
            'webhook_url' => $data['webhook_url'] ?? null,
            'encrypted_webhook_secret' => $data['webhook_secret'] ?? null,
            'status' => 'active',
        ]);

        return back()->with('success', 'Integração ERP criada.');
    }

    public function storePix(Request $request, ReceivableService $service): RedirectResponse
    {
        return $this->storeReceivable($request, $service, 'pix');
    }

    public function storeBoleto(Request $request, ReceivableService $service): RedirectResponse
    {
        return $this->storeReceivable($request, $service, 'boleto');
    }

    public function refreshReceivable(Request $request, Receivable $receivable, ReceivableOperationService $service): RedirectResponse
    {
        return $this->operateReceivable($request, $receivable, $service, 'refresh');
    }

    public function refundPix(Request $request, Receivable $receivable, ReceivableOperationService $service): RedirectResponse
    {
        $payload = $request->validate(['amount_minor' => ['required', 'integer', 'min:1']]);

        return $this->operateReceivable($request, $receivable, $service, 'refund', $payload);
    }

    public function updateBoleto(Request $request, Receivable $receivable, ReceivableOperationService $service): RedirectResponse
    {
        $payload = $request->validate([
            'amount_minor' => ['sometimes', 'integer', 'min:1'],
            'due_at' => ['sometimes', 'date'],
        ]);
        abort_if($payload === [], 422, 'Informe ao menos um campo para alterar.');

        return $this->operateReceivable($request, $receivable, $service, 'update', $payload);
    }

    public function cancelBoleto(Request $request, Receivable $receivable, ReceivableOperationService $service): RedirectResponse
    {
        return $this->operateReceivable($request, $receivable, $service, 'cancel');
    }

    public function sync(Request $request, BankConnection $bankConnection): RedirectResponse
    {
        abort_unless($bankConnection->organization_id === $request->user()->current_organization_id, 403);
        $this->assertOperator($request);
        abort_unless(
            in_array(Capability::Accounts->value, $bankConnection->capabilities ?? [], true)
                || in_array(Capability::PixRefund->value, $bankConnection->capabilities ?? [], true),
            422,
            'Esta conexão não oferece sincronização de extrato ou Pix recebidos.',
        );
        SyncBankConnection::dispatch((string) $bankConnection->getKey());

        return back()->with('success', 'Sincronização adicionada à fila.');
    }

    public function decide(Request $request, ReconciliationCase $reconciliation, ReconciliationDecisionService $service, OrganizationContext $context): RedirectResponse
    {
        $role = $request->user()->roleFor($context->get());
        abort_unless($role?->canApproveReconciliation(), 403);
        abort_unless($reconciliation->organization_id === $context->id(), 403);
        $data = $request->validate([
            'action' => ['required', 'in:match,split,partial,adjust,classify,ignore,reverse'],
            'expected_version' => ['required', 'integer'],
            'payload' => ['sometimes', 'array'],
            'idempotency_key' => ['required', 'string', 'max:200'],
        ]);
        $service->decide($reconciliation, $data['action'], $data['payload'] ?? [], $data['expected_version'], $data['idempotency_key'], 'hub', $request->user());

        return back()->with('success', 'Decisão registrada e enviada ao ERP.');
    }

    /** @param list<array{key:string,label:string,format?:string}> $columns */
    private function page(string $title, string $section, mixed $records, array $columns, mixed $options = null): Response
    {
        return Inertia::render('Platform/Index', compact('title', 'section', 'records', 'columns', 'options'));
    }

    private function assertAdministrator(Request $request, OrganizationContext $context): void
    {
        abort_unless(in_array($request->user()->roleFor($context->get()), [OrganizationRole::Owner, OrganizationRole::Admin], true), 403);
    }

    private function assertPermission(
        Request $request,
        OrganizationContext $context,
        OrganizationPermission $permission,
    ): void {
        abort_unless($request->user()->roleFor($context->get())?->allows($permission), 403);
    }

    /** @return array<string,mixed> */
    private function validateBankConnection(Request $request, ?string $expectedProvider = null): array
    {
        $request->mergeIfMissing([
            'provider' => $expectedProvider ?? 'sicredi',
            'product' => 'pix',
        ]);
        $provider = (string) $request->input('provider');
        $product = (string) $request->input('product');
        $allowedProducts = match ($provider) {
            'sicredi' => ['boleto', 'pix'],
            'bradesco' => ['boleto', 'pix'],
            default => [],
        };
        $allowedCapabilities = match ([$provider, $product]) {
            ['sicredi', 'boleto'] => [
                Capability::BoletoNormal->value,
                Capability::BoletoHybrid->value,
            ],
            ['bradesco', 'boleto'] => [
                Capability::BoletoNormal->value,
                Capability::BoletoHybrid->value,
            ],
            ['sicredi', 'pix'], ['bradesco', 'pix'] => [
                Capability::PixImmediate->value,
                Capability::PixDue->value,
                Capability::PixRefund->value,
                Capability::Webhooks->value,
            ],
            default => [],
        };
        $usesMtls = $product === 'pix' || $provider === 'bradesco';
        $usesSicrediBilling = $provider === 'sicredi' && $product === 'boleto';
        $usesBradescoBilling = $provider === 'bradesco' && $product === 'boleto';
        $usesSicrediPixKey = $provider === 'sicredi'
            && $product === 'pix'
            && array_intersect((array) $request->input('capabilities'), [
                Capability::PixImmediate->value,
                Capability::PixDue->value,
                Capability::Webhooks->value,
            ]) !== [];

        return $request->validate([
            'provider' => ['required', Rule::in($expectedProvider ? [$expectedProvider] : ['sicredi', 'bradesco'])],
            'product' => ['required', Rule::in($allowedProducts)],
            'company_id' => ['required', 'uuid'],
            'name' => ['required', 'string', 'max:255'],
            'environment' => ['required', Rule::in(['sandbox', 'production'])],
            'capabilities' => ['required', 'array', 'min:1'],
            'capabilities.*' => ['required', Rule::in($allowedCapabilities)],
            'client_id' => [Rule::requiredIf($usesMtls), 'nullable', 'string', 'max:1000'],
            'client_secret' => [Rule::requiredIf($usesMtls), 'nullable', 'string', 'max:2000'],
            'pix_key' => [Rule::requiredIf($usesSicrediPixKey), 'nullable', 'string', 'max:100'],
            'x_api_key' => [Rule::requiredIf($usesSicrediBilling), 'nullable', 'string', 'max:2000'],
            'beneficiary_code' => [Rule::requiredIf($usesSicrediBilling), 'nullable', 'regex:/^\d{5}$/'],
            'cooperative_code' => [Rule::requiredIf($usesSicrediBilling), 'nullable', 'regex:/^\d{4}$/'],
            'branch_code' => [Rule::requiredIf($usesSicrediBilling), 'nullable', 'regex:/^\d{2}$/'],
            'access_code' => [Rule::requiredIf($usesSicrediBilling), 'nullable', 'string', 'max:2000'],
            'wallet_code' => [Rule::requiredIf($usesBradescoBilling), 'nullable', 'regex:/^\d{1,2}$/'],
            'negotiation_number' => [Rule::requiredIf($usesBradescoBilling), 'nullable', 'regex:/^\d{18}$/'],
            'certificate' => [Rule::requiredIf($usesMtls), 'nullable', 'file', 'max:512'],
            'certificate_chain' => ['nullable', 'file', 'max:512'],
            'private_key' => [Rule::requiredIf($usesMtls), 'nullable', 'file', 'max:512'],
            'private_key_passphrase' => ['nullable', 'string', 'max:1000'],
            'webhook_secret' => [
                Rule::requiredIf($usesMtls && in_array(Capability::Webhooks->value, (array) $request->input('capabilities'), true)),
                'nullable',
                'string',
                'min:24',
                'max:2000',
            ],
        ]);
    }

    private function providerLabel(string $provider): string
    {
        return match ($provider) {
            'sicredi' => 'Sicredi',
            'bradesco' => 'Bradesco',
            default => $provider,
        };
    }

    /** @return array{message:string,http_status:?int,provider_code:?string} */
    private function providerError(Throwable $exception): array
    {
        if ($exception instanceof SicrediProviderException || $exception instanceof BradescoProviderException) {
            return [
                'message' => $exception->getMessage(),
                'http_status' => $exception->responseStatus,
                'provider_code' => $exception->providerCode,
            ];
        }

        return [
            'message' => 'Não foi possível concluir o teste da conexão.',
            'http_status' => null,
            'provider_code' => null,
        ];
    }

    private function storeReceivable(Request $request, ReceivableService $service, string $kind): RedirectResponse
    {
        $this->assertOperator($request);
        $requiresPayer = $kind === 'boleto' || $request->input('subtype') === 'due';
        $requiresAddress = $kind === 'boleto';
        $data = $request->validate([
            'erp_title_id' => ['required', 'uuid'],
            'bank_connection_id' => ['required', 'uuid'],
            'amount_minor' => ['nullable', 'integer', 'min:1'],
            'subtype' => ['required', 'in:immediate,due,normal,hybrid'],
            'due_at' => ['nullable', 'date', Rule::requiredIf($request->input('subtype') === 'due')],
            'reference' => ['nullable', 'string', 'max:255'],
            'payer' => [Rule::requiredIf($requiresPayer), 'array'],
            'payer.nome' => [Rule::requiredIf($requiresPayer), 'nullable', 'string', 'max:200'],
            'payer.cpf' => [
                Rule::requiredIf($requiresPayer && ! $request->filled('payer.cnpj')),
                'nullable',
                'digits:11',
            ],
            'payer.cnpj' => [
                Rule::requiredIf($requiresPayer && ! $request->filled('payer.cpf')),
                'nullable',
                'digits:14',
            ],
            'payer.endereco' => [Rule::requiredIf($requiresAddress), 'nullable', 'string', 'max:200'],
            'payer.numero' => [Rule::requiredIf($requiresAddress), 'nullable', 'string', 'max:20'],
            'payer.complemento' => ['nullable', 'string', 'max:100'],
            'payer.bairro' => [Rule::requiredIf($requiresAddress), 'nullable', 'string', 'max:100'],
            'payer.cidade' => [Rule::requiredIf($requiresAddress), 'nullable', 'string', 'max:100'],
            'payer.uf' => [Rule::requiredIf($requiresAddress), 'nullable', 'string', 'size:2'],
            'payer.cep' => [Rule::requiredIf($requiresAddress), 'nullable', 'digits:8'],
            'payer.email' => ['nullable', 'email', 'max:200'],
            'options' => ['sometimes', 'array'],
            'idempotency_key' => ['required', 'uuid'],
        ]);
        $title = ErpTitle::query()->whereKey($data['erp_title_id'])->firstOrFail();
        abort_if($kind === 'boleto' && empty($data['due_at']) && $title->due_at === null, 422, 'O boleto exige uma data de vencimento.');
        $connection = BankConnection::query()->whereKey($data['bank_connection_id'])->firstOrFail();
        $requiredCapability = match ([$kind, $data['subtype']]) {
            ['pix', 'immediate'] => Capability::PixImmediate->value,
            ['pix', 'due'] => Capability::PixDue->value,
            ['boleto', 'normal'] => Capability::BoletoNormal->value,
            ['boleto', 'hybrid'] => Capability::BoletoHybrid->value,
            default => null,
        };
        abort_unless(
            $requiredCapability !== null && in_array($requiredCapability, $connection->capabilities ?? [], true),
            422,
            'A conexão selecionada não oferece esta modalidade de cobrança.',
        );
        $service->create($kind, $title, $connection, $data['idempotency_key'], $data);

        return back()->with('success', 'Cobrança adicionada à fila bancária.');
    }

    /** @param array<string,mixed> $payload */
    private function operateReceivable(
        Request $request,
        Receivable $receivable,
        ReceivableOperationService $service,
        string $action,
        array $payload = [],
    ): RedirectResponse {
        abort_unless($receivable->organization_id === $request->user()->current_organization_id, 403);
        $this->assertOperator($request);
        $data = $request->validate(['idempotency_key' => ['required', 'uuid']]);
        $service->request($receivable, $action, $data['idempotency_key'], $payload);

        return back()->with('success', 'Operação adicionada à fila bancária.');
    }

    private function assertOperator(Request $request): void
    {
        $organization = $request->user()->currentOrganization;
        abort_unless($organization !== null, 403);
        abort_unless($request->user()->roleFor($organization)?->canApproveReconciliation(), 403);
    }
}
