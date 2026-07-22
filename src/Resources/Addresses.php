<?php

declare(strict_types=1);

namespace Bluehost\ShippoAPI\Resources;

use Bluehost\ShippoAPI\DTOs\Address;
use Bluehost\ShippoAPI\Pagination\PaginatedResponse;

/**
 * Shippo Addresses and Address Book API resource.
 *
 * Manages address objects used for shipments, rate quotes, and orders, as well as
 * persistent address book entries. Base endpoint: `/addresses/`.
 *
 * @see https://docs.goshippo.com/api-reference/addresses/
 * @see https://docs.goshippo.com/api-reference/address-book/
 */
final class Addresses extends AbstractResource
{
    protected function resourcePath(): string
    {
        return 'addresses';
    }

    /**
     * Create a new address object.
     *
     * @param array{
     *     name?: string,
     *     company?: string,
     *     street1?: string,
     *     street2?: string,
     *     street3?: string,
     *     street_no?: string,
     *     city?: string,
     *     state?: string,
     *     zip?: string,
     *     country?: string,
     *     phone?: string,
     *     email?: string,
     *     is_residential?: bool,
     *     validate?: bool,
     *     metadata?: array<string, mixed>
     * }|Address $address Address fields or an {@see Address} DTO.
     *
     * @return array<string, mixed> Created address object.
     */
    public function create(Address|array $address): array
    {
        $body = $address instanceof Address ? $address->toArray() : $address;

        return $this->post(null, null, $body);
    }

    /**
     * List all address objects for this account.
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
     * Retrieve an existing address by object ID.
     *
     * @param string $objectId Shippo address object ID.
     *
     * @return array<string, mixed> Address object.
     *
     * @throws \InvalidArgumentException When the object ID is empty.
     */
    public function retrieve(string $objectId): array
    {
        $this->assertObjectId($objectId);

        return $this->get($objectId);
    }

    /**
     * Validate an existing address by object ID.
     *
     * @param string $objectId Shippo address object ID.
     *
     * @return array<string, mixed> Address object with validation results.
     *
     * @throws \InvalidArgumentException When the object ID is empty.
     */
    public function validate(string $objectId): array
    {
        $this->assertObjectId($objectId);

        return $this->get($objectId, 'validate');
    }

    /**
     * Update an existing address book entry.
     *
     * @param string $objectId Shippo address object ID.
     * @param array{
     *     name?: string,
     *     company?: string,
     *     street1?: string,
     *     street2?: string,
     *     street3?: string,
     *     street_no?: string,
     *     city?: string,
     *     state?: string,
     *     zip?: string,
     *     country?: string,
     *     phone?: string,
     *     email?: string,
     *     is_residential?: bool,
     *     metadata?: array<string, mixed>
     * }|Address $address Updated address fields or an {@see Address} DTO.
     *
     * @return array<string, mixed> Updated address object.
     *
     * @throws \InvalidArgumentException When the object ID is empty.
     */
    public function update(string $objectId, Address|array $address): array
    {
        $this->assertObjectId($objectId);
        $body = $address instanceof Address ? $address->toArray() : $address;

        return $this->put($objectId, null, $body);
    }

    /**
     * Delete an address book entry.
     *
     * @param string $objectId Shippo address object ID.
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
