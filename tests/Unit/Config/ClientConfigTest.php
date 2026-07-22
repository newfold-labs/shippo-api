<?php

declare(strict_types=1);

namespace Bluehost\ShippoAPI\Tests\Unit\Config;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Bluehost\ShippoAPI\Auth\TokenAuthenticator;
use Bluehost\ShippoAPI\Config\ClientConfig;

final class ClientConfigTest extends TestCase
{
    public function testRejectsNonHttpsBaseUrl(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new ClientConfig('http://api.goshippo.com');
    }

    public function testTokenAuthenticatorAddsAuthorizationHeader(): void
    {
        $auth = new TokenAuthenticator('shippo_test_abc');

        $this->assertSame(
            ['Authorization' => 'ShippoToken shippo_test_abc'],
            $auth->getHeaders()
        );
    }

    public function testWithDebugAndBaseUrl(): void
    {
        $config = ClientConfig::create()
            ->withBaseUrl('https://api.goshippo.com')
            ->withDebug(true);

        $this->assertTrue($config->isDebug());
        $this->assertSame('https://api.goshippo.com', $config->getBaseUrl());
        $this->assertSame(3, $config->getMaxRetries());
    }
}
