<?php

declare(strict_types=1);

namespace Bluehost\ShippoAPI\Contracts;

/**
 * Contract for retrieving a Shippo batch by object ID.
 *
 * Implemented by batch resource classes and used by {@see \Bluehost\ShippoAPI\Polling\BatchPoller}.
 */
interface BatchRetrieverInterface
{
    /**
     * Retrieves a batch object from the Shippo API.
     *
     * @param string $objectId Shippo batch object ID.
     *
     * @return array<string, mixed> Decoded batch response body.
     */
    public function retrieve(string $objectId): array;
}
