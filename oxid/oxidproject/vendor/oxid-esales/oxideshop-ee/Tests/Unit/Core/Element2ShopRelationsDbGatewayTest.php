<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */
namespace OxidEsales\EshopEnterprise\Tests\Unit\Core;

use OxidEsales\EshopEnterprise\Core\Element2ShopRelationsDbGateway;
use OxidEsales\EshopEnterprise\Core\Element2ShopRelationsSqlGenerator;

use \PHPUnit\Framework\MockObject\MockObject;
use \oxTestModules;

/**
 * Testing OxidEsales\EshopEnterprise\Core\Element2ShopRelationsDbGateway class.
 *
 * @group database-adapter
 */
class Element2ShopRelationsDbGatewayTest extends \oxUnitTestCase
{
    /**
     * Test set/get database class object.
     */
    public function testSetGetDb()
    {
        $oShopRelationsDbGateway = oxNew(\OxidEsales\Eshop\Core\Element2ShopRelationsDbGateway::class);

        // assert default gateway

        $oCustomDb = oxNew('stdClass');

        $oShopRelationsDbGateway->setDbGateway($oCustomDb);
        $this->assertSame($oCustomDb, $oShopRelationsDbGateway->getDbGateway());
    }

    /**
     * Test set/get SQL generator class object.
     */
    public function testSetGetSqlGenerator()
    {
        $oShopRelationsDbGateway = oxNew(\OxidEsales\Eshop\Core\Element2ShopRelationsDbGateway::class);

        // assert default SQL generator
        $this->assertTrue($oShopRelationsDbGateway->getSqlGenerator() instanceof Element2ShopRelationsSqlGenerator);

        $oCustomShopRelationsSqlGenerator = oxNew('stdClass');

        $oShopRelationsDbGateway->setSqlGenerator($oCustomShopRelationsSqlGenerator);
        $this->assertSame($oCustomShopRelationsSqlGenerator, $oShopRelationsDbGateway->getSqlGenerator());
    }

    /**
     * Tests add item to shop.
     */
    public function testAddToShop()
    {
        $iItemId = 123;
        $sItemType = 'oxarticles';
        $iShopId = 45;

        /** @var Element2ShopRelationsDbGateway|MockObject $oShopRelationsDbGateway */
        $oShopRelationsDbGateway = $this->getMock(\OxidEsales\Eshop\Core\Element2ShopRelationsDbGateway::class, array('_addSql', 'flush'));
        $oShopRelationsDbGateway->expects($this->once())->method('_addSql')->with($this->anything());
        $oShopRelationsDbGateway->expects($this->never())->method('flush');

        /** @var Element2ShopRelationsSqlGenerator|MockObject $oSqlGenerator */
        $oSqlGenerator = $this->getMock(\OxidEsales\Eshop\Core\Element2ShopRelationsSqlGenerator::class, array('getSqlForAddToShop'));
        $oSqlGenerator->expects($this->once())->method('getSqlForAddToShop')->with('oxarticles', 123, 45)->will($this->returnValue(1));
        $oShopRelationsDbGateway->setSqlGenerator($oSqlGenerator);

        $oShopRelationsDbGateway->addToShop($iItemId, $sItemType, $iShopId);
    }

    /**
     * Tests remove item from shop.
     */
    public function testRemoveFromShop()
    {
        $iItemId = 123;
        $sItemType = 'oxarticles';
        $iShopId = 45;

        /** @var Element2ShopRelationsDbGateway|MockObject $oShopRelationsDbGateway */
        $oShopRelationsDbGateway = $this->getMock(\OxidEsales\Eshop\Core\Element2ShopRelationsDbGateway::class, array('_addSql', 'flush'));
        $oShopRelationsDbGateway->expects($this->once())->method('_addSql')->with($this->anything());
        $oShopRelationsDbGateway->expects($this->never())->method('flush');

        /** @var Element2ShopRelationsSqlGenerator|MockObject $oSqlGenerator */
        $oSqlGenerator = $this->getMock(\OxidEsales\Eshop\Core\Element2ShopRelationsSqlGenerator::class, array('getSqlForRemoveFromShop'));
        $oSqlGenerator->expects($this->once())->method('getSqlForRemoveFromShop')->with('oxarticles', 123, 45)->will($this->returnValue(1));
        $oShopRelationsDbGateway->setSqlGenerator($oSqlGenerator);

        $oShopRelationsDbGateway->removeFromShop($iItemId, $sItemType, $iShopId);
    }

