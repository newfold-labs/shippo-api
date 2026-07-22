<?php

declare(strict_types=1);

namespace Bluehost\ShippoAPI\Resources;

/**
 * Shippo Embedded Authorization API resource.
 *
 * Generates JWT tokens for embedded and white-label integrations that authenticate
 * client-side applications with the Shippo API. Base endpoint: `/embedded/authz`.
 *
 * @see https://docs.goshippo.com/guides/authentication-using-jwt
 * @see https://docs.goshippo.com/shipping-elements/auth
 */
final class EmbeddedAuth extends AbstractResource
{
    protected function resourcePath(): string
    {
        return 'embedded/authz';
    }

    /**
     * Generate a JWT for embedded or white-label integrations.
     *
     * @param array{
     *     scope?: string,
     *     exp?: int
     * } $payload Authorization payload, e.g. `['scope' => 'embedded:carriers']`.
     * @param array<string, string> $extraHeaders Optional request headers, e.g.
     *                                            `['SHIPPO-ACCOUNT-ID' => '...']`.
     *
     * @return array<string, mixed> JWT authorization response.
     */
    public function authorize(array $payload, array $extraHeaders = []): array
    {
        return $this->httpClient->request(
            'POST',
            $this->requestBuilder->buildPath($this->resourcePath()),
            [],
            $payload,
            $extraHeaders
        );
    }

    /**
     * Generate a JWT for a managed Shippo account (platform / white-label).
     *
     * Sends the `SHIPPO-ACCOUNT-ID` header automatically for the given account.
     *
     * @param string $shippoAccountId Shippo managed account object ID.
     * @param array{
     *     scope?: string,
     *     exp?: int
     * } $payload Authorization payload.
     *
     * @return array<string, mixed> JWT authorization response.
     *
     * @throws \InvalidArgumentException When the Shippo account ID is empty.
     */
    public function authorizeForAccount(string $shippoAccountId, array $payload): array
    {
        $this->assertObjectId($shippoAccountId);

        return $this->authorize($payload, [
            'SHIPPO-ACCOUNT-ID' => $shippoAccountId,
        ]);
    }
}
