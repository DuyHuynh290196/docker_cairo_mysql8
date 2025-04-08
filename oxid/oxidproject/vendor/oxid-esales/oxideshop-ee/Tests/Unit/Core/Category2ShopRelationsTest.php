<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */
namespace OxidEsales\EshopEnterprise\Tests\Unit\Core;

use \oxCategory;
use OxidEsales\EshopEnterprise\Core\Category2ShopRelations;
use OxidEsales\EshopEnterprise\Core\Element2ShopRelations;

use \PHPUnit\Framework\MockObject\MockObject;
use \oxTestModules;

/**
 * Testing oxCategory2ShopRelations class.
 */
class Category2ShopRelationsTest extends \oxUnitTestCase
{
    /**
     * Tests adding category to shop which has no subcategories and no objects.
     */
    public function testAddObjectToShopNoSubCategoriesNoObjects()
    {
        $sCategoryId = '_testCategoryId';

        /** @var oxCategory|MockObject $oCategory */
        $oxCategory = $this->getMock(\OxidEsales\Eshop\Application\Model\Category::class, array('getFieldFromSubCategories', 'getCategoryObjectIds'));
        $oxCategory->expects($this->once())->method('getCategoryObjectIds')->with($sCategoryId)
            ->will($this->returnValue(array()));
        $oxCategory->expects($this->once())->method('getFieldFromSubCategories')->with($this->anything(), $sCategoryId)
            ->will($this->returnValue(array()));

        oxTestModules::addModuleObject('oxCategory', $oxCategory);

        /** @var Category2ShopRelations|MockObject $oCategory2ShopRelations */
        $oCategory2ShopRelations = $this->getMock(\OxidEsales\Eshop\Core\Category2ShopRelations::class, array('addToShop'), array('oxCategory'));
        $oCategory2ShopRelations->expects($this->once())->method('addToShop')->with($sCategoryId);

        $oCategory = oxNew(\OxidEsales\Eshop\Application\Model\Category::class);
        $oCategory->setId($sCategoryId);

        $oCategory2ShopRelations->addObjectToShop($oCategory);
    }

    /**
     * Tests adding category to shop which has no subcategories but has one object.
     */
    public function testAddObjectToShopNoSubCategoriesWithObject()
    {
        $sCategoryId = '_testCategoryId';
        $sObjectId = '_testObject2CategoryId';

        /** @var oxCategory|MockObject $oxCategory */
        $oxCategory = $this->getMock(\OxidEsales\Eshop\Application\Model\Category::class, array('getFieldFromSubCategories', 'getCategoryObjectIds'));
        $oxCategory->expects($this->once())->method('getCategoryObjectIds')->with($sCategoryId)
            ->will($this->returnValue(array($sObjectId)));
        $oxCategory->expects($this->once())->method('getFieldFromSubCategories')->with($this->anything(), $sCategoryId)
            ->will($this->returnValue(array()));

        oxTestModules::addModuleObject('oxCategory', $oxCategory);

        /** @var Element2ShopRelations|MockObject $oxShopRelations */
        $oxShopRelations = $this->getMock(\OxidEsales\Eshop\Core\Element2ShopRelations::class, array('addToShop'), array('oxCategory'));
        $oxShopRelations->expects($this->at(0))->method('addToShop')->with($sObjectId);

        oxTestModules::addModuleObject('oxElement2ShopRelations', $oxShopRelations);

        /** @var Category2ShopRelations|MockObject $oCategory2ShopRelations */
        $oCategory2ShopRelations = $this->getMock(\OxidEsales\Eshop\Core\Category2ShopRelations::class, array('addToShop'), array('oxCategory'));
        $oCategory2ShopRelations->expects($this->at(0))->method('addToShop')->with($sCategoryId);

        $oCategory = new \OxidEsales\Eshop\Application\Model\Category();
        $oCategory->setId($sCategoryId);

        $oCategory2ShopRelations->addObjectToShop($oCategory);
    }

    /**
     * Tests adding category to shop which has one subcategory and no objects.
     */
    public function testAddObjectToShopWithSubCategoryNoObjects()
    {
        $sCategoryId = '_testCategoryId';
        $sSubCategoryId1 = '_testSubCategoryId1';

        /** @var oxCategory|MockObject $oCategory */
        $oxCategory = $this->getMock(\OxidEsales\Eshop\Application\Model\Category::class, array('getFieldFromSubCategories', 'getCategoryObjectIds'));
        $oxCategory->expects($this->at(0))->method('getCategoryObjectIds')->with($sCategoryId)
            ->will($this->returnValue(array()));
        $oxCategory->expects($this->at(1))->method('getFieldFromSubCategories')->with($this->anything(), $sCategoryId)
            ->will($this->returnValue(array($sSubCategoryId1)));
        $oxCategory->expects($this->at(2))->method('getCategoryObjectIds')->with($sSubCategoryId1)
            ->will($this->returnValue(array()));

        oxTestModules::addModuleObject('oxCategory', $oxCategory);

        /** @var Category2ShopRelations|MockObject $oCategory2ShopRelations */
        $oCategory2ShopRelations = $this->getMock(\OxidEsales\Eshop\Core\Category2ShopRelations::class, array('addToShop'), array('oxCategory'));
        $oCategory2ShopRelations->expects($this->at(0))->method('addToShop')->with($sCategoryId);
        $oCategory2ShopRelations->expects($this->at(1))->method('addToShop')->with($sSubCategoryId1);

        $oCategory = new \OxidEsales\Eshop\Application\Model\Category();
        $oCategory->setId($sCategoryId);

        $oCategory2ShopRelations->addObjectToShop($oCategory);
    }

    /**
     * Tests adding category to shop which has one subcategory and one object.
     */
    public function testAddObjectToShopWithSubCategoryWithObject()
    {
        $this->markTestIncomplete('TODO');
    }
}
