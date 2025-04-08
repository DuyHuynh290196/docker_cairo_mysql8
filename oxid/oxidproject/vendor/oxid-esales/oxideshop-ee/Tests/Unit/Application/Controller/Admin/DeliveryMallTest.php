<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Tests\Unit\Application\Controller\Admin;

use \OxidEsales\TestingLibrary\UnitTestCase;

/**
 * Tests for Delivery_Mall class
 */
class Unit_Admin_DeliveryMallTest extends UnitTestCase
{
    /**
     * Delivery_Mall::Render() test case
     */
    public function testRender()
    {
        $oView = $this->getProxyClass("Delivery_Mall");
        $this->assertEquals('oxdelivery', $oView->getNonPublicVar("_sMallTable"));
        $this->assertEquals('admin_mall.tpl', $oView->render());
    }
}
