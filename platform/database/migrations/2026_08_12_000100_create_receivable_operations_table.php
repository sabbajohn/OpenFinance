<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('receivable_operations', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('organization_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('company_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('receivable_id')->constrained()->cascadeOnDelete();
            $table->string('action', 32);
            $table->string('idempotency_key', 255);
            $table->string('status', 24)->default('pending');
            $table->json('payload');
            $table->json('provider_result')->nullable();
            $table->unsignedSmallInteger('attempts')->default(0);
            $table->text('last_error')->nullable();
            $table->timestampTz('completed_at')->nullable();
            $table->timestampsTz();
            $table->unique(['organization_id', 'idempotency_key']);
            $table->index(['receivable_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('receivable_operations');
    }
};
