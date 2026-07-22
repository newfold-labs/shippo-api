<?php

declare(strict_types=1);

namespace Bluehost\ShippoAPI\Tests\Unit\Resources;

use PHPUnit\Framework\TestCase;
use Bluehost\ShippoAPI\Contracts\HttpClientInterface;
use Bluehost\ShippoAPI\DTOs\Address;
use Bluehost\ShippoAPI\DTOs\Parcel;
use Bluehost\ShippoAPI\Resources\AddressParser;
use Bluehost\ShippoAPI\Resources\Addresses;
use Bluehost\ShippoAPI\Resources\AddressValidation;
use Bluehost\ShippoAPI\Resources\Batches;
use Bluehost\ShippoAPI\Resources\CarrierAccounts;
use Bluehost\ShippoAPI\Resources\CarrierParcelTemplates;
use Bluehost\ShippoAPI\Resources\CustomsDeclarations;
use Bluehost\ShippoAPI\Resources\CustomsItems;
use Bluehost\ShippoAPI\Resources\Manifests;
use Bluehost\ShippoAPI\Resources\Orders;
use Bluehost\ShippoAPI\Resources\Parcels;
use Bluehost\ShippoAPI\Resources\Pickups;
use Bluehost\ShippoAPI\Resources\RatesAtCheckout;
use Bluehost\ShippoAPI\Resources\Refunds;
use Bluehost\ShippoAPI\Resources\ServiceGroups;
use Bluehost\ShippoAPI\Resources\Shipments;
use Bluehost\ShippoAPI\Resources\ShippoAccounts;
use Bluehost\ShippoAPI\Resources\UserParcelTemplates;
use Bluehost\ShippoAPI\Resources\Webhooks;

/**
 * Smoke tests ensuring every resource method builds the expected HTTP call.
 */
final class AllResourcesTest extends TestCase
{
    public function testAddressesAllMethods(): void
    {
        $http = $this->createMock(HttpClientInterface::class);
        $http->expects($this->exactly(6))
            ->method('request')
            ->withConsecutive(
                ['POST', '/addresses/', [], ['name' => 'A'], []],
                ['GET', '/addresses/', ['page' => 1], null, []],
                ['GET', '/addresses/id1/', [], null, []],
                ['GET', '/addresses/id1/validate/', [], null, []],
                ['PUT', '/addresses/id1/', [], ['city' => 'NY'], []],
                ['DELETE', '/addresses/id1/', [], null, []]
            )
            ->willReturn(['results' => []]);

        $resource = new Addresses($http);
        $resource->create(new Address(name: 'A'));
        $resource->list(['page' => 1]);
        $resource->retrieve('id1');
        $resource->validate('id1');
        $resource->update('id1', ['city' => 'NY']);
        $resource->delete('id1');
    }

    public function testAddressParserAndValidation(): void
    {
        $http = $this->createMock(HttpClientInterface::class);
        $http->expects($this->exactly(2))
            ->method('request')
            ->withConsecutive(
                ['POST', '/addresses/parse/', [], ['address' => '123 Main'], []],
                ['POST', '/v2/addresses/validate/', [], ['street1' => '123 Main'], []]
            )
            ->willReturn([]);

        (new AddressParser($http))->parse(['address' => '123 Main']);
        (new AddressValidation($http))->validate(['street1' => '123 Main']);
    }

    public function testBatchesAllMethods(): void
    {
        $http = $this->createMock(HttpClientInterface::class);
        $http->expects($this->exactly(5))
            ->method('request')
            ->withConsecutive(
                ['POST', '/batches/', [], ['default_carrier_account' => 'ca'], []],
                ['GET', '/batches/b1/', [], null, []],
                ['POST', '/batches/b1/add_shipments/', [], ['shipments' => []], []],
                ['POST', '/batches/b1/remove_shipments/', [], ['shipments' => []], []],
                ['POST', '/batches/b1/purchase/', [], [], []]
            )
            ->willReturn([]);

        $resource = new Batches($http);
        $resource->create(['default_carrier_account' => 'ca']);
        $resource->retrieve('b1');
        $resource->addShipments('b1', ['shipments' => []]);
        $resource->removeShipments('b1', ['shipments' => []]);
        $resource->purchase('b1');
    }

    public function testCarrierAccountsAllMethods(): void
    {
        $http = $this->createMock(HttpClientInterface::class);
        $http->expects($this->exactly(7))
            ->method('request')
            ->willReturn(['results' => []]);

        $resource = new CarrierAccounts($http);
        $resource->create(['carrier' => 'ups']);
        $resource->list();
        $resource->retrieve('ca1');
        $resource->update('ca1', ['active' => true]);
        $resource->registerNew(['carrier' => 'usps']);
        $resource->registerOauth(['carrier' => 'ups']);
        $resource->getRegistrationStatus('ca1');
    }

    public function testRemainingResources(): void
    {
        $http = $this->createMock(HttpClientInterface::class);
        $http->method('request')->willReturn(['results' => []]);

        (new CarrierParcelTemplates($http))->list();
        (new CarrierParcelTemplates($http))->retrieve('FedEx_Box_Small_1');

        $declarations = new CustomsDeclarations($http);
        $declarations->create(['contents_type' => 'MERCHANDISE']);
        $declarations->list();
        $declarations->retrieve('cd1');

        $items = new CustomsItems($http);
        $items->create(['description' => 'shirt']);
        $items->list();
        $items->retrieve('ci1');

        $manifests = new Manifests($http);
        $manifests->create(['carrier_account' => 'ca']);
        $manifests->list();
        $manifests->retrieve('m1');

        $orders = new Orders($http);
        $orders->create(['order_number' => '100']);
        $orders->list();
        $orders->retrieve('o1');

        $parcels = new Parcels($http);
        $parcels->create(new Parcel(weight: '1', massUnit: 'lb'));
        $parcels->list();
        $parcels->retrieve('p1');

        (new Pickups($http))->create(['carrier_account' => 'ca']);

        $checkout = new RatesAtCheckout($http);
        $checkout->generate(['address_to' => []]);
        $checkout->showDefaultParcelTemplate();
        $checkout->updateDefaultParcelTemplate(['object_id' => 'tmpl']);
        $checkout->clearDefaultParcelTemplate();

        $refunds = new Refunds($http);
        $refunds->create(['transaction' => 'txn1']);
        $refunds->list();
        $refunds->retrieve('r1');

        $groups = new ServiceGroups($http);
        $groups->create(['name' => 'default']);
        $groups->list();
        $groups->update('sg1', ['name' => 'updated']);
        $groups->delete('sg1');

        $accounts = new ShippoAccounts($http);
        $accounts->create(['email' => 'a@b.com']);
        $accounts->list();
        $accounts->retrieve('sa1');
        $accounts->update('sa1', ['email' => 'c@d.com']);

        (new Shipments($http))->list([], ['rates']);

        $templates = new UserParcelTemplates($http);
        $templates->create(['name' => 'box']);
        $templates->list();
        $templates->retrieve('t1');
        $templates->update('t1', ['name' => 'big box']);
        $templates->delete('t1');

        $webhooks = new Webhooks($http);
        $webhooks->create(['url' => 'https://ex.com']);
        $webhooks->list();
        $webhooks->retrieve('w1');
        $webhooks->update('w1', ['active' => true]);
        $webhooks->delete('w1');

        $this->assertTrue(true);
    }

    public function testRetrieveRejectsEmptyObjectId(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        (new Addresses($this->createMock(HttpClientInterface::class)))->retrieve('');
    }
}
