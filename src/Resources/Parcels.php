<?php

declare(strict_types=1);

namespace Bluehost\ShippoAPI\Resources;

use Bluehost\ShippoAPI\DTOs\Parcel;
use Bluehost\ShippoAPI\Pagination\PaginatedResponse;

/**
 * Shippo Parcels API resource.
 *
 * Creates and manages parcel dimension and weight objects used in shipments.
 * Base endpoint: `/parcels/`.
 *
 * @see https://docs.goshippo.com/api-reference/parcels/
 */
final class Parcels extends AbstractResource
{
    protected function resourcePath(): string
    {
        return 'parcels';
    }

    /**
     * Create a new parcel object.
     *
     * @param array{
     *     length?: string,
     *     width?: string,
     *     height?: string,
     *     distance_unit?: string,
     *     weight?: string,
     *     mass_unit?: string,
     *     template?: string,
     *     metadata?: array<string, mixed>
     * }|Parcel $parcel Parcel fields or a {@see Parcel} DTO.
     *
     * @return array<string, mixed> Created parcel object.
     */
    public function create(Parcel|array $parcel): array
    {
        $body = $parcel instanceof Parcel ? $parcel->toArray() : $parcel;

        return $this->post(null, null, $body);
    }

    /**
     * List all parcel objects.
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
     * Retrieve a parcel by object ID.
     *
     * @param string $objectId Shippo parcel object ID.
     *
     * @return array<string, mixed> Parcel object.
     *
     * @throws \InvalidArgumentException When the object ID is empty.
     */
    public function retrieve(string $objectId): array
    {
        $this->assertObjectId($objectId);

        return $this->get($objectId);
    }
}
