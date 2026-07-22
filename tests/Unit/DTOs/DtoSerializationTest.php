<?php

declare(strict_types=1);

namespace Bluehost\ShippoAPI\Tests\Unit\DTOs;

use PHPUnit\Framework\TestCase;
use Bluehost\ShippoAPI\DTOs\Address;
use Bluehost\ShippoAPI\DTOs\Parcel;
use Bluehost\ShippoAPI\DTOs\Shipment;
use Bluehost\ShippoAPI\DTOs\Transaction;

final class DtoSerializationTest extends TestCase
{
    public function testAddressSerializesToSnakeCase(): void
    {
        $address = new Address(
            name: 'John Doe',
            street1: '123 Main St',
            city: 'San Francisco',
            state: 'CA',
            zip: '94117',
            country: 'US',
            isResidential: true
        );

        $this->assertSame([
            'name' => 'John Doe',
            'street1' => '123 Main St',
            'city' => 'San Francisco',
            'state' => 'CA',
            'zip' => '94117',
            'country' => 'US',
            'is_residential' => true,
        ], $address->toArray());
    }

    public function testShipmentSerializesNestedObjects(): void
    {
        $shipment = new Shipment(
            addressFrom: new Address(street1: '1 Sender Rd', city: 'Boston', state: 'MA', zip: '02118', country: 'US'),
            addressTo: new Address(street1: '2 Receiver Rd', city: 'New York', state: 'NY', zip: '10001', country: 'US'),
            parcels: [
                new Parcel(length: '5', width: '5', height: '5', distanceUnit: 'in', weight: '2', massUnit: 'lb'),
            ],
            async: false
        );

        $payload = $shipment->toArray();

        $this->assertSame('1 Sender Rd', $payload['address_from']['street1']);
        $this->assertSame('in', $payload['parcels'][0]['distance_unit']);
        $this->assertFalse($payload['async']);
    }

    public function testTransactionSerializesRatePurchasePayload(): void
    {
        $transaction = new Transaction(
            rate: 'rate_123',
            labelFileType: 'PDF',
            async: 'false'
        );

        $this->assertSame([
            'rate' => 'rate_123',
            'label_file_type' => 'PDF',
            'async' => 'false',
        ], $transaction->toArray());
    }
}
