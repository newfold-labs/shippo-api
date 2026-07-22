<?php

declare(strict_types=1);

namespace Bluehost\ShippoAPI\Resources;

use Bluehost\ShippoAPI\Pagination\PaginatedResponse;

/**
 * Shippo Customs Items API resource.
 *
 * Creates and manages individual customs item objects used within customs
 * declarations. Base endpoint: `/customs/items/`.
 *
 * @see https://docs.goshippo.com/api-reference/customs-items/
 */
final class CustomsItems extends AbstractResource
{
    protected function resourcePath(): string
    {
        return 'customs/items';
    }

    /**
     * Create a new customs item.
     *
     * @param array{
     *     description?: string,
     *     quantity?: int,
     *     net_weight?: string,
     *     mass_unit?: string,
     *     value_amount?: string,
     *     value_currency?: string,
     *     origin_country?: string,
     *     tariff_number?: string,
     *     sku_code?: string,
     *     hs_code?: string,
     *     metadata?: string
     * } $item Customs item fields.
     *
     * @return array<string, mixed> Created customs item object.
     */
    public function create(array $item): array
    {
        return $this->post(null, null, $item);
    }

    /**
     * List all customs item objects.
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
     * Retrieve a customs item by object ID.
     *
     * @param string $objectId Shippo customs item object ID.
     *
     * @return array<string, mixed> Customs item object.
     *
     * @throws \InvalidArgumentException When the object ID is empty.
     */
    public function retrieve(string $objectId): array
    {
        $this->assertObjectId($objectId);

        return $this->get($objectId);
    }
}
