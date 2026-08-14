<?php

namespace App\Http\Controllers\Api\V1;

use App\Domain\Banking\Models\BankConnection;
use App\Domain\Banking\Services\BankProviderRegistry;
use App\Domain\Banking\Services\ConnectionContextFactory;
use App\Domain\Events\Services\InboxService;
use App\Http\Controllers\Controller;
use GuzzleHttp\Psr7\ServerRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Sabba\OpenFinance\Core\Contracts\WebhookVerifier;
use Sabba\OpenFinance\Core\DTO\ConnectionContext;

class BankWebhookController extends Controller
{
    public function __invoke(
        Request $request,
        BankConnection $bankConnection,
        BankProviderRegistry $providers,
        ConnectionContextFactory $contexts,
        InboxService $inbox,
    ): JsonResponse {
        $provider = $providers->for($bankConnection);
        abort_unless($provider instanceof WebhookVerifier, 404);

        $headers = collect($request->headers->all())->map(fn (array $values) => $values)->all();
        $psrRequest = new ServerRequest($request->method(), $request->fullUrl(), $headers, $request->getContent());
        $valid = $contexts->with(
            $bankConnection,
            fn (ConnectionContext $context): bool => $provider->verify($context, $psrRequest),
        );
        abort_unless($valid, 401, 'Assinatura do banco inválida.');

        $payload = $request->json()->all();
        $providerEventId = $request->header('X-Request-Id')
            ?? data_get($payload, 'id')
            ?? data_get($payload, 'evento.id')
            ?? hash('sha256', $request->getContent());
        $event = $inbox->receive(
            source: $bankConnection->provider,
            eventType: $bankConnection->provider.'.webhook',
            idempotencyKey: implode(':', [$bankConnection->provider, $bankConnection->getKey(), 'webhook', $providerEventId]),
            payload: $payload,
            organizationId: $bankConnection->organization_id,
            companyId: $bankConnection->company_id,
            correlationId: (string) $providerEventId,
        );

        return response()->json(['data' => ['event_id' => $event->getKey(), 'status' => 'accepted']], 202);
    }
}
