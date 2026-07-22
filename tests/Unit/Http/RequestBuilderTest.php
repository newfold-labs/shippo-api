<?php

declare(strict_types=1);

namespace Bluehost\ShippoAPI\Tests\Unit\Http;

use PHPUnit\Framework\TestCase;
use Bluehost\ShippoAPI\Http\RequestBuilder;

final class RequestBuilderTest extends TestCase
{
    private RequestBuilder $builder;

    protected function setUp(): void
    {
        $this->builder = new RequestBuilder();
    }

    public function testBuildPathWithoutId(): void
    {
        $this->assertSame('/addresses/', $this->builder->buildPath('addresses'));
    }

    public function testBuildPathWithIdAndAction(): void
    {
        $this->assertSame(
            '/addresses/abc123/validate/',
            $this->builder->buildPath('addresses', 'abc123', 'validate')
        );
    }

    public function testBuildPathEncodesUnsafeCharacters(): void
    {
        $this->assertSame(
            '/addresses/foo%2Fbar/',
            $this->builder->buildPath('addresses', 'foo/bar')
        );
    }

    public function testBuildQueryFiltersNullValues(): void
    {
        $query = $this->builder->buildQuery([
            'page' => 2,
            'results' => 25,
            'expand' => null,
            'async' => true,
        ]);

        $this->assertSame([
            'page' => 2,
            'results' => 25,
            'async' => 'true',
        ], $query);
    }
}
