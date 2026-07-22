<?php

declare(strict_types=1);

namespace Bluehost\ShippoAPI\Resources;

use Bluehost\ShippoAPI\Pagination\PaginatedResponse;

/**
 * Shippo Carrier Parcel Templates API resource.
 *
 * Lists and retrieves predefined parcel templates with preset dimensions for
 * supported carriers. Base endpoint: `/carrier-parcel-templates/`.
 *
 * @see https://docs.goshippo.com/api-reference/carrier-parcel-templates/
 * @see https://docs.goshippo.com/api-reference/parcel-templates/
 */
final class CarrierParcelTemplates extends AbstractResource
{
    protected function resourcePath(): string
    {
        return 'carrier-parcel-templates';
    }

    /**
     * List all carrier parcel template objects.
     *
     * @param array{
     *     page?: int,
     *     results?: int
     * } $query Pagination parameters.
     *
     * @return PaginatedResponse<array<string, mixed>>
     */
    public function list(array $query = []): PaginatedResponse
    {
        return parent::list($query);
    }

    /**
     * Retrieve a carrier parcel template by token.
     *
     * @param string $token Carrier parcel template token.
     *
     * @return array<string, mixed> Carrier parcel template object.
     *
     * @throws \InvalidArgumentException When the token is empty.
     */
    public function retrieve(string $token): array
    {
        $this->assertObjectId($token);

        return $this->get($token);
    }
}
