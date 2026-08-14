<?php

namespace App\Domain\Banking\Jobs;

use App\Domain\Banking\Models\BankAccount;
use App\Domain\Banking\Models\BankConnection;
use App\Domain\Banking\Models\SyncRun;
use App\Domain\Banking\Services\BankProviderRegistry;
use App\Domain\Banking\Services\ConnectionContextFactory;
use App\Domain\Events\Services\InboxService;
use DateTimeImmutable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\ThrottlesExceptions;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Queue\SerializesModels;
use Sabba\OpenFinance\Core\Contracts\AccountDataProvider;
use Sabba\OpenFinance\Core\Contracts\PixReceiptsProvider;
use Sabba\OpenFinance\Core\DTO\CanonicalTransaction;
use Sabba\OpenFinance\Core\DTO\ConnectionContext;
use Sabba\OpenFinance\Core\DTO\PixReceiptQuery;
use Sabba\OpenFinance\Core\DTO\TransactionQuery;
use Sabba\OpenFinance\Core\Enums\Capability;
use Throwable;

class SyncBankConnection implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 8;

    public int $timeout = 840;

    public function __construct(
        public readonly string $connectionId,
        public readonly ?string $from = null,
        public readonly ?string $to = null,
    ) {
        $this->onQueue('bank-sync');
    }

    /** @return list<object> */
    public function middleware(): array
    {
        return [
            (new WithoutOverlapping('bank-sync:'.$this->connectionId))->releaseAfter(60)->expireAfter(900)->shared(),
            (new ThrottlesExceptions(5, 300))->by('bank-provider:'.$this->connectionId)->backoff(30),
        ];
    }

    /** @return list<int> */
    public function backoff(): array
    {
        return [30, 60, 180, 600, 1800];
    }

    public function handle(
        BankProviderRegistry $providers,
        ConnectionContextFactory $contexts,
        InboxService $inbox,
    ): void {
        $connection = BankConnection::query()->withoutGlobalScopes()->findOrFail($this->connectionId);
        if (! in_array($connection->status, ['active', 'degraded', 'action_required'], true)) {
            return;
        }

        $provider = $providers->for($connection);
        $accountSync = $provider instanceof AccountDataProvider
            && in_array(Capability::Accounts->value, $connection->capabilities ?? [], true);
        $receiptSync = $provider instanceof PixReceiptsProvider
            && in_array(Capability::PixRefund->value, $connection->capabilities ?? [], true);
        if (! $accountSync && ! $receiptSync) {
            return;
        }

        $run = SyncRun::query()->withoutGlobalScopes()->create([
            'organization_id' => $connection->organization_id,
            'company_id' => $connection->company_id,
            'bank_connection_id' => $connection->getKey(),
            'capability' => $accountSync ? 'transactions' : 'pix.receipts',
            'status' => 'running',
            'started_at' => now('UTC'),
        ]);

        try {
            $contexts->with($connection, function (ConnectionContext $context) use ($provider, $connection, $inbox, $run, $accountSync): void {
                if (! $accountSync) {
                    $this->syncPixReceipts($provider, $context, $connection, $inbox, $run);

                    return;
                }

                $this->discoverAccounts($provider, $context, $connection);
                $accounts = BankAccount::query()->withoutGlobalScopes()
                    ->where('bank_connection_id', $connection->getKey())
                    ->where('status', 'active')
                    ->get();

                foreach ($accounts as $account) {
                    $balance = $provider->balance($context, $account->provider_account_id);
                    $account->forceFill([
                        'available_balance_minor' => $balance->available->minor,
                        'current_balance_minor' => $balance->current->minor,
                        'balance_observed_at' => $balance->observedAt,
                    ])->save();

                    $from = new DateTimeImmutable($this->from ?? now('UTC')->subDays((int) config('openfinance.sync.overlap_days'))->toIso8601String());
                    $to = new DateTimeImmutable($this->to ?? now('UTC')->toIso8601String());
                    $cursor = null;
                    do {
                        $page = $provider->transactions(new TransactionQuery(
                            context: $context,
                            accountExternalId: $account->provider_account_id,
                            from: $from,
                            to: $to,
                            cursor: $cursor,
                        ));

                        foreach ($page->transactions as $transaction) {
                            $payload = $this->payload($connection, $account, $transaction);
                            $versionedContent = $payload['transaction'];
                            unset($versionedContent['observed_at']);
                            $contentVersion = hash('sha256', json_encode($versionedContent, JSON_THROW_ON_ERROR));
                            $resourceId = $transaction->externalId ?: hash('sha256', json_encode($payload['transaction'], JSON_THROW_ON_ERROR));
                            $inbox->receive(
                                source: $connection->provider,
                                eventType: 'bank.transaction.observed',
                                idempotencyKey: implode(':', [$connection->provider, $connection->getKey(), 'bank_transaction', $resourceId, $contentVersion]),
                                payload: $payload,
                                organizationId: $connection->organization_id,
                                companyId: $connection->company_id,
                                correlationId: (string) $run->getKey(),
                            );
                            $run->items_seen++;
                        }

                        $cursor = $page->nextCursor;
                        $run->cursor = $cursor;
                        $run->save();
                    } while ($cursor !== null);
                }
            });

            $run->forceFill(['status' => 'completed', 'finished_at' => now('UTC')])->save();
            $connection->forceFill(['status' => 'active', 'last_synced_at' => now('UTC'), 'last_error' => null])->save();
        } catch (Throwable $exception) {
            $run->forceFill([
                'status' => 'failed',
                'finished_at' => now('UTC'),
                'error' => mb_substr($exception->getMessage(), 0, 4000),
            ])->save();
            $connection->forceFill([
                'last_error' => mb_substr($exception->getMessage(), 0, 4000),
                'status' => 'degraded',
            ])->save();

            throw $exception;
        }
    }

    private function syncPixReceipts(
        PixReceiptsProvider $provider,
        ConnectionContext $context,
        BankConnection $connection,
        InboxService $inbox,
        SyncRun $run,
    ): void {
        $account = BankAccount::query()->withoutGlobalScopes()->updateOrCreate(
            [
                'bank_connection_id' => $connection->getKey(),
                'provider_account_id' => 'pix-received',
            ],
            [
                'organization_id' => $connection->organization_id,
                'company_id' => $connection->company_id,
                'type' => 'pix',
                'bank_code' => match ($connection->provider) {
                    'sicredi' => '748',
                    'bradesco' => '237',
                    default => null,
                },
                'number_masked' => 'Recebimentos Pix',
                'currency' => 'BRL',
                'metadata' => ['virtual' => true, 'source' => 'pix.receipts'],
                'status' => 'active',
            ],
        );
        $from = new DateTimeImmutable($this->from ?? now('UTC')->subDays((int) config('openfinance.sync.overlap_days'))->toIso8601String());
        $to = new DateTimeImmutable($this->to ?? now('UTC')->toIso8601String());
        $cursor = null;

        do {
            $page = $provider->receivedPix(new PixReceiptQuery(
                context: $context,
                from: $from,
                to: $to,
                cursor: $cursor,
            ));

            foreach ($page->transactions as $transaction) {
                $payload = $this->payload($connection, $account, $transaction);
                $versionedContent = $payload['transaction'];
                unset($versionedContent['observed_at']);
                $contentVersion = hash('sha256', json_encode($versionedContent, JSON_THROW_ON_ERROR));
                $resourceId = $transaction->externalId ?: hash('sha256', json_encode($payload['transaction'], JSON_THROW_ON_ERROR));
                $inbox->receive(
                    source: $connection->provider,
                    eventType: 'bank.transaction.observed',
                    idempotencyKey: implode(':', [$connection->provider, $connection->getKey(), 'pix_receipt', $resourceId, $contentVersion]),
                    payload: $payload,
                    organizationId: $connection->organization_id,
                    companyId: $connection->company_id,
                    correlationId: (string) $run->getKey(),
                );
                $run->items_seen++;
            }

            $cursor = $page->nextCursor;
            $run->cursor = $cursor;
            $run->save();
        } while ($cursor !== null);
    }

    private function discoverAccounts(AccountDataProvider $provider, ConnectionContext $context, BankConnection $connection): void
    {
        foreach ($provider->accounts($context) as $snapshot) {
            BankAccount::query()->withoutGlobalScopes()->updateOrCreate(
                [
                    'bank_connection_id' => $connection->getKey(),
                    'provider_account_id' => $snapshot->externalId,
                ],
                [
                    'organization_id' => $connection->organization_id,
                    'company_id' => $connection->company_id,
                    'type' => $snapshot->type,
                    'bank_code' => $snapshot->bankCode,
                    'branch' => $snapshot->branch,
                    'number_masked' => $snapshot->numberMasked,
                    'currency' => $snapshot->currency,
                    'metadata' => $snapshot->metadata,
                    'status' => 'active',
                ],
            );
        }
    }

    /** @return array<string,mixed> */
    private function payload(BankConnection $connection, BankAccount $account, CanonicalTransaction $transaction): array
    {
        return [
            'connection_id' => $connection->getKey(),
            'account_id' => $account->getKey(),
            'transaction' => [
                'external_id' => $transaction->externalId,
                'account_external_id' => $account->provider_account_id,
                'type' => $transaction->type,
                'direction' => $transaction->direction->value,
                'status' => $transaction->status->value,
                'amount_minor' => $transaction->amount->minor,
                'currency' => $transaction->amount->currency,
                'occurred_at' => $transaction->occurredAt->format(DATE_ATOM),
                'observed_at' => $transaction->observedAt->format(DATE_ATOM),
                'description' => $transaction->description,
                'counterparty_name' => $transaction->counterpartyName,
                'counterparty_tax_id' => $transaction->counterpartyTaxId,
                'identifiers' => $transaction->identifiers,
                'metadata' => $transaction->metadata,
            ],
        ];
    }
}
