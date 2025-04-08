<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Tests\Unit\Core;

use Exception;
use oxDB;
use oxField;
use OxidEsales\TestingLibrary\UnitTestCase;
use oxTestModules;

/**
 * Test oxCache module
 */
class modoxCacheForBase extends \OxidEsales\EshopEnterprise\Core\Cache\DynamicContent\ContentCache
{
    /**
     * Throw an exception on reset.
     *
     * @return null
     */
    public function reset($blResetFileCache = true)
    {
        throw new Exception('xxx', 111);
    }
}

class BaseTest extends UnitTestCase
{
    /**
     * Initialize the fixture.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->cleanUpTable('oxactions');
        $this->cleanUpTable('oxattribute');
        $this->cleanUpTable('oxarticles');
        $this->cleanUpTable('oxnews');

        $this->getConfig();
        $this->getSession();

        $this->cleanUpTable('oxobjectrights');
    }

    /**
     * Tear down the fixture.
     */
    protected function tearDown(): void
    {
        $this->cleanUpTable('oxactions');
        $this->cleanUpTable('oxattribute');
        $this->cleanUpTable('oxarticles');
        $this->cleanUpTable('oxnews');

        $this->cleanUpTable('oxobjectrights');

        oxRemClassModule('modoxCacheForBase');
        parent::teardown();
    }


    /**
     * Testing cache reset func.
     */
    public function testResetCacheAdminMode()
    {
        oxTestModules::addModuleObject('oxCache', new modoxCacheForBase);

        $oBase = oxNew(\OxidEsales\Eshop\Core\Model\BaseModel::class);
        $oBase->setAdminMode(true);
        try {
            $oBase->UNITresetCache();
        } catch (Exception $oE) {
            $this->assertEquals(111, $oE->getCode());

            return;
        }
        $this->fail('error exec. testResetCacheAdminMode');
    }

    /**
     * Testing cache reset in non admin mode.
     */
    public function testResetCacheNonAdminMode()
    {
        oxTestModules::addModuleObject('oxCache', new modoxCacheForBase);

        $oBase = oxNew(\OxidEsales\Eshop\Application\Model\Review::class);
        $oBase->setAdminMode(false);
        try {
            $oBase->UNITresetCache();
        } catch (Exception $oE) {
            $this->fail('error exec. testResetCacheNonAdminMode');
        }
    }

    /**
     * Test get rights sql snippet in admin mode without rights snippet.
     */
    public function testGetSqlRightsSnippetAdminModeNoRightsSnippet()
    {
        $oBase = $this->getMock(\OxidEsales\Eshop\Core\Model\BaseModel::class, array('isAdmin'));
        $oBase->expects($this->any())->method('isAdmin')->will($this->returnValue(true));
        $this->assertEquals('', $oBase->UNITgetSqlRightsSnippet());
    }

    /**
     * Test get rights sql snippet with disable rights&roles.
     */
    public function testGetSqlRightsSnippetRROffNoRightsSnippet()
    {
        $oBase = $this->getMock(\OxidEsales\Eshop\Core\Model\BaseModel::class, array('getRights'));
        $oBase->expects($this->any())->method('getRights')->will($this->returnValue(null));
        $this->assertEquals('', $oBase->UNITgetSqlRightsSnippet());
    }

    /**
     * Test get rights sql snippet with enables rights&roles for non admin.
     */
    public function testGetSqlRightsSnippetRROnNonAdmin()
    {
        $sQ = " and ( ( ";
        $sQ .= "( select oxobjectrights.oxobjectid from oxobjectrights where oxobjectrights.oxobjectid = xxx.oxid and oxobjectrights.oxaction = 1 limit 1 ) is null ";

        $aGroupIdx = array(1, 60, 120);
        if (is_array($aGroupIdx) && count($aGroupIdx)) {
            $sSel = "";
            $iCnt = 0;
            foreach ($aGroupIdx as $iOffset => $iBitMap) {
                if ($iCnt) {
                    $sSel .= " | ";
                }
                $sSel .= " ( oxobjectrights.oxgroupidx & $iBitMap and oxobjectrights.oxoffset = $iOffset ) ";
                $iCnt++;
            }

            $sQ .= ") or (";
            $sQ .= "( select oxobjectrights.oxobjectid from oxobjectrights where oxobjectrights.oxobjectid = xxx.oxid and oxobjectrights.oxaction = 1 and $sSel limit 1 ) is not null ";
        }

        $sQ .= " ) ) ";

        $oRR = $this->getMock(\OxidEsales\Eshop\Application\Model\Rights::class, array('getUserGroupIndex'));
        $oRR->expects($this->any())->method('getUserGroupIndex')->will($this->returnValue($aGroupIdx));

        $oBase = $this->getMock(\OxidEsales\Eshop\Core\Model\BaseModel::class, array('isAdmin', 'getRights', 'getViewName'));
        $oBase->expects($this->any())->method('isAdmin')->will($this->returnValue(false));
        $oBase->expects($this->any())->method('getRights')->will($this->returnValue($oRR));
        $oBase->expects($this->any())->method('getViewName')->will($this->returnValue('xxx'));
        $this->assertEquals($sQ, $oBase->UNITgetSqlRightsSnippet());
    }

