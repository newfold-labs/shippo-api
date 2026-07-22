<?php

declare(strict_types=1);

namespace Bluehost\ShippoAPI\Tests\Unit\Resources;

use PHPUnit\Framework\TestCase;
use Bluehost\ShippoAPI\Contracts\HttpClientInterface;
use Bluehost\ShippoAPI\Resources\Batches;
use Bluehost\ShippoAPI\Resources\CarrierAccounts;
use Bluehost\ShippoAPI\Resources\Tracking;
use Bluehost\ShippoAPI\Resources\Webhooks;

final class Phase2ResourceTest extends TestCase
{
    public function testBatchesPurchaseEndpoint(): void
    {
        $http = $this->createMock(HttpClientInterface::class);
        $http->expects($this->once())
            ->method('request')
            ->with('POST', '/batches/batch_1/purchase/')
            ->willReturn(['status' => 'PURCHASING']);

        $resource = new Batches($http);
        $result = $resource->purchase('batch_1');

        $this->assertSame('PURCHASING', $result['status']);
    }

    public function testCarrierAccountsRegisterOauthEndpoint(): void
    {
        $http = $this->createMock(HttpClientInterface::class);
        $http->expects($this->once())
            ->method('request')
            ->with('POST', '/carrier_accounts/register/oauth/', [], ['carrier' => 'ups'])
            ->willReturn(['status' => 'connected']);

        $resource = new CarrierAccounts($http);
        $resource->registerOauth(['carrier' => 'ups']);
    }

    public function testTrackingGetBuildsCarrierPath(): void
    {
        $http = $this->createMock(HttpClientInterface::class);
        $http->expects($this->once())
            ->method('request')
            ->with('GET', '/tracks/usps/9400111899223197428490/')
            ->willReturn(['tracking_status' => ['status' => 'TRANSIT']]);

        $resource = new Tracking($http);
        $result = $resource->retrieveStatus('usps', '9400111899223197428490');

        $this->assertSame('TRANSIT', $result['tracking_status']['status']);
    }

    public function testWebhooksDeleteEndpoint(): void
    {
        $http = $this->createMock(HttpClientInterface::class);
        $http->expects($this->once())
            ->method('request')
            ->with('DELETE', '/webhooks/hook_1/')
            ->willReturn([]);

        $resource = new Webhooks($http);
        $resource->delete('hook_1');
    }
}
