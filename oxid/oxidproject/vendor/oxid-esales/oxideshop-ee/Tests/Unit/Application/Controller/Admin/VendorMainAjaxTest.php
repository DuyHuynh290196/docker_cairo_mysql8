<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Tests\Unit\Application\Controller\Admin;

use OxidEsales\TestingLibrary\UnitTestCase;
use oxTestModules;

/**
 * Tests for Shop_Cache class.
 */
class VendorMainAjaxTest extends UnitTestCase
{
    /**
     * AttributeMainAjax::removeVendor() test case
     *
     * @return null
     */
    public function testRemoveVendor_resetingCache()
    {
        $this->setRequestParameter("oxid", "_testVendorId");

        $oVendor = $this->getMock(\OxidEsales\Eshop\Application\Model\Vendor::class, array("load", "executeDependencyEvent"));
        $oVendor->expects($this->once())->method('load')->with($this->equalTo("_testVendorId"));
        $oVendor->expects($this->once())->method('executeDependencyEvent');
        oxTestModules::addModuleObject("oxVendor", $oVendor);

        $oView = $this->getMock(\OxidEsales\Eshop\Application\Controller\Admin\VendorMainAjax::class, array("_getActionIds"));
        $oView->expects($this->any())->method('_getActionIds')->will($this->returnValue(array('_testArticle1')));

        $oView->removeVendor();
    }

    /**
     * AttributeMainAjax::addVendor() test case
     *
     * @return null
     */
    public function testAddVendor_resetingCache()
    {
        $this->setRequestParameter("synchoxid", "_testVendorId");

        $oVendor = $this->getMock(\OxidEsales\Eshop\Application\Model\Vendor::class, array("load", "executeDependencyEvent"));
        $oVendor->expects($this->once())->method('load')->with($this->equalTo("_testVendorId"));
        $oVendor->expects($this->once())->method('executeDependencyEvent');
        oxTestModules::addModuleObject("oxVendor", $oVendor);

        $oView = $this->getMock(\OxidEsales\Eshop\Application\Controller\Admin\VendorMainAjax::class, array("_getActionIds", "resetCounter"));
        $oView->expects($this->any())->method('_getActionIds')->will($this->returnValue(array('_testArticle3')));
        $oView->expects($this->any())->method('resetCounter')->with($this->equalTo("vendorArticle"), $this->equalTo("_testVendorId"));

        $oView->addVendor();
    }
}
