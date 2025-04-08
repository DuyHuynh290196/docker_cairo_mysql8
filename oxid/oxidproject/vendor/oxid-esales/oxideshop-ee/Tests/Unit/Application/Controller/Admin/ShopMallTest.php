<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Tests\Unit\Application\Controller\Admin;

use OxidEsales\TestingLibrary\UnitTestCase;
use oxTestModules;
use oxDb;
use oxField;

/**
 * Tests for ShopMall class.
 */
class ShopMallTest extends UnitTestCase
{
    protected $_iActShopId = null;

    /**
     * Initialize the fixture.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->_iActShopId = \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->getOne('select max( oxid )+1 as oxid from oxshops');

        // creating NON active shop
        $oShop = oxNew(\OxidEsales\Eshop\Core\Model\BaseModel::class);
        $oShop->init('oxshops');
        $oShop->setId($this->_iActShopId);
        $oShop->oxshops__oxactive = new \OxidEsales\Eshop\Core\Field(0, \OxidEsales\Eshop\Core\Field::T_RAW);
        $oShop->oxshops__oxparentid = new \OxidEsales\Eshop\Core\Field(1, \OxidEsales\Eshop\Core\Field::T_RAW);
        $oShop->oxshops__oxisinherited = new \OxidEsales\Eshop\Core\Field(0, \OxidEsales\Eshop\Core\Field::T_RAW);
        $oShop->oxshops__oxname = new \OxidEsales\Eshop\Core\Field('testshop', \OxidEsales\Eshop\Core\Field::T_RAW);
        $oShop->save();

        // saving shop's config variable
        $this->getConfig()->saveShopConfVar('bool', 'blMallInherit_oxarticles', 1, $this->_iActShopId);
    }

    /**
     * Tear down the fixture.
     */
    protected function tearDown(): void
    {
        $oShop = oxNew(\OxidEsales\Eshop\Core\Model\BaseModel::class);
        $oShop->init('oxshops');
        $oShop->delete($this->_iActShopId);

        // cleaning up config table
        \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute("delete from oxshops where oxid = '{$this->_iActShopId}'");

        parent::tearDown();
    }

    /**
     * \OxidEsales\Eshop\Application\Controller\Admin\ShopMain::Render() test case
     */
    public function testRender()
    {
        // testing..
        $oView = oxNew(\OxidEsales\Eshop\Application\Controller\Admin\ShopMall::class);
        $this->assertEquals('shop_mall.tpl', $oView->render());
    }

    /**
     * \OxidEsales\Eshop\Application\Controller\Admin\ShopMain::Save() test case
     */
    public function testSave()
    {
        oxTestModules::addFunction('oxshop', 'load', '{ return true; }');
        oxTestModules::addFunction('oxshop', 'assign', '{ return true; }');
        oxTestModules::addFunction('oxshop', 'save', '{ return true; }');

        $this->getSession()->setVariable("malladmin", false);

        // testing..
        $oView = $this->getMock(\OxidEsales\Eshop\Application\Controller\Admin\ShopMall::class, array("saveConfVars"));
        $oView->expects($this->never())->method('saveConfVars');
        $oView->save();

        $this->getSession()->setVariable("malladmin", true);

        $oView = $this->getMock(\OxidEsales\Eshop\Application\Controller\Admin\ShopMall::class, array("saveConfVars"));
        $oView->expects($this->once())->method('saveConfVars');
        $oView->save();
    }

    /**
     * \OxidEsales\Eshop\Application\Controller\Admin\ShopMain::changeInheritance() test case
     */
    public function testChangeInheritance()
    {
        /** @var oxShop|PHPUnit\Framework\MockObject\MockObject $oShop */
        $oShop = $this->getMock(\OxidEsales\Eshop\Application\Model\Shop::class, array('updateInheritance', 'generateViews'), array(), '', false);
        $oShop->expects($this->once())->method('updateInheritance');
        $oShop->expects($this->once())->method('generateViews');

        /** @var Shop_Mall|PHPUnit\Framework\MockObject\MockObject $oView */
        $oView = $this->getMock(\OxidEsales\Eshop\Application\Controller\Admin\ShopMall::class, array('save', '_getEditShop'));
        $oView->expects($this->once())->method('save');
        $oView->expects($this->once())->method('_getEditShop')->will($this->returnValue($oShop));
        $oView->changeInheritance();
    }
}
