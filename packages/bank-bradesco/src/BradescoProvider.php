<?php

namespace Sabba\OpenFinance\Bradesco;

use DateTimeImmutable;
use Psr\Http\Message\ServerRequestInterface;
use Sabba\OpenFinance\Core\Contracts\PixReceivablesProvider;
use Sabba\OpenFinance\Core\Contracts\WebhookVerifier;
use Sabba\OpenFinance\Core\DTO\ConnectionContext;
use Sabba\OpenFinance\Core\DTO\Money;
use Sabba\OpenFinance\Core\DTO\ReceivableCommand;
use Sabba\OpenFinance\Core\DTO\ReceivableResult;

final readonly class BradescoProvider implements PixReceivablesProvider, WebhookVerifier
{
    public function __construct(private BradescoHttpClient $http) {}

    public function createPix(ConnectionContext $context, ReceivableCommand $command): ReceivableResult
    {
        if ($command->dueAt !== null) {
            throw new BradescoProviderException('A integração Bradesco habilitada não oferece Pix com vencimento.');
        }

        $pixKey = $command->options['pix_key'] ?? $context->credentials['default_pix_key'] ?? null;
        if (! is_string($pixKey) || $pixKey === '') {
            throw new BradescoProviderException('Informe uma chave Pix para criar a cobrança no Bradesco.');
        }

        $path = str_replace(
            '{txid}',
            rawurlencode($command->reference),
            $this->path($context, 'pix', 'charge', '/cob/{txid}'),
        );
        $payload = [
            'calendario' => ['expiracao' => $command->options['expires_in'] ?? 3600],
            'valor' => ['original' => $command->amount->toDecimal()],
            'chave' => $pixKey,
            'solicitacaoPagador' => $command->options['message'] ?? null,
            ...($command->payer ? ['devedor' => $command->payer] : []),
        ];
        $response = $this->http->request($context, 'pix', 'PUT', $path, [
            'json' => array_filter($payload, fn (mixed $value): bool => $value !== null),
        ]);

        return $this->receivableResult($response, $command->amount, $command->reference);
    }

    public function getPix(ConnectionContext $context, string $externalId): ReceivableResult
    {
        $path = str_replace(
            '{txid}',
            rawurlencode($externalId),
            $this->path($context, 'pix', 'charge', '/cob/{txid}'),
        );
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

    /**
     * O edge deve validar o certificado cliente do callback Bradesco e injetar
     * um segredo interno antes que a requisição alcance a aplicação.
     */
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
        $paidAt = $this->nullable($this->value($data, ['paidAt', 'dataLiquidacao', 'pix.0.horario', 'horario.liquidacao']));

        return new ReceivableResult(
            externalId: (string) $this->value($data, ['txid', 'id', 'externalId'], $fallbackId),
            status: $this->status((string) $this->value($data, ['status', 'situacao'], 'pending')),
            amount: $this->moneyFromResponse($data, $fallbackAmount),
            copyAndPaste: $this->nullable($this->value($data, ['pixCopiaECola', 'copyAndPaste', 'qrCode', 'qr_code', 'brcode', 'payload'])),
            paidAt: $paidAt ? new DateTimeImmutable($paidAt) : null,
            metadata: $response,
        );
    }

    private function moneyFromResponse(array $response, ?Money $fallback = null): Money
    {
        $value = $this->value($response, ['valor.original', 'valor', 'amount']);

        return $value !== null ? Money::fromDecimal((string) $value) : ($fallback ?? new Money(0));
    }

    private function status(string $status): string
    {
        return match (mb_strtolower($status)) {
            'ativa', 'active', 'created' => 'active',
            'concluida', 'concluída', 'concluido', 'concluído', 'paid', 'liquidado', 'liquidada' => 'paid',
            'devolvido', 'devolvida', 'refunded' => 'refunded',
            'removida_pelo_usuario_recebedor', 'removida_pelo_psp', 'cancelado', 'cancelada', 'removed' => 'cancelled',
            'em_processamento', 'pending', 'pendente' => 'pending',
            'nao_realizado', 'não_realizado', 'failed', 'rejeitado', 'rejeitada' => 'failed',
            default => mb_strtolower($status),
        };
    }

    private function path(ConnectionContext $context, string $product, string $key, string $default): string
    {
        return (string) ($context->credentials['products'][$product]['paths'][$key] ?? $default);
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
}
