<?php

declare(strict_types=1);

namespace Bluehost\ShippoAPI\Exceptions;

/**
 * Thrown when the Shippo API returns HTTP 401 (unauthorized).
 *
 * Indicates an invalid, expired, or missing API token or JWT.
 */
class AuthenticationException extends ShippoException
{
}
