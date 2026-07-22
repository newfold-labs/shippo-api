<?php

declare(strict_types=1);

namespace Bluehost\ShippoAPI\Resources;

use InvalidArgumentException;
use Bluehost\ShippoAPI\Concerns\ExpandableQuery;
use Bluehost\ShippoAPI\Contracts\HttpClientInterface;
use Bluehost\ShippoAPI\Http\RequestBuilder;
use Bluehost\ShippoAPI\Pagination\PaginatedResponse;

/**
 * Base class for Shippo API resource clients.
 *
 * Provides shared HTTP helpers (GET, POST, PUT, DELETE) and pagination utilities
 * used by concrete resource classes. Each subclass defines its own API path via
 * {@see resourcePath()}.
 *
 * @see https://docs.goshippo.com/api-reference/overview
 */
abstract class AbstractResource
{
    use ExpandableQuery;

    /** @var RequestBuilder */
    protected $requestBuilder;

    /** @var HttpClientInterface */
    protected $httpClient;

    /**
     * Create a new resource client bound to the given HTTP adapter.
     *
     * @param HttpClientInterface $httpClient HTTP client used for API requests.
     */
    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
        $this->requestBuilder = new RequestBuilder();
    }

    /**
     * Return the base API path segment for this resource (without leading or trailing slashes).
     *
     * @return string Resource path segment, e.g. "addresses" or "customs/declarations".
     */
    abstract protected function resourcePath(): string;

    /**
     * Send a GET request to this resource.
     *
     * Builds a path from {@see resourcePath()}, an optional object ID, and an optional action.
     *
     * @param string|null          $id     Object ID appended to the resource path, or null for collection.
     * @param string|null          $action Sub-resource action, e.g. "validate".
     * @param array<string, mixed> $query  Query string parameters.
     *
     * @return array<string, mixed> Decoded JSON response body.
     */
    protected function get(?string $id = null, ?string $action = null, array $query = []): array
    {

        return $this->httpClient->request(
            'GET',
            $this->requestBuilder->buildPath($this->resourcePath(), $id, $action),
            $query
        );
    }

    /**
     * Send a POST request to this resource.
     *
     * @param string|null          $id     Object ID appended to the resource path, or null for collection.
     * @param string|null          $action Sub-resource action, e.g. "purchase".
     * @param array<string, mixed> $body   JSON request body.
     * @param array<string, mixed> $query  Query string parameters.
     *
     * @return array<string, mixed> Decoded JSON response body.
     */
    protected function post(?string $id = null, ?string $action = null, array $body = [], array $query = []): array
    {
        return $this->httpClient->request(
            'POST',
            $this->requestBuilder->buildPath($this->resourcePath(), $id, $action),
            $query,
            $body
        );
    }

    /**
     * Send a PUT request to this resource.
     *
     * @param string|null          $id     Object ID appended to the resource path.
     * @param string|null          $action Sub-resource action.
     * @param array<string, mixed> $body   JSON request body.
     * @param array<string, mixed> $query  Query string parameters.
     *
     * @return array<string, mixed> Decoded JSON response body.
     */
    protected function put(?string $id = null, ?string $action = null, array $body = [], array $query = []): array
    {
        return $this->httpClient->request(
            'PUT',
            $this->requestBuilder->buildPath($this->resourcePath(), $id, $action),
            $query,
            $body
        );
    }

    /**
     * Send a DELETE request to this resource.
     *
     * @param string|null          $id     Object ID appended to the resource path.
     * @param string|null          $action Sub-resource action.
     * @param array<string, mixed> $query  Query string parameters.
     *
     * @return array<string, mixed> Decoded JSON response body.
     */
    protected function sendDelete(?string $id = null, ?string $action = null, array $query = []): array
    {
        return $this->httpClient->request(
            'DELETE',
            $this->requestBuilder->buildPath($this->resourcePath(), $id, $action),
            $query
        );
    }

    /**
     * List all objects for this resource with optional pagination filters.
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
    protected function list(array $query = []): PaginatedResponse
    {
        $response = $this->get(null, null, $query);

        return $this->toPaginatedResponse($response);
    }

    /**
     * Convert a Shippo list API response into a {@see PaginatedResponse}.
     *
     * @param array<string, mixed> $response Raw list response from the API.
     *
     * @return PaginatedResponse<array<string, mixed>>
     */
    protected function toPaginatedResponse(array $response): PaginatedResponse
    {
        $results = $response['results'] ?? [];

        if (! is_array($results)) {
            $results = [];
        }

        /** @var PaginatedResponse<array<string, mixed>> $paginated */
        $paginated = new PaginatedResponse(
            $results,
            isset($response['next']) && is_string($response['next']) ? $response['next'] : null,
            isset($response['previous']) && is_string($response['previous']) ? $response['previous'] : null,
            isset($response['count']) && is_int($response['count']) ? $response['count'] : null
        );

        return $paginated;
    }

    /**
     * Assert that a Shippo object ID is non-empty before building a request path.
     *
     * @param string $objectId Shippo object ID (e.g. address, shipment, or transaction ID).
     *
     * @throws \InvalidArgumentException When the object ID is an empty string.
     */
    protected function assertObjectId(string $objectId): void
    {
        if ($objectId === '') {
            throw new InvalidArgumentException('Object ID cannot be empty.');
        }
    }
}
