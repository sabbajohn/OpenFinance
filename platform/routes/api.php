<?php

use App\Http\Controllers\Api\V1\ApiClientController;
use App\Http\Controllers\Api\V1\BankConnectionSyncController;
use App\Http\Controllers\Api\V1\BankTransactionController;
use App\Http\Controllers\Api\V1\BankWebhookController;
use App\Http\Controllers\Api\V1\ErpMirrorController;
use App\Http\Controllers\Api\V1\ReceivableController;
use App\Http\Controllers\Api\V1\ReconciliationController;
use App\Http\Controllers\Api\V1\ReconciliationDecisionController;
use App\Http\Controllers\Api\V1\WebhookEndpointController;
use App\Http\Controllers\MetricsController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function (): void {
    Route::get('/health', fn () => response()->json(['data' => ['status' => 'ok', 'time' => now()->toIso8601String()]]));
    Route::post('/webhooks/banks/{bankConnection}', BankWebhookController::class)
        ->middleware('throttle:600,1')
        ->name('bank-webhooks.receive');
    Route::post('/webhooks/banks/{bankConnection}/pix', BankWebhookController::class)
        ->middleware('throttle:600,1')
        ->name('bank-webhooks.pix.receive');

    Route::middleware(['api.client', 'audit'])->group(function (): void {
        Route::post('/erp/accounts/bulk', [ErpMirrorController::class, 'accounts'])
            ->middleware(['api.scope:erp:write', 'idempotency']);
        Route::post('/erp/titles/bulk', [ErpMirrorController::class, 'titles'])
            ->middleware(['api.scope:erp:write', 'idempotency']);

        Route::get('/bank-transactions', [BankTransactionController::class, 'index'])->middleware('api.scope:banking:read');
        Route::post('/bank-connections/{bankConnection}/sync', BankConnectionSyncController::class)
            ->middleware(['api.scope:banking:write', 'idempotency']);

        Route::get('/reconciliations', [ReconciliationController::class, 'index'])->middleware('api.scope:reconciliation:read');
        Route::post('/reconciliations/{reconciliation}/decisions', [ReconciliationController::class, 'decide'])
            ->middleware(['api.scope:reconciliation:write', 'idempotency']);
        Route::post('/reconciliation-decisions/{decision}/confirm', [ReconciliationDecisionController::class, 'confirm'])
            ->middleware(['api.scope:reconciliation:write', 'idempotency']);
        Route::post('/reconciliation-decisions/{decision}/reject', [ReconciliationDecisionController::class, 'reject'])
            ->middleware(['api.scope:reconciliation:write', 'idempotency']);

        Route::post('/pix/charges', [ReceivableController::class, 'storePix'])
            ->middleware(['api.scope:receivables:write', 'idempotency']);
        Route::post('/boletos', [ReceivableController::class, 'storeBoleto'])
            ->middleware(['api.scope:receivables:write', 'idempotency']);
        Route::get('/receivables/{receivable}', [ReceivableController::class, 'show'])->middleware('api.scope:receivables:read');
        Route::post('/receivables/{receivable}/refresh', [ReceivableController::class, 'refresh'])
            ->middleware(['api.scope:receivables:write', 'idempotency']);
        Route::post('/pix/charges/{receivable}/refunds', [ReceivableController::class, 'refundPix'])
            ->middleware(['api.scope:receivables:write', 'idempotency']);
        Route::patch('/boletos/{receivable}', [ReceivableController::class, 'updateBoleto'])
            ->middleware(['api.scope:receivables:write', 'idempotency']);
        Route::post('/boletos/{receivable}/cancel', [ReceivableController::class, 'cancelBoleto'])
            ->middleware(['api.scope:receivables:write', 'idempotency']);

        Route::get('/api-clients', [ApiClientController::class, 'index'])->middleware('api.scope:clients:manage');
        Route::post('/api-clients', [ApiClientController::class, 'store'])->middleware(['api.scope:clients:manage', 'idempotency']);
        Route::post('/api-clients/{apiClient}/rotate', [ApiClientController::class, 'rotate'])->middleware(['api.scope:clients:manage', 'idempotency']);
        Route::get('/webhook-endpoints', [WebhookEndpointController::class, 'index'])->middleware('api.scope:webhooks:manage');
        Route::post('/webhook-endpoints', [WebhookEndpointController::class, 'store'])->middleware(['api.scope:webhooks:manage', 'idempotency']);
        Route::post('/webhook-deliveries/{delivery}/replay', [WebhookEndpointController::class, 'replay'])
            ->middleware(['api.scope:webhooks:manage', 'idempotency']);
    });
});

Route::get('/internal/metrics', MetricsController::class)->middleware('throttle:60,1');
