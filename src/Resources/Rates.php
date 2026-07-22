<?php

declare(strict_types=1);

namespace Bluehost\ShippoAPI\Resources;

/**
 * Shippo Rates API resource.
 *
 * Retrieves shipping rate objects by rate ID or lists rates for a shipment.
 * Base endpoints: `/rates/` and `/shipments/{id}/rates/`.
 *
 * @see https://docs.goshippo.com/api-reference/rates/
 */
final class Rates extends AbstractResource
{
    protected function resourcePath(): string
    {
        return 'rates';
    }

    /**
     * Retrieve a rate by object ID.
     *
     * Rates older than 390 days are not returned by the API.
     *
     * @param string $objectId Shippo rate object ID.
     *
     * @return array<string, mixed> Rate object.
     *
     * @throws \InvalidArgumentException When the object ID is empty.
     */
    public function retrieve(string $objectId): array
    {
        $this->assertObjectId($objectId);

        return $this->get($objectId);
    }

    /**
     * Retrieve all rates for a shipment.
     *
     * @param string $shipmentId Shippo shipment object ID.
     * @param array{
     *     page?: int,
     *     results?: int
     * } $query Pagination parameters.
     *
     * @return array<string, mixed> Paginated rates response for the shipment.
     *
     * @throws \InvalidArgumentException When the shipment ID is empty.
     */
    public function retrieveForShipment(string $shipmentId, array $query = []): array
    {
        $this->assertObjectId($shipmentId);

        return $this->httpClient->request(
            'GET',
            $this->requestBuilder->buildPath('shipments', $shipmentId, 'rates'),
            $query
        );
    }

    /**
     * Retrieve shipment rates converted to a specific currency.
     *
     * @param string $shipmentId Shippo shipment object ID.
     * @param string $currency   ISO 4217 currency code, e.g. "USD" or "EUR".
     * @param array{
     *     page?: int,
     *     results?: int
     * } $query Pagination parameters.
     *
     * @return array<string, mixed> Paginated rates response in the requested currency.
     *
     * @throws \InvalidArgumentException When the shipment ID or currency code is empty.
     */
    public function retrieveForShipmentInCurrency(string $shipmentId, string $currency, array $query = []): array
    {
        $this->assertObjectId($shipmentId);

        if ($currency === '') {
            throw new \InvalidArgumentException('Currency code cannot be empty.');
        }

        return $this->httpClient->request(
            'GET',
            $this->requestBuilder->buildPath('shipments', $shipmentId, 'rates/' . rawurlencode($currency)),
            $query
        );
    }
}
