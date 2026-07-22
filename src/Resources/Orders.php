<?php

declare(strict_types=1);

namespace Bluehost\ShippoAPI\Resources;

use Bluehost\ShippoAPI\Pagination\PaginatedResponse;

/**
 * Shippo Orders API resource.
 *
 * Creates and manages order objects that group line items and shipping details.
 * Base endpoint: `/orders/`.
 *
 * @see https://docs.goshippo.com/api-reference/orders/
 */
final class Orders extends AbstractResource
{
    protected function resourcePath(): string
    {
        return 'orders';
    }

    /**
     * Create a new order.
     *
     * @param array{
     *     order_number?: string,
     *     order_status?: string,
     *     placed_at?: string,
     *     shop_app?: string,
     *     weight?: string,
     *     weight_unit?: string,
     *     total_tax?: string,
     *     total_price?: string,
     *     subtotal_price?: string,
     *     currency?: string,
     *     shipping_method?: string,
     *     shipping_cost?: string,
     *     shipping_cost_currency?: string,
     *     notes?: string,
     *     test?: bool,
     *     to_address?: array<string, mixed>|string,
     *     from_address?: array<string, mixed>|string,
     *     line_items?: array<int, array<string, mixed>>,
     *     transactions?: array<int, string>
     * } $order Order fields.
     *
     * @return array<string, mixed> Created order object.
     */
    public function create(array $order): array
    {
        return $this->post(null, null, $order);
    }

    /**
     * List all order objects.
     *
     * @param array{
     *     page?: int,
     *     results?: int,
     *     object_created_gt?: string,
     *     object_created_gte?: string,
     *     object_created_lt?: string,
     *     object_created_lte?: string,
     *     order_status?: string,
     *     shop_app?: string
     * } $query Pagination and filtering parameters.
     *
     * @return PaginatedResponse<array<string, mixed>>
     */
    public function list(array $query = []): PaginatedResponse
    {
        return parent::list($query);
    }

    /**
     * Retrieve an order by object ID.
     *
     * @param string $objectId Shippo order object ID.
     *
     * @return array<string, mixed> Order object.
     *
     * @throws \InvalidArgumentException When the object ID is empty.
     */
    public function retrieve(string $objectId): array
    {
        $this->assertObjectId($objectId);

        return $this->get($objectId);
    }
}
