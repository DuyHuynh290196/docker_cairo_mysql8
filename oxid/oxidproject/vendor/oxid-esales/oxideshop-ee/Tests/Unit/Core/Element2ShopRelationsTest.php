<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */
namespace OxidEsales\EshopEnterprise\Tests\Unit\Core;

use \oxArticle;
use OxidEsales\EshopEnterprise\Core\Element2ShopRelationsDbGateway;
use OxidEsales\EshopEnterprise\Core\Element2ShopRelations;

use \PHPUnit\Framework\MockObject\MockObject;
use \oxTestModules;

/**
 * Testing OxidEsales\EshopEnterprise\Core\Element2ShopRelations class.
 */
class Element2ShopRelationsTest extends \oxUnitTestCase
{
    /**
     * Provides shop ID or list of shops.
     *
     * @return array
     */
    public function dpTestListOfShops()
    {
        return array(
            array(45, 1),
            array(array(), 0),
            array(array(27), 1),
            array(array(3, 46, 5), 3),
        );
    }

    /**
     * Tests construct method if table name is set
     */
    public function testConstructWithParamShopIdsProvided()
    {
        $oShopRelations = oxNew(\OxidEsales\Eshop\Core\Element2ShopRelations::class, 'oxarticles');

        $this->assertEquals('oxarticles', $oShopRelations->getItemType());
    }

    /**
     * Tests set/get database gateway.
     */
    public function testSetGetDbGateway()
    {
        $oShopRelations = oxNew(\OxidEsales\Eshop\Core\Element2ShopRelations::class, null);

        // assert default gateway
        $this->isInstanceOf('OxidEsales\EshopEnterprise\Core\Element2ShopRelationsDbGateway', $oShopRelations->getDbGateway());

        $oCustomDbGateway = oxNew('stdClass');

        $oShopRelations->setDbGateway($oCustomDbGateway);
        $this->assertSame($oCustomDbGateway, $oShopRelations->getDbGateway());
    }

    /**
     * Tests set/get shop ID or list of shop IDs
     *
     * @param int|array $aShopIds          Shop ID or list of shop IDs.
     * @param int       $iExpectsToProcess Number of shops expected to be processed.
     *
     * @dataProvider dpTestListOfShops
     */
    public function testSetGetShopIds($aShopIds, $iExpectsToProcess)
    {
        $oShopRelations = oxNew(\OxidEsales\Eshop\Core\Element2ShopRelations::class, null);

        $oShopRelations->setShopIds($aShopIds);

        $this->assertTrue(is_array($oShopRelations->getShopIds()));
        $this->assertEquals($iExpectsToProcess, count($oShopRelations->getShopIds()));
    }

    /**
     * Tests set/get item type
     */
    public function testSetGetItemType()
    {
        $oShopRelations = oxNew(\OxidEsales\Eshop\Core\Element2ShopRelations::class, null);

        $oShopRelations->setItemType('oxcategories');
        $this->assertEquals('oxcategories', $oShopRelations->getItemType());
    }

    /**
     * Tests add item to shop or list of shops.
     *
     * @param int|array $aShopIds          Shop ID or list of shop IDs.
     * @param int       $iExpectsToProcess Number of shops expected to be processed.
     *
     * @dataProvider dpTestListOfShops
     */
    public function testAddToShop($aShopIds, $iExpectsToProcess)
    {
        $iItemMapId = 123;
        $sItemType = 'oxarticles';

        /** @var Element2ShopRelationsDbGateway|MockObject $oShopRelationsDbGateway */
        $oShopRelationsDbGateway = $this->getMock(\OxidEsales\Eshop\Core\Element2ShopRelationsDbGateway::class, array('addToShop'));
        $oShopRelationsDbGateway->expects($this->exactly($iExpectsToProcess))->method('addToShop');

        /** @var Element2ShopRelations|MockObject $oShopRelations */
        $oShopRelations = $this->getMock(\OxidEsales\Eshop\Core\Element2ShopRelations::class, array('_autoExecute'), array($sItemType));
        $oShopRelations->expects($this->once())->method('_autoExecute');
        $oShopRelations->setShopIds($aShopIds);

        $oShopRelations->setDbGateway($oShopRelationsDbGateway);

        $oShopRelations->addToShop($iItemMapId);
    }

