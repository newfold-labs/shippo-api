<?php

declare(strict_types=1);

namespace Bluehost\ShippoAPI\DTOs\Concerns;

/**
 * Trait providing array serialization and deserialization for DTOs.
 */
trait ArraySerializable
{
    /**
     * Creates a DTO instance from an API response array.
     *
     * @param array<string, mixed> $data Decoded API data.
     *
     * @return static New DTO instance.
     */
    public static function fromArray(array $data): static
    {
        return new static(...static::mapFromArray($data));
    }

    /**
     * Converts the DTO to an API request array, omitting null values.
     *
     * @return array<string, mixed> Serialized data with snake_case keys.
     */
    public function toArray(): array
    {
        return array_filter(
            $this->serialize(),
            static fn (mixed $value): bool => $value !== null
        );
    }

    /**
     * Maps API array data to constructor arguments.
     *
     * @param array<string, mixed> $data Decoded API data.
     *
     * @return array<string, mixed> Constructor argument map.
     */
    protected static function mapFromArray(array $data): array
    {
        return $data;
    }

    /**
     * Serializes DTO properties to API snake_case keys.
     *
     * @return array<string, mixed> Raw serialized data.
     */
    abstract protected function serialize(): array;
}