    /**
     * Tests remove item from shop.
     */
    public function testInheritFromShop()
    {
        $iParentShopId = 45;
        $iSubShopId = 123;
        $sItemType = 'oxarticles';

        /** @var Element2ShopRelationsDbGateway|MockObject $oShopRelationsDbGateway */
        $oShopRelationsDbGateway = $this->getMock(\OxidEsales\Eshop\Core\Element2ShopRelationsDbGateway::class, array('_addSql', 'flush'));
        $oShopRelationsDbGateway->expects($this->once())->method('_addSql')->with($this->anything());
        $oShopRelationsDbGateway->expects($this->never())->method('flush');

        /** @var Element2ShopRelationsSqlGenerator|MockObject $oSqlGenerator */
        $oSqlGenerator = $this->getMock(\OxidEsales\Eshop\Core\Element2ShopRelationsSqlGenerator::class, array('getSqlForInheritFromShop'));
        $oSqlGenerator->expects($this->once())->method('getSqlForInheritFromShop')->with('oxarticles', 45, 123)->will($this->returnValue(1));
        $oShopRelationsDbGateway->setSqlGenerator($oSqlGenerator);

        $oShopRelationsDbGateway->inheritFromShop($iParentShopId, $iSubShopId, $sItemType);
    }

    /**
     * Tests remove item from shop.
     */
    public function testRemoveInheritedFromShop()
    {
        $iParentShopId = 45;
        $iSubShopId = 123;
        $sItemType = 'oxarticles';

        /** @var Element2ShopRelationsDbGateway|MockObject $oShopRelationsDbGateway */
        $oShopRelationsDbGateway = $this->getMock(\OxidEsales\Eshop\Core\Element2ShopRelationsDbGateway::class, array('_addSql', 'flush'));
        $oShopRelationsDbGateway->expects($this->once())->method('_addSql')->with($this->anything());
        $oShopRelationsDbGateway->expects($this->never())->method('flush');

        /** @var Element2ShopRelationsSqlGenerator|MockObject $oSqlGenerator */
        $oSqlGenerator = $this->getMock(\OxidEsales\Eshop\Core\Element2ShopRelationsSqlGenerator::class, array('getSqlForRemoveInheritedFromShop'));
        $oSqlGenerator->expects($this->once())->method('getSqlForRemoveInheritedFromShop')->with('oxarticles', 45, 123)->will($this->returnValue(1));
        $oShopRelationsDbGateway->setSqlGenerator($oSqlGenerator);

        $oShopRelationsDbGateway->removeInheritedFromShop($iParentShopId, $iSubShopId, $sItemType);
    }

    /**
     * Tests copy inheritance information from one item to another.
     */
    public function testCopyInheritance()
    {
        $iSourceItem = 123;
        $iItem = 456;
        $sItemType = 'oxarticles';

        /** @var Element2ShopRelationsDbGateway|MockObject $oShopRelationsDbGateway */
        $oShopRelationsDbGateway = $this->getMock(\OxidEsales\Eshop\Core\Element2ShopRelationsDbGateway::class, array('_addSql', 'flush'));
        $oShopRelationsDbGateway->expects($this->once())->method('_addSql')->with($this->anything());
        $oShopRelationsDbGateway->expects($this->never())->method('flush');

        /** @var Element2ShopRelationsSqlGenerator|MockObject $oSqlGenerator */
        $oSqlGenerator = $this->getMock(\OxidEsales\Eshop\Core\Element2ShopRelationsSqlGenerator::class, array('getSqlForCopyInheritance'));
        $oSqlGenerator->expects($this->once())->method('getSqlForCopyInheritance')->with('oxarticles', 123, 456)->will($this->returnValue(1));
        $oShopRelationsDbGateway->setSqlGenerator($oSqlGenerator);

        $oShopRelationsDbGateway->copyInheritance($iSourceItem, $iItem, $sItemType);
    }

    /**
     * Tests execute SQL queries from the list with empty list.
     */
    public function testFlushSqlListEmpty()
    {
        /** @var Element2ShopRelationsDbGateway|MockObject $oShopRelationsDbGateway */
        $oShopRelationsDbGateway = $this->getMock(
            'oxElement2ShopRelationsDbGateway',
            array('_getSqlList', '_clearSqlList', 'getDbGateway')
        );

        $oShopRelationsDbGateway->expects($this->once())->method('_getSqlList')->will($this->returnValue(array()));
        $oShopRelationsDbGateway->expects($this->never())->method('getDbGateway');
        $oShopRelationsDbGateway->expects($this->once())->method('_clearSqlList');

        $oShopRelationsDbGateway->flush();
    }

