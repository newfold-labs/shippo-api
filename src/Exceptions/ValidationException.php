<?php

declare(strict_types=1);

namespace Bluehost\ShippoAPI\Exceptions;

/**
 * Thrown when the Shippo API returns HTTP 400 or 422 (validation error).
 *
 * Indicates the request payload or parameters failed server-side validation.
 */
class ValidationException extends ShippoException
{
}
