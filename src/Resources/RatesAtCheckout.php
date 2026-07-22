<?php

declare(strict_types=1);

namespace Bluehost\ShippoAPI\Resources;

/**
 * Shippo Rates at Checkout (Live Rates) API resource.
 *
 * Generates live shipping rates for checkout flows and manages the default parcel
 * template used for live rate requests. Base endpoint: `/live-rates/`.
 *
 * @see https://docs.goshippo.com/api-reference/rates-at-checkout/
 */
final class RatesAtCheckout extends AbstractResource
{
    protected function resourcePath(): string
    {
        return 'live-rates';
    }

    /**
     * Generate a live rates request for checkout.
     *
     * @param array{
     *     address_from?: array<string, mixed>|string,
     *     address_to?: array<string, mixed>|string,
     *     line_items?: array<int, array<string, mixed>>,
     *     parcel?: array<string, mixed>|string,
     *     carrier_accounts?: array<int, string>,
     *     async?: bool
     * } $payload Live rates request payload.
     *
     * @return array<string, mixed> Live rates response.
     */
    public function generate(array $payload): array
    {
        return $this->post(null, null, $payload);
    }

    /**
     * Show the currently configured default parcel template for live rates.
     *
     * @return array<string, mixed> Default parcel template object.
     */
    public function showDefaultParcelTemplate(): array
    {
        return $this->get(null, 'default-parcel-template');
    }

    /**
     * Update the default parcel template for live rates.
     *
     * @param array{
     *     object_id?: string
     * } $payload Payload identifying the user parcel template to use as default.
     *
     * @return array<string, mixed> Updated default parcel template object.
     */
    public function updateDefaultParcelTemplate(array $payload): array
    {
        return $this->put(null, 'default-parcel-template', $payload);
    }

    /**
     * Clear the currently configured default parcel template for live rates.
     *
     * @return array<string, mixed> Deletion confirmation response.
     */
    public function clearDefaultParcelTemplate(): array
    {
        return parent::sendDelete(null, 'default-parcel-template');
    }
}