    /**
     * Testing init forcing core table usage.
     */
    public function testInitForceCoreTableUsage()
    {
        $oBase = oxNew(\OxidEsales\Eshop\Core\Model\BaseModel::class);
        $oBase->setForceCoreTableUsage(true);
        $oBase->init("oxarticles");

        $this->assertEquals("oxarticles", $oBase->getCoreTableName());
        $this->assertEquals("oxv_oxarticles", $oBase->getViewName());

        $oBase->setForceCoreTableUsage(false);
        $this->assertEquals("oxv_oxarticles_1", $oBase->getViewName());
    }

    /**
     * Test get update fields when some of them are denied by rights&roles.
     */
    public function testGetUpdateFieldsSomeFieldDeniedByRR()
    {
        $oBase = $this->getMock(\OxidEsales\Eshop\Core\Model\BaseModel::class, array('canUpdateField'));
        $oBase->expects($this->any())->method('canUpdateField')->will($this->onConsecutiveCalls(true, false, false, false));
        $oBase->init('oxactions');
        $this->assertEquals("oxid = ''", $oBase->UNITgetUpdateFields(false));
    }

    /**
     * Test set shop id with non numeric value.
     */
    public function testSetShopIdNonNumericEE()
    {
        $oBase = oxNew(\OxidEsales\Eshop\Core\Model\BaseModel::class);
        $oBase->setShopId("testShopId");
        $this->assertEquals(1, $oBase->getShopId());
    }

    /**
     * Test get view name.
     */
    public function testGetViewName()
    {
        $oBase = oxNew(\OxidEsales\Eshop\Core\Model\BaseModel::class);
        $oBase->init("oxarticles");

        $this->assertEquals("oxv_oxarticles", $oBase->getViewName(1));
        $this->assertEquals("oxv_oxarticles_1", $oBase->getViewName(0));
        $this->assertEquals("oxv_oxarticles_1", $oBase->getViewName());
        $oBase->setForceCoreTableUsage(1);
        $this->assertEquals("oxv_oxarticles_1", $oBase->getViewName(0));
        $this->assertEquals("oxv_oxarticles", $oBase->getViewName(1));
        $this->assertEquals("oxv_oxarticles", $oBase->getViewName());
        $this->assertEquals("oxv_oxarticles_1", $oBase->getViewName(0));
    }

    /**
     * Test set force core table usage.
     */
    public function testSetForceCoreTableUsage()
    {
        $oBase = oxNew(\OxidEsales\Eshop\Core\Model\BaseModel::class);
        $oBase->setForceCoreTableUsage(true);
        $this->assertTrue($oBase->getForceCoreTableUsage());
        $oBase->setForceCoreTableUsage(false);
        $this->assertFalse($oBase->getForceCoreTableUsage());
    }

    /**
     * Test set disable shop check.
     */
    public function testSetDisableShopCheck()
    {
        $oBase = oxNew(\OxidEsales\Eshop\Core\Model\BaseModel::class);
        $oBase->setDisableShopCheck(true);
        $this->assertTrue($oBase->getDisableShopCheck());
        $oBase->setDisableShopCheck(false);
        $this->assertFalse($oBase->getDisableShopCheck());
    }

    /**
     * Test assign without active shop id.
     */
    public function testAssignWithoutActShopId()
    {
        $oDB = \OxidEsales\Eshop\Core\DatabaseProvider::getDb(\OxidEsales\Eshop\Core\DatabaseProvider::FETCH_MODE_ASSOC);
        $oBase = oxNew(\OxidEsales\Eshop\Core\Model\BaseModel::class);
        $oBase->init("oxarticles");
        $oBase->setId("2000");
        $oBase->setConfig(null);
        $select = "select * from oxarticles where oxid = '2000'";
        $rs = $oDB->select($select);
        $oBase->assign($rs->fields);
        $this->assertEquals("2000", $oBase->getId());
        $this->assertFalse($oBase->isDerived());
    }

    /**
     * Test assign with wrong active shop id.
     */
    public function testAssignWrongActShopId()
    {
        $oConfig = $this->getMock(\OxidEsales\Eshop\Core\Config::class, array('getShopId'), array(), '', false);
        $oConfig->expects($this->any())->method('getShopId')->will($this->returnValue(2));

        $oDB = \OxidEsales\Eshop\Core\DatabaseProvider::getDb(\OxidEsales\Eshop\Core\DatabaseProvider::FETCH_MODE_ASSOC);
        $oBase = oxNew(\OxidEsales\Eshop\Core\Model\BaseModel::class);
        $oBase->init("oxarticles");
        $oBase->setId("2000");
        $oBase->setConfig($oConfig);
        $select = "select * from oxarticles where oxid = '2000'";
        $rs = $oDB->select($select);
        $oBase->assign($rs->fields);
        $this->assertEquals("2000", $oBase->getId());
        $this->assertTrue($oBase->isDerived());
    }

