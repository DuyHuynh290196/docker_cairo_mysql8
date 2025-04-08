<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */
namespace OxidEsales\EshopEnterprise\Tests\Unit\Application\Controller\Admin;

use OxidEsales\TestingLibrary\UnitTestCase;
use OxidEsales\EshopEnterprise\Application\Controller\Admin\Navigation;
use OxidEsales\EshopEnterprise\Core\AdminRights;
use oxNavigationTree;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * Tests for Manufacturer_Mall class
 */
class NavigationTest extends UnitTestCase
{
    /**
     * Navigation::chshp() test case
     */
    public function testChshpEERightsDoesNotAllowToView()
    {
        $this->setRequestParameter("listview", "testlistview");
        $this->setRequestParameter("editview", "testeditview");
        $this->setRequestParameter("actedit", "testactedit");

        /** @var AdminRights|MockObject $oAdminRights */
        $oAdminRights = $this->getMock(\OxidEsales\Eshop\Core\AdminRights::class, array("getViewRightsIndex"));
        $oAdminRights->expects($this->once())->method('getViewRightsIndex')->with($this->equalTo("testClassId"))->will($this->returnValue(0));

        /** @var oxNavigationTree|MockObject $oNavigation */
        $oNavigation = $this->getMock(\OxidEsales\Eshop\Application\Controller\Admin\NavigationTree::class, array("getClassId"));
        $oNavigation->expects($this->once())->method('getClassId')->will($this->returnValue("testClassId"));

        /** @var Navigation|MockObject $oView */
        $oView = $this->getMock(\OxidEsales\Eshop\Application\Controller\Admin\NavigationController::class, array("getRights", "getNavigation"));
        $oView->expects($this->once())->method('getRights')->will($this->returnValue($oAdminRights));
        $oView->expects($this->once())->method('getNavigation')->will($this->returnValue($oNavigation));
        $oView->chshp();

        $this->assertNull($oView->getViewDataElement("listview"));
        $this->assertNull($oView->getViewDataElement("editview"));
        $this->assertNull($oView->getViewDataElement("actedit"));
        $this->assertEquals(true, $oView->getViewDataElement("loadbasefrm"));
    }

    /**
     * Navigation::chshp() test case
     */
    public function testChshpEE()
    {
        $this->setRequestParameter("listview", "testlistview");
        $this->setRequestParameter("editview", "testeditview");
        $this->setRequestParameter("actedit", "testactedit");

        /** @var AdminRights|MockObject $oAdminRights */
        $oAdminRights = $this->getMock(\OxidEsales\Eshop\Core\AdminRights::class, array("getViewRightsIndex"));
        $oAdminRights->expects($this->at(0))->method('getViewRightsIndex')->with($this->equalTo("testClassId"))->will($this->returnValue(1));
        $oAdminRights->expects($this->at(1))->method('getViewRightsIndex')->with($this->equalTo("testClassId"))->will($this->returnValue(0));

        /** @var oxNavigationTree|MockObject $oNavigation */
        $oNavigation = $this->getMock(\OxidEsales\Eshop\Application\Controller\Admin\NavigationTree::class, array("getClassId", "markNodeActive", "getActiveTab"));
        $oNavigation->expects($this->at(0))->method('getClassId')->with($this->equalTo("testlistview"))->will($this->returnValue("testClassId"));
        $oNavigation->expects($this->at(1))->method('markNodeActive')->with($this->equalTo("testlistview"));
        $oNavigation->expects($this->at(2))->method('getClassId')->with($this->equalTo("testeditview"))->will($this->returnValue("testClassId"));
        $oNavigation->expects($this->at(3))->method('getActiveTab')->will($this->returnValue("testtab"));

        /** @var Navigation|MockObject $oView */
        $oView = $this->getMock(\OxidEsales\Eshop\Application\Controller\Admin\NavigationController::class, array("getRights", "getNavigation"));
        $oView->expects($this->once())->method('getRights')->will($this->returnValue($oAdminRights));
        $oView->expects($this->once())->method('getNavigation')->will($this->returnValue($oNavigation));
        $oView->chshp();

        $this->assertEquals("testlistview", $oView->getViewDataElement("listview"));
        $this->assertEquals("testtab", $oView->getViewDataElement("editview"));
        $this->assertEquals(0, $oView->getViewDataElement("actedit"));
        $this->assertEquals(true, $oView->getViewDataElement("loadbasefrm"));
    }
}
