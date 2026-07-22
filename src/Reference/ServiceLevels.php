<?php

declare(strict_types=1);

namespace Bluehost\ShippoAPI\Reference;

/**
 * Common Shippo service level tokens (PHP 8.0 compatible constants).
 *
 * @see https://docs.goshippo.com/api-reference/service-levels/
 */
final class ServiceLevels
{
    // USPS
    /** @var string USPS Priority Mail service level token. */
    public const USPS_PRIORITY = 'usps_priority';
    /** @var string USPS Priority Express service level token. */
    public const USPS_PRIORITY_EXPRESS = 'usps_priority_express';
    /** @var string USPS Priority Mail Express service level token. */
    public const USPS_PRIORITY_MAIL_EXPRESS = 'usps_priority_mail_express';
    /** @var string USPS Priority Mail service level token (alternate). */
    public const USPS_PRIORITY_MAIL = 'usps_priority_mail';
    /** @var string USPS First Class Mail service level token. */
    public const USPS_FIRST = 'usps_first';
    /** @var string USPS Parcel Select service level token. */
    public const USPS_PARCEL_SELECT = 'usps_parcel_select';
    /** @var string USPS Media Mail service level token. */
    public const USPS_MEDIA_MAIL = 'usps_media_mail';
    /** @var string USPS Ground Advantage service level token. */
    public const USPS_GROUND_ADVANTAGE = 'usps_ground_advantage';

    // UPS
    /** @var string UPS Ground service level token. */
    public const UPS_GROUND = 'ups_ground';
    /** @var string UPS 3 Day Select service level token. */
    public const UPS_3_DAY_SELECT = 'ups_3_day_select';
    /** @var string UPS 2nd Day Air service level token. */
    public const UPS_2ND_DAY_AIR = 'ups_2nd_day_air';
    /** @var string UPS Next Day Air service level token. */
    public const UPS_NEXT_DAY_AIR = 'ups_next_day_air';
    /** @var string UPS Next Day Air Saver service level token. */
    public const UPS_NEXT_DAY_AIR_SAVER = 'ups_next_day_air_saver';
    /** @var string UPS SurePost service level token. */
    public const UPS_SUREPOST = 'ups_surepost';

    // FedEx
    /** @var string FedEx Ground service level token. */
    public const FEDEX_GROUND = 'fedex_ground';
    /** @var string FedEx Home Delivery service level token. */
    public const FEDEX_HOME_DELIVERY = 'fedex_home_delivery';
    /** @var string FedEx Express Saver service level token. */
    public const FEDEX_EXPRESS_SAVER = 'fedex_express_saver';
    /** @var string FedEx 2 Day service level token. */
    public const FEDEX_2_DAY = 'fedex_2_day';
    /** @var string FedEx 2 Day AM service level token. */
    public const FEDEX_2_DAY_AM = 'fedex_2_day_am';
    /** @var string FedEx Standard Overnight service level token. */
    public const FEDEX_STANDARD_OVERNIGHT = 'fedex_standard_overnight';
    /** @var string FedEx Priority Overnight service level token. */
    public const FEDEX_PRIORITY_OVERNIGHT = 'fedex_priority_overnight';

    // DHL Express
    /** @var string DHL Express Worldwide service level token. */
    public const DHL_EXPRESS_WORLDWIDE = 'dhl_express_worldwide';

    private function __construct()
    {
    }

    /**
     * Returns all known service level tokens.
     *
     * @return array<int, string> Service level token values.
     */
    public static function all(): array
    {
        return [
            self::USPS_PRIORITY,
            self::USPS_PRIORITY_EXPRESS,
            self::USPS_PRIORITY_MAIL_EXPRESS,
            self::USPS_PRIORITY_MAIL,
            self::USPS_FIRST,
            self::USPS_PARCEL_SELECT,
            self::USPS_MEDIA_MAIL,
            self::USPS_GROUND_ADVANTAGE,
            self::UPS_GROUND,
            self::UPS_3_DAY_SELECT,
            self::UPS_2ND_DAY_AIR,
            self::UPS_NEXT_DAY_AIR,
            self::UPS_NEXT_DAY_AIR_SAVER,
            self::UPS_SUREPOST,
            self::FEDEX_GROUND,
            self::FEDEX_HOME_DELIVERY,
            self::FEDEX_EXPRESS_SAVER,
            self::FEDEX_2_DAY,
            self::FEDEX_2_DAY_AM,
            self::FEDEX_STANDARD_OVERNIGHT,
            self::FEDEX_PRIORITY_OVERNIGHT,
            self::DHL_EXPRESS_WORLDWIDE,
        ];
    }

    /**
     * Checks whether a token is a known service level.
     *
     * @param string $token Service level token to validate.
     *
     * @return bool True when the token is recognized.
     */
    public static function isValid(string $token): bool
    {
        return in_array($token, self::all(), true);
    }
}
