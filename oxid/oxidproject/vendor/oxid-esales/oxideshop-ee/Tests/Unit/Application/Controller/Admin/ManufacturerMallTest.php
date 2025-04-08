<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */
namespace OxidEsales\EshopEnterprise\Tests\Unit\Application\Controller\Admin;

use \OxidEsales\TestingLibrary\UnitTestCase;

/**
 * Tests for Manufacturer_Mall class
 */
class ManufacturerMallTest extends UnitTestCase
{
    /**
     * Manufacturer_Mall::Render() test case
     *
     * @return null
     */
    public function testRender()
    {
        $oView = $this->getProxyClass("Manufacturer_Mall");
        $this->assertEquals('oxmanufacturers', $oView->getNonPublicVar("_sMallTable"));
        $this->assertEquals('admin_mall.tpl', $oView->render());
    }
}
