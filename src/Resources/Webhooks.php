<?php

declare(strict_types=1);

namespace Bluehost\ShippoAPI\Resources;

use Bluehost\ShippoAPI\Pagination\PaginatedResponse;

/**
 * Shippo Webhooks API resource.
 *
 * Creates and manages webhook subscriptions that receive HTTP notifications when
 * specific Shippo events occur. Base endpoint: `/webhooks/`.
 *
 * @see https://docs.goshippo.com/api-reference/webhooks/
 */
final class Webhooks extends AbstractResource
{
    protected function resourcePath(): string
    {
        return 'webhooks';
    }

    /**
     * Create a new webhook subscription.
     *
     * @param array{
     *     url?: string,
     *     event?: string,
     *     is_test?: bool,
     *     active?: bool
     * } $webhook Webhook subscription fields.
     *
     * @return array<string, mixed> Created webhook object.
     */
    public function create(array $webhook): array
    {
        return $this->post(null, null, $webhook);
    }

    /**
     * List all webhook subscriptions.
     *
     * @param array{
     *     page?: int,
     *     results?: int
     * } $query Pagination parameters.
     *
     * @return PaginatedResponse<array<string, mixed>>
     */
    public function list(array $query = []): PaginatedResponse
    {
        return parent::list($query);
    }

    /**
     * Retrieve a webhook subscription by object ID.
     *
     * @param string $objectId Shippo webhook object ID.
     *
     * @return array<string, mixed> Webhook object.
     *
     * @throws \InvalidArgumentException When the object ID is empty.
     */
    public function retrieve(string $objectId): array
    {
        $this->assertObjectId($objectId);

        return $this->get($objectId);
    }

    /**
     * Update an existing webhook subscription.
     *
     * @param string $objectId Shippo webhook object ID.
     * @param array{
     *     url?: string,
     *     event?: string,
     *     is_test?: bool,
     *     active?: bool
     * } $webhook Updated webhook fields.
     *
     * @return array<string, mixed> Updated webhook object.
     *
     * @throws \InvalidArgumentException When the object ID is empty.
     */
    public function update(string $objectId, array $webhook): array
    {
        $this->assertObjectId($objectId);

        return $this->put($objectId, null, $webhook);
    }

    /**
     * Delete a webhook subscription.
     *
     * @param string $objectId Shippo webhook object ID.
     *
     * @return array<string, mixed> Deletion confirmation response.
     *
     * @throws \InvalidArgumentException When the object ID is empty.
     */
    public function delete(string $objectId): array
    {
        $this->assertObjectId($objectId);

        return parent::sendDelete($objectId);
    }
}
