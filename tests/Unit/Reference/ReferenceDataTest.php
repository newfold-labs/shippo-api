<?php

declare(strict_types=1);

namespace Bluehost\ShippoAPI\Tests\Unit\Reference;

use PHPUnit\Framework\TestCase;
use Bluehost\ShippoAPI\Reference\ParcelTemplates;
use Bluehost\ShippoAPI\Reference\ServiceLevels;

final class ReferenceDataTest extends TestCase
{
    public function testServiceLevelValidation(): void
    {
        $this->assertTrue(ServiceLevels::isValid(ServiceLevels::USPS_PRIORITY));
        $this->assertFalse(ServiceLevels::isValid('invalid_token'));
        $this->assertNotEmpty(ServiceLevels::all());
    }

    public function testParcelTemplateValidation(): void
    {
        $this->assertTrue(ParcelTemplates::isValid(ParcelTemplates::FEDEX_BOX_SMALL));
        $this->assertFalse(ParcelTemplates::isValid('invalid_template'));
        $this->assertNotEmpty(ParcelTemplates::all());
    }
}
