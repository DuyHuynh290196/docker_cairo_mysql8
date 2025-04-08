<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Tests\Unit\Model;

use \OxidEsales\TestingLibrary\UnitTestCase;

class Object2CategoryTest extends UnitTestCase
{
    /**
     * Tear down the fixture.
     *
     * @return null
     */
    protected function tearDown(): void
    {
        $this->cleanUpTable('oxobject2category');
        parent::tearDown();
    }

    /**
     * Tests if category assignment is added for inherited subshops
     */
    public function testAddElement2ShopRelations()
    {
        $element2ShopRelations = $this->getMock(\OxidEsales\Eshop\Core\Element2ShopRelations::class, array('setShopIds', 'addObjectToShop'), array('oxobject2category'));
        $element2ShopRelations->expects($this->once())->method('setShopIds')->with(array(3));
        $element2ShopRelations->expects($this->once())->method('addObjectToShop');

        $object2Category = $this->getMock(\OxidEsales\Eshop\Application\Model\Object2Category::class, array('_getInheritanceGroup', '_getElement2ShopRelations'));
        $object2Category->expects($this->once())->method('_getInheritanceGroup')->will($this->returnValue(array(2, 3)));
        $object2Category->expects($this->once())->method('_getElement2ShopRelations')->will($this->returnValue($element2ShopRelations));
        $object2Category->setId('_testId');
        $object2Category->setCategoryId('_testProduct');
        $object2Category->setProductId('_testProduct');
        $object2Category->save();
    }

    /**
     * Tests if category assignment has no subshops
     */
    public function testAddElement2ShopRelationsNoSubShops()
    {
        $element2ShopRelations = $this->getMock(\OxidEsales\Eshop\Core\Element2ShopRelations::class, array('setShopIds', 'addObjectToShop'), array('oxobject2category'));
        $element2ShopRelations->expects($this->never())->method('setShopIds');
        $element2ShopRelations->expects($this->never())->method('addObjectToShop');

        $object2Category = $this->getMock(\OxidEsales\Eshop\Application\Model\Object2Category::class, array('_getInheritanceGroup', '_getElement2ShopRelations'));
        $object2Category->expects($this->once())->method('_getInheritanceGroup')->will($this->returnValue(array(1)));
        $object2Category->expects($this->never())->method('_getElement2ShopRelations')->will($this->returnValue($element2ShopRelations));
        $object2Category->setId('_testId');
        $object2Category->setCategoryId('_testProduct');
        $object2Category->setProductId('_testProduct');
        $object2Category->save();
    }
}
