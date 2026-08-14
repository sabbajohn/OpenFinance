<?php

namespace Sabba\OpenFinance\Sdk;

use InvalidArgumentException;

final readonly class WebhookVerifier
{
    public function __construct(
        private string $secret,
        private int $toleranceSeconds = 300,
    ) {}

    public function verify(string $rawBody, string $timestamp, string $deliveryId, string $signature, ?int $now = null): bool
    {
        if (! ctype_digit($timestamp) || abs(($now ?? time()) - (int) $timestamp) > $this->toleranceSeconds) {
            return false;
        }

        $expected = hash_hmac('sha256', $timestamp.'.'.$deliveryId.'.'.$rawBody, $this->secret);
        $received = str_starts_with($signature, 'sha256=') ? substr($signature, 7) : $signature;

        return hash_equals($expected, $received);
    }

    /** @return array<string,mixed> */
    public function decode(string $rawBody, string $timestamp, string $deliveryId, string $signature): array
    {
        if (! $this->verify($rawBody, $timestamp, $deliveryId, $signature)) {
            throw new InvalidArgumentException('Assinatura de webhook inválida ou expirada.');
        }

        $event = json_decode($rawBody, true, flags: JSON_THROW_ON_ERROR);
        if (! is_array($event) || empty($event['id']) || empty($event['type']) || ! isset($event['data'])) {
            throw new InvalidArgumentException('Envelope de webhook inválido.');
        }

        return $event;
    }
}
