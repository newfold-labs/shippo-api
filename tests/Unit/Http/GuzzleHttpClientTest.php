<?php

declare(strict_types=1);

namespace Bluehost\ShippoAPI\Tests\Unit\Http;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Bluehost\ShippoAPI\Auth\TokenAuthenticator;
use Bluehost\ShippoAPI\Config\ClientConfig;
use Bluehost\ShippoAPI\Http\GuzzleHttpClient;

final class GuzzleHttpClientTest extends TestCase
{
    public function testSendsAuthenticatedRequestAndParsesResponse(): void
    {
        $mock = new MockHandler([
            new Response(200, [], '{"object_id":"addr_1","object_owner":"me"}'),
        ]);

        $guzzle = new Client([
            'handler' => HandlerStack::create($mock),
            'base_uri' => 'https://api.goshippo.com',
        ]);

        $http = new GuzzleHttpClient(
            ClientConfig::create(),
            new TokenAuthenticator('shippo_test_key'),
            $guzzle
        );

        $result = $http->request('GET', '/addresses/addr_1/');

        $this->assertSame('addr_1', $result['object_id']);
        $this->assertSame('me', $result['object_owner']);
    }

    public function testSendsJsonBodyOnPost(): void
    {
        $mock = new MockHandler([
            new Response(201, [], '{"object_id":"shp_1"}'),
        ]);

        $guzzle = new Client([
            'handler' => HandlerStack::create($mock),
            'base_uri' => 'https://api.goshippo.com',
        ]);

        $http = new GuzzleHttpClient(
            ClientConfig::create()->withDebug(true),
            new TokenAuthenticator('shippo_test_key'),
            $guzzle
        );

        $result = $http->request('POST', '/shipments/', [], ['async' => false]);

        $this->assertSame('shp_1', $result['object_id']);
    }
}
