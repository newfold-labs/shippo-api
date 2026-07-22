<?php

declare(strict_types=1);

namespace Bluehost\ShippoAPI\Tests\Unit\Http;

use PHPUnit\Framework\TestCase;
use Bluehost\ShippoAPI\Exceptions\AuthenticationException;
use Bluehost\ShippoAPI\Exceptions\RateLimitException;
use Bluehost\ShippoAPI\Exceptions\ValidationException;
use Bluehost\ShippoAPI\Http\ResponseParser;

final class ResponseParserTest extends TestCase
{
    private ResponseParser $parser;

    protected function setUp(): void
    {
        $this->parser = new ResponseParser();
    }

    public function testParsesSuccessfulResponse(): void
    {
        $result = $this->parser->parse('{"object_id":"abc"}', 200);

        $this->assertSame(['object_id' => 'abc'], $result);
    }

    public function testThrowsAuthenticationException(): void
    {
        $this->expectException(AuthenticationException::class);
        $this->expectExceptionMessage('Token does not exist');

        $this->parser->parse('{"detail":"Token does not exist"}', 401);
    }

    public function testThrowsRateLimitExceptionWithRetryAfter(): void
    {
        try {
            $this->parser->parse('{"detail":"Too many requests"}', 429, '30');
            $this->fail('Expected RateLimitException was not thrown.');
        } catch (RateLimitException $exception) {
            $this->assertSame(30, $exception->getRetryAfter());
        }
    }

    public function testThrowsApiExceptionForServerError(): void
    {
        $this->expectException(\Bluehost\ShippoAPI\Exceptions\ApiException::class);

        $this->parser->parse('{"detail":"Server error"}', 500);
    }

    public function testThrowsOnInvalidJson(): void
    {
        $this->expectException(\Bluehost\ShippoAPI\Exceptions\ShippoException::class);

        $this->parser->parse('not-json', 200);
    }
}
