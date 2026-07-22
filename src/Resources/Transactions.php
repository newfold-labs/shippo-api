<?php

declare(strict_types=1);

namespace Bluehost\ShippoAPI\Resources;

use Bluehost\ShippoAPI\DTOs\Transaction;
use Bluehost\ShippoAPI\Pagination\PaginatedResponse;

/**
 * Shippo Transactions (Shipping Labels) API resource.
 *
 * Purchases shipping labels by creating transaction objects from rate objects.
 * Base endpoint: `/transactions/`.
 *
 * @see https://docs.goshippo.com/api-reference/transactions/
 */
final class Transactions extends AbstractResource
{
    protected function resourcePath(): string
    {
        return 'transactions';
    }

    /**
     * Create a shipping label by purchasing a rate.
     *
     * @param array{
     *     rate?: string,
     *     label_file_type?: string,
     *     async?: bool|string,
     *     metadata?: array<string, mixed>
     * }|Transaction $transaction Transaction fields or a {@see Transaction} DTO.
     *
     * @return array<string, mixed> Created transaction (label) object.
     */
    public function create(Transaction|array $transaction): array
    {
        $body = $transaction instanceof Transaction ? $transaction->toArray() : $transaction;

        return $this->post(null, null, $body);
    }

    /**
     * List all shipping label (transaction) objects.
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
     * Retrieve a shipping label (transaction) by object ID.
     *
     * @param string $objectId Shippo transaction object ID.
     *
     * @return array<string, mixed> Transaction object with label URLs.
     *
     * @throws \InvalidArgumentException When the object ID is empty.
     */
    public function retrieve(string $objectId): array
    {
        $this->assertObjectId($objectId);

        return $this->get($objectId);
    }
}