    /**
     * Test assign when denied by rights & roles.
     */
    public function testAssignDeniedByRR()
    {
        $oCategory = $this->getMock(\OxidEsales\Eshop\Application\Model\Actions::class, array('canRead', '_getFieldLongName', '_setFieldData'), array(), '', false);
        $oCategory->expects($this->once())->method('canRead')->will($this->returnValue(false));
        $oCategory->expects($this->never())->method('_getFieldLongName');
        $oCategory->expects($this->never())->method('_setFieldData');

        $this->assertFalse($oCategory->assign(array('xxx')));
    }

    /**
     * Test set field data for double field type.
     */
    public function testSetFieldDataForDoubleFieldTypeEE()
    {
        $oBase = oxNew(\OxidEsales\Eshop\Core\Model\BaseModel::class);
        $oBase->setAdminMode(true);
        $oBase->init("oxarticles");
        $oBase->load("2000");
        $rs = array("oxid" => "2000", "oxprice" => "29,9");
        foreach ($rs as $name => $value) {
            $oBase->UNITsetFieldData($name, $value);
        }
        $this->assertEquals(29.9, $oBase->oxarticles__oxprice->value);
    }

    /**
     * Test set field data for double field type.
     */
    public function testSetFieldDataForDoubleFieldTypePE()
    {
        $oBase = oxNew(\OxidEsales\Eshop\Core\Model\BaseModel::class);
        $oBase->setAdminMode(true);
        $oBase->init("oxarticles");
        $oBase->load("1849");
        $rs = array("oxid" => "1849", "oxprice" => "89,9");
        foreach ($rs as $name => $value) {
            $oBase->UNITsetFieldData($name, $value);
        }
        $this->assertEquals(89.9, $oBase->oxarticles__oxprice->value);
    }

    /**
     * Test set field data denied by rights & roles.
     */
    public function testSetFieldDataDeniedByRR()
    {
        $oBase = $this->getMock(\OxidEsales\Eshop\Core\Model\BaseModel::class, array('canReadField', '_getFieldLongName', '_getFieldStatus', '_addField'));
        $oBase->expects($this->once())->method('canReadField')->will($this->returnValue(false));
        $oBase->expects($this->never())->method('_getFieldLongName');
        $oBase->expects($this->never())->method('_getFieldStatus');
        $oBase->expects($this->never())->method('_addField');
        $oBase->UNITsetFieldData('xxx', 'yyy');
    }

    /**
     * Test build select string without shop id.
     *
     * @return null
     */
    public function testBuildSelectStringWithoutShopId()
    {
        $oBase = oxNew(\OxidEsales\Eshop\Core\Model\BaseModel::class);
        $oBase->init("oxattribute");
        $oBase->setDisableShopCheck(true);

        $sSelect = $oBase->buildSelectString(array("oxid" => "111"));
        $sSelect = str_replace("  ", " ", $sSelect);
        $this->assertEquals("select `oxv_oxattribute_1`.`oxid`, `oxv_oxattribute_1`.`oxmapid`, `oxv_oxattribute_1`.`oxshopid`, `oxv_oxattribute_1`.`oxtitle`, `oxv_oxattribute_1`.`oxtitle_1`, `oxv_oxattribute_1`.`oxtitle_2`, `oxv_oxattribute_1`.`oxtitle_3`, `oxv_oxattribute_1`.`oxpos`, `oxv_oxattribute_1`.`oxtimestamp`, `oxv_oxattribute_1`.`oxdisplayinbasket` from oxv_oxattribute_1 where 1 and oxid = '111'", $sSelect);
    }

    /**
     * Test build select string without shop id.
     *
     * @return null
     */
    public function  testBuildSelectStringWithShopId()
    {
        $oBase = oxNew(\OxidEsales\Eshop\Core\Model\BaseModel::class);
        $oBase->init("oxattribute");
        $oBase->setDisableShopCheck(false);

        $sSelect = $oBase->buildSelectString(array("oxid" => "111"));
        $sSelect = str_replace("  ", " ", $sSelect);
        $this->assertEquals("select `oxv_oxattribute_1`.`oxid`, `oxv_oxattribute_1`.`oxmapid`, `oxv_oxattribute_1`.`oxshopid`, `oxv_oxattribute_1`.`oxtitle`, `oxv_oxattribute_1`.`oxtitle_1`, `oxv_oxattribute_1`.`oxtitle_2`, `oxv_oxattribute_1`.`oxtitle_3`, `oxv_oxattribute_1`.`oxpos`, `oxv_oxattribute_1`.`oxtimestamp`, `oxv_oxattribute_1`.`oxdisplayinbasket` from oxv_oxattribute_1 where 1 and oxid = '111' and oxv_oxattribute_1.oxshopid = '1'", $sSelect);
    }

