<?php

declare(strict_types=1);

namespace Bluehost\ShippoAPI\Http;

/**
 * Builds URL paths and query strings for Shippo API requests.
 */
final class RequestBuilder
{
    /**
     * Builds a REST-style API path from resource segments.
     *
     * @param string      $resource Resource name (e.g. "shipments").
     * @param string|null $id       Optional resource object ID.
     * @param string|null $action   Optional sub-action (e.g. "rates").
     *
     * @return string Normalized API path with trailing slash.
     */
    public function buildPath(string $resource, ?string $id = null, ?string $action = null): string
    {
        $path = '/' . trim($resource, '/') . '/';

        if ($id !== null) {
            $path .= rawurlencode($id) . '/';
        }

        if ($action !== null) {
            $path .= trim($action, '/') . '/';
        }

        return $path;
    }

    /**
     * Filters and normalizes query parameters for the Shippo API.
     *
     * Null values are omitted. Boolean values are converted to "true"/"false".
     *
     * @param array<string, mixed> $query Raw query parameters.
     *
     * @return array<string, string|int|float|bool> Filtered query parameters.
     */
    public function buildQuery(array $query): array
    {
        $filtered = [];

        foreach ($query as $key => $value) {
            if ($value === null) {
                continue;
            }

            if (is_bool($value)) {
                $filtered[$key] = $value ? 'true' : 'false';
                continue;
            }

            if (is_scalar($value)) {
                $filtered[$key] = $value;
            }
        }

        return $filtered;
    }
}
