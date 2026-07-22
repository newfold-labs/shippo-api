<?php

declare(strict_types=1);

namespace Bluehost\ShippoAPI\DTOs;

use Bluehost\ShippoAPI\DTOs\Concerns\ArraySerializable;
use Bluehost\ShippoAPI\DTOs\Concerns\MapsApiKeys;

/**
 * Data transfer object representing a Shippo shipping rate.
 */
final class Rate
{
    use ArraySerializable;
    use MapsApiKeys;

    /**
     * Creates a new rate DTO.
     *
     * @param string|null          $objectId         Shippo object ID.
     * @param string|null          $objectCreated    ISO 8601 creation timestamp.
     * @param string|null          $objectOwner      Owner account identifier.
     * @param string|null          $shipment         Associated shipment object ID.
     * @param string|null          $amount           Rate amount in the rate currency.
     * @param string|null          $currency         ISO currency code for the rate amount.
     * @param string|null          $amountLocal      Rate amount in local currency.
     * @param string|null          $currencyLocal    ISO currency code for the local amount.
     * @param string|null          $provider         Carrier provider name.
     * @param string|null          $providerImage75  URL to 75px provider logo.
     * @param string|null          $providerImage200 URL to 200px provider logo.
     * @param string|null          $servicelevelName Human-readable service level name.
     * @param string|null          $servicelevelToken Service level token identifier.
     * @param int|null             $estimatedDays    Estimated transit days.
     * @param bool|null            $arrivesBy        Whether a delivery time is guaranteed.
     * @param bool|null            $durationTerms    Duration terms flag from the carrier.
     * @param array<int, string>|null $attributes    Rate attribute flags (e.g. "CHEAPEST").
     * @param string|null          $carrierAccount   Carrier account object ID.
     * @param bool|null            $test             Whether this is a test-mode rate.
     * @param string|null          $zone             Shipping zone identifier.
     */
    public function __construct(
        public ?string $objectId = null,
        public ?string $objectCreated = null,
        public ?string $objectOwner = null,
        public ?string $shipment = null,
        public ?string $amount = null,
        public ?string $currency = null,
        public ?string $amountLocal = null,
        public ?string $currencyLocal = null,
        public ?string $provider = null,
        public ?string $providerImage75 = null,
        public ?string $providerImage200 = null,
        public ?string $servicelevelName = null,
        public ?string $servicelevelToken = null,
        public ?int $estimatedDays = null,
        public ?bool $arrivesBy = null,
        public ?bool $durationTerms = null,
        public ?array $attributes = null,
        public ?string $carrierAccount = null,
        public ?bool $test = null,
        public ?string $zone = null
    ) {
    }

    /**
     * @param array<string, mixed> $data
     *
     * @return array<string, mixed>
     */
    protected static function mapFromArray(array $data): array
    {
        return self::mapSnakeCaseToCamelCase($data, [
            'object_id' => 'objectId',
            'object_created' => 'objectCreated',
            'object_owner' => 'objectOwner',
            'shipment' => 'shipment',
            'amount' => 'amount',
            'currency' => 'currency',
            'amount_local' => 'amountLocal',
            'currency_local' => 'currencyLocal',
            'provider' => 'provider',
            'provider_image_75' => 'providerImage75',
            'provider_image_200' => 'providerImage200',
            'servicelevel_name' => 'servicelevelName',
            'servicelevel_token' => 'servicelevelToken',
            'estimated_days' => 'estimatedDays',
            'arrives_by' => 'arrivesBy',
            'duration_terms' => 'durationTerms',
            'attributes' => 'attributes',
            'carrier_account' => 'carrierAccount',
            'test' => 'test',
            'zone' => 'zone',
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    protected function serialize(): array
    {
        return [
            'object_id' => $this->objectId,
            'object_created' => $this->objectCreated,
            'object_owner' => $this->objectOwner,
            'shipment' => $this->shipment,
            'amount' => $this->amount,
            'currency' => $this->currency,
            'amount_local' => $this->amountLocal,
            'currency_local' => $this->currencyLocal,
            'provider' => $this->provider,
            'provider_image_75' => $this->providerImage75,
            'provider_image_200' => $this->providerImage200,
            'servicelevel_name' => $this->servicelevelName,
            'servicelevel_token' => $this->servicelevelToken,
            'estimated_days' => $this->estimatedDays,
            'arrives_by' => $this->arrivesBy,
            'duration_terms' => $this->durationTerms,
            'attributes' => $this->attributes,
            'carrier_account' => $this->carrierAccount,
            'test' => $this->test,
            'zone' => $this->zone,
        ];
    }
}
