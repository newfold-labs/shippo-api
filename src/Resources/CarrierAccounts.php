<?php

declare(strict_types=1);

namespace Bluehost\ShippoAPI\Resources;

use Bluehost\ShippoAPI\Pagination\PaginatedResponse;

/**
 * Shippo Carrier Accounts API resource.
 *
 * Manages carrier account connections, including Shippo-managed accounts and
 * OAuth registration flows. Base endpoint: `/carrier_accounts/`.
 *
 * @see https://docs.goshippo.com/api-reference/carrier-accounts/
 */
final class CarrierAccounts extends AbstractResource
{
    protected function resourcePath(): string
    {
        return 'carrier_accounts';
    }

    /**
     * Create a new carrier account or connect an existing one.
     *
     * @param array{
     *     carrier?: string,
     *     account_id?: string,
     *     parameters?: array<string, mixed>,
     *     test?: bool,
     *     active?: bool,
     *     metadata?: string
     * } $carrierAccount Carrier account fields.
     *
     * @return array<string, mixed> Created carrier account object.
     */
    public function create(array $carrierAccount): array
    {
        return $this->post(null, null, $carrierAccount);
    }

    /**
     * List all carrier accounts connected to this Shippo account.
     *
     * @param array{
     *     page?: int,
     *     results?: int,
     *     service_levels?: bool
     * } $query Pagination and filtering parameters.
     *
     * @return PaginatedResponse<array<string, mixed>>
     */
    public function list(array $query = []): PaginatedResponse
    {
        return parent::list($query);
    }

    /**
     * Retrieve a carrier account by object ID.
     *
     * @param string $objectId Shippo carrier account object ID.
     *
     * @return array<string, mixed> Carrier account object.
     *
     * @throws \InvalidArgumentException When the object ID is empty.
     */
    public function retrieve(string $objectId): array
    {
        $this->assertObjectId($objectId);

        return $this->get($objectId);
    }

    /**
     * Update an existing carrier account.
     *
     * The `account_id` and `carrier` fields cannot be changed.
     *
     * @param string $objectId Shippo carrier account object ID.
     * @param array{
     *     parameters?: array<string, mixed>,
     *     active?: bool,
     *     metadata?: string
     * } $carrierAccount Updated carrier account fields.
     *
     * @return array<string, mixed> Updated carrier account object.
     *
     * @throws \InvalidArgumentException When the object ID is empty.
     */
    public function update(string $objectId, array $carrierAccount): array
    {
        $this->assertObjectId($objectId);

        return $this->put($objectId, null, $carrierAccount);
    }

    /**
     * Add a Shippo-managed carrier account.
     *
     * @param array{
     *     carrier?: string,
     *     metadata?: string
     * } $payload Registration payload for a Shippo carrier account.
     *
     * @return array<string, mixed> Registration response.
     */
    public function registerNew(array $payload): array
    {
        return $this->post(null, 'register/new', $payload);
    }

    /**
     * Connect an existing carrier account via OAuth 2.0.
     *
     * @param array{
     *     carrier?: string,
     *     redirect_uri?: string,
     *     metadata?: string
     * } $payload OAuth registration payload.
     *
     * @return array<string, mixed> OAuth connection response.
     */
    public function registerOauth(array $payload): array
    {
        return $this->post(null, 'register/oauth', $payload);
    }

    /**
     * Get the carrier registration status for a carrier account.
     *
     * @param string $objectId Shippo carrier account object ID.
     *
     * @return array<string, mixed> Registration status object.
     *
     * @throws \InvalidArgumentException When the object ID is empty.
     */
    public function getRegistrationStatus(string $objectId): array
    {
        $this->assertObjectId($objectId);

        return $this->get($objectId, 'register');
    }
}
