<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Tests\Unit\Core;

use OxidEsales\TestingLibrary\UnitTestCase;
use oxField;

class UtilsObjectTest extends UnitTestCase
{
    /**
     * Tear down the fixture.
     *
     * @return null
     */
    public function tearDown(): void
    {
        $oShop = oxNew(\OxidEsales\Eshop\Application\Model\Shop::class);
        $oShop->delete(2);

        parent::tearDown();
    }

    public function testIsDerivedFromParentShopIsDerived()
    {
        // first insert a new shop
        $oShop = oxNew(\OxidEsales\Eshop\Application\Model\Shop::class);
        $oShop->setId(2);
        $oShop->oxshops__oxparentid = new \OxidEsales\Eshop\Core\Field(1, \OxidEsales\Eshop\Core\Field::T_RAW);
        $oShop->save();

        $oArticle = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
        $oArticle->setId('testArticle');
        $oArticle->oxarticles__oxshopid = new \OxidEsales\Eshop\Core\Field(1, \OxidEsales\Eshop\Core\Field::T_RAW);
        $oArticle->save();

        /** @var oxShopIdCalculator|PHPUnit\Framework\MockObject\MockObject $shopIdCalculator */
        $shopIdCalculator = $this->getMock(\OxidEsales\Eshop\Core\ShopIdCalculator::class, array('getShopId'), array(), '', false);
        $shopIdCalculator->expects($this->once())->method('getShopId')->will($this->returnValue(2));

        $oUtilsObject = oxNew(\OxidEsales\Eshop\Core\UtilsObject::class, null, null, $shopIdCalculator);

        $this->assertTrue($oUtilsObject->isDerivedFromParentShop('testArticle', "oxarticles"));
    }

    public function testIsDerivedFromParentShopIsNotDerived()
    {
        // first insert a new shop
        $oShop = oxNew(\OxidEsales\Eshop\Application\Model\Shop::class);
        $oShop->setId(2);
        $oShop->oxshops__oxparentid = new \OxidEsales\Eshop\Core\Field(1, \OxidEsales\Eshop\Core\Field::T_RAW);
        $oShop->save();

        $oArticle = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
        $oArticle->setId('testArticle');
        $oArticle->oxarticles__oxshopid = new \OxidEsales\Eshop\Core\Field(1, \OxidEsales\Eshop\Core\Field::T_RAW);
        $oArticle->save();

        $oConfig = $this->getMock(\OxidEsales\Eshop\Core\Config::class, array('getShopId'));
        $oConfig->expects($this->any())->method('getShopId')->will($this->returnValue(3));

        $oUtilsObject = $this->getMock(\OxidEsales\Eshop\Core\UtilsObject::class, array('getConfig'));
        $oUtilsObject->expects($this->any())->method('getConfig')->will($this->returnValue($oConfig));

        $this->assertFalse($oUtilsObject->isDerivedFromParentShop('testArticle', "oxarticles"));
    }

    public function testIsDerivedFromParentShopIsNotDerivedSameShop()
    {
        $oArticle = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
        $oArticle->setId('testArticle');
        $oArticle->oxarticles__oxshopid = new \OxidEsales\Eshop\Core\Field(1, \OxidEsales\Eshop\Core\Field::T_RAW);
        $oArticle->save();

        $oConfig = $this->getMock(\OxidEsales\Eshop\Core\Config::class, array('getShopId'));
        $oConfig->expects($this->any())->method('getShopId')->will($this->returnValue(1));

        $oUtilsObject = $this->getMock(\OxidEsales\Eshop\Core\UtilsObject::class, array('getConfig'));
        $oUtilsObject->expects($this->any())->method('getConfig')->will($this->returnValue($oConfig));

        $this->assertFalse($oUtilsObject->isDerivedFromParentShop('testArticle', "oxarticles"));
    }
}
