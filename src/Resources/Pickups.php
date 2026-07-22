<?php

declare(strict_types=1);

namespace Bluehost\ShippoAPI\Resources;

/**
 * Shippo Pickups API resource.
 *
 * Schedules carrier pickups at a specified location. Base endpoint: `/pickups/`.
 *
 * @see https://docs.goshippo.com/api-reference/pickups/create-a-pickup
 */
final class Pickups extends AbstractResource
{
    protected function resourcePath(): string
    {
        return 'pickups';
    }

    /**
     * Create a pickup request for a carrier to collect packages.
     *
     * @param array{
     *     carrier_account?: string,
     *     location?: array{
     *         building_location_type?: string,
     *         building_type?: string,
     *         instructions?: string,
     *         address?: array<string, mixed>|string
     *     },
     *     transactions?: array<int, string>,
     *     requested_start_time?: string,
     *     requested_end_time?: string,
     *     is_test?: bool,
     *     metadata?: string
     * } $pickup Pickup request fields.
     *
     * @return array<string, mixed> Created pickup object.
     */
    public function create(array $pickup): array
    {
        return $this->post(null, null, $pickup);
    }
}
