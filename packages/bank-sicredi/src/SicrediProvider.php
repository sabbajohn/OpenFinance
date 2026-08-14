<?php

namespace Sabba\OpenFinance\Sicredi;

use DateTimeImmutable;
use Psr\Http\Message\ServerRequestInterface;
use Sabba\OpenFinance\Core\Contracts\AccountDataProvider;
use Sabba\OpenFinance\Core\Contracts\BoletoReceivablesProvider;
use Sabba\OpenFinance\Core\Contracts\PixReceivablesProvider;
use Sabba\OpenFinance\Core\Contracts\WebhookVerifier;
use Sabba\OpenFinance\Core\DTO\AccountSnapshot;
use Sabba\OpenFinance\Core\DTO\BalanceSnapshot;
use Sabba\OpenFinance\Core\DTO\CanonicalTransaction;
use Sabba\OpenFinance\Core\DTO\ConnectionContext;
use Sabba\OpenFinance\Core\DTO\Money;
use Sabba\OpenFinance\Core\DTO\ReceivableCommand;
use Sabba\OpenFinance\Core\DTO\ReceivableResult;
use Sabba\OpenFinance\Core\DTO\TransactionPage;
use Sabba\OpenFinance\Core\DTO\TransactionQuery;
use Sabba\OpenFinance\Core\Enums\TransactionDirection;
use Sabba\OpenFinance\Core\Enums\TransactionStatus;

