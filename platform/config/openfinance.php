<?php

return [
    'metrics_token' => env('METRICS_TOKEN'),

    'sandbox' => [
        'enabled' => (bool) env('BANK_SANDBOX_UI_ENABLED', true),
        'test_tax_id' => preg_replace('/\D+/', '', (string) env('BANK_SANDBOX_TEST_TAX_ID', '60320001000145')),
    ],

    'raw_payloads' => [
        'disk' => env('RAW_PAYLOAD_DISK', 'raw-payloads'),
        'retention_days' => (int) env('RAW_PAYLOAD_RETENTION_DAYS', 180),
    ],

    'sync' => [
        'interval_minutes' => (int) env('BANK_SYNC_INTERVAL_MINUTES', 15),
        'initial_backfill_days' => (int) env('BANK_SYNC_BACKFILL_DAYS', 90),
        'overlap_days' => (int) env('BANK_SYNC_OVERLAP_DAYS', 3),
    ],

    'sicredi' => [
        'boleto' => [
            'environments' => [
                'sandbox' => [
                    'label' => 'Sandbox',
                    'base_url' => env('SICREDI_BOLETO_SANDBOX_BASE_URL', 'https://api-parceiro.sicredi.com.br/sb/cobranca/boleto/v1/'),
                    'token_url' => env('SICREDI_BOLETO_SANDBOX_TOKEN_URL', 'https://api-parceiro.sicredi.com.br/sb/auth/openapi/token'),
                ],
                'production' => [
                    'label' => 'Produção',
                    'base_url' => env('SICREDI_BOLETO_PRODUCTION_BASE_URL', 'https://api-parceiro.sicredi.com.br/cobranca/boleto/v1/'),
                    'token_url' => env('SICREDI_BOLETO_PRODUCTION_TOKEN_URL', 'https://api-parceiro.sicredi.com.br/auth/openapi/token'),
                ],
            ],
        ],
        'pix' => [
            'environments' => [
                'sandbox' => [
                    'label' => 'Homologação',
                    'base_url' => env('SICREDI_PIX_HOMOLOGATION_BASE_URL', ''),
                    'token_url' => env('SICREDI_PIX_HOMOLOGATION_TOKEN_URL', ''),
                ],
                'production' => [
                    'label' => 'Produção',
                    'base_url' => env('SICREDI_PIX_PRODUCTION_BASE_URL', 'https://api-pix.sicredi.com.br/api/v2/'),
                    'token_url' => env('SICREDI_PIX_PRODUCTION_TOKEN_URL', 'https://api-pix.sicredi.com.br/oauth/token'),
                ],
            ],
        ],
    ],

    'bradesco' => [
        'boleto' => [
            'environments' => [
                'sandbox' => [
                    'label' => 'Homologação',
                    'base_url' => env('BRADESCO_BOLETO_HOMOLOGATION_BASE_URL', 'https://openapisandbox.prebanco.com.br/'),
                    'token_url' => env('BRADESCO_BOLETO_HOMOLOGATION_TOKEN_URL', 'https://openapisandbox.prebanco.com.br/auth/server-mtls/v2/token'),
                ],
                'production' => [
                    'label' => 'Produção',
                    'base_url' => env('BRADESCO_BOLETO_PRODUCTION_BASE_URL', 'https://openapi.bradesco.com.br/'),
                    'token_url' => env('BRADESCO_BOLETO_PRODUCTION_TOKEN_URL', 'https://openapi.bradesco.com.br/auth/server-mtls/v2/token'),
                ],
            ],
        ],
        'pix' => [
            'receipts_timeout_seconds' => (int) env('BRADESCO_PIX_RECEIPTS_TIMEOUT_SECONDS', 45),
            'sandbox_receipts' => [
                'from' => env('BRADESCO_PIX_SANDBOX_RECEIPTS_FROM', '2024-10-02T00:00:00.000Z'),
                'to' => env('BRADESCO_PIX_SANDBOX_RECEIPTS_TO', '2024-10-03T10:06:00.000Z'),
            ],
            'environments' => [
                'sandbox' => [
                    'label' => 'Homologação',
                    'base_url' => env('BRADESCO_PIX_HOMOLOGATION_BASE_URL', 'https://openapisandbox.prebanco.com.br/'),
                    'token_url' => env('BRADESCO_PIX_HOMOLOGATION_TOKEN_URL', 'https://openapisandbox.prebanco.com.br/auth/server/oauth/token'),
                ],
                'production' => [
                    'label' => 'Produção',
                    'base_url' => env('BRADESCO_PIX_PRODUCTION_BASE_URL', 'https://qrpix.bradesco.com.br/'),
                    'token_url' => env('BRADESCO_PIX_PRODUCTION_TOKEN_URL', 'https://qrpix.bradesco.com.br/auth/server/oauth/token'),
                ],
            ],
        ],
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
