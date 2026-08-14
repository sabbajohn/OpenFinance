<?php

namespace App\Domain\Receivables\Jobs;

use App\Domain\Banking\Models\BankConnection;
use App\Domain\Banking\Services\BankProviderRegistry;
use App\Domain\Banking\Services\ConnectionContextFactory;
use App\Domain\Events\Services\OutboxService;
use App\Domain\Receivables\Models\Receivable;
use App\Domain\Receivables\Models\ReceivableOperation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\ThrottlesExceptions;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Queue\SerializesModels;
use RuntimeException;
use Sabba\OpenFinance\Core\Contracts\BoletoReceivablesProvider;
use Sabba\OpenFinance\Core\Contracts\PixReceivablesProvider;
use Sabba\OpenFinance\Core\DTO\ConnectionContext;
use Sabba\OpenFinance\Core\DTO\Money;
use Sabba\OpenFinance\Core\DTO\ReceivableResult;
use Throwable;

class ProcessReceivableOperation implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 8;

    public function __construct(
        public readonly string $operationId,
        public readonly string $bankConnectionId,
        public readonly string $product,
    ) {
        $this->onQueue('bank-sync');
    }

    /** @return list<object> */
    public function middleware(): array
    {
        return [
            (new WithoutOverlapping('receivable-operation:'.$this->operationId))->releaseAfter(15)->expireAfter(300)->shared(),
            (new ThrottlesExceptions(5, 300))->by('receivable-provider:'.$this->bankConnectionId.':'.$this->product)->backoff(30),
        ];
    }

    /** @return list<int> */
    public function backoff(): array
    {
        return [5, 15, 60, 300, 900];
    }

    public function handle(
        BankProviderRegistry $providers,
        ConnectionContextFactory $contexts,
        OutboxService $outbox,
    ): void {
        $operation = ReceivableOperation::query()->withoutGlobalScopes()->findOrFail($this->operationId);
        if ($operation->status === 'completed') {
            return;
        }

        $operation->forceFill(['status' => 'processing', 'attempts' => $operation->attempts + 1, 'last_error' => null])->save();
        $receivable = Receivable::query()->withoutGlobalScopes()->findOrFail($operation->receivable_id);
        $connection = BankConnection::query()->withoutGlobalScopes()->findOrFail($receivable->bank_connection_id);
        $provider = $providers->for($connection);

        try {
            $result = $contexts->with($connection, fn (ConnectionContext $context): ReceivableResult => $this->callProvider(
                $provider,
                $context,
                $receivable,
                $operation,
            ));
            $status = $this->normalizeStatus($result->status, $operation->action, $receivable->status);
            $attributes = [
                'status' => $status,
                'copy_and_paste' => $result->copyAndPaste ?: $receivable->copy_and_paste,
                'barcode' => $result->barcode ?: $receivable->barcode,
                'digitable_line' => $result->digitableLine ?: $receivable->digitable_line,
                'paid_at' => $result->paidAt ?: $receivable->paid_at,
                'cancelled_at' => $status === 'cancelled' ? now('UTC') : $receivable->cancelled_at,
                'metadata' => [...($receivable->metadata ?? []), 'last_provider_operation' => $result->metadata],
                'version' => $receivable->version + 1,
            ];
            if ($operation->action === 'update') {
                $attributes['amount_minor'] = (int) ($operation->payload['amount_minor'] ?? $receivable->amount_minor);
                $attributes['due_at'] = $operation->payload['due_at'] ?? $receivable->due_at;
            }
            $receivable->forceFill($attributes)->save();
            $operation->forceFill([
                'status' => 'completed',
                'provider_result' => [
                    'external_id' => $result->externalId,
                    'status' => $result->status,
                    'amount_minor' => $result->amount->minor,
                    'currency' => $result->amount->currency,
                ],
                'completed_at' => now('UTC'),
            ])->save();

            $outbox->forModel($this->eventType($receivable, $operation), $receivable, [
                'receivable_id' => $receivable->getKey(),
                'erp_title_id' => $receivable->erp_title_id,
                'operation_id' => $operation->getKey(),
                'external_id' => $receivable->provider_external_id,
                'status' => $receivable->status,
                'amount_minor' => $receivable->amount_minor,
                'currency' => $receivable->currency,
            ]);
        } catch (Throwable $exception) {
            $operation->forceFill([
                'status' => 'retrying',
                'last_error' => mb_substr($exception->getMessage(), 0, 4000),
            ])->save();
            throw $exception;
        }
    }

    public function failed(Throwable $exception): void
    {
        ReceivableOperation::query()->withoutGlobalScopes()->whereKey($this->operationId)->update([
            'status' => 'failed',
            'last_error' => mb_substr($exception->getMessage(), 0, 4000),
        ]);
    }

    private function callProvider(
        object $provider,
        ConnectionContext $context,
        Receivable $receivable,
        ReceivableOperation $operation,
    ): ReceivableResult {
        $externalId = (string) $receivable->provider_external_id;

        return match ($operation->action) {
            'refresh' => match (true) {
                $receivable->kind === 'pix' && $provider instanceof PixReceivablesProvider => $provider->getPix(
                    $context,
                    $externalId,
                    $receivable->subtype,
                ),
                $receivable->kind === 'boleto' && $provider instanceof BoletoReceivablesProvider => $provider->getBoleto(
                    $context,
                    $externalId,
                    $receivable->subtype,
                ),
                default => throw new RuntimeException('O adapter não permite consultar esta cobrança.'),
            },
            'refund' => $provider instanceof PixReceivablesProvider
                ? $provider->refundPix(
                    $context,
                    $this->refundTransactionId($receivable, $operation, $externalId),
                    (string) ($operation->payload['refund_id'] ?? $operation->getKey()),
                    new Money((int) $operation->payload['amount_minor'], $receivable->currency),
                )
                : throw new RuntimeException('O adapter não oferece devolução Pix.'),
            'update' => $provider instanceof BoletoReceivablesProvider
                ? $provider->updateBoleto(
                    $context,
                    $externalId,
                    [
                        ...$operation->payload,
                        'provider_metadata' => data_get($receivable->metadata, 'provider', []),
                    ],
                    $receivable->subtype,
                )
                : throw new RuntimeException('O adapter não oferece alteração de boleto.'),
            'cancel' => $provider instanceof BoletoReceivablesProvider
                ? $provider->cancelBoleto($context, $externalId, $receivable->subtype)
                : throw new RuntimeException('O adapter não oferece baixa de boleto.'),
            default => throw new RuntimeException('Operação de cobrança desconhecida.'),
        };
    }

    private function refundTransactionId(
        Receivable $receivable,
        ReceivableOperation $operation,
        string $fallback,
    ): string {
        $metadata = $receivable->metadata ?? [];

        foreach ([
            $operation->payload['external_transaction_id'] ?? null,
            data_get($metadata, 'last_webhook.pix.0.endToEndId'),
            data_get($metadata, 'last_provider_operation.pix.0.endToEndId'),
            data_get($metadata, 'provider.pix.0.endToEndId'),
            data_get($metadata, 'provider.data.pix.0.endToEndId'),
        ] as $candidate) {
            if (is_string($candidate) && $candidate !== '') {
                return $candidate;
            }
        }

        return $fallback;
    }

    private function normalizeStatus(string $providerStatus, string $action, string $fallback): string
    {
        return match (mb_strtolower($providerStatus)) {
            'concluida', 'concluído', 'paid', 'liquidado', 'liquidada' => 'paid',
            'devolvido', 'devolvida', 'refunded' => 'refunded',
            'cancelado', 'cancelada', 'removed' => 'cancelled',
            'expirada', 'expired' => 'expired',
            'ativa', 'active', 'created' => 'active',
            default => $action === 'cancel' ? 'cancelled' : $fallback,
        };
    }

    private function eventType(Receivable $receivable, ReceivableOperation $operation): string
    {
        return match ($operation->action) {
            'refund' => 'pix.charge.refunded',
            'cancel' => 'boleto.cancelled',
            'update' => 'boleto.updated',
            default => $receivable->kind.'.charge.updated',
        };
    }
}
