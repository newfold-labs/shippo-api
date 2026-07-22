<?php

declare(strict_types=1);

namespace Bluehost\ShippoAPI\Exceptions;

use Exception;
use Throwable;

/**
 * Base exception for all Shippo library errors.
 *
 * Thrown when an HTTP transport failure occurs or when the response body
 * cannot be decoded. More specific subclasses are used for API error responses.
 */
class ShippoException extends Exception
{
    /**
     * Creates a new Shippo exception.
     *
     * @param string               $message      Human-readable error message.
     * @param int                  $code         HTTP status code or error code.
     * @param Throwable|null       $previous     Previous exception, if any.
     * @param array<string, mixed>|null $responseBody Decoded API error response body.
     */
    public function __construct(
        string $message = '',
        int $code = 0,
        ?Throwable $previous = null,
        private ?array $responseBody = null
    ) {
        parent::__construct($message, $code, $previous);
    }

    /**
     * Returns the decoded API error response body, if available.
     *
     * @return array<string, mixed>|null Response body.
     */
    public function getResponseBody(): ?array
    {
        return $this->responseBody;
    }
}
