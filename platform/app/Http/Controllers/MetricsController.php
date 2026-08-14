<?php

namespace App\Http\Controllers;

use App\Domain\Banking\Models\BankConnection;
use App\Domain\Events\Models\InboxEvent;
use App\Domain\Events\Models\OutboxEvent;
use App\Domain\Events\Models\RawPayload;
use App\Domain\Events\Models\WebhookDelivery;
use App\Domain\Reconciliation\Models\ReconciliationCase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class MetricsController extends Controller
{
    private const QUEUES = [
        'webhooks-critical',
        'bank-sync',
        'normalization',
        'reconciliation',
        'erp-delivery',
        'maintenance',
    ];

    public function __invoke(Request $request): Response
    {
        $expected = (string) config('openfinance.metrics_token');
        abort_if($expected === '', 503, 'Métricas não configuradas.');
        abort_unless(hash_equals($expected, (string) $request->bearerToken()), 401);

        $metrics = [
            '# HELP openfinance_outbox_pending Eventos aguardando publicação durável.',
            '# TYPE openfinance_outbox_pending gauge',
            'openfinance_outbox_pending '.OutboxEvent::query()->where('status', 'pending')->count(),
            '# HELP openfinance_raw_payload_spooled Payloads aguardando envio ao object storage.',
            '# TYPE openfinance_raw_payload_spooled gauge',
            'openfinance_raw_payload_spooled '.RawPayload::query()->where('status', 'spooled')->count(),
            '# HELP openfinance_reconciliation_open Conciliações em aberto.',
            '# TYPE openfinance_reconciliation_open gauge',
            'openfinance_reconciliation_open '.ReconciliationCase::query()->withoutGlobalScopes()->where('status', 'open')->count(),
        ];

        foreach (['received', 'normalizing', 'failed'] as $status) {
            $metrics[] = sprintf(
                'openfinance_inbox_events{status="%s"} %d',
                $status,
                InboxEvent::query()->where('status', $status)->count(),
            );
        }
        foreach (['pending', 'retrying', 'failed'] as $status) {
            $metrics[] = sprintf(
                'openfinance_webhook_deliveries{status="%s"} %d',
                $status,
                WebhookDelivery::query()->where('status', $status)->count(),
            );
        }
        foreach (['active', 'degraded', 'action_required', 'failed'] as $status) {
            $metrics[] = sprintf(
                'openfinance_bank_connections{status="%s"} %d',
                $status,
                BankConnection::query()->withoutGlobalScopes()->where('status', $status)->count(),
            );
        }
        foreach (self::QUEUES as $queue) {
            $metrics[] = sprintf('openfinance_queue_depth{queue="%s"} %d', $queue, $this->queueDepth($queue));
        }

        return response(implode("\n", $metrics)."\n", 200, [
            'Content-Type' => 'text/plain; version=0.0.4; charset=utf-8',
            'Cache-Control' => 'no-store',
        ]);
    }

    private function queueDepth(string $queue): int
    {
        try {
            return (int) Redis::connection()->llen('queues:'.$queue);
        } catch (Throwable) {
            return -1;
        }
    }
}
