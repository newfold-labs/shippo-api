<?php

declare(strict_types=1);

namespace Bluehost\ShippoAPI\Resources;

/**
 * Shippo Address Parser API resource.
 *
 * Parses a single unstructured address string into a structured address object.
 * Base endpoint: `/addresses/parse/`.
 *
 * @see https://docs.goshippo.com/api-reference/parser/parse-address
 */
final class AddressParser extends AbstractResource
{
    protected function resourcePath(): string
    {
        return 'addresses';
    }

    /**
     * Parse an address string into a structured address object.
     *
     * @param array{
     *     address?: string,
     *     name?: string,
     *     company?: string
     * } $payload Parser input; typically includes an `address` string to parse.
     *
     * @return array<string, mixed> Parsed address object.
     */
    public function parse(array $payload): array
    {
        return $this->post(null, 'parse', $payload);
    }
}
