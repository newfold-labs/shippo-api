<?php

declare(strict_types=1);

namespace Bluehost\ShippoAPI\Contracts;

/**
 * Contract for providing authentication headers to Shippo API requests.
 */
interface AuthenticatorInterface
{
    /**
     * Returns HTTP headers required for API authentication.
     *
     * @return array<string, string> Header name/value pairs.
     */
    public function getHeaders(): array;
}
