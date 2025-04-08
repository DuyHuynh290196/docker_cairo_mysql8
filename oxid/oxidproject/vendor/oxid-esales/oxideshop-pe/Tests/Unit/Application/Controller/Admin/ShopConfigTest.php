<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopProfessional\Tests\Unit\Application\Controller\Admin;

class ShopConfigTest extends \OxidEsales\TestingLibrary\UnitTestCase
{
    public function testInformationSendingToOxidConfigurable()
    {
        $shopConfig = oxNew('Shop_Config');

        $this->assertFalse($shopConfig->informationSendingToOxidConfigurable());
    }
}
