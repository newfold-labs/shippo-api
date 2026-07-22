<?php

declare(strict_types=1);

namespace Bluehost\ShippoAPI\Tests\Unit\DTOs;

use PHPUnit\Framework\TestCase;
use Bluehost\ShippoAPI\DTOs\Address;
use Bluehost\ShippoAPI\DTOs\Rate;

final class DtoFromArrayTest extends TestCase
{
    public function testAddressFromArray(): void
    {
        $address = Address::fromArray([
            'name' => 'Jane',
            'street1' => '1 Main',
            'city' => 'Boston',
            'state' => 'MA',
            'zip' => '02118',
            'country' => 'US',
        ]);

        $this->assertSame('Jane', $address->name);
        $this->assertSame('Boston', $address->city);
    }

    public function testRateRoundTrip(): void
    {
        $rate = new Rate(objectId: 'rate_1', amount: '5.50', currency: 'USD', provider: 'USPS');
        $array = $rate->toArray();
        $restored = Rate::fromArray($array);

        $this->assertSame('rate_1', $restored->objectId);
        $this->assertSame('5.50', $restored->amount);
    }
}
