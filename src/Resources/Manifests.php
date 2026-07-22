<?php

declare(strict_types=1);

namespace Bluehost\ShippoAPI\Resources;

use Bluehost\ShippoAPI\Pagination\PaginatedResponse;

/**
 * Shippo Manifests API resource.
 *
 * Creates and manages manifest (scan form) objects used to hand off shipments to
 * carriers. Base endpoint: `/manifests/`.
 *
 * @see https://docs.goshippo.com/api-reference/manifests/
 */
final class Manifests extends AbstractResource
{
    protected function resourcePath(): string
    {
        return 'manifests';
    }

    /**
     * Create a new manifest.
     *
     * @param array{
     *     carrier_account?: string,
     *     shipment_date?: string,
     *     address_from?: array<string, mixed>|string,
     *     transactions?: array<int, string>,
     *     async?: bool
     * } $manifest Manifest fields.
     *
     * @return array<string, mixed> Created manifest object.
     */
    public function create(array $manifest): array
    {
        return $this->post(null, null, $manifest);
    }

    /**
     * List all manifest objects.
     *
     * @param array{
     *     page?: int,
     *     results?: int,
     *     object_created_gt?: string,
     *     object_created_gte?: string,
     *     object_created_lt?: string,
     *     object_created_lte?: string
     * } $query Pagination and filtering parameters.
     *
     * @return PaginatedResponse<array<string, mixed>>
     */
    public function list(array $query = []): PaginatedResponse
    {
        return parent::list($query);
    }

    /**
     * Retrieve a manifest by object ID.
     *
     * @param string $objectId Shippo manifest object ID.
     *
     * @return array<string, mixed> Manifest object.
     *
     * @throws \InvalidArgumentException When the object ID is empty.
     */
    public function retrieve(string $objectId): array
    {
        $this->assertObjectId($objectId);

        return $this->get($objectId);
    }
}
