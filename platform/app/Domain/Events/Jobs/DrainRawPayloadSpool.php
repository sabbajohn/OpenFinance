<?php

namespace App\Domain\Events\Jobs;

use App\Domain\Events\Models\RawPayload;
use App\Domain\Events\Services\RawPayloadStore;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DrainRawPayloadSpool implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public readonly ?string $payloadId = null)
    {
        $this->onQueue('maintenance');
    }

    public function handle(RawPayloadStore $store): void
    {
        RawPayload::query()
            ->when($this->payloadId, fn ($query) => $query->whereKey($this->payloadId))
            ->where('status', 'spooled')
            ->orderBy('created_at')
            ->limit(500)
            ->get()
            ->each(fn (RawPayload $payload) => $store->drain($payload));
    }
}
