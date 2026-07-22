<?php

declare(strict_types=1);

namespace Bluehost\ShippoAPI\Auth;

use InvalidArgumentException;
use Bluehost\ShippoAPI\Contracts\AuthenticatorInterface;

/**
 * Authenticates Shippo API requests using a JWT bearer token.
 *
 * Used for embedded auth and other JWT-based Shippo flows.
 */
final class JwtAuthenticator implements AuthenticatorInterface
{
    /** @var string */
    private $jwtToken;

    /**
     * Creates a new JWT authenticator.
     *
     * @param string $jwtToken JWT bearer token.
     *
     * @throws \InvalidArgumentException When the JWT token is empty.
     */
    public function __construct(string $jwtToken)
    {
        if ($jwtToken === '') {
            throw new InvalidArgumentException('JWT token cannot be empty.');
        }

        $this->jwtToken = $jwtToken;
    }

    /**
     * Returns the Authorization header for JWT authentication.
     *
     * @return array<string, string> Header name/value pairs.
     */
    public function getHeaders(): array
    {
        return [
            'Authorization' => 'JWT ' . $this->jwtToken,
        ];
    }
}