final readonly class SicrediProvider implements AccountDataProvider, BoletoReceivablesProvider, PixReceivablesProvider, WebhookVerifier
{
    public function __construct(private SicrediHttpClient $http) {}

    public function accounts(ConnectionContext $context): iterable
    {
        $response = $this->http->request($context, 'accounts', 'GET', $this->path($context, 'accounts', 'accounts', '/contas'));

        foreach ($this->items($response, ['data', 'contas', 'accounts']) as $account) {
            yield new AccountSnapshot(
                externalId: (string) $this->value($account, ['id', 'accountId', 'numeroConta', 'conta']),
                type: strtolower((string) $this->value($account, ['type', 'tipo'], 'checking')),
                currency: strtoupper((string) $this->value($account, ['currency', 'moeda'], 'BRL')),
                bankCode: $this->nullable($this->value($account, ['bankCode', 'codigoBanco'], '748')),
                branch: $this->nullable($this->value($account, ['branch', 'agencia', 'cooperativa'])),
                numberMasked: $this->mask((string) $this->value($account, ['number', 'numeroConta', 'conta'])),
                metadata: $account,
            );
        }
    }

    public function balance(ConnectionContext $context, string $accountExternalId): BalanceSnapshot
    {
        $path = str_replace('{accountId}', rawurlencode($accountExternalId), $this->path($context, 'accounts', 'balance', '/contas/{accountId}/saldo'));
        $response = $this->http->request($context, 'accounts', 'GET', $path);
        $data = $this->firstObject($response, ['data', 'saldo', 'balance']);
        $currency = strtoupper((string) $this->value($data, ['currency', 'moeda'], 'BRL'));

        return new BalanceSnapshot(
            accountExternalId: $accountExternalId,
            available: Money::fromDecimal((string) $this->value($data, ['available', 'saldoDisponivel', 'valorDisponivel'], '0'), $currency),
            current: Money::fromDecimal((string) $this->value($data, ['current', 'saldoAtual', 'saldo'], '0'), $currency),
            observedAt: new DateTimeImmutable((string) $this->value($data, ['observedAt', 'dataHora', 'updatedAt'], 'now')),
        );
    }

    public function transactions(TransactionQuery $query): TransactionPage
    {
        $path = str_replace('{accountId}', rawurlencode($query->accountExternalId), $this->path($query->context, 'accounts', 'transactions', '/contas/{accountId}/extrato'));
        $response = $this->http->request($query->context, 'accounts', 'GET', $path, [
            'query' => array_filter([
                'dataInicio' => $query->from->format('Y-m-d'),
                'dataFim' => $query->to->format('Y-m-d'),
                'cursor' => $query->cursor,
                'limit' => $query->limit,
            ], fn (mixed $value): bool => $value !== null),
        ]);
        $transactions = [];

        foreach ($this->items($response, ['data', 'lancamentos', 'transactions']) as $item) {
            $signed = Money::fromDecimal((string) $this->value($item, ['amount', 'valor', 'valorLancamento'], '0'));
            $directionValue = strtolower((string) $this->value($item, ['direction', 'natureza', 'tipoMovimento']));
            $direction = match (true) {
                in_array($directionValue, ['credit', 'credito', 'crédito', 'c'], true) => TransactionDirection::Credit,
                in_array($directionValue, ['debit', 'debito', 'débito', 'd'], true) => TransactionDirection::Debit,
                default => $signed->minor >= 0 ? TransactionDirection::Credit : TransactionDirection::Debit,
            };

            $transactions[] = new CanonicalTransaction(
                externalId: $this->nullable($this->value($item, ['id', 'transactionId', 'identificador', 'codigoLancamento'])),
                type: strtolower((string) $this->value($item, ['category', 'tipo', 'tipoLancamento'], 'other')),
                direction: $direction,
                status: $this->status((string) $this->value($item, ['status', 'situacao'], 'posted')),
                amount: $signed->absolute(),
                occurredAt: new DateTimeImmutable((string) $this->value($item, ['date', 'data', 'dataMovimento'], 'now')),
                observedAt: new DateTimeImmutable,
                description: $this->nullable($this->value($item, ['description', 'descricao', 'historico'])),
                counterpartyName: $this->nullable($this->value($item, ['counterparty.name', 'contraparte.nome', 'nomeContraparte'])),
                counterpartyTaxId: $this->nullable($this->value($item, ['counterparty.taxId', 'contraparte.documento', 'documentoContraparte'])),
                identifiers: array_filter([
                    'end_to_end_id' => $this->value($item, ['endToEndId', 'end_to_end_id']),
                    'txid' => $this->value($item, ['txid', 'txId']),
                    'document_number' => $this->value($item, ['documentNumber', 'numeroDocumento']),
                    'our_number' => $this->value($item, ['ourNumber', 'nossoNumero']),
                ]),
                metadata: $item,
            );
        }

        return new TransactionPage($transactions, $this->nullable($this->value($response, ['nextCursor', 'meta.nextCursor', 'pagination.next'])));
    }

    public function createPix(ConnectionContext $context, ReceivableCommand $command): ReceivableResult
    {
        $due = $command->dueAt !== null;
        $resource = $due ? 'pix_due' : 'pix';
        $path = $this->path($context, 'pix', $resource, $due ? '/cobv/{txid}' : '/cob/{txid}');
        $path = str_replace('{txid}', rawurlencode($command->reference), $path);
        $payload = [
            'calendario' => $due ? ['dataDeVencimento' => $command->dueAt->format('Y-m-d')] : ['expiracao' => $command->options['expires_in'] ?? 3600],
            'valor' => ['original' => $command->amount->toDecimal()],
            'chave' => $command->options['pix_key'] ?? $context->credentials['default_pix_key'] ?? null,
            'solicitacaoPagador' => $command->options['message'] ?? null,
            ...($command->payer ? ['devedor' => $command->payer] : []),
        ];
        $response = $this->http->request($context, 'pix', 'PUT', $path, ['json' => array_filter($payload, fn (mixed $value): bool => $value !== null)]);

        return $this->receivableResult($response, $command->amount, $command->reference);
    }

    public function getPix(ConnectionContext $context, string $externalId): ReceivableResult
    {
        $path = str_replace('{txid}', rawurlencode($externalId), $this->path($context, 'pix', 'pix', '/cob/{txid}'));
        $response = $this->http->request($context, 'pix', 'GET', $path);

        return $this->receivableResult($response, $this->moneyFromResponse($response), $externalId);
    }

    public function refundPix(ConnectionContext $context, string $externalId, string $refundId, Money $amount): ReceivableResult
    {
        $path = str_replace(
            ['{endToEndId}', '{refundId}'],
            [rawurlencode($externalId), rawurlencode($refundId)],
            $this->path($context, 'pix', 'refund', '/pix/{endToEndId}/devolucao/{refundId}'),
        );
        $response = $this->http->request($context, 'pix', 'PUT', $path, [
            'json' => ['valor' => $amount->toDecimal()],
        ]);

        return $this->receivableResult($response, $amount, $refundId);
    }

    public function createBoleto(ConnectionContext $context, ReceivableCommand $command): ReceivableResult
    {
        $payload = [
            ...$command->options,
            'seuNumero' => $command->reference,
            'valor' => $command->amount->toDecimal(),
            'dataVencimento' => $command->dueAt?->format('Y-m-d'),
            'pagador' => $command->payer,
        ];
        $response = $this->http->request($context, 'boleto', 'POST', $this->path($context, 'boleto', 'boletos', '/boletos'), [
            'json' => array_filter($payload, fn (mixed $value): bool => $value !== null && $value !== []),
        ]);

        return $this->receivableResult($response, $command->amount, $command->reference);
    }

    public function getBoleto(ConnectionContext $context, string $externalId): ReceivableResult
    {
        $response = $this->http->request($context, 'boleto', 'GET', $this->path($context, 'boleto', 'boletos', '/boletos'), [
            'query' => ['nossoNumero' => $externalId],
        ]);

        return $this->receivableResult($response, $this->moneyFromResponse($response), $externalId);
    }

    public function cancelBoleto(ConnectionContext $context, string $externalId): ReceivableResult
    {
        $path = str_replace('{nossoNumero}', rawurlencode($externalId), $this->path($context, 'boleto', 'cancel', '/boletos/{nossoNumero}/baixa'));
        $response = $this->http->request($context, 'boleto', 'PATCH', $path);

        return $this->receivableResult($response, $this->moneyFromResponse($response), $externalId);
    }

    public function updateBoleto(ConnectionContext $context, string $externalId, array $changes): ReceivableResult
    {
        $path = str_replace('{nossoNumero}', rawurlencode($externalId), $this->path($context, 'boleto', 'update', '/boletos/{nossoNumero}'));
        $response = $this->http->request($context, 'boleto', 'PATCH', $path, [
            'json' => array_filter([
                ...($changes['options'] ?? []),
                'valor' => isset($changes['amount_minor']) ? (new Money((int) $changes['amount_minor']))->toDecimal() : null,
                'dataVencimento' => $changes['due_at'] ?? null,
                'pagador' => $changes['payer'] ?? null,
            ], fn (mixed $value): bool => $value !== null && $value !== []),
        ]);

        return $this->receivableResult($response, $this->moneyFromResponse($response), $externalId);
    }

    public function verify(ConnectionContext $context, ServerRequestInterface $request): bool
    {
        $secret = (string) ($context->credentials['webhook_secret'] ?? '');
        $header = (string) ($context->credentials['webhook_header'] ?? 'Authorization');
        $received = $request->getHeaderLine($header);
        $received = str_starts_with($received, 'Bearer ') ? substr($received, 7) : $received;

        return $secret !== '' && hash_equals($secret, $received);
    }

    private function receivableResult(array $response, Money $fallbackAmount, string $fallbackId): ReceivableResult
    {
        $data = $this->firstObject($response, ['data', 'body']);

        return new ReceivableResult(
            externalId: (string) $this->value($data, ['txid', 'nossoNumero', 'id', 'externalId'], $fallbackId),
            status: strtolower((string) $this->value($data, ['status', 'situacao'], 'pending')),
            amount: $this->moneyFromResponse($data, $fallbackAmount),
            copyAndPaste: $this->nullable($this->value($data, ['pixCopiaECola', 'copyAndPaste', 'brcode'])),
            barcode: $this->nullable($this->value($data, ['codigoBarras', 'barcode'])),
            digitableLine: $this->nullable($this->value($data, ['linhaDigitavel', 'digitableLine'])),
            paidAt: ($paid = $this->nullable($this->value($data, ['paidAt', 'dataLiquidacao']))) ? new DateTimeImmutable($paid) : null,
            metadata: $response,
        );
    }

    private function moneyFromResponse(array $response, ?Money $fallback = null): Money
    {
        $value = $this->value($response, ['valor.original', 'valor', 'amount']);

        return $value !== null ? Money::fromDecimal((string) $value) : ($fallback ?? new Money(0));
    }

    private function status(string $status): TransactionStatus
    {
        return match (strtolower($status)) {
            'pending', 'pendente' => TransactionStatus::Pending,
            'reversed', 'estornado', 'devolvido' => TransactionStatus::Reversed,
            'deleted', 'excluido' => TransactionStatus::Deleted,
            'failed', 'rejeitado' => TransactionStatus::Failed,
            default => TransactionStatus::Posted,
        };
    }

    private function path(ConnectionContext $context, string $product, string $key, string $default): string
    {
        return (string) ($context->credentials['products'][$product]['paths'][$key] ?? $default);
    }

    /** @return list<array<string,mixed>> */
    private function items(array $response, array $keys): array
    {
        foreach ($keys as $key) {
            $value = $this->dot($response, $key);
            if (is_array($value) && array_is_list($value)) {
                return array_values(array_filter($value, 'is_array'));
            }
        }

        return array_is_list($response) ? array_values(array_filter($response, 'is_array')) : [];
    }

    /** @return array<string,mixed> */
    private function firstObject(array $response, array $keys): array
    {
        foreach ($keys as $key) {
            $value = $this->dot($response, $key);
            if (is_array($value) && ! array_is_list($value)) {
                return $value;
            }
        }

        return $response;
    }

    private function value(array $data, array $keys, mixed $default = null): mixed
    {
        foreach ($keys as $key) {
            $value = $this->dot($data, $key);
            if ($value !== null && $value !== '') {
                return $value;
            }
        }

        return $default;
    }

    private function dot(array $data, string $key): mixed
    {
        $value = $data;
        foreach (explode('.', $key) as $segment) {
            if (! is_array($value) || ! array_key_exists($segment, $value)) {
                return null;
            }
            $value = $value[$segment];
        }

        return $value;
    }

    private function nullable(mixed $value): ?string
    {
        return $value === null || $value === '' ? null : (string) $value;
    }

    private function mask(string $value): ?string
    {
        $value = trim($value);
        if ($value === '') {
            return null;
        }

        return str_repeat('*', max(0, strlen($value) - 4)).substr($value, -4);
    }
}
