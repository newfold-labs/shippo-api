<?php

declare(strict_types=1);

namespace Bluehost\ShippoAPI\Resources;

use Bluehost\ShippoAPI\Contracts\BatchRetrieverInterface;

/**
 * Shippo Batches API resource.
 *
 * Creates and manages batches for purchasing shipping labels for many shipments
 * at once. Batch operations are asynchronous. Base endpoint: `/batches/`.
 *
 * @see https://docs.goshippo.com/api-reference/batches/
 */
final class Batches extends AbstractResource implements BatchRetrieverInterface
{
    protected function resourcePath(): string
    {
        return 'batches';
    }

    /**
     * Create a new batch object.
     *
     * @param array{
     *     default_carrier_account?: string,
     *     default_servicelevel_token?: string,
     *     label_filetype?: string,
     *     metadata?: string,
     *     batch_shipments?: array<int, array{
     *         shipment?: string,
     *         metadata?: string
     *     }>
     * } $batch Batch configuration and optional initial shipments.
     *
     * @return array<string, mixed> Created batch object.
     */
    public function create(array $batch): array
    {
        return $this->post(null, null, $batch);
    }

    /**
     * Retrieve an existing batch by object ID.
     *
     * @param string $objectId Shippo batch object ID.
     *
     * @return array<string, mixed> Batch object with shipment statuses.
     *
     * @throws \InvalidArgumentException When the object ID is empty.
     */
    public function retrieve(string $objectId): array
    {
        $this->assertObjectId($objectId);

        return $this->get($objectId);
    }

    /**
     * Add shipments to an existing batch.
     *
     * @param string $objectId Shippo batch object ID.
     * @param array{
     *     shipments?: array<int, array{
     *         shipment?: string,
     *         metadata?: string
     *     }>
     * } $payload Shipments to add to the batch.
     *
     * @return array<string, mixed> Updated batch object.
     *
     * @throws \InvalidArgumentException When the object ID is empty.
     */
    public function addShipments(string $objectId, array $payload): array
    {
        $this->assertObjectId($objectId);

        return $this->post($objectId, 'add_shipments', $payload);
    }

    /**
     * Remove shipments from an existing batch.
     *
     * @param string $objectId Shippo batch object ID.
     * @param array{
     *     shipments?: array<int, array{
     *         shipment?: string
     *     }>
     * } $payload Shipments to remove from the batch.
     *
     * @return array<string, mixed> Updated batch object.
     *
     * @throws \InvalidArgumentException When the object ID is empty.
     */
    public function removeShipments(string $objectId, array $payload): array
    {
        $this->assertObjectId($objectId);

        return $this->post($objectId, 'remove_shipments', $payload);
    }

    /**
     * Purchase all valid shipments in a batch.
     *
     * @param string $objectId Shippo batch object ID.
     *
     * @return array<string, mixed> Batch object with purchase status.
     *
     * @throws \InvalidArgumentException When the object ID is empty.
     */
    public function purchase(string $objectId): array
    {
        $this->assertObjectId($objectId);

        return $this->post($objectId, 'purchase');
    }
}
