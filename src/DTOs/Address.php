<?php

declare(strict_types=1);

namespace Bluehost\ShippoAPI\DTOs;

use Bluehost\ShippoAPI\DTOs\Concerns\ArraySerializable;

/**
 * Data transfer object representing a Shippo address.
 */
final class Address
{
    use ArraySerializable;

    /**
     * Creates a new address DTO.
     *
     * @param string|null              $name              Recipient full name.
     * @param string|null              $company           Company name.
     * @param string|null              $street1           Primary street address line.
     * @param string|null              $street2           Secondary street address line.
     * @param string|null              $street3           Tertiary street address line.
     * @param string|null              $streetNo          Street number.
     * @param string|null              $city              City name.
     * @param string|null              $state             State or province code.
     * @param string|null              $zip               Postal or ZIP code.
     * @param string|null              $country           ISO 3166-1 alpha-2 country code.
     * @param string|null              $phone             Contact phone number.
     * @param string|null              $email             Contact email address.
     * @param bool|null                $isResidential     Whether the address is residential.
     * @param bool|null                $validate          Whether to validate the address on create.
     * @param string|null              $objectId          Shippo object ID.
     * @param string|null              $objectOwner       Owner account identifier.
     * @param string|null              $objectCreated     ISO 8601 creation timestamp.
     * @param string|null              $objectUpdated     ISO 8601 last update timestamp.
     * @param bool|null                $isComplete        Whether the address passed validation.
     * @param array<string, mixed>|null $validationResults Address validation result details.
     * @param array<string, mixed>|null $metadata          Custom metadata key/value pairs.
     * @param bool|null                $test              Whether this is a test-mode address.
     */
    public function __construct(
        public ?string $name = null,
        public ?string $company = null,
        public ?string $street1 = null,
        public ?string $street2 = null,
        public ?string $street3 = null,
        public ?string $streetNo = null,
        public ?string $city = null,
        public ?string $state = null,
        public ?string $zip = null,
        public ?string $country = null,
        public ?string $phone = null,
        public ?string $email = null,
        public ?bool $isResidential = null,
        public ?bool $validate = null,
        public ?string $objectId = null,
        public ?string $objectOwner = null,
        public ?string $objectCreated = null,
        public ?string $objectUpdated = null,
        public ?bool $isComplete = null,
        public ?array $validationResults = null,
        public ?array $metadata = null,
        public ?bool $test = null
    ) {
    }

    /**
     * Normalizes an address value to an API-compatible array or object ID string.
     *
     * @param array<string, mixed>|self|string $value Address DTO, array, or object ID.
     *
     * @return array<string, mixed>|string Normalized address representation.
     */
    public static function normalize(array|self|string $value): array|string
    {
        if ($value instanceof self) {
            return $value->toArray();
        }

        return $value;
    }

    /**
     * @return array<string, mixed>
     */
    protected function serialize(): array
    {
        return [
            'name' => $this->name,
            'company' => $this->company,
            'street1' => $this->street1,
            'street2' => $this->street2,
            'street3' => $this->street3,
            'street_no' => $this->streetNo,
            'city' => $this->city,
            'state' => $this->state,
            'zip' => $this->zip,
            'country' => $this->country,
            'phone' => $this->phone,
            'email' => $this->email,
            'is_residential' => $this->isResidential,
            'validate' => $this->validate,
            'object_id' => $this->objectId,
            'object_owner' => $this->objectOwner,
            'object_created' => $this->objectCreated,
            'object_updated' => $this->objectUpdated,
            'is_complete' => $this->isComplete,
            'validation_results' => $this->validationResults,
            'metadata' => $this->metadata,
            'test' => $this->test,
        ];
    }
}
