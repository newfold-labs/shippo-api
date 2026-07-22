<?php

declare(strict_types=1);

namespace Bluehost\ShippoAPI\Tests\Unit\Exceptions;

use PHPUnit\Framework\TestCase;
use Bluehost\ShippoAPI\Exceptions\ApiException;
use Bluehost\ShippoAPI\Exceptions\AuthenticationException;
use Bluehost\ShippoAPI\Exceptions\RateLimitException;
use Bluehost\ShippoAPI\Exceptions\ShippoException;
use Bluehost\ShippoAPI\Exceptions\ValidationException;

final class ShippoExceptionTest extends TestCase
{
    public function testShippoExceptionStoresResponseBody(): void
    {
        $exception = new ShippoException('error', 500, null, ['detail' => 'fail']);

        $this->assertSame('error', $exception->getMessage());
        $this->assertSame(500, $exception->getCode());
        $this->assertSame(['detail' => 'fail'], $exception->getResponseBody());
    }

    public function testExceptionHierarchy(): void
    {
        $this->assertInstanceOf(ShippoException::class, new AuthenticationException());
        $this->assertInstanceOf(ShippoException::class, new ValidationException());
        $this->assertInstanceOf(ShippoException::class, new ApiException());
        $this->assertInstanceOf(ShippoException::class, new RateLimitException());
    }

    public function testRateLimitExceptionRetryAfter(): void
    {
        $exception = new RateLimitException('limited', 429, null, null, 30);
        $this->assertSame(30, $exception->getRetryAfter());
    }
}
