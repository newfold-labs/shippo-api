<?php

declare(strict_types=1);

namespace Bluehost\ShippoAPI\Tests\Unit\Pagination;

use PHPUnit\Framework\TestCase;
use Bluehost\ShippoAPI\Pagination\PaginatedResponse;

final class PaginatedResponseTest extends TestCase
{
    public function testAccessorsAndMap(): void
    {
        $response = new PaginatedResponse(
            [['object_id' => '1'], ['object_id' => '2']],
            'https://api.goshippo.com/next',
            null,
            2
        );

        $this->assertCount(2, $response->getResults());
        $this->assertSame('https://api.goshippo.com/next', $response->getNext());
        $this->assertNull($response->getPrevious());
        $this->assertSame(2, $response->getCount());

        $ids = $response->map(static function (array $row): string {
            return $row['object_id'];
        });

        $this->assertSame(['1', '2'], $ids);
    }
}
