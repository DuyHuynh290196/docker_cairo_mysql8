<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Tests\Unit\Model;

use OxidEsales\Eshop\Core\Registry;
use \oxDb;
use \oxAttributeList;
use \OxidEsales\TestingLibrary\UnitTestCase;

class AttributeListTest extends UnitTestCase
{
    /**
     * Tear down the fixture.
     *
     * @return null
     */
    protected function tearDown(): void
    {
        $myDB = \OxidEsales\Eshop\Core\DatabaseProvider::getDB();

        $myDB->Execute('delete from oxattribute where oxid = "test%" ');
        $myDB->Execute('delete from oxobject2attribute where oxid = "test%" ');

        $myDB->Execute("update oxattribute set oxdisplayinbasket = 0 where oxid = '8a142c3f0b9527634.96987022' ");

        parent::tearDown();
    }

    /**
     * Test load attributes with sorting.
     *
     * @return null
     */
    public function testLoadAttributesWithSort()
    {
        Registry::getLang()->setBaseLanguage(0);

        $myDB = \OxidEsales\Eshop\Core\DatabaseProvider::getDB();

        $sSql = "insert into oxattribute (oxid, oxshopid, oxtitle, oxpos ) values ('test3', '1', 'test3', '3'), ('test1', '1', 'test1', '1'), ('test2', '1', 'test2', '2')";
        $myDB->execute($sSql);
        $sSql = "insert into oxattribute2shop (`OXSHOPID`, `OXMAPOBJECTID`) values (1, (select `OXMAPID` from oxattribute where `OXID` = 'test3'))";
        $myDB->execute($sSql);
        $sSql = "insert into oxattribute2shop (`OXSHOPID`, `OXMAPOBJECTID`) values (1, (select `OXMAPID` from oxattribute where `OXID` = 'test1'))";
        $myDB->execute($sSql);
        $sSql = "insert into oxattribute2shop (`OXSHOPID`, `OXMAPOBJECTID`) values (1, (select `OXMAPID` from oxattribute where `OXID` = 'test2'))";
        $myDB->execute($sSql);

        $sArtId = 'testArt';
        $sSql = "insert into oxobject2attribute (oxid, oxobjectid, oxattrid, oxvalue ) values ('test3', '$sArtId', 'test3', '3'), ('test1', '$sArtId', 'test1', '1'), ('test2', '$sArtId', 'test2', '2')";
        $myDB->execute($sSql);

        $oAttrList = oxNew(\OxidEsales\Eshop\Application\Model\AttributeList::class);
        $oAttrList->loadAttributes($sArtId);
        $iCnt = 1;
        foreach ($oAttrList as $sId => $aAttr) {
            $this->assertEquals('test' . $iCnt, $sId);
            $this->assertEquals((string) $iCnt, $aAttr->oxattribute__oxvalue->value);
            $iCnt++;
        }
    }

    public function testGetCategoryAttributes()
    {
        $sCategoryId = '30e44ab85808a1f05.26160932';
        $sAttributeId = '8a142c3f14ef22a14.79693851';

        $oAttrList = oxNew(\OxidEsales\Eshop\Application\Model\AttributeList::class);
        $oAttrList->getCategoryAttributes($sCategoryId, 1);
        $oAttribute = $oAttrList->offsetGet($sAttributeId);

        $this->assertEquals(1, $oAttrList->count());
        $this->assertEquals(4, count($oAttribute->getValues()));
    }
}
