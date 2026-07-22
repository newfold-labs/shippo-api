<?php

declare(strict_types=1);

namespace Bluehost\ShippoAPI\Tests\Unit\Auth;

use PHPUnit\Framework\TestCase;
use Bluehost\ShippoAPI\Auth\BearerAuthenticator;
use Bluehost\ShippoAPI\Auth\JwtAuthenticator;

final class JwtAuthenticatorTest extends TestCase
{
    public function testJwtAuthenticatorHeader(): void
    {
        $auth = new JwtAuthenticator('eyJ.test.token');

        $this->assertSame(
            ['Authorization' => 'JWT eyJ.test.token'],
            $auth->getHeaders()
        );
    }

    public function testBearerAuthenticatorHeader(): void
    {
        $auth = new BearerAuthenticator('oauth_token');

        $this->assertSame(
            ['Authorization' => 'Bearer oauth_token'],
            $auth->getHeaders()
        );
    }
}
