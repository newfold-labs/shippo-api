<?php

declare(strict_types=1);

namespace Bluehost\ShippoAPI\Exceptions;

/**
 * Thrown when the Shippo API returns HTTP 429 (too many requests).
 *
 * May include a Retry-After value indicating when the request can be retried.
 */
class RateLimitException extends ShippoException
{
    /**
     * Creates a new rate limit exception.
     *
     * @param string                    $message      Human-readable error message.
     * @param int                       $code         HTTP status code (typically 429).
     * @param \Throwable|null           $previous     Previous exception, if any.
     * @param array<string, mixed>|null $responseBody Decoded API error response body.
     * @param int|null                  $retryAfter   Seconds to wait before retrying, from Retry-After header.
     */
    public function __construct(
        string $message = 'Rate limit exceeded.',
        int $code = 429,
        ?\Throwable $previous = null,
        ?array $responseBody = null,
        private ?int $retryAfter = null
    ) {
        parent::__construct($message, $code, $previous, $responseBody);
    }

    /**
     * Returns the number of seconds to wait before retrying, if provided.
     *
     * @return int|null Retry-after value in seconds.
     */
    public function getRetryAfter(): ?int
    {
        return $this->retryAfter;
    }
}
