<?php

declare(strict_types=1);

namespace Bluehost\ShippoAPI\Tests\Unit\Resources;

use PHPUnit\Framework\TestCase;
use Bluehost\ShippoAPI\Contracts\HttpClientInterface;
use Bluehost\ShippoAPI\Resources\EmbeddedAuth;
use Bluehost\ShippoAPI\Resources\Shipments;

final class Phase3ResourceTest extends TestCase
{
    public function testEmbeddedAuthAuthorizeForAccount(): void
    {
        $http = $this->createMock(HttpClientInterface::class);
        $http->expects($this->once())
            ->method('request')
            ->with(
                'POST',
                '/embedded/authz/',
                [],
                ['scope' => 'embedded:carriers'],
                ['SHIPPO-ACCOUNT-ID' => 'acct_1']
            )
            ->willReturn(['token' => 'jwt', 'expires_in' => '123']);

        $resource = new EmbeddedAuth($http);
        $result = $resource->authorizeForAccount('acct_1', ['scope' => 'embedded:carriers']);

        $this->assertSame('jwt', $result['token']);
    }

    public function testShipmentsRetrieveWithExpandQuery(): void
    {
        $http = $this->createMock(HttpClientInterface::class);
        $http->expects($this->once())
            ->method('request')
            ->with('GET', '/shipments/shp_1/', ['expand' => 'rates'])
            ->willReturn(['object_id' => 'shp_1']);

        $resource = new Shipments($http);
        $resource->retrieve('shp_1', [], ['rates']);
    }
}
