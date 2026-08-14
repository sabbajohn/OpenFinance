<?php

namespace Tests\Unit;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Sabba\OpenFinance\Sdk\WebhookVerifier;

class PhpSdkWebhookVerifierTest extends TestCase
{
    public function test_it_validates_and_decodes_the_documented_hmac_envelope(): void
    {
        $body = json_encode([
            'id' => 'event-1',
            'type' => 'bank.transaction.created',
            'schema_version' => 1,
            'occurred_at' => '2026-08-12T12:00:00Z',
            'organization_id' => 'organization-1',
            'company_id' => 'company-1',
            'correlation_id' => 'correlation-1',
            'data' => ['transaction_id' => 'transaction-1'],
        ], JSON_THROW_ON_ERROR);
        $timestamp = (string) time();
        $deliveryId = 'delivery-1';
        $signature = 'sha256='.hash_hmac('sha256', $timestamp.'.'.$deliveryId.'.'.$body, 'fixture-secret');
        $verifier = new WebhookVerifier('fixture-secret');

        $this->assertTrue($verifier->verify($body, $timestamp, $deliveryId, $signature, (int) $timestamp));
        $this->assertSame('bank.transaction.created', $verifier->decode($body, $timestamp, $deliveryId, $signature)['type']);
    }

    public function test_it_rejects_expired_signatures(): void
    {
        $verifier = new WebhookVerifier('fixture-secret');

        $this->expectException(InvalidArgumentException::class);
        $verifier->decode('{}', '1', 'delivery-1', 'sha256=invalid');
    }
}
