<?php

namespace App\Domain\Receivables\Services;

use App\Domain\Events\Models\InboxEvent;
use App\Domain\Events\Services\OutboxService;
use App\Domain\Receivables\Models\Receivable;

final readonly class SicrediWebhookHandler
{
    public function __construct(private OutboxService $outbox) {}

    /** @param array<string,mixed> $payload */
    public function handle(InboxEvent $event, array $payload): void
    {
        $externalId = $this->find($payload, ['txid', 'nossoNumero', 'externalId', 'id']);
        if (! $externalId) {
            return;
        }

        $receivable = Receivable::query()->withoutGlobalScopes()
            ->where('organization_id', $event->organization_id)
            ->where('provider_external_id', (string) $externalId)
            ->first();
        if (! $receivable) {
            return;
        }

        $providerStatus = strtolower((string) $this->find($payload, ['status', 'situacao']) ?: $receivable->status);
        $status = match ($providerStatus) {
            'concluida', 'concluído', 'paid', 'liquidado', 'liquidada' => 'paid',
            'devolvido', 'refunded' => 'refunded',
            'cancelado', 'cancelada', 'removed' => 'cancelled',
            'expirada', 'expired' => 'expired',
            default => $providerStatus,
        };
        if ($status === $receivable->status) {
            return;
        }

        $receivable->forceFill([
            'status' => $status,
            'paid_at' => $status === 'paid' ? now('UTC') : $receivable->paid_at,
            'cancelled_at' => in_array($status, ['cancelled', 'expired'], true) ? now('UTC') : $receivable->cancelled_at,
            'version' => $receivable->version + 1,
        ])->save();
        $verb = match ($status) {
            'paid' => 'paid',
            'refunded' => 'refunded',
            'cancelled', 'expired' => $status,
            default => 'updated',
        };
        $this->outbox->forModel($receivable->kind.'.charge.'.$verb, $receivable, [
            'receivable_id' => $receivable->getKey(),
            'erp_title_id' => $receivable->erp_title_id,
            'external_id' => $externalId,
            'status' => $status,
            'paid_at' => $receivable->paid_at?->toIso8601String(),
        ], $event->correlation_id);
    }

    /** @param array<string,mixed> $payload */
    /**
     * @param  array<string,mixed>  $payload
     * @param  list<string>  $keys
     */
    private function find(array $payload, array $keys): mixed
    {
        foreach ($payload as $key => $value) {
            if (in_array($key, $keys, true) && $value !== null && $value !== '') {
                return $value;
            }
            if (is_array($value) && ($found = $this->find($value, $keys)) !== null) {
                return $found;
            }
        }

        return null;
    }
}
