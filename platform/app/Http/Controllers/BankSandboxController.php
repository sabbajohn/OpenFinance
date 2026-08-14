<?php

namespace App\Http\Controllers;

use App\Domain\Banking\Models\BankConnection;
use App\Domain\Banking\Models\BankSandboxRun;
use App\Domain\Banking\Services\BankSandboxRunner;
use App\Support\OrganizationContext;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;
use Sabba\OpenFinance\Core\Enums\Capability;

class BankSandboxController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Platform/Sandbox', [
            'enabled' => (bool) config('openfinance.sandbox.enabled'),
            'connections' => BankConnection::query()
                ->with('company:id,trade_name,legal_name')
                ->where('environment', 'sandbox')
                ->latest()
                ->get()
                ->map(fn (BankConnection $connection): array => [
                    'id' => $connection->getKey(),
                    'name' => $connection->name,
                    'provider' => $connection->provider,
                    'product' => data_get($connection->sync_settings, 'product', 'pix'),
                    'status' => $connection->status,
                    'company_name' => $connection->company?->trade_name ?: $connection->company?->legal_name,
                    'can_test_receipts' => in_array(Capability::PixRefund->value, $connection->capabilities ?? [], true),
                ]),
            'runs' => BankSandboxRun::query()
                ->with(['connection:id,name,provider', 'user:id,name'])
                ->latest()
                ->limit(100)
                ->get(),
        ]);
    }

    public function store(
        Request $request,
        OrganizationContext $context,
        BankSandboxRunner $runner,
    ): RedirectResponse {
        abort_unless(config('openfinance.sandbox.enabled'), 404);
        $data = $request->validate([
            'bank_connection_id' => ['required', 'uuid'],
            'suite' => ['required', Rule::in(['authentication', 'pix_receipts'])],
        ]);
        $connection = BankConnection::query()->whereKey($data['bank_connection_id'])->firstOrFail();
        abort_unless($connection->organization_id === $context->id(), 403);
        abort_unless($connection->environment === 'sandbox', 422, 'Selecione uma conexão configurada para Sandbox.');
        abort_if(
            $data['suite'] === 'pix_receipts'
                && ! in_array(Capability::PixRefund->value, $connection->capabilities ?? [], true),
            422,
            'Esta conexão não possui a consulta de Pix recebidos habilitada.',
        );

        $run = $runner->run($connection, $request->user(), $data['suite']);
        Inertia::flash('toast', $run->status === 'passed'
            ? ['type' => 'success', 'message' => 'Suíte Sandbox concluída com sucesso.']
            : ['type' => 'error', 'message' => $run->error ?: 'A suíte Sandbox encontrou uma falha.']);

        return back();
    }
}
