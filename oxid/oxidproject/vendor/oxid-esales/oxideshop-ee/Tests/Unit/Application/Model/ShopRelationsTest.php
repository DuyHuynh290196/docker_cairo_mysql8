<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Tests\Unit\Application\Model;

/**
 * Testing ShopRelations class.
 */
class ShopRelationsTest extends \OxidEsales\TestingLibrary\UnitTestCase
{
    /**
     * Tests if shop element inherited from parent shop
     */
    public function testIsShopElementInherited()
    {
        $shopId = 1;
        $this->getConfig()->saveShopConfVar("bool", 'blMallInherit_oxarticles', true, $shopId);
        $this->getConfig()->saveShopConfVar("bool", 'blMallInherit_oxattributes', false, $shopId);
        // different handling for categories (there is no such legal option)
        $this->getConfig()->saveShopConfVar("bool", 'blMallInherit_oxcategories', true, $shopId);

        $shopRelations = oxNew(\OxidEsales\Eshop\Application\Model\ShopRelations::class, $shopId);

        $this->assertTrue($shopRelations->isShopElementInherited('oxarticles'));
        $this->assertFalse($shopRelations->isShopElementInherited('oxattributes'));
        $this->assertFalse($shopRelations->isShopElementInherited('oxcategories'));
    }

    /**
     * Tests if shop element inherited from all shops
     */
    public function testIsShopElementInheritedForMultiShop()
    {
        $shopId = 1;
        $this->getConfig()->saveShopConfVar("bool", 'blMallInherit_oxcategories', false, $shopId);
        $this->getConfig()->saveShopConfVar("bool", 'blMallInherit_oxattributes', false, $shopId);

        $shopRelations = oxNew(\OxidEsales\Eshop\Application\Model\ShopRelations::class, $shopId);
        $shopRelations->setIsMultiShopType(true);

        $this->assertTrue($shopRelations->isShopElementInherited('oxattributes'));
        $this->assertFalse($shopRelations->isShopElementInherited('oxcategories'));
    }

    /**
     * Tests if shop element inherited from all shops
     */
    public function testIsCategoryInheritedInMultiShop()
    {
        $shopId = 1;
        $this->getConfig()->saveShopConfVar("bool", 'blMultishopInherit_oxcategories', true, $shopId);

        $shopRelations = oxNew(\OxidEsales\Eshop\Application\Model\ShopRelations::class, $shopId);
        $shopRelations->setIsMultiShopType(true);

        $this->assertTrue($shopRelations->isShopElementInherited('oxcategories'));
    }

    /**
     * Test Setter and getter for Shop id
     */
    public function testSetGetShopId()
    {
        $shopRelations = oxNew(\OxidEsales\Eshop\Application\Model\ShopRelations::class, null);
        $shopRelations->setShopId(2);
        $this->assertEquals(2, $shopRelations->getShopId());
    }

    /**
     * Test Setter and getter for Shop id, if it is set in constructor
     */
    public function testSetGetShopIdInConstructor()
    {
        $shopRelations = oxNew(\OxidEsales\Eshop\Application\Model\ShopRelations::class, 1);
        $this->assertEquals(1, $shopRelations->getShopId());
    }
}