    /**
     * Test delete denied by rights & roles.
     *
     * @return null
     */
    public function  testDeleteWithDeniedByRR()
    {
        $oBase = $this->getMock(\OxidEsales\Eshop\Core\Model\BaseModel::class, array('canDelete', 'isDerived', 'onChange'));
        $oBase->expects($this->any())->method('canDelete')->will($this->returnValue(false));
        $oBase->expects($this->any())->method('isDerived')->will($this->returnValue(false));
        $oBase->expects($this->never())->method('onChange');

        // now deleting and checking for records in DB
        $this->assertFalse($oBase->delete("_test"));
    }

    /**
     * Test unassign from shop.
     */
    public function  testUnassignFromShop()
    {
        $myDB = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();
        $sInsert = "Insert into oxattribute (`OXID`,`OXSHOPID`,`OXTITLE`) values ('_test',1,'test')";
        $this->addToDatabase($sInsert, 'oxattribute');
        $oBase = oxNew(\OxidEsales\Eshop\Core\Model\BaseModel::class);
        $oBase->init('oxattribute');
        $oBase->setId('_test');
        $sQ = 'select count(*) from oxv_oxattribute_1 where oxid = "_test" ';
        $this->assertEquals(1, (int) $myDB->getOne($sQ));
        $sResult = $oBase->unassignFromShop(1);
        $this->assertEquals(0, (int) $myDB->getOne($sQ));
        $this->assertTrue($sResult);
    }

    /**
     * Test unassign from shop when shop is not set.
     */
    public function  testUnassignFromShopIfShopNotSet()
    {
        $oConfig = $this->getMock(\OxidEsales\Eshop\Core\Config::class, array('getShopId'), array(), '', false);
        $oConfig->expects($this->any())->method('getShopId')->will($this->returnValue(false));

        $myDB = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();
        $sInsert = "Insert into oxattribute (`OXID`,`OXSHOPID`,`OXTITLE`) values ('_test',1,'test')";
        $this->addToDatabase($sInsert, 'oxattribute');
        $oBase = oxNew(\OxidEsales\Eshop\Core\Model\BaseModel::class);
        $oBase->init('oxattribute');
        $oBase->setId('_test');
        $oBase->setConfig($oConfig);
        $sQ = 'select count(*) from oxv_oxattribute_1 where oxid = "_test" ';
        $this->assertEquals(1, (int) $myDB->getOne($sQ));
        $sResult = $oBase->unassignFromShop(null);
        $this->assertEquals(1, (int) $myDB->getOne($sQ));
        $this->assertFalse($sResult);
    }

    /**
     * Test unassign from shop when shop is not set.
     */
    public function  testUnassignWithSetOxid()
    {
        $myDB = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();
        $sInsert = "Insert into oxattribute (`OXID`,`OXSHOPID`,`OXTITLE`) values ('_test',1,'test')";
        $this->addToDatabase($sInsert, 'oxattribute');
        $oBase = oxNew(\OxidEsales\Eshop\Core\Model\BaseModel::class);
        $oBase->init("oxattribute");
        $oBase->setId("_test");
        $sQ = 'select count(*) from oxv_oxattribute_1 where oxid = "_test" ';
        $this->assertEquals(1, (int) $myDB->getOne($sQ));
        $sResult = $oBase->unassignFromShop(1);
        $this->assertEquals(0, (int) $myDB->getOne($sQ));
        $this->assertTrue($sResult);
    }

    /**
     * Test unassign from shop without oxid.
     */
    public function  testUnassignWithoutOxid()
    {
        $myDB = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();
        $sInsert = "Insert into oxattribute (`OXID`,`OXSHOPID`,`OXTITLE`) values ('_test',1,'test')";
        $this->addToDatabase($sInsert, 'oxattribute');
        $oBase = oxNew(\OxidEsales\Eshop\Core\Model\BaseModel::class);
        $oBase->init("oxattribute");
        $sQ = 'select count(*) from oxv_oxattribute_1 where oxid = "_test" ';
        $this->assertEquals(1, (int) $myDB->getOne($sQ));
        $sResult = $oBase->unassignFromShop(1);
        $this->assertEquals(1, (int) $myDB->getOne($sQ));
        $this->assertFalse($sResult);
    }

