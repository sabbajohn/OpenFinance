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
            ],
            'recentTransactions' => BankTransaction::query()->latest('occurred_at')->limit(8)->get(),
            'queueHealth' => OutboxEvent::query()->where('organization_id', $context->id())->selectRaw('status, count(*) as total')->groupBy('status')->pluck('total', 'status'),
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

    public function bankConnections(): Response
    {
        $connections = BankConnection::query()
            ->with('company:id,trade_name,legal_name')
            ->withCount('accounts')
            ->latest()
            ->get()
            ->map(function (BankConnection $connection): array {
                $credentials = $connection->encrypted_credentials;
                $pix = is_array($credentials) ? data_get($credentials, 'products.pix', []) : [];
                $settings = is_array($connection->sync_settings) ? $connection->sync_settings : [];

                return [
                    'id' => $connection->getKey(),
                    'company_id' => $connection->company_id,
                    'company_name' => $connection->company?->trade_name ?: $connection->company?->legal_name,
                    'name' => $connection->name,
                    'provider' => $connection->provider,
                    'environment' => $connection->environment,
                    'status' => $connection->status,
                    'capabilities' => $connection->capabilities,
                    'accounts_count' => $connection->accounts_count,
                    'certificate_expires_at' => $connection->certificate_expires_at,
                    'last_synced_at' => $connection->last_synced_at,
                    'last_error' => $connection->last_error,
                    'last_test_at' => $settings['last_connection_test_at'] ?? null,
                    'last_test' => $settings['last_connection_test'] ?? null,
                    'configured' => is_array($pix) && ! empty($pix['client_id']) && ! empty($pix['certificate_pem']),
                    'client_id_hint' => is_array($pix) && ! empty($pix['client_id'])
                        ? '••••'.substr((string) $pix['client_id'], -6)
                        : null,
                    'scope' => is_array($pix) ? ($pix['scope'] ?? null) : null,
                    'webhook_url' => in_array(Capability::Webhooks->value, $connection->capabilities ?? [], true)
                        ? route('bank-webhooks.pix.receive', ['bankConnection' => $connection])
                        : null,
                    'can_sync' => in_array(Capability::Accounts->value, $connection->capabilities ?? [], true),
                ];
            });

        return Inertia::render('Platform/BankConnections', [
            'connections' => $connections,
            'companies' => Company::query()->select('id', 'trade_name', 'legal_name')->orderBy('trade_name')->get(),
            'providers' => [
                [
                    'value' => 'sicredi',
                    'label' => 'Sicredi',
                    'default_name' => 'Sicredi Pix',
                    'portal_url' => 'https://developer.sicredi.com.br',
                    'capabilities' => [
                        Capability::PixImmediate->value,
                        Capability::PixDue->value,
                        Capability::PixRefund->value,
                        Capability::Webhooks->value,
                    ],
                ],
                [
                    'value' => 'bradesco',
                    'label' => 'Bradesco',
                    'default_name' => 'Bradesco Pix',
                    'portal_url' => 'https://developers.bradesco.com.br',
                    'capabilities' => [
                        Capability::PixImmediate->value,
                        Capability::PixRefund->value,
                        Capability::Webhooks->value,
                    ],
                ],
            ],
            'presets' => [
                'sicredi' => config('openfinance.sicredi.pix.environments'),
                'bradesco' => config('openfinance.bradesco.pix.environments'),
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
        return $this->page('Transações bancárias', 'transactions', BankTransaction::query()->latest('occurred_at')->limit(500)->get(), [
            ['key' => 'occurred_at', 'label' => 'Data'],
            ['key' => 'description', 'label' => 'Descrição'],
            ['key' => 'direction', 'label' => 'Direção'],
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
            'connections' => BankConnection::query()->where('status', 'active')->get(['id', 'company_id', 'name']),
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
        abort_unless(Company::query()->whereKey($data['company_id'])->exists(), 422);
        $configuration = match ($data['provider']) {
            'sicredi' => $sicrediConfigurator->build($data),
            'bradesco' => $bradescoConfigurator->build($data),
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
            'sync_settings' => ['source' => $data['provider'].'_pix', 'last_connection_test' => ['status' => 'pending']],
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
        abort_unless(Company::query()->whereKey($data['company_id'])->exists(), 422);
        $configuration = match ($data['provider']) {
            'sicredi' => $sicrediConfigurator->build($data),
            'bradesco' => $bradescoConfigurator->build($data),
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
            'sync_settings' => ['source' => $data['provider'].'_pix', 'last_connection_test' => ['status' => 'pending']],
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
        $this->assertAdministrator($request, $context);
        abort_unless($bankConnection->organization_id === $context->id(), 403);

        try {
            $client = match ($bankConnection->provider) {
                'sicredi' => $sicredi,
                'bradesco' => $bradesco,
                default => abort(422, 'Banco não suportado.'),
            };
            $result = $contexts->with(
                $bankConnection,
                fn ($connectionContext): array => $client->testAuthentication($connectionContext, 'pix'),
            );
            $settings = is_array($bankConnection->sync_settings) ? $bankConnection->sync_settings : [];
            $bankConnection->forceFill([
                'status' => 'active',
                'last_error' => null,
                'sync_settings' => [
                    ...$settings,
                    'last_connection_test_at' => now('UTC')->toIso8601String(),
                    'last_connection_test' => ['status' => 'passed', ...$result],
                ],
            ])->save();
            Inertia::flash('toast', ['type' => 'success', 'message' => 'mTLS e OAuth2 validados com sucesso no '.$this->providerLabel($bankConnection->provider).'.']);
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
        abort_unless(in_array(Capability::Accounts->value, $bankConnection->capabilities ?? [], true), 422, 'Esta conexão não oferece sincronização de contas e extrato.');
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

    /** @return array<string,mixed> */
    private function validateBankConnection(Request $request, ?string $expectedProvider = null): array
    {
        $request->mergeIfMissing(['provider' => $expectedProvider ?? 'sicredi']);
        $provider = (string) $request->input('provider');
        $allowedCapabilities = match ($provider) {
            'sicredi' => [
                Capability::PixImmediate->value,
                Capability::PixDue->value,
                Capability::PixRefund->value,
                Capability::Webhooks->value,
            ],
            'bradesco' => [
                Capability::PixImmediate->value,
                Capability::PixRefund->value,
                Capability::Webhooks->value,
            ],
            default => [],
        };

        return $request->validate([
            'provider' => ['required', Rule::in($expectedProvider ? [$expectedProvider] : ['sicredi', 'bradesco'])],
            'company_id' => ['required', 'uuid'],
            'name' => ['required', 'string', 'max:255'],
            'environment' => ['required', Rule::in(['sandbox', 'production'])],
            'capabilities' => ['required', 'array', 'min:1'],
            'capabilities.*' => ['required', Rule::in($allowedCapabilities)],
            'client_id' => ['required', 'string', 'max:1000'],
            'client_secret' => ['required', 'string', 'max:2000'],
            'pix_key' => ['nullable', 'string', 'max:100'],
            'certificate' => ['required', 'file', 'max:512'],
            'certificate_chain' => ['nullable', 'file', 'max:512'],
            'private_key' => ['required', 'file', 'max:512'],
            'private_key_passphrase' => ['nullable', 'string', 'max:1000'],
            'webhook_secret' => [
                Rule::requiredIf($provider === 'bradesco' && in_array(Capability::Webhooks->value, (array) $request->input('capabilities'), true)),
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
        $data = $request->validate([
            'erp_title_id' => ['required', 'uuid'],
            'bank_connection_id' => ['required', 'uuid'],
            'amount_minor' => ['nullable', 'integer', 'min:1'],
            'subtype' => ['required', 'in:immediate,due,normal,hybrid'],
            'due_at' => ['nullable', 'date'],
            'reference' => ['nullable', 'string', 'max:255'],
            'payer' => ['sometimes', 'array'],
            'options' => ['sometimes', 'array'],
            'idempotency_key' => ['required', 'uuid'],
        ]);
        $title = ErpTitle::query()->whereKey($data['erp_title_id'])->firstOrFail();
        $connection = BankConnection::query()->whereKey($data['bank_connection_id'])->firstOrFail();
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