    /**
     * Tests execute SQL queries from the list with one query in the list.
     */
    public function testFlushSqlList1Query()
    {
        /** @var Element2ShopRelationsDbGateway|MockObject $oShopRelationsDbGateway */
        $oShopRelationsDbGateway = $this->getMock(
            'oxElement2ShopRelationsDbGateway',
            array('_getSqlList', '_clearSqlList', 'getDbGateway')
        );

        /** @var DatabaseInterface|MockObject $oDb */
        $oDb = $this->getDbObjectMock();

        $oShopRelationsDbGateway->expects($this->once())
            ->method('_getSqlList')
            ->will(
                $this->returnValue(
                    array(
                         array('test SQL query 1', array('test', 'SQL', 'params', '1')),
                    )
                )
            );

        $oShopRelationsDbGateway->expects($this->exactly(1))->method('getDbGateway')->will($this->returnValue($oDb));
        $oShopRelationsDbGateway->expects($this->once())->method('_clearSqlList');

        $oDb->expects($this->at(0))->method('execute')->with('test SQL query 1', array('test', 'SQL', 'params', '1'));

        $oShopRelationsDbGateway->flush();
    }

    /**
     * Tests execute SQL queries from the list with two queries in the list.
     */
    public function testFlushSqlList2Queries()
    {
        /** @var Element2ShopRelationsDbGateway|MockObject $oShopRelationsDbGateway */
        $oShopRelationsDbGateway = $this->getMock(
            'oxElement2ShopRelationsDbGateway',
            array('_getSqlList', '_clearSqlList', 'getDbGateway')
        );

        /** @var DatabaseInterface|MockObject $oDb */
        $oDb = $this->getDbObjectMock();

        $oShopRelationsDbGateway->expects($this->once())
            ->method('_getSqlList')
            ->will(
                $this->returnValue(
                    array(
                         array('test SQL query 1', array('test', 'SQL', 'params', '1')),
                         array('test SQL query 2', array('test', 'SQL', 'params', '2')),
                    )
                )
            );

        $oShopRelationsDbGateway->expects($this->exactly(2))->method('getDbGateway')->will($this->returnValue($oDb));
        $oShopRelationsDbGateway->expects($this->once())->method('_clearSqlList');

        $oDb->expects($this->at(0))->method('execute')->with('test SQL query 1', array('test', 'SQL', 'params', '1'));
        $oDb->expects($this->at(1))->method('execute')->with('test SQL query 2', array('test', 'SQL', 'params', '2'));

        $oShopRelationsDbGateway->flush();
    }

    /**
     * Test remove item from all shops.
     */
    public function testRemoveFromAllShop()
    {
        $iItemId = 123;
        $sItemType = 'oxarticles';

        /** @var Element2ShopRelationsDbGateway|MockObject $oShopRelationsDbGateway */
        $oShopRelationsDbGateway = $this->getMock(\OxidEsales\Eshop\Core\Element2ShopRelationsDbGateway::class, array('_addSql', 'flush'));
        $oShopRelationsDbGateway->expects($this->once())->method('_addSql')->with($this->anything());
        $oShopRelationsDbGateway->expects($this->never())->method('flush');

        /** @var Element2ShopRelationsSqlGenerator|MockObject $oSqlGenerator */
        $oSqlGenerator = $this->getMock(\OxidEsales\Eshop\Core\Element2ShopRelationsSqlGenerator::class, array('getSqlForRemoveFromAllShops'));
        $oSqlGenerator->expects($this->once())->method('getSqlForRemoveFromAllShops')->with('oxarticles', 123)->will($this->returnValue(1));
        $oShopRelationsDbGateway->setSqlGenerator($oSqlGenerator);

        $oShopRelationsDbGateway->removeFromAllShops($iItemId, $sItemType);
    }

    /**
     * Tests isInShop() getter
     */
    public function testIsInShop()
    {
        $iItemId = 123;
        $sItemType = 'oxarticles';
        $aSubshops = array(1, 2, 5);

        $aSqlParams = array($iItemId, 1, 2, 5);

        $oDbGateway = $this->getMock('stdClass', array('getOne'));
        $oDbGateway->expects($this->once())
            ->method('getOne')->with($this->anything(), $aSqlParams)->will($this->returnValue(10));

        /** @var Element2ShopRelationsDbGateway|MockObject $oShopRelationsDbGateway */
        $oShopRelationsDbGateway = $this->getMock(\OxidEsales\Eshop\Core\Element2ShopRelationsDbGateway::class, array('getDbGateway'));
        $oShopRelationsDbGateway->expects($this->once())->method('getDbGateway')->will($this->returnValue($oDbGateway));

        /** @var Element2ShopRelationsSqlGenerator|MockObject $oSqlGenerator */
        $oSqlGenerator = $this->getMock(\OxidEsales\Eshop\Core\Element2ShopRelationsSqlGenerator::class, array('getSqlForIsInShop'));
        $oSqlGenerator->expects($this->once())->method('getSqlForIsInShop')->with('oxarticles', 123, $aSubshops)
            ->will($this->returnValue(array('1', $aSqlParams)));
        $oShopRelationsDbGateway->setSqlGenerator($oSqlGenerator);

        $this->assertTrue($oShopRelationsDbGateway->isInShop($iItemId, $sItemType, $aSubshops));
    }

