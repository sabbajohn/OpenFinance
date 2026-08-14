<?php

namespace Tests\Feature;

use App\Domain\Events\Jobs\DrainRawPayloadSpool;
use App\Domain\Events\Jobs\ProcessInboxEvent;
use App\Domain\Events\Models\InboxEvent;
use App\Domain\Events\Models\RawPayload;
use App\Domain\Events\Services\InboxService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class InboxIdempotencyTest extends TestCase
{
    use RefreshDatabase;

    public function test_repeated_delivery_is_persisted_once(): void
    {
        Queue::fake();
        $service = app(InboxService::class);
        $first = $service->receive('sicredi', 'sicredi.webhook', 'same-key', ['id' => 'provider-event']);
        $second = $service->receive('sicredi', 'sicredi.webhook', 'same-key', ['id' => 'provider-event']);

        $this->assertTrue($first->is($second));
        $this->assertDatabaseCount(InboxEvent::class, 1);
        $this->assertDatabaseCount(RawPayload::class, 1);
        Queue::assertPushed(DrainRawPayloadSpool::class, 1);
        Queue::assertPushed(ProcessInboxEvent::class, 1);
    }
}
