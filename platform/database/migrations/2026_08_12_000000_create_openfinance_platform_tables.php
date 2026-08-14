<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('organizations', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('status', 32)->default('active');
            $table->timestampsTz();
        });

        Schema::table('users', function (Blueprint $table): void {
            $table->foreignUuid('current_organization_id')
                ->nullable()
                ->after('id')
                ->constrained('organizations')
                ->nullOnDelete();
        });

        Schema::create('organization_user', function (Blueprint $table): void {
            $table->foreignUuid('organization_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('role', 32);
            $table->timestampTz('accepted_at')->nullable();
            $table->timestampsTz();
            $table->primary(['organization_id', 'user_id']);
            $table->index(['user_id', 'role']);
        });

        Schema::create('organization_invitations', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('organization_id')->constrained()->cascadeOnDelete();
            $table->string('email');
            $table->string('role', 32);
            $table->string('token_hash', 64)->unique();
            $table->foreignId('invited_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestampTz('expires_at');
            $table->timestampTz('accepted_at')->nullable();
            $table->timestampsTz();
            $table->unique(['organization_id', 'email']);
        });

        Schema::create('companies', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('organization_id')->constrained()->cascadeOnDelete();
            $table->string('legal_name');
            $table->string('trade_name')->nullable();
            $table->string('tax_id', 20);
            $table->string('timezone', 64)->default('America/Sao_Paulo');
            $table->string('status', 32)->default('active');
            $table->timestampsTz();
            $table->unique(['organization_id', 'tax_id']);
            $table->index(['organization_id', 'status']);
        });

        Schema::create('api_clients', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('organization_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('company_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('token_prefix', 20)->unique();
            $table->string('token_hash', 64)->unique();
            $table->json('scopes');
            $table->json('allowed_ips')->nullable();
            $table->timestampTz('last_used_at')->nullable();
            $table->timestampTz('expires_at')->nullable();
            $table->timestampTz('revoked_at')->nullable();
            $table->timestampsTz();
            $table->index(['organization_id', 'revoked_at']);
        });

        Schema::create('bank_connections', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('organization_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('company_id')->constrained()->cascadeOnDelete();
            $table->string('provider', 64);
            $table->string('name');
            $table->string('environment', 24)->default('sandbox');
            $table->string('status', 32)->default('draft');
            $table->json('capabilities');
            $table->longText('encrypted_credentials')->nullable();
            $table->timestampTz('certificate_expires_at')->nullable();
            $table->json('sync_settings')->nullable();
            $table->timestampTz('last_synced_at')->nullable();
            $table->text('last_error')->nullable();
            $table->unsignedBigInteger('version')->default(1);
            $table->timestampsTz();
            $table->index(['organization_id', 'company_id', 'status']);
            $table->index(['provider', 'status']);
        });

        Schema::create('bank_accounts', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('organization_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('company_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('bank_connection_id')->constrained()->cascadeOnDelete();
            $table->string('provider_account_id');
            $table->string('type', 32)->default('checking');
            $table->string('bank_code', 16)->nullable();
            $table->string('branch', 32)->nullable();
            $table->string('number_masked', 64)->nullable();
            $table->char('currency', 3)->default('BRL');
            $table->bigInteger('available_balance_minor')->nullable();
            $table->bigInteger('current_balance_minor')->nullable();
            $table->timestampTz('balance_observed_at')->nullable();
            $table->string('status', 32)->default('active');
            $table->json('metadata')->nullable();
            $table->timestampsTz();
            $table->unique(['bank_connection_id', 'provider_account_id']);
            $table->index(['organization_id', 'company_id', 'status']);
        });

        Schema::create('erp_connections', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('organization_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('company_id')->constrained()->cascadeOnDelete();
            $table->string('provider', 64)->default('simpleslaravel');
            $table->string('name');
            $table->string('base_url')->nullable();
            $table->string('webhook_url')->nullable();
            $table->longText('encrypted_webhook_secret')->nullable();
            $table->string('status', 32)->default('draft');
            $table->timestampTz('last_synced_at')->nullable();
            $table->text('last_error')->nullable();
            $table->timestampsTz();
            $table->index(['organization_id', 'company_id', 'status']);
        });

        Schema::create('erp_financial_accounts', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('organization_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('company_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('erp_connection_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('bank_account_id')->nullable()->constrained()->nullOnDelete();
            $table->string('external_id');
            $table->string('name');
            $table->char('currency', 3)->default('BRL');
            $table->string('status', 32)->default('active');
            $table->json('metadata')->nullable();
            $table->timestampsTz();
            $table->unique(['erp_connection_id', 'external_id']);
            $table->index(['organization_id', 'company_id', 'status']);
        });

        Schema::create('erp_titles', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('organization_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('company_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('erp_connection_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('erp_financial_account_id')->nullable()->constrained()->nullOnDelete();
            $table->string('external_id');
            $table->string('external_version')->nullable();
            $table->string('type', 16);
            $table->string('status', 32);
            $table->string('document_number')->nullable();
            $table->string('description');
            $table->bigInteger('amount_minor');
            $table->bigInteger('open_amount_minor');
            $table->char('currency', 3)->default('BRL');
            $table->date('issued_at')->nullable();
            $table->date('due_at')->nullable();
            $table->string('counterparty_name')->nullable();
            $table->string('counterparty_tax_id_hash', 64)->nullable();
            $table->json('identifiers')->nullable();
            $table->json('metadata')->nullable();
            $table->timestampTz('source_updated_at')->nullable();
            $table->timestampsTz();
            $table->unique(['erp_connection_id', 'external_id']);
            $table->index(['organization_id', 'company_id', 'type', 'status']);
            $table->index(['company_id', 'due_at']);
            $table->index('document_number');
        });

        Schema::create('raw_payloads', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('organization_id')->nullable()->constrained()->nullOnDelete();
            $table->string('disk', 32)->default('raw-payloads');
            $table->string('path')->nullable();
            $table->string('status', 24)->default('spooled');
            $table->string('content_type', 128)->default('application/json');
            $table->string('checksum_sha256', 64);
            $table->unsignedBigInteger('compressed_size');
            $table->longText('encrypted_blob')->nullable();
            $table->timestampTz('stored_at')->nullable();
            $table->timestampTz('expires_at');
            $table->timestampsTz();
            $table->index(['status', 'created_at']);
            $table->index('expires_at');
        });

        Schema::create('sync_runs', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('organization_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('company_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('bank_connection_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('bank_account_id')->nullable()->constrained()->nullOnDelete();
            $table->string('capability', 64);
            $table->string('status', 24)->default('pending');
            $table->string('cursor')->nullable();
            $table->json('checkpoint')->nullable();
            $table->unsignedInteger('items_seen')->default(0);
            $table->unsignedInteger('items_created')->default(0);
            $table->unsignedInteger('items_updated')->default(0);
            $table->unsignedInteger('items_duplicate')->default(0);
            $table->timestampTz('started_at')->nullable();
            $table->timestampTz('finished_at')->nullable();
            $table->text('error')->nullable();
            $table->timestampsTz();
            $table->index(['bank_connection_id', 'capability', 'status']);
        });

        Schema::create('bank_transactions', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('organization_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('company_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('bank_connection_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('bank_account_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('raw_payload_id')->nullable()->constrained()->nullOnDelete();
            $table->string('external_id')->nullable();
            $table->string('fingerprint', 64);
            $table->string('type', 32)->default('other');
            $table->string('direction', 8);
            $table->string('status', 24)->default('posted');
            $table->bigInteger('amount_minor');
            $table->char('currency', 3)->default('BRL');
            $table->timestampTz('occurred_at');
            $table->timestampTz('observed_at');
            $table->string('description')->nullable();
            $table->string('counterparty_name')->nullable();
            $table->string('counterparty_tax_id_hash', 64)->nullable();
            $table->json('identifiers')->nullable();
            $table->json('metadata')->nullable();
            $table->unsignedBigInteger('version')->default(1);
            $table->timestampTz('deleted_at')->nullable();
            $table->timestampsTz();
            $table->unique(['bank_connection_id', 'fingerprint']);
            $table->index(['organization_id', 'company_id', 'occurred_at']);
            $table->index(['bank_account_id', 'status', 'occurred_at']);
            $table->index(['bank_connection_id', 'external_id']);
        });

        Schema::create('inbox_events', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('organization_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignUuid('company_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignUuid('raw_payload_id')->nullable()->constrained()->nullOnDelete();
            $table->string('source', 64);
            $table->string('event_type', 128);
            $table->string('idempotency_key', 255)->unique();
            $table->string('status', 24)->default('received');
            $table->string('correlation_id', 128)->nullable();
            $table->timestampTz('received_at');
            $table->timestampTz('processed_at')->nullable();
            $table->text('error')->nullable();
            $table->timestampsTz();
            $table->index(['source', 'status', 'received_at']);
        });

        Schema::create('outbox_events', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('organization_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignUuid('company_id')->nullable()->constrained()->nullOnDelete();
            $table->string('aggregate_type', 96);
            $table->uuid('aggregate_id');
            $table->string('event_type', 128);
            $table->unsignedSmallInteger('schema_version')->default(1);
            $table->string('correlation_id', 128)->nullable();
            $table->json('payload');
            $table->string('status', 24)->default('pending');
            $table->unsignedSmallInteger('attempts')->default(0);
            $table->timestampTz('available_at');
            $table->timestampTz('published_at')->nullable();
            $table->text('last_error')->nullable();
            $table->timestampsTz();
            $table->index(['status', 'available_at']);
            $table->index(['organization_id', 'event_type', 'created_at']);
            $table->index(['aggregate_type', 'aggregate_id']);
        });

        $this->createEventLedger();

        Schema::create('reconciliation_cases', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('organization_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('company_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('bank_transaction_id')->constrained()->cascadeOnDelete();
            $table->string('status', 32)->default('open');
            $table->unsignedSmallInteger('best_score')->default(0);
            $table->boolean('auto_eligible')->default(false);
            $table->unsignedBigInteger('version')->default(1);
            $table->timestampTz('resolved_at')->nullable();
            $table->timestampsTz();
            $table->unique('bank_transaction_id');
            $table->index(['organization_id', 'company_id', 'status']);
        });

        Schema::create('reconciliation_candidates', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('organization_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('reconciliation_case_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('erp_title_id')->constrained()->cascadeOnDelete();
            $table->unsignedSmallInteger('score');
            $table->bigInteger('suggested_amount_minor');
            $table->json('signals');
            $table->timestampsTz();
            $table->unique(['reconciliation_case_id', 'erp_title_id']);
            $table->index(['reconciliation_case_id', 'score']);
        });

        Schema::create('reconciliation_decisions', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('organization_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('company_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('reconciliation_case_id')->constrained()->cascadeOnDelete();
            $table->foreignId('requested_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignUuid('requested_by_api_client_id')->nullable()->constrained('api_clients')->nullOnDelete();
            $table->string('source', 24);
            $table->string('action', 32);
            $table->unsignedBigInteger('expected_version');
            $table->string('idempotency_key', 255);
            $table->string('status', 32)->default('pending_erp');
            $table->json('payload');
            $table->json('erp_result')->nullable();
            $table->timestampTz('confirmed_at')->nullable();
            $table->timestampTz('rejected_at')->nullable();
            $table->timestampsTz();
            $table->unique(['organization_id', 'idempotency_key']);
            $table->index(['reconciliation_case_id', 'status']);
        });

        Schema::create('receivables', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('organization_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('company_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('bank_connection_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('erp_title_id')->constrained()->restrictOnDelete();
            $table->foreignUuid('raw_payload_id')->nullable()->constrained()->nullOnDelete();
            $table->string('kind', 16);
            $table->string('subtype', 32)->nullable();
            $table->string('provider_external_id')->nullable();
            $table->string('idempotency_key', 255);
            $table->string('status', 32)->default('pending');
            $table->bigInteger('amount_minor');
            $table->char('currency', 3)->default('BRL');
            $table->string('reference')->nullable();
            $table->text('copy_and_paste')->nullable();
            $table->string('barcode')->nullable();
            $table->string('digitable_line')->nullable();
            $table->date('due_at')->nullable();
            $table->timestampTz('paid_at')->nullable();
            $table->timestampTz('cancelled_at')->nullable();
            $table->json('metadata')->nullable();
            $table->unsignedBigInteger('version')->default(1);
            $table->timestampsTz();
            $table->unique(['organization_id', 'idempotency_key']);
            $table->index(['company_id', 'kind', 'status']);
            $table->index(['bank_connection_id', 'provider_external_id']);
        });

        Schema::create('webhook_endpoints', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('organization_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('company_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('url');
            $table->longText('encrypted_secret');
            $table->json('events');
            $table->string('status', 24)->default('active');
            $table->timestampTz('last_success_at')->nullable();
            $table->timestampTz('last_failure_at')->nullable();
            $table->timestampsTz();
            $table->index(['organization_id', 'status']);
        });

        Schema::create('webhook_deliveries', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('organization_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('webhook_endpoint_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('outbox_event_id')->constrained()->cascadeOnDelete();
            $table->string('status', 24)->default('pending');
            $table->unsignedSmallInteger('attempts')->default(0);
            $table->unsignedSmallInteger('response_status')->nullable();
            $table->timestampTz('next_attempt_at')->nullable();
            $table->timestampTz('delivered_at')->nullable();
            $table->text('last_error')->nullable();
            $table->timestampsTz();
            $table->unique(['webhook_endpoint_id', 'outbox_event_id']);
            $table->index(['status', 'next_attempt_at']);
        });

        Schema::create('audit_logs', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('organization_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignUuid('company_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignUuid('api_client_id')->nullable()->constrained('api_clients')->nullOnDelete();
            $table->string('action', 128);
            $table->string('subject_type', 128)->nullable();
            $table->string('subject_id', 64)->nullable();
            $table->string('ip_hash', 64)->nullable();
            $table->json('metadata')->nullable();
            $table->timestampTz('occurred_at');
            $table->timestampsTz();
            $table->index(['organization_id', 'occurred_at']);
            $table->index(['subject_type', 'subject_id']);
        });
    }

    public function down(): void
    {
        foreach ([
            'audit_logs',
            'webhook_deliveries',
            'webhook_endpoints',
            'receivables',
            'reconciliation_decisions',
            'reconciliation_candidates',
            'reconciliation_cases',
            'event_ledger',
            'outbox_events',
            'inbox_events',
            'bank_transactions',
            'sync_runs',
            'raw_payloads',
            'erp_titles',
            'erp_financial_accounts',
            'erp_connections',
            'bank_accounts',
            'bank_connections',
            'api_clients',
            'companies',
            'organization_invitations',
            'organization_user',
        ] as $table) {
            Schema::dropIfExists($table);
        }

        Schema::table('users', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('current_organization_id');
        });

        Schema::dropIfExists('organizations');
    }

    private function createEventLedger(): void
    {
        if (DB::getDriverName() === 'pgsql') {
            DB::statement(<<<'SQL'
                CREATE TABLE event_ledger (
                    id uuid NOT NULL,
                    organization_id uuid NULL,
                    company_id uuid NULL,
                    event_type varchar(128) NOT NULL,
                    aggregate_type varchar(96) NOT NULL,
                    aggregate_id uuid NOT NULL,
                    schema_version smallint NOT NULL DEFAULT 1,
                    payload jsonb NOT NULL,
                    correlation_id varchar(128) NULL,
                    occurred_at timestamptz NOT NULL,
                    created_at timestamptz NULL,
                    updated_at timestamptz NULL,
                    PRIMARY KEY (id, occurred_at)
                ) PARTITION BY RANGE (occurred_at)
            SQL);
            DB::statement('CREATE TABLE event_ledger_default PARTITION OF event_ledger DEFAULT');
            DB::statement('CREATE INDEX event_ledger_org_type_time_idx ON event_ledger (organization_id, event_type, occurred_at)');

            return;
        }

        Schema::create('event_ledger', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->uuid('organization_id')->nullable();
            $table->uuid('company_id')->nullable();
            $table->string('event_type', 128);
            $table->string('aggregate_type', 96);
            $table->uuid('aggregate_id');
            $table->unsignedSmallInteger('schema_version')->default(1);
            $table->json('payload');
            $table->string('correlation_id', 128)->nullable();
            $table->timestampTz('occurred_at');
            $table->timestampsTz();
            $table->index(['organization_id', 'event_type', 'occurred_at']);
        });
    }
};
