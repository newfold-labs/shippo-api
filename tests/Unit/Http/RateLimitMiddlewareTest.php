<?php

declare(strict_types=1);

namespace Bluehost\ShippoAPI\Tests\Unit\Http;

use PHPUnit\Framework\TestCase;
use Bluehost\ShippoAPI\Exceptions\RateLimitException;
use Bluehost\ShippoAPI\Http\RateLimitMiddleware;
use Bluehost\ShippoAPI\Contracts\HttpClientInterface;

final class RateLimitMiddlewareTest extends TestCase
{
    public function testRetriesOnRateLimitAndEventuallySucceeds(): void
    {
        $inner = $this->createMock(HttpClientInterface::class);
        $inner->expects($this->exactly(2))
            ->method('request')
            ->willReturnOnConsecutiveCalls(
                $this->throwException(new RateLimitException('Too many requests', 429, null, null, 0)),
                ['object_id' => 'ok']
            );

        $middleware = new RateLimitMiddleware($inner, 2);
        $result = $middleware->request('GET', '/addresses/');

        $this->assertSame('ok', $result['object_id']);
    }

    public function testRetriesOnServerError(): void
    {
        $inner = $this->createMock(HttpClientInterface::class);
        $inner->expects($this->exactly(2))
            ->method('request')
            ->willReturnOnConsecutiveCalls(
                $this->throwException(new \Bluehost\ShippoAPI\Exceptions\ApiException('Server error', 500)),
                ['object_id' => 'recovered']
            );

        $middleware = new RateLimitMiddleware($inner, 1);
        $result = $middleware->request('GET', '/shipments/');

        $this->assertSame('recovered', $result['object_id']);
    }
}
