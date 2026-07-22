<?php

declare(strict_types=1);

namespace Bluehost\ShippoAPI\DTOs\Concerns;

/**
 * Trait for mapping snake_case API keys to camelCase property names.
 */
trait MapsApiKeys
{
    /**
     * Maps snake_case API keys to camelCase keys using the provided map.
     *
     * @param array<string, mixed> $data Source data with snake_case keys.
     * @param array<string, string> $map  Snake_case to camelCase key mapping.
     *
     * @return array<string, mixed> Data with camelCase keys.
     */
    protected static function mapSnakeCaseToCamelCase(array $data, array $map): array
    {
        $mapped = [];

        foreach ($map as $snake => $camel) {
            if (array_key_exists($snake, $data)) {
                $mapped[$camel] = $data[$snake];
            }
        }

        return $mapped;
    }
}
