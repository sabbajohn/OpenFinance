<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bank_sandbox_runs', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('organization_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('company_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('bank_connection_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('suite', 64);
            $table->string('environment', 24);
            $table->string('status', 24)->default('running');
            $table->json('steps')->nullable();
            $table->json('summary')->nullable();
            $table->timestampTz('started_at');
            $table->timestampTz('finished_at')->nullable();
            $table->text('error')->nullable();
            $table->timestampsTz();
            $table->index(['organization_id', 'status', 'created_at']);
            $table->index(['bank_connection_id', 'suite', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bank_sandbox_runs');
    }
};
