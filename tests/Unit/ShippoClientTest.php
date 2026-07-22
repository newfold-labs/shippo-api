<?php

declare(strict_types=1);

namespace Bluehost\ShippoAPI\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Bluehost\ShippoAPI\Auth\TokenAuthenticator;
use Bluehost\ShippoAPI\Config\ClientConfig;
use Bluehost\ShippoAPI\Contracts\HttpClientInterface;
use Bluehost\ShippoAPI\Polling\BatchPoller;
use Bluehost\ShippoAPI\Resources\Addresses;
use Bluehost\ShippoAPI\Resources\EmbeddedAuth;
use Bluehost\ShippoAPI\Resources\Rates;
use Bluehost\ShippoAPI\Resources\Shipments;
use Bluehost\ShippoAPI\Resources\Tracking;
use Bluehost\ShippoAPI\ShippoClient;
use Bluehost\ShippoAPI\Webhooks\WebhookVerifier;

final class ShippoClientTest extends TestCase
{
    public function testCreateReturnsClientWithResources(): void
    {
        $http = $this->createMock(HttpClientInterface::class);
        $config = ClientConfig::create();
        $client = ShippoClient::fromConfig($config, new TokenAuthenticator('shippo_test_key'), $http);

        $this->assertInstanceOf(Addresses::class, $client->addresses());
        $this->assertInstanceOf(Shipments::class, $client->shipments());
        $this->assertInstanceOf(Rates::class, $client->rates());
        $this->assertInstanceOf(Tracking::class, $client->tracking());
        $this->assertInstanceOf(EmbeddedAuth::class, $client->embeddedAuth());
        $this->assertSame($client->addresses(), $client->addresses());
    }

    public function testCreateWithJwtUsesJwtAuthenticator(): void
    {
        $client = ShippoClient::createWithJwt('eyJ.test.token');
        $this->assertInstanceOf(ShippoClient::class, $client);
        $this->assertInstanceOf(ClientConfig::class, $client->getConfig());
    }

    public function testFactoryCreateWithApiToken(): void
    {
        $client = ShippoClient::create('shippo_test_abc');
        $this->assertInstanceOf(ShippoClient::class, $client);
    }

    public function testBatchPollerAndWebhookVerifier(): void
    {
        $http = $this->createMock(HttpClientInterface::class);
        $client = ShippoClient::fromConfig(
            ClientConfig::create(),
            new TokenAuthenticator('t'),
            $http,
            false
        );

        $this->assertInstanceOf(BatchPoller::class, $client->batchPoller(10, 1));
        $this->assertInstanceOf(WebhookVerifier::class, $client->webhookVerifier());
    }

    public function testAllResourceAccessors(): void
    {
        $http = $this->createMock(HttpClientInterface::class);
        $client = ShippoClient::fromConfig(
            ClientConfig::create(),
            new TokenAuthenticator('t'),
            $http,
            false
        );

        $this->assertNotNull($client->addressParser());
        $this->assertNotNull($client->addressValidation());
        $this->assertNotNull($client->batches());
        $this->assertNotNull($client->carrierAccounts());
        $this->assertNotNull($client->carrierParcelTemplates());
        $this->assertNotNull($client->customsDeclarations());
        $this->assertNotNull($client->customsItems());
        $this->assertNotNull($client->manifests());
        $this->assertNotNull($client->orders());
        $this->assertNotNull($client->parcels());
        $this->assertNotNull($client->pickups());
        $this->assertNotNull($client->ratesAtCheckout());
        $this->assertNotNull($client->refunds());
        $this->assertNotNull($client->serviceGroups());
        $this->assertNotNull($client->shippoAccounts());
        $this->assertNotNull($client->transactions());
        $this->assertNotNull($client->userParcelTemplates());
        $this->assertNotNull($client->webhooks());
    }
}