    /**
     * Test unassign with set shopid.
     */
    public function  testUnassignSettingShopId()
    {
        $myDB = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();
        $sInsert = "Insert into oxattribute (`OXID`,`OXSHOPID`,`OXTITLE`) values ('_test',1,'test')";
        $rez = $myDB->Execute($sInsert);
        $oBase = oxNew(\OxidEsales\Eshop\Core\Model\BaseModel::class);
        $oBase->init("oxattribute");
        $oBase->setId('_test');
        $sResult = $oBase->unassignFromShop('2');
        $this->assertEquals(1, (int) $myDB->getOne('select count(*) from oxattribute where oxid = "_test"'));
        $this->assertEquals(true, $sResult);
    }

    /**
     * Test update denied by rights & roles.
     */
    public function testUpdateDeniedByRR()
    {
        $oBase = $this->getMock(\OxidEsales\Eshop\Core\Model\BaseModel::class, array('isDerived', 'canUpdate', 'getId', 'beforeUpdate'));
        $oBase->expects($this->any())->method('isDerived')->will($this->returnValue(false));
        $oBase->expects($this->once())->method('canUpdate')->will($this->returnValue(false));
        $oBase->expects($this->never())->method('getId');
        $oBase->expects($this->never())->method('beforeUpdate');

        $oBase->UNITupdate();
    }

    /**
     * Test can update in non admin mode.
     */
    public function testCanUpdateNonAdmin()
    {
        $oBase = $this->getMock(\OxidEsales\Eshop\Core\Model\BaseModel::class, array('isAdmin', 'getRights'));
        $oBase->expects($this->once())->method('isAdmin')->will($this->returnValue(false));
        $oBase->expects($this->never())->method('getRights');
        $this->assertTrue($oBase->canUpdate());
    }

    /**
     * Test can update in admin mode with rights & roles disabled.
     */
    public function testCanUpdateAdminButRRisOff()
    {
        $oBase = $this->getMock(\OxidEsales\Eshop\Core\Model\BaseModel::class, array('isAdmin', 'getRights'));
        $oBase->expects($this->once())->method('isAdmin')->will($this->returnValue(true));
        $oBase->expects($this->once())->method('getRights')->will($this->returnValue(null));
        $this->assertTrue($oBase->canUpdate());
    }

    /**
     * Test can update in admin mode.
     */
    public function testCanUpdateAdmin()
    {
        $oRights = $this->getMock(\OxidEsales\Eshop\Core\AdminRights::class, array('hasRights'));
        $oRights->expects($this->once())->method('hasRights')->will($this->returnValue(false));

        $oBase = $this->getMock(\OxidEsales\Eshop\Core\Model\BaseModel::class, array('isAdmin', 'getRights'));
        $oBase->expects($this->once())->method('isAdmin')->will($this->returnValue(true));
        $oBase->expects($this->once())->method('getRights')->will($this->returnValue($oRights));
        $this->assertFalse($oBase->canUpdate());
    }

    /**
     * Test can update field with rights & roles disabled.
     */
    public function testCanUpdateFieldRrIsOff()
    {
        $oBase = oxNew(\OxidEsales\Eshop\Core\Model\BaseModel::class);
        $this->assertTrue($oBase->canUpdateField('xxx'));
    }

    /**
     * Test can update field.
     */
    public function testCanUpdateField()
    {
        $oRights = $this->getMock(\OxidEsales\Eshop\Core\AdminRights::class, array('hasRights'));
        $oRights->expects($this->once())->method('hasRights')->will($this->returnValue(false));

        $oBase = $this->getMock(\OxidEsales\Eshop\Application\Model\Article::class, array('getRights', 'isAdmin'), array(), '', false);
        $oBase->expects($this->once())->method('getRights')->will($this->returnValue($oRights));
        $oBase->expects($this->once())->method('isAdmin')->will($this->returnValue(true));
        $this->assertFalse($oBase->canUpdateField('oxactive'));
    }

    /**
     * Test can read in non admin mode.
     */
    public function testCanReadNonAdmin()
    {
        $oBase = $this->getMock(\OxidEsales\Eshop\Core\Model\BaseModel::class, array('isAdmin', 'getRights'));
        $oBase->expects($this->once())->method('isAdmin')->will($this->returnValue(false));
        $oBase->expects($this->never())->method('getRights');

        $this->assertTrue($oBase->canRead());
    }

    /**
     * Test can read in admin mode with rights & roles disabled.
     */
    public function testCanReadAdminButRRisOff()
    {
        $oBase = $this->getMock(\OxidEsales\Eshop\Core\Model\BaseModel::class, array('isAdmin', 'getRights'));
        $oBase->expects($this->once())->method('isAdmin')->will($this->returnValue(true));
        $oBase->expects($this->once())->method('getRights')->will($this->returnValue(null));

        $this->assertTrue($oBase->canRead());
    }

