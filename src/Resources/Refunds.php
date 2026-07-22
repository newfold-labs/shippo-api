<?php

declare(strict_types=1);

namespace Bluehost\ShippoAPI\Resources;

use Bluehost\ShippoAPI\Pagination\PaginatedResponse;

/**
 * Shippo Refunds API resource.
 *
 * Creates and manages refund requests for purchased shipping labels.
 * Base endpoint: `/refunds/`.
 *
 * @see https://docs.goshippo.com/api-reference/refunds/
 */
final class Refunds extends AbstractResource
{
    protected function resourcePath(): string
    {
        return 'refunds';
    }

    /**
     * Create a new refund request for a transaction.
     *
     * @param array{
     *     transaction?: string,
     *     async?: bool
     * } $refund Refund fields; `transaction` is the transaction object ID to refund.
     *
     * @return array<string, mixed> Created refund object.
     */
    public function create(array $refund): array
    {
        return $this->post(null, null, $refund);
    }

    /**
     * List all refund objects.
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
     * Retrieve a refund by object ID.
     *
     * @param string $objectId Shippo refund object ID.
     *
     * @return array<string, mixed> Refund object.
     *
     * @throws \InvalidArgumentException When the object ID is empty.
     */
    public function retrieve(string $objectId): array
    {
        $this->assertObjectId($objectId);

        return $this->get($objectId);
    }
}
