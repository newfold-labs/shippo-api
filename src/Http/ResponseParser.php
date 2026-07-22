<?php

declare(strict_types=1);

namespace Bluehost\ShippoAPI\Http;

use JsonException;
use Bluehost\ShippoAPI\Exceptions\ApiException;
use Bluehost\ShippoAPI\Exceptions\AuthenticationException;
use Bluehost\ShippoAPI\Exceptions\RateLimitException;
use Bluehost\ShippoAPI\Exceptions\ShippoException;
use Bluehost\ShippoAPI\Exceptions\ValidationException;

/**
 * Parses Shippo API HTTP responses and maps error status codes to exceptions.
 */
final class ResponseParser
{
    private const JSON_MAX_DEPTH = 512;

    /**
     * Decodes a JSON response body and throws on non-success status codes.
     *
     * @param string      $body        Raw response body.
     * @param int         $statusCode  HTTP status code.
     * @param string|null $retryAfter  Retry-After header value, if present.
     *
     * @return array<string, mixed> Decoded response body.
     *
     * @throws AuthenticationException When HTTP 401 is returned.
     * @throws RateLimitException      When HTTP 429 is returned.
     * @throws ValidationException     When HTTP 400 or 422 is returned.
     * @throws ApiException            When any other error status is returned.
     * @throws ShippoException         When the response body is not valid JSON.
     */
    public function parse(string $body, int $statusCode, ?string $retryAfter = null): array
    {
        $decoded = $this->decodeJson($body);

        if ($statusCode >= 200 && $statusCode < 300) {
            return $decoded;
        }

        $this->throwForStatus($statusCode, $decoded, $retryAfter);

        return $decoded;
    }

    /**
     * @return array<string, mixed>
     */
    private function decodeJson(string $body): array
    {
        if ($body === '') {
            return [];
        }

        try {
            $decoded = json_decode($body, true, self::JSON_MAX_DEPTH, JSON_THROW_ON_ERROR);
        } catch (JsonException $exception) {
            throw new ShippoException('Failed to decode Shippo API response.', 0, $exception);
        }

        if (! is_array($decoded)) {
            throw new ShippoException('Shippo API response must be a JSON object.');
        }

        return $decoded;
    }

    /**
     * @param array<string, mixed> $body
     */
    private function throwForStatus(int $statusCode, array $body, ?string $retryAfter): void
    {
        $message = $this->extractErrorMessage($body);

        match (true) {
            $statusCode === 401 => throw new AuthenticationException($message, $statusCode, null, $body),
            $statusCode === 429 => throw new RateLimitException(
                $message,
                $statusCode,
                null,
                $body,
                $retryAfter !== null ? (int) $retryAfter : null
            ),
            $statusCode === 400, $statusCode === 422 => throw new ValidationException(
                $message,
                $statusCode,
                null,
                $body
            ),
            default => throw new ApiException($message, $statusCode, null, $body),
        };
    }

    /**
     * @param array<string, mixed> $body
     */
    private function extractErrorMessage(array $body): string
    {
        foreach (['detail', 'message', 'error'] as $key) {
            if (isset($body[$key]) && is_string($body[$key]) && $body[$key] !== '') {
                return $body[$key];
            }
        }

        return 'Shippo API request failed.';
    }
}
