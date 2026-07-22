<?php

declare(strict_types=1);

namespace Bluehost\ShippoAPI\Resources;

use Bluehost\ShippoAPI\Pagination\PaginatedResponse;

/**
 * Shippo Service Groups API resource.
 *
 * Creates and manages service groups that define which carrier service levels are
 * available for rate shopping. Base endpoint: `/service-groups/`.
 *
 * @see https://docs.goshippo.com/api-reference/service-groups/
 */
final class ServiceGroups extends AbstractResource
{
    protected function resourcePath(): string
    {
        return 'service-groups';
    }

    /**
     * Create a new service group.
     *
     * @param array{
     *     name?: string,
     *     description?: string,
     *     flat_rate?: string,
     *     flat_rate_currency?: string,
     *     type?: string,
     *     service_levels?: array<int, array{
     *         account_object_id?: string,
     *         service_level_token?: string
     *     }>,
     *     is_active?: bool
     * } $serviceGroup Service group fields.
     *
     * @return array<string, mixed> Created service group object.
     */
    public function create(array $serviceGroup): array
    {
        return $this->post(null, null, $serviceGroup);
    }

    /**
     * List all service group objects.
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
     * Update an existing service group.
     *
     * @param string $objectId Shippo service group object ID.
     * @param array{
     *     name?: string,
     *     description?: string,
     *     flat_rate?: string,
     *     flat_rate_currency?: string,
     *     type?: string,
     *     service_levels?: array<int, array{
     *         account_object_id?: string,
     *         service_level_token?: string
     *     }>,
     *     is_active?: bool
     * } $serviceGroup Updated service group fields.
     *
     * @return array<string, mixed> Updated service group object.
     *
     * @throws \InvalidArgumentException When the object ID is empty.
     */
    public function update(string $objectId, array $serviceGroup): array
    {
        $this->assertObjectId($objectId);

        return $this->put($objectId, null, $serviceGroup);
    }

    /**
     * Delete a service group.
     *
     * @param string $objectId Shippo service group object ID.
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