    /**
     * Tests add item object to shop or list of shops.
     */
    public function testAddObjectToShop()
    {
        $iItemId = 123;
        $sItemType = 'oxarticles';

        /** @var oxArticle|MockObject $oArticle */
        $oArticle = $this->getMock(\OxidEsales\Eshop\Application\Model\Article::class, array('getId'));
        $oArticle->expects($this->once())->method('getId')->will($this->returnValue($iItemId));

        /** @var Element2ShopRelations|MockObject $oShopRelations */
        $oShopRelations = $this->getMock(\OxidEsales\Eshop\Core\Element2ShopRelations::class, array('addToShop'), array($sItemType));
        $oShopRelations->expects($this->once())->method('addToShop')->with($iItemId);

        $oShopRelations->addObjectToShop($oArticle);
    }

    /**
     * Tests remove item object from shop or list of shops.
     */
    public function testRemoveObjectFromShop()
    {
        $iItemId = 123;
        $sItemType = 'oxarticles';

        /** @var oxArticle|MockObject $oArticle */
        $oArticle = $this->getMock(\OxidEsales\Eshop\Application\Model\Article::class, array('getId'));
        $oArticle->expects($this->once())->method('getId')->will($this->returnValue($iItemId));

        /** @var Element2ShopRelations|MockObject $oShopRelations */
        $oShopRelations = $this->getMock(\OxidEsales\Eshop\Core\Element2ShopRelations::class, array('removeFromShop'), array($sItemType));
        $oShopRelations->expects($this->once())->method('removeFromShop')->with($iItemId);

        $oShopRelations->removeObjectFromShop($oArticle);
    }

    /**
     * Tests remove item from shop or list of shops.
     *
     * @param int|array $aShopIds          Shop ID or list of shop IDs.
     * @param int       $iExpectsToProcess Number of shops expected to be processed.
     *
     * @dataProvider dpTestListOfShops
     */
    public function testRemoveFromShop($aShopIds, $iExpectsToProcess)
    {
        $iItemMapId = 123;
        $sItemType = 'oxarticles';

        /** @var Element2ShopRelationsDbGateway|MockObject $oShopRelationsDbGateway */
        $oShopRelationsDbGateway = $this->getMock(\OxidEsales\Eshop\Core\Element2ShopRelationsDbGateway::class, array('removeFromShop'));
        $oShopRelationsDbGateway->expects($this->exactly($iExpectsToProcess))->method('removeFromShop');

        /** @var Element2ShopRelations|MockObject $oShopRelations */
        $oShopRelations = $this->getMock(\OxidEsales\Eshop\Core\Element2ShopRelations::class, array('_autoExecute'), array($sItemType));
        $oShopRelations->expects($this->once())->method('_autoExecute');
        $oShopRelations->setShopIds($aShopIds);

        $oShopRelations->setDbGateway($oShopRelationsDbGateway);

        $oShopRelations->removeFromShop($iItemMapId);
    }

    /**
     * Tests inherit items by type to sub shop(-s) from parent shop.
     *
     * @param int|array $aShopIds          Shop ID or list of shop IDs.
     * @param int       $iExpectsToProcess Number of shops expected to be processed.
     *
     * @dataProvider dpTestListOfShops
     */
    public function testInheritFromShop($aShopIds, $iExpectsToProcess)
    {
        $iParentShopId = 456;
        $sItemType = 'oxarticles';

        /** @var Element2ShopRelationsDbGateway|MockObject $oShopRelationsDbGateway */
        $oShopRelationsDbGateway = $this->getMock(\OxidEsales\Eshop\Core\Element2ShopRelationsDbGateway::class, array('inheritFromShop'));
        $oShopRelationsDbGateway->expects($this->exactly($iExpectsToProcess))->method('inheritFromShop');

        /** @var Element2ShopRelations|MockObject $oShopRelations */
        $oShopRelations = $this->getMock(\OxidEsales\Eshop\Core\Element2ShopRelations::class, array('_autoExecute'), array($sItemType));
        $oShopRelations->expects($this->once())->method('_autoExecute');
        $oShopRelations->setShopIds($aShopIds);

        $oShopRelations->setDbGateway($oShopRelationsDbGateway);

        $oShopRelations->inheritFromShop($iParentShopId);
    }