    /**
     * Test isInShop getter when results did not return correct results
     */
    public function testIsInShopNoCorrectResult()
    {
        $iItemId = 123;
        $sItemType = 'oxarticles';
        $aSubshops = array(1, 2, 5);

        $aSqlParams = array($iItemId, 1, 2, 5);

        $oDbGateway = $this->getMock('stdClass', array('getOne'));
        $oDbGateway->expects($this->once())
            ->method('getOne')->with($this->anything(), $aSqlParams)->will($this->returnValue(false));

        /** @var Element2ShopRelationsDbGateway|MockObject $oShopRelationsDbGateway */
        $oShopRelationsDbGateway = $this->getMock(\OxidEsales\Eshop\Core\Element2ShopRelationsDbGateway::class, array('getDbGateway'));
        $oShopRelationsDbGateway->expects($this->once())->method('getDbGateway')->will($this->returnValue($oDbGateway));

        /** @var Element2ShopRelationsSqlGenerator|MockObject $oSqlGenerator */
        $oSqlGenerator = $this->getMock(\OxidEsales\Eshop\Core\Element2ShopRelationsSqlGenerator::class, array('getSqlForIsInShop'));
        $oSqlGenerator->expects($this->once())->method('getSqlForIsInShop')->with('oxarticles', 123, $aSubshops)
            ->will($this->returnValue(array('1', $aSqlParams)));
        $oShopRelationsDbGateway->setSqlGenerator($oSqlGenerator);

        $this->assertFalse($oShopRelationsDbGateway->isInShop($iItemId, $sItemType, $aSubshops));
    }

    /**
     * Tests add all item to shop.
     */
    public function testInheritAllElements()
    {
        $sItemType = 'oxarticles';
        $iShopId = 45;

        /** @var Element2ShopRelationsDbGateway|MockObject $oShopRelationsDbGateway */
        $oShopRelationsDbGateway = $this->getMock(\OxidEsales\Eshop\Core\Element2ShopRelationsDbGateway::class, array('_addSql', 'flush'));
        $oShopRelationsDbGateway->expects($this->once())->method('_addSql')->with($this->anything());
        $oShopRelationsDbGateway->expects($this->never())->method('flush');

        /** @var Element2ShopRelationsSqlGenerator|MockObject $oSqlGenerator */
        $oSqlGenerator = $this->getMock(\OxidEsales\Eshop\Core\Element2ShopRelationsSqlGenerator::class, array('getSqlForInheritAllElements'));
        $oSqlGenerator->expects($this->once())->method('getSqlForInheritAllElements')->with($iShopId, $sItemType)->will($this->returnValue(1));
        $oShopRelationsDbGateway->setSqlGenerator($oSqlGenerator);

        $oShopRelationsDbGateway->inheritAllElements($iShopId, $sItemType);
    }

    /**
     * Tests remove all item from shop.
     */
    public function testRemoveAllElements()
    {
        $sItemType = 'oxarticles';
        $iShopId = 45;

        /** @var Element2ShopRelationsDbGateway|MockObject $oShopRelationsDbGateway */
        $oShopRelationsDbGateway = $this->getMock(\OxidEsales\Eshop\Core\Element2ShopRelationsDbGateway::class, array('_addSql', 'flush'));
        $oShopRelationsDbGateway->expects($this->once())->method('_addSql')->with($this->anything());
        $oShopRelationsDbGateway->expects($this->never())->method('flush');

        /** @var Element2ShopRelationsSqlGenerator|MockObject $oSqlGenerator */
        $oSqlGenerator = $this->getMock(\OxidEsales\Eshop\Core\Element2ShopRelationsSqlGenerator::class, array('getSqlForRemoveAllElements'));
        $oSqlGenerator->expects($this->once())->method('getSqlForRemoveAllElements')->with($iShopId, $sItemType)->will($this->returnValue(1));
        $oShopRelationsDbGateway->setSqlGenerator($oSqlGenerator);

        $oShopRelationsDbGateway->removeAllElements($iShopId, $sItemType);
    }

}
