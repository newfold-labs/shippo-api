<?php

declare(strict_types=1);

namespace Bluehost\ShippoAPI\Polling;

use InvalidArgumentException;
use Bluehost\ShippoAPI\Contracts\BatchRetrieverInterface;

/**
 * Polls Shippo batch objects until a terminal status is reached.
 */
final class BatchPoller
{
    /** @var string Batch status: queued for processing. */
    public const STATUS_QUEUED = 'QUEUED';

    /** @var string Batch status: validation succeeded. */
    public const STATUS_VALID = 'VALID';

    /** @var string Batch status: validation failed. */
    public const STATUS_INVALID = 'INVALID';

    /** @var string Batch status: purchase in progress. */
    public const STATUS_PURCHASING = 'PURCHASING';

    /** @var string Batch status: purchase completed. */
    public const STATUS_PURCHASED = 'PURCHASED';

    /** @var int */
    private $maxAttempts;

    /** @var int */
    private $intervalSeconds;

    /**
     * Creates a new batch poller.
     *
     * @param int $maxAttempts     Maximum number of poll attempts before timing out.
     * @param int $intervalSeconds Seconds to wait between poll attempts.
     *
     * @throws \InvalidArgumentException When maxAttempts or intervalSeconds is less than 1.
     */
    public function __construct(int $maxAttempts = 60, int $intervalSeconds = 2)
    {
        if ($maxAttempts < 1) {
            throw new InvalidArgumentException('maxAttempts must be at least 1.');
        }

        if ($intervalSeconds < 1) {
            throw new InvalidArgumentException('intervalSeconds must be at least 1.');
        }

        $this->maxAttempts = $maxAttempts;
        $this->intervalSeconds = $intervalSeconds;
    }

    /**
     * Polls until batch validation completes (VALID or INVALID).
     *
     * @param BatchRetrieverInterface $batches Batch retriever implementation.
     * @param string                  $batchId Shippo batch object ID.
     *
     * @return array<string, mixed> Final batch object.
     *
     * @throws \InvalidArgumentException When the batch ID is empty or polling times out.
     */
    public function waitUntilValidated(BatchRetrieverInterface $batches, string $batchId): array
    {
        return $this->pollUntil($batches, $batchId, [self::STATUS_VALID, self::STATUS_INVALID]);
    }

    /**
     * Polls until batch purchase completes (PURCHASED).
     *
     * @param BatchRetrieverInterface $batches Batch retriever implementation.
     * @param string                  $batchId Shippo batch object ID.
     *
     * @return array<string, mixed> Final batch object.
     *
     * @throws \InvalidArgumentException When the batch ID is empty or polling times out.
     */
    public function waitUntilPurchased(BatchRetrieverInterface $batches, string $batchId): array
    {
        return $this->pollUntil($batches, $batchId, [self::STATUS_PURCHASED]);
    }

    /**
     * Polls until the batch reaches one of the given terminal statuses.
     *
     * @param BatchRetrieverInterface $batches          Batch retriever implementation.
     * @param string                  $batchId          Shippo batch object ID.
     * @param array<int, string>      $terminalStatuses Status values that end polling.
     *
     * @return array<string, mixed> Final batch object.
     *
     * @throws \InvalidArgumentException When the batch ID is empty or polling times out.
     */
    public function pollUntil(BatchRetrieverInterface $batches, string $batchId, array $terminalStatuses): array
    {
        if ($batchId === '') {
            throw new InvalidArgumentException('Batch ID cannot be empty.');
        }

        for ($attempt = 0; $attempt < $this->maxAttempts; ++$attempt) {
            $batch = $batches->retrieve($batchId);
            $status = isset($batch['batch_status']) && is_string($batch['batch_status'])
                ? $batch['batch_status']
                : '';

            if (in_array($status, $terminalStatuses, true)) {
                return $batch;
            }

            if ($attempt < $this->maxAttempts - 1) {
                sleep($this->intervalSeconds);
            }
        }

        throw new InvalidArgumentException(
            sprintf(
                'Batch %s did not reach status %s within %d attempts.',
                $batchId,
                implode('|', $terminalStatuses),
                $this->maxAttempts
            )
        );
    }
}
