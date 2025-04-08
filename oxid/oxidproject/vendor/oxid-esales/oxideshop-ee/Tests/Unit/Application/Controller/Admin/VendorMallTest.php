<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Tests\Unit\Application\Controller\Admin;

use OxidEsales\TestingLibrary\UnitTestCase;
use oxTestModules;

/**
 * Tests for Vendor_Mall class
 */
class VendorMallTest extends UnitTestCase
{
    /**
     * Vendor_Mall::Render() test case
     *
     * @return null
     */
    public function testRender()
    {
        // testing..
        $oView = $this->getProxyClass("Vendor_Mall");
        $this->assertEquals('admin_mall.tpl', $oView->render());
        $this->assertEquals('oxvendor', $oView->getNonPublicVar("_sMallTable"));
    }
}
