<?php

use App\Domain\Banking\Jobs\SyncBankConnection;
use App\Domain\Banking\Models\BankConnection;
use App\Domain\ERP\Models\ErpConnection;
use App\Domain\Events\Jobs\DrainRawPayloadSpool;
use App\Domain\Events\Jobs\PublishOutboxBatch;
use App\Domain\Events\Jobs\RecoverPendingDeliveries;
use App\Domain\Events\Jobs\RecoverProcessingPipelines;
use App\Domain\Events\Models\WebhookEndpoint;
use App\Domain\Events\Services\OutboxService;
use App\Domain\Identity\Models\ApiClient;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use Symfony\Component\Console\Command\Command;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('openfinance:recrypt-secrets', function (): void {
    $count = 0;
    BankConnection::query()->withoutGlobalScopes()->whereNotNull('encrypted_credentials')->each(function (BankConnection $model) use (&$count): void {
        $value = $model->encrypted_credentials;
        $model->encrypted_credentials = $value;
        $model->saveQuietly();
        $count++;
    });
    ErpConnection::query()->withoutGlobalScopes()->whereNotNull('encrypted_webhook_secret')->each(function (ErpConnection $model) use (&$count): void {
        $value = $model->encrypted_webhook_secret;
        $model->encrypted_webhook_secret = $value;
        $model->saveQuietly();
        $count++;
    });
    WebhookEndpoint::query()->withoutGlobalScopes()->each(function (WebhookEndpoint $model) use (&$count): void {
        $value = $model->encrypted_secret;
        $model->encrypted_secret = $value;
        $model->saveQuietly();
        $count++;
    });
    $this->info("{$count} registros recriptografados com a APP_KEY atual.");
})->purpose('Recriptografa segredos após configurar APP_PREVIOUS_KEYS e trocar APP_KEY');

Artisan::command('openfinance:check-certificates', function (OutboxService $outbox): void {
    BankConnection::query()->withoutGlobalScopes()
        ->whereNotNull('certificate_expires_at')
        ->where('certificate_expires_at', '<=', now('UTC')->addDays(30))
        ->each(function (BankConnection $connection) use ($outbox): void {
            if ($connection->status !== 'action_required') {
                $connection->forceFill(['status' => 'action_required'])->save();
                $outbox->forModel('bank.connection.action_required', $connection, [
                    'connection_id' => $connection->getKey(),
                    'provider' => $connection->provider,
                    'reason' => 'certificate_expiring',
                    'certificate_expires_at' => $connection->certificate_expires_at->toIso8601String(),
                ]);
            }
        });
})->purpose('Emite alertas para certificados bancários próximos da expiração');

Artisan::command('openfinance:issue-api-client {organization} {name} {--company=} {--scopes=*}', function (): int {
    $scopes = $this->option('scopes') ?: ['*'];
    $issued = ApiClient::issue([
        'organization_id' => $this->argument('organization'),
        'company_id' => $this->option('company') ?: null,
        'name' => $this->argument('name'),
        'scopes' => $scopes,
    ]);
    $this->warn('Copie agora; o token não será exibido novamente:');
    $this->line($issued['token']);

    return Command::SUCCESS;
})->purpose('Emite um token opaco para bootstrap de uma integração ERP');

Schedule::job(new PublishOutboxBatch)->everySecond()->withoutOverlapping();
Schedule::job(new RecoverPendingDeliveries)->everyMinute()->withoutOverlapping();
Schedule::job(new RecoverProcessingPipelines)->everyMinute()->withoutOverlapping();
Schedule::job(new DrainRawPayloadSpool)->everyMinute()->withoutOverlapping();
Schedule::command('horizon:snapshot')->everyFiveMinutes();
Schedule::command('openfinance:check-certificates')->hourly()->withoutOverlapping();
Schedule::call(function (): void {
    BankConnection::query()->withoutGlobalScopes()
        ->whereIn('status', ['active', 'degraded', 'action_required'])
        ->where(fn ($query) => $query->whereNull('last_synced_at')->orWhere('last_synced_at', '<=', now('UTC')->subMinutes((int) config('openfinance.sync.interval_minutes'))))
        ->pluck('id')
        ->each(fn (string $id) => SyncBankConnection::dispatch($id));
})->everyMinute()->name('dispatch-bank-syncs')->withoutOverlapping();
