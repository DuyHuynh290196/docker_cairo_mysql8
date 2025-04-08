<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Tests\Unit\Application\Controller\Admin;

use OxidEsales\TestingLibrary\UnitTestCase;

/**
 * Tests for Discount_Mall class
 */
class DiscountMallTest extends UnitTestCase
{
    /**
     * Discount_Mall::Render() test case
     *
     * @return null
     */
    public function testRender()
    {
        $oView = $this->getProxyClass("Discount_Mall");
        $this->assertEquals('oxdiscount', $oView->getNonPublicVar("_sMallTable"));
        $this->assertEquals('admin_mall.tpl', $oView->render());
    }
}
