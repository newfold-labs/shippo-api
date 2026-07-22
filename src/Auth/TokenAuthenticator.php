<?php

declare(strict_types=1);

namespace Bluehost\ShippoAPI\Auth;

use InvalidArgumentException;
use Bluehost\ShippoAPI\Contracts\AuthenticatorInterface;

/**
 * Authenticates Shippo API requests using a ShippoToken API key.
 */
final class TokenAuthenticator implements AuthenticatorInterface
{
    /**
     * Creates a new token authenticator.
     *
     * @param string $apiToken Shippo API token.
     *
     * @throws \InvalidArgumentException When the API token is empty.
     */
    public function __construct(
        private string $apiToken
    ) {
        if ($apiToken === '') {
            throw new InvalidArgumentException('API token cannot be empty.');
        }
    }

    /**
     * Returns the Authorization header for ShippoToken authentication.
     *
     * @return array<string, string> Header name/value pairs.
     */
    public function getHeaders(): array
    {
        return [
            'Authorization' => 'ShippoToken ' . $this->apiToken,
        ];
    }
}
