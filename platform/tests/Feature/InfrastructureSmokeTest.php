<?php

namespace Tests\Feature;

use App\Domain\Events\Services\RawPayloadStore;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;

class InfrastructureSmokeTest extends TestCase
{
    public function test_postgres_redis_and_minio_round_trip(): void
    {
        if (! filter_var(env('RUN_INFRASTRUCTURE_TESTS', false), FILTER_VALIDATE_BOOL)) {
            $this->markTestSkipped('Defina RUN_INFRASTRUCTURE_TESTS=true para testar os serviços reais.');
        }

        $this->assertSame('pgsql', DB::connection()->getDriverName());
        $this->assertNotFalse(DB::selectOne('select current_timestamp as checked_at'));

        $redisKey = 'openfinance:infrastructure-smoke:'.Str::uuid7();
        Redis::set($redisKey, 'redis-ok');
        $this->assertSame('redis-ok', Redis::get($redisKey));

        Queue::fake();
        $payload = app(RawPayloadStore::class)->store(
            ['probe' => 'minio-roundtrip'],
            null,
            'infrastructure-smoke',
        );

        try {
            $this->assertSame('spooled', $payload->status);
            $this->assertTrue(app(RawPayloadStore::class)->drain($payload));
            $this->assertSame('stored', $payload->refresh()->status);
            $this->assertNull($payload->encrypted_blob);
            $this->assertTrue(Storage::disk($payload->disk)->exists($payload->path));
            $this->assertSame(
                ['probe' => 'minio-roundtrip'],
                app(RawPayloadStore::class)->readJson($payload),
            );
        } finally {
            Redis::del($redisKey);

            if ($payload->path) {
                Storage::disk($payload->disk)->delete($payload->path);
            }

            $payload->delete();
        }
    }
}
