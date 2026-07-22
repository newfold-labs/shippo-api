<?php

declare(strict_types=1);

namespace Bluehost\ShippoAPI\Tests\Unit\Polling;

use PHPUnit\Framework\TestCase;
use Bluehost\ShippoAPI\Contracts\BatchRetrieverInterface;
use Bluehost\ShippoAPI\Polling\BatchPoller;

final class BatchPollerTest extends TestCase
{
    public function testWaitUntilValidatedReturnsOnTerminalStatus(): void
    {
        $batches = new class () implements BatchRetrieverInterface {
            public function retrieve(string $objectId): array
            {
                return ['object_id' => $objectId, 'batch_status' => BatchPoller::STATUS_VALID];
            }
        };

        $poller = new BatchPoller(3, 1);
        $result = $poller->waitUntilValidated($batches, 'batch_1');

        $this->assertSame(BatchPoller::STATUS_VALID, $result['batch_status']);
    }

    public function testPollUntilThrowsWhenTimeout(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $batches = new class () implements BatchRetrieverInterface {
            public function retrieve(string $objectId): array
            {
                return ['batch_status' => BatchPoller::STATUS_QUEUED];
            }
        };

        $poller = new BatchPoller(2, 1);
        $poller->pollUntil($batches, 'batch_1', [BatchPoller::STATUS_VALID]);
    }
}
