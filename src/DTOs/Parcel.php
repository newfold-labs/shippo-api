<?php

declare(strict_types=1);

namespace Bluehost\ShippoAPI\DTOs;

use Bluehost\ShippoAPI\DTOs\Concerns\ArraySerializable;

/**
 * Data transfer object representing a Shippo parcel.
 */
final class Parcel
{
    use ArraySerializable;

    /**
     * Creates a new parcel DTO.
     *
     * @param string|null              $length        Parcel length.
     * @param string|null              $width         Parcel width.
     * @param string|null              $height        Parcel height.
     * @param string|null              $distanceUnit  Distance unit (e.g. "in", "cm").
     * @param string|null              $weight        Parcel weight.
     * @param string|null              $massUnit      Mass unit (e.g. "lb", "kg").
     * @param string|null              $template      Carrier parcel template token.
     * @param string|null              $objectId      Shippo object ID.
     * @param string|null              $objectOwner   Owner account identifier.
     * @param string|null              $objectCreated ISO 8601 creation timestamp.
     * @param string|null              $objectUpdated ISO 8601 last update timestamp.
     * @param array<string, mixed>|null $metadata     Custom metadata key/value pairs.
     */
    public function __construct(
        public ?string $length = null,
        public ?string $width = null,
        public ?string $height = null,
        public ?string $distanceUnit = null,
        public ?string $weight = null,
        public ?string $massUnit = null,
        public ?string $template = null,
        public ?string $objectId = null,
        public ?string $objectOwner = null,
        public ?string $objectCreated = null,
        public ?string $objectUpdated = null,
        public ?array $metadata = null
    ) {
    }

    /**
     * Normalizes a parcel value to an API-compatible array or object ID string.
     *
     * @param array<string, mixed>|self|string $value Parcel DTO, array, or object ID.
     *
     * @return array<string, mixed>|string Normalized parcel representation.
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
            'length' => $this->length,
            'width' => $this->width,
            'height' => $this->height,
            'distance_unit' => $this->distanceUnit,
            'weight' => $this->weight,
            'mass_unit' => $this->massUnit,
            'template' => $this->template,
            'object_id' => $this->objectId,
            'object_owner' => $this->objectOwner,
            'object_created' => $this->objectCreated,
            'object_updated' => $this->objectUpdated,
            'metadata' => $this->metadata,
        ];
    }
}
