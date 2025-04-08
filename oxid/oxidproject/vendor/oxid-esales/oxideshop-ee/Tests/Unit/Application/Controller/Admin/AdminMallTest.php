<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */
namespace OxidEsales\EshopEnterprise\Tests\Unit\Application\Controllers\Admin;

/**
 * Tests for Admin_Mall class
 */
class Unit_Admin_AdminMallTest extends \oxUnitTestCase
{
    /**
     * Admin_Mall::getSubShopList() test case
     */
    public function testGetSubShopList()
    {
        $oView = $this->getProxyClass("Admin_Mall");
        $this->assertEquals(0, $oView->getSubShopList(1)->count());
    }

    /**
     * Admin_Mall::getMarkedShopList() test case
     */
    public function testGetMarkedShopList()
    {
        $oShop = $this->getMock(\OxidEsales\Eshop\Application\Model\Shop::class, array("getId"));
        $oShop->expects($this->once())->method('getId')->will($this->returnValue(2));

        $oView = $this->getMock(\OxidEsales\Eshop\Application\Controller\Admin\AdminMall::class, array("getSubShopList", "_getItemAssignedShopIds"));
        $oView->expects($this->once())->method('getSubShopList')->will($this->returnValue(array($oShop)));
        $oView->expects($this->once())->method('_getItemAssignedShopIds')->will($this->returnValue(array(1, 2)));

        $oShopList = $oView->getMarkedShopList();
        $this->assertEquals(1, count($oShopList));
        $this->assertTrue($oShopList[0]->selected);
    }

    /**
     * Admin_Mall::Render() test case
     *
     * @return null
     */
    public function testRender()
    {
        $oView = oxNew(\OxidEsales\Eshop\Application\Controller\Admin\AdminMall::class);
        $sTplName = $oView->render();

        // testing view data
        $aViewData = $oView->getViewData();
        $this->assertNull($aViewData["allowSharedEdit"]);
        $this->assertNull($aViewData["malladmin"]);
        $this->assertNull($aViewData["updatelist"]);
        $this->assertEquals('admin_mall', $aViewData["class"]);
        $this->assertTrue($aViewData["allowAssign"]);

        $this->assertEquals('admin_mall.tpl', $sTplName);
    }

    /**
     * Admin_Mall::AssignToSubshops() test case
     *
     * @return null
     */
    public function testAssignToSubshops()
    {
        $oView = $this->getProxyClass("Admin_Mall");
        $oView->setNonPublicVar("_sMallTable", "oxarticles");
        $oView->setNonPublicVar("_blAllowSubshopAssign", true);
        $oView->setObjectClassName("oxarticle");
        $oView->assignToSubShops();

        $this->assertEquals("oxarticles", $oView->getNonPublicVar("_sMallTable"));
    }

}