    /**
     * Test can read in admin mode.
     */
    public function testCanReadAdmin()
    {
        $oRights = $this->getMock(\OxidEsales\Eshop\Core\AdminRights::class, array('hasRights'));
        $oRights->expects($this->once())->method('hasRights')->will($this->returnValue(false));

        $oBase = $this->getMock(\OxidEsales\Eshop\Core\Model\BaseModel::class, array('isAdmin', 'getRights'));
        $oBase->expects($this->once())->method('isAdmin')->will($this->returnValue(true));
        $oBase->expects($this->once())->method('getRights')->will($this->returnValue($oRights));

        $this->assertFalse($oBase->canRead());
    }

    /**
     * Test can read field in non admin mode.
     */
    public function testCanReadFieldNonAdmin()
    {
        $oBase = $this->getMock(\OxidEsales\Eshop\Core\Model\BaseModel::class, array('isAdmin', 'getRights'));
        $oBase->expects($this->once())->method('isAdmin')->will($this->returnValue(false));
        $oBase->expects($this->never())->method('getRights');

        $this->assertTrue($oBase->canReadField('xxx'));
    }

    /**
     * Test can read field in admin mode with rights & roles disabled.
     *
     * @return null
     */
    public function testCanReadFieldAdminButRRisOff()
    {
        $oBase = $this->getMock(\OxidEsales\Eshop\Core\Model\BaseModel::class, array('isAdmin', 'getRights'));
        $oBase->expects($this->once())->method('isAdmin')->will($this->returnValue(true));
        $oBase->expects($this->once())->method('getRights')->will($this->returnValue(null));

        $this->assertTrue($oBase->canReadField('xxx'));
    }

    /**
     * Test can read field in admin mode.
     *
     * @return null
     */
    public function testCanReadFieldAdmin()
    {
        $oRights = $this->getMock(\OxidEsales\Eshop\Core\AdminRights::class, array('hasRights'));
        $oRights->expects($this->once())->method('hasRights')->will($this->returnValue(false));

        $oBase = $this->getMock(\OxidEsales\Eshop\Core\Model\BaseModel::class, array('isAdmin', 'getRights'));
        $oBase->expects($this->once())->method('isAdmin')->will($this->returnValue(true));
        $oBase->expects($this->once())->method('getRights')->will($this->returnValue($oRights));

        $this->assertFalse($oBase->canReadField('xxx'));
    }

    /**
     * Test can insert with rights & roles disabled.
     */
    public function testCanInsertRrIsOff()
    {
        $oBase = $this->getMock(\OxidEsales\Eshop\Core\Model\BaseModel::class, array('getRights', 'isAdmin'), array(), '', false);
        $oBase->expects($this->once())->method('getRights')->will($this->returnValue(null));
        $oBase->expects($this->once())->method('isAdmin')->will($this->returnValue(true));
        $this->assertTrue($oBase->canInsert());
    }

    /**
     * Test can insert.
     */
    public function  testCanInsert()
    {
        $oRights = $this->getMock(\OxidEsales\Eshop\Core\AdminRights::class, array('hasRights'));
        $oRights->expects($this->once())->method('hasRights')->will($this->returnValue(true));

        $oBase = $this->getMock(\OxidEsales\Eshop\Application\Model\Article::class, array('getRights', 'isAdmin'), array(), '', false);
        $oBase->expects($this->once())->method('getRights')->will($this->returnValue($oRights));
        $oBase->expects($this->once())->method('isAdmin')->will($this->returnValue(true));
        $oBase->canInsert();
    }

    /**
     * Test can delete disabled rights & roles musql allow.
     */
    public function  testCanDeleteDisabledRrMustAllow()
    {
        $oBase = oxNew(\OxidEsales\Eshop\Core\Model\BaseModel::class);
        $this->assertTrue($oBase->canDelete());
    }

    /**
     * Test can delete.
     */
    public function  testCanDelete()
    {
        $oRights = $this->getMock(\OxidEsales\Eshop\Core\AdminRights::class, array('hasRights'));
        $oRights->expects($this->once())->method('hasRights')->will($this->returnValue(false));

        $oBase = $this->getMock(\OxidEsales\Eshop\Application\Model\Article::class, array('getRights', 'isAdmin'), array(), '', false);
        $oBase->expects($this->once())->method('getRights')->will($this->returnValue($oRights));
        $oBase->expects($this->once())->method('isAdmin')->will($this->returnValue(true));

        $this->assertFalse($oBase->canDelete());
    }

    /**
     * Test insert with rights & roles enabled.
     */
    public function  testInsertWithRRTrue()
    {
        $myDB = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();
        $oBase = $this->getMock(\OxidEsales\Eshop\Core\Model\BaseModel::class, array('canInsert'));
        $oBase->expects($this->any())
            ->method('canInsert')
            ->will($this->returnValue(true));
        $oBase->init('oxnews');
        $oBase->oxnews__oxshortdesc = new \OxidEsales\Eshop\Core\Field("test1", \OxidEsales\Eshop\Core\Field::T_RAW);
        $sResult = $oBase->UNITinsert();
        $this->assertEquals(1, (int) $myDB->getOne('select count(*) from oxnews where oxshortdesc = "test1"'));
        $this->assertNotNull($sResult);

        //clean it
        $myDB->Execute('delete from oxnews where oxshortdesc = "test1"');
    }

