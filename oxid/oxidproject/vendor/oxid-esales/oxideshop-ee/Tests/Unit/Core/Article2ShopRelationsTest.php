<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */
namespace OxidEsales\EshopEnterprise\Tests\Unit\Core;

use OxidEsales\EshopEnterprise\Core\Article2ShopRelations;
use \oxArticle;
use \PHPUnit\Framework\MockObject\MockObject;

class Article2ShopRelationsTest extends \oxUnitTestCase
{
    /**
     * Checks upateVariantInheritanceFromParent method with a 1 variant
     */
    public function testUpdateVariantInheritance()
    {
        /** @var Article2ShopRelations|MockObject $oShopRelations */
        $oShopRelations = $this->getMock(
            "oxArticle2ShopRelations",
            array("updateInheritanceFromParent"),
            array("oxarticles")
        );
        $oShopRelations->expects($this->once())->method("updateInheritanceFromParent")->with(11, 1);

        /** @var \OxidEsales\Eshop\Application\Model\Article $oArticle */
        $oArticle = $this->_getMockedClass("oxArticle", array("getId" => 1, "getVariantIds" => array(11)));

        $oShopRelations->updateVariantInheritance($oArticle);
    }

    /**
     * Checks that updateInheritanceFromParent calls relevant methods from parent class
     */
    public function testUpdateInheritanceFromParent()
    {
        /** @var Article2ShopRelations|MockObject $oShopRelations */
        $oShopRelations = $this->getMock(
            "oxArticle2ShopRelations",
            array("removeFromAllShops", "copyInheritance"),
            array("oxarticles")
        );
        $oShopRelations->expects($this->once())->method("removeFromAllShops")->with("testId")
            ->will($this->returnValue(null));
        $oShopRelations->expects($this->once())->method("copyInheritance")->with("testParentId", "testId")
            ->will($this->returnValue(null));

        $oShopRelations->updateInheritanceFromParent("testId", "testParentId");
    }

    /**
     * Checks if product was added with variants, then update variants too.
     */
    public function testAddObjectToShopWithVariants()
    {
        /** @var \OxidEsales\Eshop\Application\Model\Article $oArticle */
        $oArticle = $this->_getMockedClass(
            "oxArticle",
            array("getVariantsCount" => 1, "getId" => 1, "isVariant" => false)
        );

        /** @var Article2ShopRelations|MockObject $oShopRelations */
        $oShopRelations = $this->getMock(
            "oxArticle2ShopRelations",
            array("updateVariantInheritance", "updateInheritanceFromParent", "addToShop"),
            array("oxarticles")
        );
        $oShopRelations->expects($this->once())->method("addToShop")->will($this->returnValue(null));
        $oShopRelations->expects($this->once())->method("updateVariantInheritance")->with($oArticle);
        $oShopRelations->expects($this->never())->method("updateInheritanceFromParent");

        $oShopRelations->addObjectToShop($oArticle);
    }

    /**
     * Checks if product is variant, then update inheritance according to parent product.
     */
    public function testAddObjectToShopWithParent()
    {
        /** @var \OxidEsales\Eshop\Application\Model\Article $oParent */
        $oParent = $this->_getMockedClass("oxArticle", array("getId" => "testParentId"));

        /** @var \OxidEsales\Eshop\Application\Model\Article|MockObject $oArticle */
        $oArticle = $this->_getMockedClass(
            "oxArticle",
            array(
                 "isVariant"        => true,
                 "getId"            => "testId",
                 "getParentArticle" => $oParent,
                 "getVariantsCount" => 0
            )
        );

        /** @var Article2ShopRelations|MockObject $oShopRelations */
        $oShopRelations = $this->getMock(
            "oxArticle2ShopRelations",
            array("updateVariantInheritance", "updateInheritanceFromParent", "addToShop"),
            array("oxarticles")
        );
        $oShopRelations->expects($this->once())->method("addToShop")->will($this->returnValue(null));
        $oShopRelations->expects($this->never())->method("updateVariantInheritance");
        $oShopRelations->expects($this->never())->method("updateInheritanceFromParent");

        $oShopRelations->addObjectToShop($oArticle);
    }

    /**
     * Checks simple product, no additional actions needed.
     */
    public function testAddObjectToShop()
    {
        /** @var \OxidEsales\Eshop\Application\Model\Article $oArticle */
        $oArticle = $this->_getMockedClass(
            "oxArticle",
            array("getId" => 1, "getVariantsCount" => 0, "isVariant" => false)
        );

        /** @var Article2ShopRelations|MockObject $oShopRelations */
        $oShopRelations = $this->getMock(
            "oxArticle2ShopRelations",
            array("updateVariantInheritance", "updateInheritanceFromParent", "addToShop"),
            array("oxarticles")
        );
        $oShopRelations->expects($this->never())->method("updateVariantInheritance");
        $oShopRelations->expects($this->never())->method("updateInheritanceFromParent");

        $oShopRelations->addObjectToShop($oArticle);
    }

    /**
     * Checls object removal when no variants are present
     */
    public function testRemoveObjectFromShop()
    {
        /** @var \OxidEsales\Eshop\Application\Model\Article $oArticle */
        $oArticle = $this->_getMockedClass("oxArticle", array("getId" => 1, "getVariantIds" => array()));

        /** @var Article2ShopRelations|MockObject $oShopRelations */
        $oShopRelations = $this->getMock(\OxidEsales\Eshop\Core\Article2ShopRelations::class, array("removeFromShop"), array("oxarticles"));
        $oShopRelations->expects($this->once())->method("removeFromShop")->will($this->returnValue(null));

        $oShopRelations->removeObjectFromShop($oArticle);
    }

    /**
     * Checks object removal with several returned variants
     */
    public function testRemoveObjectFromShopWithSeveralVariants()
    {
        /** @var \OxidEsales\Eshop\Application\Model\Article $oArticle */
        $oArticle = $this->_getMockedClass('oxArticle', array("getId" => 1, "getVariantIds" => array(1, 2, 3)));

        /** @var Article2ShopRelations|MockObject $oShopRelations */
        $oShopRelations = $this->getMock(
            "oxArticle2ShopRelations",
            array("removeFromShop", "removeFromAllShops", "copyInheritance"),
            array("oxarticles")
        );
        $oShopRelations->expects($this->once())->method("removeFromShop")->will($this->returnValue(null));
        $oShopRelations->expects($this->exactly(3))->method("removeFromAllShops")->will($this->returnValue(null));
        $oShopRelations->expects($this->exactly(3))->method("copyInheritance")->will($this->returnValue(null));

        $oShopRelations->removeObjectFromShop($oArticle);
    }

    /**
     * Returns mocked class. Supports function => return value implementation
     *
     * @param string $sClass           Class name to be mocked.
     * @param array  $aMockedFunctions Function array containing method names and return values for respective methods.
     *
     * @return object|MockObject
     */
    protected function _getMockedClass($sClass, $aMockedFunctions = array()) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $oClass = $this->getMock($sClass, array_keys($aMockedFunctions));
        foreach ($aMockedFunctions as $sFunction => $mResult) {
            $oClass->expects($this->any())->method($sFunction)->will($this->returnValue($mResult));
        }

        return $oClass;
    }
}
