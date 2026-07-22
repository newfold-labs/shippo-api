<?php

declare(strict_types=1);

namespace Bluehost\ShippoAPI\Tests\Unit\Resources;

use PHPUnit\Framework\TestCase;
use Bluehost\ShippoAPI\Contracts\HttpClientInterface;
use Bluehost\ShippoAPI\DTOs\Address;
use Bluehost\ShippoAPI\DTOs\Transaction;
use Bluehost\ShippoAPI\Resources\Addresses;
use Bluehost\ShippoAPI\Resources\Rates;
use Bluehost\ShippoAPI\Resources\Transactions;

final class ResourceTest extends TestCase
{
    public function testAddressesCreateSendsPostRequest(): void
    {
        $http = $this->createMock(HttpClientInterface::class);
        $http->expects($this->once())
            ->method('request')
            ->with(
                'POST',
                '/addresses/',
                [],
                [
                    'name' => 'Jane',
                    'street1' => '123 Main St',
                    'city' => 'SF',
                    'state' => 'CA',
                    'zip' => '94117',
                    'country' => 'US',
                ]
            )
            ->willReturn(['object_id' => 'addr_1']);

        $resource = new Addresses($http);
        $result = $resource->create(new Address(
            name: 'Jane',
            street1: '123 Main St',
            city: 'SF',
            state: 'CA',
            zip: '94117',
            country: 'US'
        ));

        $this->assertSame('addr_1', $result['object_id']);
    }

    public function testRatesRetrieveForShipmentUsesNestedPath(): void
    {
        $http = $this->createMock(HttpClientInterface::class);
        $http->expects($this->once())
            ->method('request')
            ->with('GET', '/shipments/shp_1/rates/', ['results' => 25])
            ->willReturn(['results' => []]);

        $resource = new Rates($http);
        $resource->retrieveForShipment('shp_1', ['results' => 25]);
    }

    public function testTransactionsCreateSendsRateId(): void
    {
        $http = $this->createMock(HttpClientInterface::class);
        $http->expects($this->once())
            ->method('request')
            ->with('POST', '/transactions/', [], ['rate' => 'rate_99'])
            ->willReturn(['object_id' => 'txn_1', 'status' => 'SUCCESS']);

        $resource = new Transactions($http);
        $result = $resource->create(new Transaction(rate: 'rate_99'));

        $this->assertSame('SUCCESS', $result['status']);
    }
}