    /**
     * Test insert with rights & roles disabled.
     */
    public function  testInsertWithRRFalse()
    {
        $myDB = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();
        $oBase = $this->getMock(\OxidEsales\Eshop\Core\Model\BaseModel::class, array('canInsert'));
        $oBase->expects($this->any())
            ->method('canInsert')
            ->will($this->returnValue(false));
        $oBase->init('oxnews');
        $oBase->oxnews__oxshortdesc = new \OxidEsales\Eshop\Core\Field("test1", \OxidEsales\Eshop\Core\Field::T_RAW);
        $sResult = $oBase->UNITinsert();
        $this->assertEquals(0, (int) $myDB->getOne('select count(*) from oxnews where oxshortdesc = "test1"'));
        $this->assertEquals(false, $sResult);

        //clean it
        $myDB->Execute('delete from oxnews where oxshortdesc = "test1"');
    }

    /**
     * Test get object view name.
     */
    public function  testGetObjectViewName()
    {
        $oBase = oxNew(\OxidEsales\Eshop\Core\Model\BaseModel::class);
        $oBase->setForceCoreTableUsage(false);
        $sResult = $oBase->UNITgetObjectViewName("oxarticles", "1");
        $this->assertEquals("oxv_oxarticles_1", $sResult);
    }

    /**
     * Test get object view name, forcing core table usage.
     */
    public function  testGetObjectViewNameForceCoreTblUsage()
    {
        $oBase = oxNew(\OxidEsales\Eshop\Core\Model\BaseModel::class);
        $oBase->setForceCoreTableUsage(true);
        $sResult = $oBase->UNITgetObjectViewName("oxarticles", "1");
        $this->assertEquals("oxv_oxarticles", $sResult);
    }

    /**
     * Test get object view name for multishop table.
     */
    public function  testGetObjectViewNameNotMullShopTable()
    {
        $oBase = oxNew(\OxidEsales\Eshop\Core\Model\BaseModel::class);
        $oBase->setForceCoreTableUsage(true);
        $sResult = $oBase->UNITgetObjectViewName("oxnews", "1");
        $this->assertEquals("oxv_oxnews", $sResult);
    }

    /**
     * Test can do in admin mode.
     */
    public function testCanDoIfAdmin()
    {
        $oBase = $this->getMock(\OxidEsales\Eshop\Core\Model\BaseModel::class, array('isAdmin', 'getRights', 'getId'), array(), '', false);
        $oBase->expects($this->once())->method('isAdmin')->will($this->returnValue(true));
        $oBase->expects($this->once())->method('getRights');
        $oBase->expects($this->never())->method('getId');

        $this->assertTrue($oBase->canDo());
    }

    /**
     * Test can do with id not set.
     */
    public function  testCanDoIdNotSet()
    {
        $oRights = oxNew(\OxidEsales\Eshop\Application\Model\Rights::class);

        $oBase = $this->getMock(\OxidEsales\Eshop\Core\Model\BaseModel::class, array('isAdmin', 'getRights', 'getId'), array(), '', false);
        $oBase->expects($this->once())->method('isAdmin')->will($this->returnValue(false));
        $oBase->expects($this->once())->method('getRights')->will($this->returnValue($oRights));
        $oBase->expects($this->once())->method('getId')->will($this->returnValue(null));

        $this->assertFalse($oBase->canDo());
    }

    /**
     *  Test can do with all set.
     */
    public function testCanDoAllIsSet()
    {
        $oRights = $this->getMock(\OxidEsales\Eshop\Application\Model\Rights::class, array('hasObjectRights'));
        $oRights->expects($this->once())->method('hasObjectRights')->will($this->returnValue(false));

        $oBase = $this->getMock(\OxidEsales\Eshop\Core\Model\BaseModel::class, array('isAdmin', 'getRights', 'getId'), array(), '', false);
        $oBase->expects($this->once())->method('isAdmin')->will($this->returnValue(false));
        $oBase->expects($this->once())->method('getRights')->will($this->returnValue($oRights));
        $oBase->expects($this->once())->method('getId')->will($this->returnValue('xxx'));

        $this->assertFalse($oBase->canDo());
    }

    /**
     * Test disable shop check, default is true.
     */
    public function testDisableShopCheckByDefaultIsTrue()
    {
        $oBase = oxNew(\OxidEsales\Eshop\Core\Model\BaseModel::class);
        $this->assertTrue($oBase->getDisableShopCheck());
    }

