<?php

return [
    'metrics_token' => env('METRICS_TOKEN'),

    'raw_payloads' => [
        'disk' => env('RAW_PAYLOAD_DISK', 'raw-payloads'),
        'retention_days' => (int) env('RAW_PAYLOAD_RETENTION_DAYS', 180),
    ],

    'sync' => [
        'interval_minutes' => (int) env('BANK_SYNC_INTERVAL_MINUTES', 15),
        'initial_backfill_days' => (int) env('BANK_SYNC_BACKFILL_DAYS', 90),
        'overlap_days' => (int) env('BANK_SYNC_OVERLAP_DAYS', 3),
    ],

    'reconciliation' => [
        'auto_enabled' => (bool) env('RECONCILIATION_AUTO_ENABLED', true),
    ],

    'webhooks' => [
        'timeout_seconds' => (int) env('WEBHOOK_TIMEOUT_SECONDS', 5),
        'replay_window_seconds' => (int) env('WEBHOOK_REPLAY_WINDOW_SECONDS', 300),
        'max_attempts' => (int) env('WEBHOOK_MAX_ATTEMPTS', 12),
    ],
];
