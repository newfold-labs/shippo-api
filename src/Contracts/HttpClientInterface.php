<?php

declare(strict_types=1);

namespace Bluehost\ShippoAPI\Contracts;

/**
 * Contract for sending HTTP requests to the Shippo API.
 */
interface HttpClientInterface
{
    /**
     * Sends an HTTP request to the Shippo API.
     *
     * @param string                    $method  HTTP method (e.g. GET, POST).
     * @param string                    $path    API path relative to the base URL.
     * @param array<string, mixed>      $query   Query string parameters.
     * @param array<string, mixed>|null $body    JSON request body, or null for no body.
     * @param array<string, string>     $headers Additional HTTP headers.
     *
     * @return array<string, mixed> Decoded JSON response body.
     */
    public function request(
        string $method,
        string $path,
        array $query = [],
        ?array $body = null,
        array $headers = []
    ): array;
}