    /**
     * With #4536 bug fix access to any article from any subshop was added,
     * but it should only be accessible when shared basket is enabled.
     *
     * Tests if item is not loaded from another subshop.
     * This test is important for certain cases when item is loaded from different subshops
     */
    public function testLoadItemFromAnyShopWhenSharedBasketDisabled()
    {
        $iShopId = 2;
        $this->getDb()->execute("CREATE OR REPLACE SQL SECURITY INVOKER VIEW oxv_oxarticles_{$iShopId}_de AS
           SELECT OXID,OXMAPID,oxarticles.OXSHOPID,OXPARENTID,OXACTIVE,OXHIDDEN,OXACTIVEFROM,OXACTIVETO,OXARTNUM,OXEAN,OXDISTEAN,OXMPN,OXTITLE_1 AS OXTITLE,OXSHORTDESC_1 AS OXSHORTDESC,OXPRICE,OXBLFIXEDPRICE,OXPRICEA,OXPRICEB,OXPRICEC,OXBPRICE,OXTPRICE,OXUNITNAME,OXUNITQUANTITY,OXEXTURL,OXURLDESC_1 AS OXURLDESC,OXURLIMG,OXVAT,OXTHUMB,OXICON,OXPIC1,OXPIC2,OXPIC3,OXPIC4,OXPIC5,OXPIC6,OXPIC7,OXPIC8,OXPIC9,OXPIC10,OXPIC11,OXPIC12,OXWEIGHT,OXSTOCK,OXSTOCKFLAG,OXSTOCKTEXT_1 AS OXSTOCKTEXT,OXNOSTOCKTEXT_1 AS OXNOSTOCKTEXT,OXDELIVERY,OXINSERT,oxarticles.OXTIMESTAMP,OXLENGTH,OXWIDTH,OXHEIGHT,OXFILE,OXSEARCHKEYS_1 AS OXSEARCHKEYS,OXBUNDLEID,OXTEMPLATE,OXQUESTIONEMAIL,OXISSEARCH,OXISCONFIGURABLE,OXVARNAME_1 AS OXVARNAME,OXVARSTOCK,OXVARCOUNT,OXVARSELECT_1 AS OXVARSELECT,OXVARMINPRICE,OXVARMAXPRICE,OXFOLDER,OXSUBCLASS,OXSORT,OXSOLDAMOUNT,OXNONMATERIAL,OXFREESHIPPING,OXREMINDACTIVE,OXREMINDAMOUNT,OXAMITEMID,OXAMTASKID,OXVENDORID,OXMANUFACTURERID,OXSKIPDISCOUNTS,OXORDERINFO,OXPIXIEXPORT,OXPIXIEXPORTED,OXVPE,OXRATING,OXRATINGCNT,OXMINDELTIME,OXMAXDELTIME,OXDELTIMEUNIT,OXUPDATEPRICE, OXUPDATEPRICEA, OXUPDATEPRICEB, OXUPDATEPRICEC, OXUPDATEPRICETIME, OXISDOWNLOADABLE, OXSHOWCUSTOMAGREEMENT FROM oxarticles INNER JOIN oxarticles2shop AS t2s ON t2s.OXMAPOBJECTID = oxarticles.OXMAPID WHERE t2s.OXSHOPID = {$iShopId}");

        $oConfig = $this->getConfig();
        $oConfig->setConfigParam('blMallSharedBasket', false);
        $oConfig->setShopId($iShopId);

        $oBaseObject = oxNew(\OxidEsales\Eshop\Core\Model\MultiLanguageModel::class);
        $oBaseObject->init("oxarticles");
        $this->assertFalse($oBaseObject->load("1126"));
    }

    /**
     * With #4536 bug fix access to any article from any subshop was added,
     * but it should only be accessible when shared basket is enabled.
     *
     * Tests if item is loaded from another subshop.
     * This test is important for certain cases when item is loaded from different subshops
     */
    public function testLoadItemFromAnyShopWhenSharedBasketEnabled()
    {
        $iShopId = 2;

        $oConfig = $this->getConfig();
        $oConfig->setConfigParam('blMallSharedBasket', true);
        $oConfig->setShopId($iShopId);

        $oBaseObject = oxNew(\OxidEsales\Eshop\Core\Model\MultiLanguageModel::class);
        $oBaseObject->init("oxarticles");

        $this->assertTrue($oBaseObject->load("1126"));
        $this->assertEquals("Bar-Set ABSINTH", $oBaseObject->oxarticles__oxtitle->value);
    }

    /**
     * Test use Master DB
     */
    public function testUseMaster()
    {
        $oBase = oxNew(\OxidEsales\Eshop\Core\Model\BaseModel::class);

        //default false
        $this->assertFalse($oBase->getUseMaster());

        //set master
        $oBase->setUseMaster();
        $this->assertTrue($oBase->getUseMaster());

        //set slave
        $oBase->setUseMaster(false);
        $this->assertFalse($oBase->getUseMaster());

        //set master
        $oBase->setUseMaster(true);
        $this->assertTrue($oBase->getUseMaster());
    }
}
