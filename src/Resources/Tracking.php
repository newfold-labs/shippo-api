<?php

declare(strict_types=1);

namespace Bluehost\ShippoAPI\Resources;

use InvalidArgumentException;

/**
 * Shippo Tracking API resource.
 *
 * Retrieves tracking status for shipments and registers tracking webhooks for
 * carrier updates. Base endpoint: `/tracks/`.
 *
 * @see https://docs.goshippo.com/api-reference/tracking-status/
 */
final class Tracking extends AbstractResource
{
    protected function resourcePath(): string
    {
        return 'tracks';
    }

    /**
     * Get the tracking status for a carrier and tracking number.
     *
     * @param string $carrier        Carrier token, e.g. "usps" or "ups".
     * @param string $trackingNumber Carrier tracking number.
     *
     * @return array<string, mixed> Tracking status object with tracking history.
     *
     * @throws \InvalidArgumentException When the carrier or tracking number is empty.
     */
    public function retrieveStatus(string $carrier, string $trackingNumber): array
    {
        if ($carrier === '') {
            throw new InvalidArgumentException('Carrier cannot be empty.');
        }

        if ($trackingNumber === '') {
            throw new InvalidArgumentException('Tracking number cannot be empty.');
        }

        $path = '/tracks/' . rawurlencode($carrier) . '/' . rawurlencode($trackingNumber) . '/';

        return $this->httpClient->request('GET', $path);
    }

    /**
     * Register a tracking webhook for carrier status updates.
     *
     * @param array{
     *     carrier?: string,
     *     tracking_number?: string,
     *     metadata?: string
     * } $payload Tracking webhook registration fields.
     *
     * @return array<string, mixed> Registered tracking object.
     */
    public function registerWebhook(array $payload): array
    {
        return $this->post(null, null, $payload);
    }
}