    /**
     * Tests remove items by type from sub shop(-s) that were inherited from parent shop.
     *
     * @param int|array $aShopIds          Shop ID or list of shop IDs.
     * @param int       $iExpectsToProcess Number of shops expected to be processed.
     *
     * @dataProvider dpTestListOfShops
     */
    public function testRemoveInheritedFromShop($aShopIds, $iExpectsToProcess)
    {
        $iParentShopId = 456;
        $sItemType = 'oxarticles';

        /** @var Element2ShopRelationsDbGateway|MockObject $oShopRelationsDbGateway */
        $oShopRelationsDbGateway = $this->getMock(\OxidEsales\Eshop\Core\Element2ShopRelationsDbGateway::class, array('removeInheritedFromShop'));
        $oShopRelationsDbGateway->expects($this->exactly($iExpectsToProcess))->method('removeInheritedFromShop');

        /** @var Element2ShopRelations|MockObject $oShopRelations */
        $oShopRelations = $this->getMock(\OxidEsales\Eshop\Core\Element2ShopRelations::class, array('_autoExecute'), array($sItemType));
        $oShopRelations->expects($this->once())->method('_autoExecute');
        $oShopRelations->setShopIds($aShopIds);

        $oShopRelations->setDbGateway($oShopRelationsDbGateway);

        $oShopRelations->removeInheritedFromShop($iParentShopId);
    }

    /**
     * Tests remove item from all shops.
     */
    public function testRemoveFromAllShops()
    {
        $iItemMapId = 123;
        $sItemType = 'oxarticles';

        /** @var Element2ShopRelationsDbGateway|MockObject $oShopRelationsDbGateway */
        $oShopRelationsDbGateway = $this->getMock(\OxidEsales\Eshop\Core\Element2ShopRelationsDbGateway::class, array('removeFromAllShops'));
        $oShopRelationsDbGateway->expects($this->exactly(1))->method('removeFromAllShops');

        /** @var Element2ShopRelations|MockObject $oShopRelations */
        $oShopRelations = $this->getMock(\OxidEsales\Eshop\Core\Element2ShopRelations::class, array('_autoExecute'), array($sItemType));
        $oShopRelations->expects($this->once())->method('_autoExecute');
        $oShopRelations->setDbGateway($oShopRelationsDbGateway);
        $oShopRelations->setShopIds(1);

        $oShopRelations->removeFromAllShops($iItemMapId);
    }

    /**
     * Tests copy inheritance information from one item to another.
     */
    public function testCopyInheritance()
    {
        $iSourceItemMapId = 123;
        $iItemMapId = 456;
        $sItemType = 'oxarticles';

        /** @var Element2ShopRelationsDbGateway|MockObject $oShopRelationsDbGateway */
        $oShopRelationsDbGateway = $this->getMock(\OxidEsales\Eshop\Core\Element2ShopRelationsDbGateway::class, array('copyInheritance'));
        $oShopRelationsDbGateway->expects($this->exactly(1))->method('copyInheritance');

        /** @var Element2ShopRelations|MockObject $oShopRelations */
        $oShopRelations = $this->getMock(\OxidEsales\Eshop\Core\Element2ShopRelations::class, array('_autoExecute'), array($sItemType));
        $oShopRelations->expects($this->once())->method('_autoExecute');

        $oShopRelations->setDbGateway($oShopRelationsDbGateway);

        $oShopRelations->copyInheritance($iSourceItemMapId, $iItemMapId);
    }

    /**
     * Tests execute stacked commands to add/remove item to shop.
     */
    public function testExecute()
    {
        /** @var Element2ShopRelationsDbGateway|MockObject $oShopRelationsDbGateway */
        $oShopRelationsDbGateway = $this->getMock(\OxidEsales\Eshop\Core\Element2ShopRelationsDbGateway::class, array('flush'));
        $oShopRelationsDbGateway->expects($this->once())->method('flush');

        $oShopRelations = oxNew(\OxidEsales\Eshop\Core\Element2ShopRelations::class, null);

        $oShopRelations->setDbGateway($oShopRelationsDbGateway);

        $oShopRelations->execute();
    }

    /**
     * Data provider for testAutoExecute.
     *
     * @return array
     */
    public function dpTestAutoExecute()
    {
        return array(
            array(true),
            array(false),
        );
    }

