<?php

declare(strict_types=1);

namespace Bluehost\ShippoAPI\Exceptions;

/**
 * Thrown when the Shippo API returns an unexpected error response.
 *
 * Used for HTTP status codes other than 400, 401, 422, and 429 (e.g. 404, 500).
 */
class ApiException extends ShippoException
{
}
