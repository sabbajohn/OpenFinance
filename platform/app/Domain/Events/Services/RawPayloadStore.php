<?php

namespace App\Domain\Events\Services;

use App\Domain\Events\Jobs\DrainRawPayloadSpool;
use App\Domain\Events\Models\RawPayload;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

class RawPayloadStore
{
    /** @param array<string,mixed>|string $payload */
    public function store(array|string $payload, ?string $organizationId, string $source): RawPayload
    {
        $plain = is_string($payload)
            ? $payload
            : (string) json_encode($payload, JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        $compressed = gzencode($plain, 6);
        $encrypted = Crypt::encryptString($compressed === false ? $plain : $compressed);
        $id = (string) Str::uuid7();
        $path = sprintf(
            '%s/%s/%s/%s.payload',
            now('UTC')->format('Y/m/d'),
            preg_replace('/[^a-z0-9_-]+/i', '-', $source),
            $organizationId ?? 'unassigned',
            $id,
        );

        $model = new RawPayload([
            'id' => $id,
            'organization_id' => $organizationId,
            'disk' => (string) config('openfinance.raw_payloads.disk'),
            'path' => $path,
            'status' => 'spooled',
            'content_type' => 'application/json',
            'checksum_sha256' => hash('sha256', $plain),
            'compressed_size' => strlen($encrypted),
            'encrypted_blob' => $encrypted,
            'expires_at' => now('UTC')->addDays((int) config('openfinance.raw_payloads.retention_days')),
        ]);
        $model->save();

        // O ACK do webhook depende só do PostgreSQL. MinIO é drenado fora da requisição.
        DrainRawPayloadSpool::dispatch((string) $model->getKey())->afterCommit();

        return $model;
    }

    public function drain(RawPayload $payload): bool
    {
        if ($payload->status === 'stored') {
            return true;
        }

        if (! $payload->encrypted_blob || ! $payload->path) {
            return false;
        }

        Storage::disk($payload->disk)->put($payload->path, $payload->encrypted_blob);
        $payload->forceFill([
            'status' => 'stored',
            'encrypted_blob' => null,
            'stored_at' => now('UTC'),
        ])->save();

        return true;
    }

    /** @return array<string,mixed> */
    public function readJson(RawPayload $payload): array
    {
        $encrypted = $payload->encrypted_blob;
        if (! $encrypted && $payload->path) {
            $encrypted = Storage::disk($payload->disk)->get($payload->path);
        }

        if (! is_string($encrypted) || $encrypted === '') {
            throw new RuntimeException('Payload bruto não está disponível.');
        }

        $compressed = Crypt::decryptString($encrypted);
        $plain = gzdecode($compressed);
        $decoded = json_decode($plain === false ? $compressed : $plain, true, flags: JSON_THROW_ON_ERROR);

        if (! is_array($decoded)) {
            throw new RuntimeException('Payload bruto não contém um objeto JSON.');
        }

        return $decoded;
    }
}
