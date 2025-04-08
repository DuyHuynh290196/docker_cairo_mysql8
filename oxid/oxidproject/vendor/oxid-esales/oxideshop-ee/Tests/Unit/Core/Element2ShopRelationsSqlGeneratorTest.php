<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */
namespace OxidEsales\EshopEnterprise\Tests\Unit\Core;

use \PHPUnit\Framework\MockObject\MockObject;
use \oxTestModules;

/**
 * Testing OxidEsales\EshopEnterprise\Core\Element2ShopRelationsSqlGenerator class.
 */
class Element2ShopRelationsSqlGeneratorTest extends \oxUnitTestCase
{
    /**
     * Asserts that expected table is in SQL query.
     *
     * @param string $sSql           SQL query.
     * @param string $sExpectedTable Expected table.
     * @param string $sMessage       Message to print on failure.
     */
    private function _assertSqlInsertIntoTable($sSql, $sExpectedTable, $sMessage = "") // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $sPatternQuote = '[\'"`]';
        $this->assertRegExp("#insert(?: [^ ]+)? into ({$sPatternQuote}){$sExpectedTable}\\1 #i", $sSql, $sMessage);
    }

    /**
     * Asserts that expected table is in SQL query.
     *
     * @param string $sSql           SQL query.
     * @param string $sExpectedTable Expected table.
     * @param string $sMessage       Message to print on failure.
     */
    private function _assertSqlDeleteFromTable($sSql, $sExpectedTable, $sMessage = "") // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $sPatternQuote = '[\'"`]';
        $this->assertRegExp("#delete(?: [^ ]+)? from ({$sPatternQuote}){$sExpectedTable}\\1 #i", $sSql, $sMessage);
    }

    /**
     * Data provider for testing correct mapping tables.
     *
     * @return array
     */
    public function dpTestGetMappingTable()
    {
        return array(
            array('oxarticles', 'oxarticles2shop'),
            array('oxobject2category', 'oxobject2category'),
        );
    }

    /**
     * Tests get SQL for adding item to shop.
     *
     * @param string $sItemTable    Item table.
     * @param string $sMappingTable Item mapping table.
     *
     * @dataProvider dpTestGetMappingTable
     */
    public function testGetSqlForAddToShop($sItemTable, $sMappingTable)
    {
        $iItemId = 123;
        $iShopId = 45;

        $oShopRelationsSqlGenerator = oxNew(\OxidEsales\Eshop\Core\Element2ShopRelationsSqlGenerator::class);

        list($sSql, $aParams) = $oShopRelationsSqlGenerator->getSqlForAddToShop($sItemTable, $iItemId, $iShopId);

        $this->_assertSqlInsertIntoTable($sSql, $sMappingTable);
        //$this->assertEquals(array($iShopId, $iItemId), $aParams);
    }

    /**
     * Tests get SQL for removing item from shop.
     *
     * @param string $sItemTable    Item table.
     * @param string $sMappingTable Item mapping table.
     *
     * @dataProvider dpTestGetMappingTable
     */
    public function testGetSqlForRemoveFromShop($sItemTable, $sMappingTable)
    {
        $iItemId = 123;
        $iShopId = 45;

        $oShopRelationsSqlGenerator = oxNew(\OxidEsales\Eshop\Core\Element2ShopRelationsSqlGenerator::class);

        list($sSql, $aParams) = $oShopRelationsSqlGenerator->getSqlForRemoveFromShop($sItemTable, $iItemId, $iShopId);

        $this->_assertSqlDeleteFromTable($sSql, $sMappingTable);
        $expected = [
            ':oxshopid' => $iShopId,
            ':oxid' => $iItemId
        ];
        $this->assertEquals($expected, $aParams);
    }

    /**
     * Tests get SQL for removing all items by type from shop.
     */
    public function testGetSqlForRemoveFromAllShops()
    {
        $sItemTable = 'oxarticles';
        $iItemId = 123;

        $oShopRelationsSqlGenerator = oxNew(\OxidEsales\Eshop\Core\Element2ShopRelationsSqlGenerator::class);

        $aSql = $oShopRelationsSqlGenerator->getSqlForRemoveFromAllShops($sItemTable, $iItemId);

        $this->assertTrue(is_array($aSql));
        $this->assertEquals(2, count($aSql));
        $this->assertEquals([':oxid' => $iItemId], $aSql[1]);
    }

    /**
     * Tests get SQL for inheriting items by type to sub shop from parent shop.
     *
     * @param string $sItemTable    Item table.
     * @param string $sMappingTable Item mapping table.
     *
     * @dataProvider dpTestGetMappingTable
     */
    public function testGetSqlForInheritFromShop($sItemTable, $sMappingTable)
    {
        $iSubShopId = 123;
        $iParentShopId = 45;

        $oShopRelationsSqlGenerator = oxNew(\OxidEsales\Eshop\Core\Element2ShopRelationsSqlGenerator::class);

        list($sSql, $aParams) = $oShopRelationsSqlGenerator
            ->getSqlForInheritFromShop($sItemTable, $iParentShopId, $iSubShopId);

        $this->_assertSqlInsertIntoTable($sSql, $sMappingTable);
        // $this->assertEquals(array($iSubShopId, $iParentShopId, $iParentShopId), $aParams);
    }

    /**
     * Tests get SQL for removing items by type from sub shop that were inherited from parent shop.
     *
     * @param string $sItemTable    Item table.
     * @param string $sMappingTable Item mapping table.
     *
     * @dataProvider dpTestGetMappingTable
     */
    public function testGetSqlForRemoveInheritedFromShop($sItemTable, $sMappingTable)
    {
        $iSubShopId = 123;
        $iParentShopId = 45;

        $oShopRelationsSqlGenerator = oxNew(\OxidEsales\Eshop\Core\Element2ShopRelationsSqlGenerator::class);

        list($sSql, $aParams) = $oShopRelationsSqlGenerator
            ->getSqlForRemoveInheritedFromShop($sItemTable, $iParentShopId, $iSubShopId);

        $this->_assertSqlDeleteFromTable($sSql, $sMappingTable);
        $expected = [
            ':subShopId' => $iSubShopId,
            ':parentShopId' => $iParentShopId
        ];
        $this->assertEquals($expected, $aParams);
    }

    /**
     * Tests get SQL for copying inheritance information from one item to another.
     */
    public function testGetSqlForCopyInheritance()
    {
        $sItemTable = 'oxarticles';
        $iSourceItemId = 123;
        $iDestinationItemId = 456;

        $oShopRelationsSqlGenerator = oxNew(\OxidEsales\Eshop\Core\Element2ShopRelationsSqlGenerator::class);

        $aSql = $oShopRelationsSqlGenerator->getSqlForCopyInheritance($sItemTable, $iSourceItemId, $iDestinationItemId);

        $this->assertTrue(is_array($aSql));
        $this->assertEquals(2, count($aSql));
        $this->assertEquals(array($iDestinationItemId, $iSourceItemId), $aSql[1]);
    }

    /**
     * Tests get SQL for checking if item is in one of the shops.
     */
    public function testGetSqlForIsInShop()
    {
        $sItemTable = 'oxarticles';
        $iItemId = 123;
        $aSubshopIds = array(1, 2, 5);

        $oShopRelationsSqlGenerator = oxNew(\OxidEsales\Eshop\Core\Element2ShopRelationsSqlGenerator::class);

        $aSql = $oShopRelationsSqlGenerator->getSqlForIsInShop($sItemTable, $iItemId, $aSubshopIds);

        $this->assertTrue(is_array($aSql));
        $this->assertEquals(2, count($aSql));
        $this->assertEquals(array($iItemId, 1, 2, 5), $aSql[1]);
    }

    /**
     * Tests get SQL for getting shop IDs for item.
     */
    public function testGetSqlForGetShopIds()
    {
        $sItemTable = 'oxarticles';
        $iItemId = 123;

        $oShopRelationsSqlGenerator = oxNew(\OxidEsales\Eshop\Core\Element2ShopRelationsSqlGenerator::class);

        $aSql = $oShopRelationsSqlGenerator->getSqlForGetShopIds($sItemTable, $iItemId);

        $this->assertTrue(is_array($aSql));
        $this->assertEquals(2, count($aSql));
        $this->assertEquals(array($iItemId), $aSql[1]);
    }

    /**
     * Tests get SQL for getting shop IDs for item.
     */
    public function testGetSqlForGetShopIdsForObject2Category()
    {
        $sItemTable = 'oxobject2category';
        $iItemId = 123;

        $oShopRelationsSqlGenerator = oxNew(\OxidEsales\Eshop\Core\Element2ShopRelationsSqlGenerator::class);

        $aSql = $oShopRelationsSqlGenerator->getSqlForGetShopIds($sItemTable, $iItemId);
        $this->assertTrue(is_array($aSql));
        $this->assertEquals(2, count($aSql));
        $this->assertEquals(array($iItemId), $aSql[1]);
    }

    /**
     * Tests get SQL for adding all items to shop.
     */
    public function testGetSqlForInheritAllElements()
    {
        $sItemTable = 'oxarticles';
        $sShopId = 45;

        $oShopRelationsSqlGenerator = oxNew(\OxidEsales\Eshop\Core\Element2ShopRelationsSqlGenerator::class);

        $aSql = $oShopRelationsSqlGenerator->getSqlForInheritAllElements($sShopId, $sItemTable);

        $this->assertTrue(is_array($aSql));
        $this->assertEquals(2, count($aSql));
        $this->assertEquals(array($sShopId), $aSql[1]);
    }

    /**
     * Tests get SQL for adding all object2category items to shop.
     */
    public function testGetSqlForInheritAllElementsForObject2Category()
    {
        $sItemTable = 'oxobject2category';
        $sShopId = 45;

        $oShopRelationsSqlGenerator = oxNew(\OxidEsales\Eshop\Core\Element2ShopRelationsSqlGenerator::class);

        $aSql = $oShopRelationsSqlGenerator->getSqlForInheritAllElements($sShopId, $sItemTable);

        $this->assertTrue(is_array($aSql));
        $this->assertEquals(2, count($aSql));
        $this->assertEquals(array($sShopId, $sShopId), $aSql[1]);
    }

    /**
     * Tests get SQL for adding all items to shop.
     */
    public function testGetSqlForRemoveAllElements()
    {
        $sItemTable = 'oxarticles';
        $sShopId = 45;

        $oShopRelationsSqlGenerator = oxNew(\OxidEsales\Eshop\Core\Element2ShopRelationsSqlGenerator::class);

        $aSql = $oShopRelationsSqlGenerator->getSqlForRemoveAllElements($sShopId, $sItemTable);

        $this->assertTrue(is_array($aSql));
        $this->assertEquals(2, count($aSql));
        $this->assertEquals([':oxshopid' => $sShopId], $aSql[1]);
    }
}
