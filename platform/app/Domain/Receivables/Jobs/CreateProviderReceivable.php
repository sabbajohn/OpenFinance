<?php

namespace App\Domain\Receivables\Jobs;

use App\Domain\Banking\Models\BankConnection;
use App\Domain\Banking\Services\BankProviderRegistry;
use App\Domain\Banking\Services\ConnectionContextFactory;
use App\Domain\Events\Services\OutboxService;
use App\Domain\Receivables\Models\Receivable;
use DateTimeImmutable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\ThrottlesExceptions;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Queue\SerializesModels;
use Sabba\OpenFinance\Core\Contracts\BoletoReceivablesProvider;
use Sabba\OpenFinance\Core\Contracts\PixReceivablesProvider;
use Sabba\OpenFinance\Core\DTO\ConnectionContext;
use Sabba\OpenFinance\Core\DTO\Money;
use Sabba\OpenFinance\Core\DTO\ReceivableCommand;
use Sabba\OpenFinance\Core\DTO\ReceivableResult;
use Throwable;

class CreateProviderReceivable implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 8;

    public function __construct(
        public readonly string $receivableId,
        public readonly string $bankConnectionId,
    ) {
        $this->onQueue('bank-sync');
    }

    /** @return list<object> */
    public function middleware(): array
    {
        return [
            (new WithoutOverlapping('receivable:'.$this->receivableId))->releaseAfter(15)->expireAfter(180)->shared(),
            (new ThrottlesExceptions(5, 300))->by('receivable-provider:'.$this->bankConnectionId.':create')->backoff(30),
        ];
    }

    public function handle(
        BankProviderRegistry $providers,
        ConnectionContextFactory $contexts,
        OutboxService $outbox,
    ): void {
        $receivable = Receivable::query()->withoutGlobalScopes()->findOrFail($this->receivableId);
        if ($receivable->provider_external_id || ! in_array($receivable->status, ['pending', 'failed'], true)) {
            return;
        }

        $connection = BankConnection::query()->withoutGlobalScopes()->findOrFail($receivable->bank_connection_id);
        $provider = $providers->for($connection);
        $metadata = $receivable->metadata ?? [];
        $command = new ReceivableCommand(
            idempotencyKey: $receivable->idempotency_key,
            reference: $receivable->reference ?: (string) $receivable->getKey(),
            amount: new Money((int) $receivable->amount_minor, $receivable->currency),
            dueAt: $receivable->due_at ? new DateTimeImmutable($receivable->due_at->format('Y-m-d')) : null,
            payer: $metadata['payer'] ?? [],
            options: $metadata['options'] ?? [],
        );

        try {
            $result = $contexts->with($connection, function (ConnectionContext $context) use ($provider, $receivable, $command): ReceivableResult {
                if ($receivable->kind === 'pix' && $provider instanceof PixReceivablesProvider) {
                    return $provider->createPix($context, $command);
                }
                if ($receivable->kind === 'boleto' && $provider instanceof BoletoReceivablesProvider) {
                    return $provider->createBoleto($context, $command);
                }

                throw new \RuntimeException("O adapter não oferece {$receivable->kind}.");
            });

            $receivable->forceFill([
                'provider_external_id' => $result->externalId,
                'status' => $result->status,
                'copy_and_paste' => $result->copyAndPaste,
                'barcode' => $result->barcode,
                'digitable_line' => $result->digitableLine,
                'paid_at' => $result->paidAt,
                'metadata' => [...$metadata, 'provider' => $result->metadata],
                'version' => $receivable->version + 1,
            ])->save();
            $outbox->forModel($receivable->kind.'.charge.created', $receivable, [
                'receivable_id' => $receivable->getKey(),
                'erp_title_id' => $receivable->erp_title_id,
                'kind' => $receivable->kind,
                'subtype' => $receivable->subtype,
                'external_id' => $receivable->provider_external_id,
                'status' => $receivable->status,
                'amount_minor' => $receivable->amount_minor,
                'currency' => $receivable->currency,
                'copy_and_paste' => $receivable->copy_and_paste,
                'barcode' => $receivable->barcode,
                'digitable_line' => $receivable->digitable_line,
            ]);
        } catch (Throwable $exception) {
            $receivable->forceFill(['status' => 'failed'])->save();
            throw $exception;
        }
    }
}
