<?php

declare(strict_types=1);

namespace Bluehost\ShippoAPI\Resources;

use Bluehost\ShippoAPI\Pagination\PaginatedResponse;

/**
 * Shippo Accounts (Platform) API resource.
 *
 * Creates and manages Shippo managed accounts for platform and white-label
 * integrations. Base endpoint: `/shippo-accounts/`.
 *
 * @see https://docs.goshippo.com/api-reference/shippo-accounts/
 */
final class ShippoAccounts extends AbstractResource
{
    protected function resourcePath(): string
    {
        return 'shippo-accounts';
    }

    /**
     * Create a new Shippo managed account.
     *
     * @param array{
     *     email?: string,
     *     first_name?: string,
     *     last_name?: string,
     *     company_name?: string,
     *     metadata?: string
     * } $account Shippo managed account fields.
     *
     * @return array<string, mixed> Created Shippo account object.
     */
    public function create(array $account): array
    {
        return $this->post(null, null, $account);
    }

    /**
     * List all Shippo managed account objects.
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
     * Retrieve a Shippo managed account by object ID.
     *
     * @param string $objectId Shippo account object ID.
     *
     * @return array<string, mixed> Shippo account object.
     *
     * @throws \InvalidArgumentException When the object ID is empty.
     */
    public function retrieve(string $objectId): array
    {
        $this->assertObjectId($objectId);

        return $this->get($objectId);
    }

    /**
     * Update a Shippo managed account.
     *
     * @param string $objectId Shippo account object ID.
     * @param array{
     *     email?: string,
     *     first_name?: string,
     *     last_name?: string,
     *     company_name?: string,
     *     metadata?: string
     * } $account Updated account fields.
     *
     * @return array<string, mixed> Updated Shippo account object.
     *
     * @throws \InvalidArgumentException When the object ID is empty.
     */
    public function update(string $objectId, array $account): array
    {
        $this->assertObjectId($objectId);

        return $this->put($objectId, null, $account);
    }
}
