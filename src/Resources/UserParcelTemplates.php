<?php

declare(strict_types=1);

namespace Bluehost\ShippoAPI\Resources;

use Bluehost\ShippoAPI\Pagination\PaginatedResponse;

/**
 * Shippo User Parcel Templates API resource.
 *
 * Creates and manages custom parcel templates with user-defined dimensions for
 * reuse across shipments and live rates. Base endpoint: `/user-parcel-templates/`.
 *
 * @see https://docs.goshippo.com/api-reference/user-parcel-templates/
 */
final class UserParcelTemplates extends AbstractResource
{
    protected function resourcePath(): string
    {
        return 'user-parcel-templates';
    }

    /**
     * Create a new user parcel template.
     *
     * @param array{
     *     name?: string,
     *     length?: string,
     *     width?: string,
     *     height?: string,
     *     distance_unit?: string,
     *     weight?: string,
     *     weight_unit?: string
     * } $template User parcel template fields.
     *
     * @return array<string, mixed> Created user parcel template object.
     */
    public function create(array $template): array
    {
        return $this->post(null, null, $template);
    }

    /**
     * List all user parcel template objects.
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
     * Retrieve a user parcel template by object ID.
     *
     * @param string $objectId Shippo user parcel template object ID.
     *
     * @return array<string, mixed> User parcel template object.
     *
     * @throws \InvalidArgumentException When the object ID is empty.
     */
    public function retrieve(string $objectId): array
    {
        $this->assertObjectId($objectId);

        return $this->get($objectId);
    }

    /**
     * Update an existing user parcel template.
     *
     * @param string $objectId Shippo user parcel template object ID.
     * @param array{
     *     name?: string,
     *     length?: string,
     *     width?: string,
     *     height?: string,
     *     distance_unit?: string,
     *     weight?: string,
     *     weight_unit?: string
     * } $template Updated template fields.
     *
     * @return array<string, mixed> Updated user parcel template object.
     *
     * @throws \InvalidArgumentException When the object ID is empty.
     */
    public function update(string $objectId, array $template): array
    {
        $this->assertObjectId($objectId);

        return $this->put($objectId, null, $template);
    }

    /**
     * Delete a user parcel template.
     *
     * @param string $objectId Shippo user parcel template object ID.
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
