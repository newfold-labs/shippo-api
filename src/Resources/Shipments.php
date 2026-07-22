<?php

declare(strict_types=1);

namespace Bluehost\ShippoAPI\Resources;

use Bluehost\ShippoAPI\DTOs\Shipment;
use Bluehost\ShippoAPI\Pagination\PaginatedResponse;

/**
 * Shippo Shipments API resource.
 *
 * Creates and manages shipment objects used to obtain rates and purchase labels.
 * Base endpoint: `/shipments/`.
 *
 * @see https://docs.goshippo.com/api-reference/shipments/
 */
final class Shipments extends AbstractResource
{
    protected function resourcePath(): string
    {
        return 'shipments';
    }

    /**
     * Create a new shipment object.
     *
     * @param array{
     *     address_from?: array<string, mixed>|string,
     *     address_to?: array<string, mixed>|string,
     *     address_return?: array<string, mixed>|string,
     *     parcels?: array<int, array<string, mixed>|string>,
     *     async?: bool,
     *     customs_declaration?: array<string, mixed>|string,
     *     carrier_accounts?: array<int, string>|string,
     *     extra?: array<string, mixed>,
     *     metadata?: array<string, mixed>
     * }|Shipment $shipment Shipment fields or a {@see Shipment} DTO.
     *
     * @return array<string, mixed> Created shipment object.
     */
    public function create(Shipment|array $shipment): array
    {
        $body = $shipment instanceof Shipment ? $shipment->toArray() : $shipment;

        return $this->post(null, null, $body);
    }

    /**
     * Retrieve a shipment by object ID.
     *
     * Shipments older than 390 days are not returned by the API.
     *
     * @param string               $objectId Shippo shipment object ID.
     * @param array<string, mixed> $query    Optional query parameters.
     * @param array<int, string>   $expand   Related objects to expand inline, e.g. `['rates']`.
     *
     * @return array<string, mixed> Shipment object.
     *
     * @throws \InvalidArgumentException When the object ID is empty.
     */
    public function retrieve(string $objectId, array $query = [], array $expand = []): array
    {
        $this->assertObjectId($objectId);

        return $this->get($objectId, null, $this->withExpand($query, $expand));
    }

    /**
     * List all shipment objects.
     *
     * @param array{
     *     page?: int,
     *     results?: int,
     *     object_created_gt?: string,
     *     object_created_gte?: string,
     *     object_created_lt?: string,
     *     object_created_lte?: string
     * } $query  Pagination and filtering parameters.
     * @param array<int, string> $expand Related objects to expand inline on each result.
     *
     * @return PaginatedResponse<array<string, mixed>>
     */
    public function list(array $query = [], array $expand = []): PaginatedResponse
    {
        return parent::list($this->withExpand($query, $expand));
    }
}
