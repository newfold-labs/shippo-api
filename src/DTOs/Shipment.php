<?php

declare(strict_types=1);

namespace Bluehost\ShippoAPI\DTOs;

use Bluehost\ShippoAPI\DTOs\Concerns\ArraySerializable;

/**
 * Data transfer object representing a Shippo shipment.
 */
final class Shipment
{
    use ArraySerializable;

    /**
     * Creates a new shipment DTO.
     *
     * @param array<string, mixed>|Address|string|null $addressFrom        Sender address DTO, array, or object ID.
     * @param array<string, mixed>|Address|string|null $addressTo          Recipient address DTO, array, or object ID.
     * @param array<string, mixed>|Address|string|null $addressReturn      Return address DTO, array, or object ID.
     * @param array<int, array<string, mixed>|Parcel|string>|null $parcels Parcel DTOs, arrays, or object IDs.
     * @param bool|null                              $async              Whether to create the shipment asynchronously.
     * @param array<string, mixed>|null              $customsDeclaration Customs declaration object data or ID.
     * @param string|null                            $carrierAccounts    Comma-separated carrier account object IDs.
     * @param array<string, mixed>|null              $extra              Extra shipment options (insurance, signature, etc.).
     * @param array<string, mixed>|null              $metadata           Custom metadata key/value pairs.
     * @param string|null                            $objectId           Shippo object ID.
     * @param string|null                            $objectOwner        Owner account identifier.
     * @param string|null                            $objectCreated      ISO 8601 creation timestamp.
     * @param string|null                            $objectUpdated      ISO 8601 last update timestamp.
     * @param string|null                            $status             Shipment status (e.g. "SUCCESS").
     * @param array<int, array<string, mixed>>|null  $rates              Available rates returned by the API.
     * @param array<int, array<string, mixed>>|null  $messages           Warning or error messages from carriers.
     */
    public function __construct(
        public Address|array|string|null $addressFrom = null,
        public Address|array|string|null $addressTo = null,
        public Address|array|string|null $addressReturn = null,
        public ?array $parcels = null,
        public ?bool $async = null,
        public ?array $customsDeclaration = null,
        public ?string $carrierAccounts = null,
        public ?array $extra = null,
        public ?array $metadata = null,
        public ?string $objectId = null,
        public ?string $objectOwner = null,
        public ?string $objectCreated = null,
        public ?string $objectUpdated = null,
        public ?string $status = null,
        public ?array $rates = null,
        public ?array $messages = null
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    protected function serialize(): array
    {
        return [
            'address_from' => $this->addressFrom !== null ? Address::normalize($this->addressFrom) : null,
            'address_to' => $this->addressTo !== null ? Address::normalize($this->addressTo) : null,
            'address_return' => $this->addressReturn !== null ? Address::normalize($this->addressReturn) : null,
            'parcels' => $this->serializeParcels(),
            'async' => $this->async,
            'customs_declaration' => $this->customsDeclaration,
            'carrier_accounts' => $this->carrierAccounts,
            'extra' => $this->extra,
            'metadata' => $this->metadata,
            'object_id' => $this->objectId,
            'object_owner' => $this->objectOwner,
            'object_created' => $this->objectCreated,
            'object_updated' => $this->objectUpdated,
            'status' => $this->status,
            'rates' => $this->rates,
            'messages' => $this->messages,
        ];
    }

    /**
     * @return array<int, array<string, mixed>|string>|null
     */
    private function serializeParcels(): ?array
    {
        if ($this->parcels === null) {
            return null;
        }

        return array_map(
            static fn (array|Parcel|string $parcel): array|string => Parcel::normalize($parcel),
            $this->parcels
        );
    }
}
