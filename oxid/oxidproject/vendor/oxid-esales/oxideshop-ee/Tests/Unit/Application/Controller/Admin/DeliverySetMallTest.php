<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Tests\Unit\Application\Controller\Admin;

use \OxidEsales\TestingLibrary\UnitTestCase;

/**
 * Tests for DeliverySet_Mall class
 */
class DeliverySetMallTest extends UnitTestCase
{
    /**
     * DeliverySet_Mall::Render() test case
     *
     * @return null
     */
    public function testRender()
    {
        $oView = $this->getProxyClass("DeliverySet_Mall");
        $this->assertEquals('oxdeliveryset', $oView->getNonPublicVar("_sMallTable"));
        $this->assertEquals('admin_mall.tpl', $oView->render());
    }
}
