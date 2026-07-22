<?php

declare(strict_types=1);

namespace Bluehost\ShippoAPI\Reference;

/**
 * Common carrier parcel template tokens (PHP 8.0 compatible constants).
 *
 * @see https://docs.goshippo.com/api-reference/parcel-templates/
 */
final class ParcelTemplates
{
    // USPS
    /** @var string USPS flat rate envelope template token. */
    public const USPS_FLAT_RATE_ENVELOPE = 'USPS_FlatRateEnvelope';
    /** @var string USPS flat rate legal envelope template token. */
    public const USPS_FLAT_RATE_LEGAL_ENVELOPE = 'USPS_FlatRateLegalEnvelope';
    /** @var string USPS flat rate padded envelope template token. */
    public const USPS_FLAT_RATE_PADDED_ENVELOPE = 'USPS_FlatRatePaddedEnvelope';
    /** @var string USPS small flat rate box template token. */
    public const USPS_SMALL_FLAT_RATE_BOX = 'USPS_SmallFlatRateBox';
    /** @var string USPS medium flat rate box (variant 1) template token. */
    public const USPS_MEDIUM_FLAT_RATE_BOX_1 = 'USPS_MediumFlatRateBox1';
    /** @var string USPS medium flat rate box (variant 2) template token. */
    public const USPS_MEDIUM_FLAT_RATE_BOX_2 = 'USPS_MediumFlatRateBox2';
    /** @var string USPS large flat rate box template token. */
    public const USPS_LARGE_FLAT_RATE_BOX = 'USPS_LargeFlatRateBox';

    // FedEx
    /** @var string FedEx small box template token. */
    public const FEDEX_BOX_SMALL = 'FedEx_Box_Small_1';
    /** @var string FedEx medium box template token. */
    public const FEDEX_BOX_MEDIUM = 'FedEx_Box_Medium_1';
    /** @var string FedEx large box template token. */
    public const FEDEX_BOX_LARGE = 'FedEx_Box_Large_1';
    /** @var string FedEx envelope template token. */
    public const FEDEX_ENVELOPE = 'FedEx_Envelope';
    /** @var string FedEx pak template token. */
    public const FEDEX_PAK = 'FedEx_Pak';

    // UPS
    /** @var string UPS Express small box template token. */
    public const UPS_EXPRESS_BOX_SMALL = 'UPS_Express_Box_Small';
    /** @var string UPS Express medium box template token. */
    public const UPS_EXPRESS_BOX_MEDIUM = 'UPS_Express_Box_Medium';
    /** @var string UPS Express large box template token. */
    public const UPS_EXPRESS_BOX_LARGE = 'UPS_Express_Box_Large';
    /** @var string UPS Express envelope template token. */
    public const UPS_EXPRESS_ENVELOPE = 'UPS_Express_Envelope';

    private function __construct()
    {
    }

    /**
     * Returns all known parcel template tokens.
     *
     * @return array<int, string> Parcel template token values.
     */
    public static function all(): array
    {
        return [
            self::USPS_FLAT_RATE_ENVELOPE,
            self::USPS_FLAT_RATE_LEGAL_ENVELOPE,
            self::USPS_FLAT_RATE_PADDED_ENVELOPE,
            self::USPS_SMALL_FLAT_RATE_BOX,
            self::USPS_MEDIUM_FLAT_RATE_BOX_1,
            self::USPS_MEDIUM_FLAT_RATE_BOX_2,
            self::USPS_LARGE_FLAT_RATE_BOX,
            self::FEDEX_BOX_SMALL,
            self::FEDEX_BOX_MEDIUM,
            self::FEDEX_BOX_LARGE,
            self::FEDEX_ENVELOPE,
            self::FEDEX_PAK,
            self::UPS_EXPRESS_BOX_SMALL,
            self::UPS_EXPRESS_BOX_MEDIUM,
            self::UPS_EXPRESS_BOX_LARGE,
            self::UPS_EXPRESS_ENVELOPE,
        ];
    }

    /**
     * Checks whether a token is a known parcel template.
     *
     * @param string $token Parcel template token to validate.
     *
     * @return bool True when the token is recognized.
     */
    public static function isValid(string $token): bool
    {
        return in_array($token, self::all(), true);
    }
}
