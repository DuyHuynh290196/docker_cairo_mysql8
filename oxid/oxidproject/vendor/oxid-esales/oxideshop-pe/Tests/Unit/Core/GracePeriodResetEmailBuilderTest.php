<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopProfessional\Tests\Unit\Core;

use OxidEsales\Eshop\Core\Registry;
use OxidEsales\EshopProfessional\Core\GracePeriodResetEmailBuilder;

class GracePeriodResetEmailBuilderTest extends \oxUnitTestCase
{
    public function testGetEmailAndCheckIfBodyStartsCorrectly()
    {
        $lang = Registry::getLang();

        $graceResetEmailBuilder = new GracePeriodResetEmailBuilder();
        $graceResetEmail = $graceResetEmailBuilder->build();

        $expectedStart = $lang->translateString(
            'SHOP_LICENSE_ERROR_GRACE_RESET',
            null,
            true
        );

        $this->assertStringStartsWith($expectedStart, $graceResetEmail->getBody(), 'Email content start is not that as it should be.');
    }

    public function testGetEmailAndCheckIfBodyEndsWithOriginInformation()
    {
        $this->setConfigParam('sShopURL', 'someTestUrl');
        $lang = Registry::getLang();

        $graceResetEmailBuilder = new GracePeriodResetEmailBuilder();
        $graceResetEmail = $graceResetEmailBuilder->build();

        $expectedEnd = sprintf($lang->translateString(
            'SHOP_EMAIL_ORIGIN_MESSAGE',
            null,
            true
        ), "someTestUrl");

        $this->assertStringEndsWith($expectedEnd, $graceResetEmail->getBody(), 'Email content end is not that as it should be.');
    }
}
