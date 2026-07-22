<?php

declare(strict_types=1);

namespace Bluehost\ShippoAPI\Tests\Unit\Webhooks;

use PHPUnit\Framework\TestCase;
use Bluehost\ShippoAPI\Webhooks\WebhookVerifier;

final class WebhookVerifierTest extends TestCase
{
    private WebhookVerifier $verifier;

    protected function setUp(): void
    {
        $this->verifier = new WebhookVerifier();
    }

    public function testVerifyHmacWithValidSignature(): void
    {
        $payload = '{"event":"track_updated"}';
        $secret = 'test_secret';
        $signature = hash_hmac('sha256', $payload, $secret);

        $this->assertTrue($this->verifier->verifyHmac($payload, $signature, $secret));
    }

    public function testVerifyHmacRejectsInvalidSignature(): void
    {
        $this->assertFalse($this->verifier->verifyHmac('{}', 'invalid', 'secret'));
    }

    public function testVerifyIpAllowsListedAddress(): void
    {
        $this->assertTrue($this->verifier->verifyIp('203.0.113.1', ['203.0.113.1', '203.0.113.2']));
    }

    public function testVerifyShippoSignatureHeader(): void
    {
        $payload = '{"event":"track_updated"}';
        $secret = 'shippo_secret';
        $timestamp = '1688493073';
        $signature = hash_hmac('sha256', $timestamp . '.' . $payload, $secret);
        $header = 't=' . $timestamp . ',v1=' . $signature;

        $this->assertTrue($this->verifier->verifyShippoSignatureHeader($payload, $header, $secret));
    }

    public function testWebhookEventFromArray(): void
    {
        $event = \Bluehost\ShippoAPI\Webhooks\WebhookEvent::fromArray([
            'event' => 'track_updated',
            'test' => true,
            'data' => ['tracking_number' => '123'],
        ]);

        $this->assertSame('track_updated', $event->getEvent());
        $this->assertTrue($event->isTest());
        $this->assertSame('123', $event->getData()['tracking_number']);
    }

    public function testVerifyUrlToken(): void
    {
        $this->assertTrue($this->verifier->verifyUrlToken('abc123', 'abc123'));
        $this->assertFalse($this->verifier->verifyUrlToken('wrong', 'abc123'));
    }
}
