<?php

namespace App\Domain\Events\Jobs;

use App\Domain\Events\Models\WebhookDelivery;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RecoverPendingDeliveries implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct()
    {
        $this->onQueue('maintenance');
    }

    public function handle(): void
    {
        WebhookDelivery::query()
            ->whereIn('status', ['pending', 'retrying'])
            ->where(fn ($query) => $query->whereNull('next_attempt_at')->orWhere('next_attempt_at', '<=', now('UTC')))
            ->orderBy('created_at')
            ->limit(500)
            ->pluck('id')
            ->each(fn (string $id) => DeliverWebhook::dispatch($id));
    }
}
