<?php

declare(strict_types=1);

namespace Bluehost\ShippoAPI\Tests\Integration;

use PHPUnit\Framework\TestCase;
use Bluehost\ShippoAPI\ShippoClient;

/**
 * Optional live API tests. Run with:
 * SHIPPO_API_TOKEN=shippo_test_xxx vendor/bin/phpunit --group integration
 *
 * @group integration
 */
final class ShippoIntegrationTest extends TestCase
{
    public function testListCarrierParcelTemplates(): void
    {
        $token = getenv('SHIPPO_API_TOKEN');
        if ($token === false || $token === '') {
            $this->markTestSkipped('Set SHIPPO_API_TOKEN to run integration tests.');
        }

        $client = ShippoClient::create($token);
        $response = $client->carrierParcelTemplates()->list();

        $this->assertIsArray($response->getResults());
    }
}