    /**
     * Tests auto execute stacked commands to add/remove item to shop.
     *
     * @param bool $blAutoExecute Whether to automatically execute stacked commands to add/remove item to shop.
     *
     * @dataProvider dpTestAutoExecute
     */
    public function testAutoExecute($blAutoExecute)
    {
        $iItemMapId = 123;
        $sItemType = 'oxarticles';

        /** @var Element2ShopRelationsDbGateway|MockObject $oShopRelationsDbGateway */
        $oShopRelationsDbGateway = $this->getMock(\OxidEsales\Eshop\Core\Element2ShopRelationsDbGateway::class, array('addToShop'));
        $oShopRelationsDbGateway->expects($this->once())->method('addToShop');

        /** @var Element2ShopRelations|MockObject $oShopRelations */
        $oShopRelations = $this->getMock(\OxidEsales\Eshop\Core\Element2ShopRelations::class, array('execute'), array($sItemType, $blAutoExecute));

        if ($blAutoExecute) {
            $oShopRelations->expects($this->once())->method('execute');
        } else {
            $oShopRelations->expects($this->never())->method('execute');
        }

        $oShopRelations->setShopIds(array(1));
        $oShopRelations->setDbGateway($oShopRelationsDbGateway);

        $oShopRelations->addToShop($iItemMapId);
    }

    /**
     * Tests OxShopRelation::isInShop() getter
     */
    public function testIsInShop()
    {
        $iItemMapId = 123;
        $sItemType = 'oxarticles';

        /** @var Element2ShopRelationsDbGateway|MockObject $oShopRelationsDbGateway */
        $oShopRelationsDbGateway = $this->getMock(\OxidEsales\Eshop\Core\Element2ShopRelationsDbGateway::class, array('isInShop'));
        $oShopRelationsDbGateway->expects($this->once())->method('isInShop');

        $oShopRelations = oxNew(\OxidEsales\Eshop\Core\Element2ShopRelations::class, $sItemType);

        $oShopRelations->setDbGateway($oShopRelationsDbGateway);

        $oShopRelations->setShopIds(5);

        $oShopRelations->isInShop($iItemMapId);
    }

    /**
     * Tests add all elements to shop.
     */
    public function testInheritAllElements()
    {
        $sItemType = 'oxarticles';
        $sShopId = 5;

        /** @var Element2ShopRelationsDbGateway|MockObject $oShopRelationsDbGateway */
        $oShopRelationsDbGateway = $this->getMock(\OxidEsales\Eshop\Core\Element2ShopRelationsDbGateway::class, array('inheritAllElements'));
        $oShopRelationsDbGateway->expects($this->exactly(1))->method('inheritAllElements')->with($sShopId, $sItemType);

        /** @var Element2ShopRelations|MockObject $oShopRelations */
        $oShopRelations = $this->getMock(\OxidEsales\Eshop\Core\Element2ShopRelations::class, array('_autoExecute'), array($sItemType));
        $oShopRelations->expects($this->once())->method('_autoExecute');

        $oShopRelations->setDbGateway($oShopRelationsDbGateway);
        $oShopRelations->setShopIds($sShopId);

        $oShopRelations->inheritAllElements();
    }

    /**
     * Tests remove all elements from shop.
     */
    public function testRemoveAllElements()
    {
        $sItemType = 'oxarticles';
        $sShopId = 5;

        /** @var Element2ShopRelationsDbGateway|MockObject $oShopRelationsDbGateway */
        $oShopRelationsDbGateway = $this->getMock(\OxidEsales\Eshop\Core\Element2ShopRelationsDbGateway::class, array('removeAllElements'));
        $oShopRelationsDbGateway->expects($this->exactly(1))->method('removeAllElements')->with($sShopId, $sItemType);

        /** @var Element2ShopRelations|MockObject $oShopRelations */
        $oShopRelations = $this->getMock(\OxidEsales\Eshop\Core\Element2ShopRelations::class, array('_autoExecute'), array($sItemType));
        $oShopRelations->expects($this->once())->method('_autoExecute');

        $oShopRelations->setDbGateway($oShopRelationsDbGateway);
        $oShopRelations->setShopIds($sShopId);

        $oShopRelations->removeAllElements();
    }
}
