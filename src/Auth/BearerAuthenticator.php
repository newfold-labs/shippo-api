<?php

declare(strict_types=1);

namespace Bluehost\ShippoAPI\Auth;

use InvalidArgumentException;
use Bluehost\ShippoAPI\Contracts\AuthenticatorInterface;

/**
 * Authenticates Shippo API requests using a standard Bearer token.
 */
final class BearerAuthenticator implements AuthenticatorInterface
{
    /** @var string */
    private $bearerToken;

    /**
     * Creates a new bearer token authenticator.
     *
     * @param string $bearerToken Bearer token value.
     *
     * @throws \InvalidArgumentException When the bearer token is empty.
     */
    public function __construct(string $bearerToken)
    {
        if ($bearerToken === '') {
            throw new InvalidArgumentException('Bearer token cannot be empty.');
        }

        $this->bearerToken = $bearerToken;
    }

    /**
     * Returns the Authorization header for Bearer authentication.
     *
     * @return array<string, string> Header name/value pairs.
     */
    public function getHeaders(): array
    {
        return [
            'Authorization' => 'Bearer ' . $this->bearerToken,
        ];
    }
}
