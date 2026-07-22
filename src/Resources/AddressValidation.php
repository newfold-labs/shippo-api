<?php

declare(strict_types=1);

namespace Bluehost\ShippoAPI\Resources;

/**
 * Shippo Address Validation API resource (v2).
 *
 * Validates US and international addresses and returns recommended corrections.
 * Base endpoint: `/v2/addresses/validate`.
 *
 * @see https://docs.goshippo.com/api-reference/validation/validate-address
 */
final class AddressValidation extends AbstractResource
{
    protected function resourcePath(): string
    {
        return 'v2/addresses';
    }

    /**
     * Validate an address and return recommended corrections.
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
     *     is_residential?: bool
     * } $address Address fields to validate.
     *
     * @return array<string, mixed> Validation results with recommended address.
     */
    public function validate(array $address): array
    {
        return $this->post(null, 'validate', $address);
    }
}
