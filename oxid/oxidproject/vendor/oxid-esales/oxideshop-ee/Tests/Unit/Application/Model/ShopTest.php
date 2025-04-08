<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Tests\Unit\Application\Model;

use oxDb;
use oxField;
use oxUtilsObject;
use Exception;
use OxidEsales\Eshop\Core\Registry;
use oxTestModules;
use PHPUnit\Framework\MockObject\MockObject;
use OxidEsales\EshopEnterprise\Core\DbMetaDataHandler;
use OxidEsales\EshopEnterprise\Application\Model\Shop;
use OxidEsales\EshopEnterprise\Core\Element2ShopRelations;
use oxShopViewValidator;

class ShopTest extends \OxidEsales\TestingLibrary\UnitTestCase
{
    /** @var string Test shop id. */
    protected $shopId = null;

    /**  @var array Tables for which temporary views were created during testing. */
    protected $tablesWithTemporaryViews = null;

    /** @var array Shop aware tables. */
    protected $shopAwareTables = array(
        'oxconfig', 'oxcategories', 'oxprice2article',
        'oxdelivery', 'oxvoucherseries',
        'oxnews', 'oxcontents',
        'oxobject2group', 'oxpricealarm',
        'oxactions2article', 'oxroles', 'oxfield2shop', 'oxcache'
    );

    /**
     * Initialize the fixture.
     */
    protected function setUp(): void
    {
        parent::setUp();
        $database = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();
        $config = $this->getConfig();
        $this->tablesWithTemporaryViews = $config->getConfigParam('aMultiShopTables');

        $this->shopAwareTables = array_merge($this->shopAwareTables, $this->tablesWithTemporaryViews);

        // creating test shop
        $shop = oxNew(\OxidEsales\Eshop\Application\Model\Shop::class);
        $shop->oxshops__oxactive = new \OxidEsales\Eshop\Core\Field(1, \OxidEsales\Eshop\Core\Field::T_RAW);
        $shop->oxshops__oxname = new \OxidEsales\Eshop\Core\Field('Test shop', \OxidEsales\Eshop\Core\Field::T_RAW);
        $shop->oxshops__oxdefcat = new \OxidEsales\Eshop\Core\Field('testDefaultCategory', \OxidEsales\Eshop\Core\Field::T_RAW);
        $shop->setId(9);
        $this->shopId = 9;
        $shop->save();

        $query = "
            insert into oxconfig
            (OXID, OXSHOPID, OXMODULE, OXVARNAME, OXVARTYPE, OXVARVALUE)
            select '_test_1645d4beee4fd0cd51', 9, OXMODULE, OXVARNAME, OXVARTYPE, OXVARVALUE
            from oxconfig
            where oxvarname = 'aLanguageParams'
                and oxshopid = 1
            limit 1;";
        $database->execute($query);

        $query = "
            insert into oxconfig
            (OXID, OXSHOPID, OXMODULE, OXVARNAME, OXVARTYPE, OXVARVALUE)
            select '_test_c3c8f337276ebb616', 9, OXMODULE, OXVARNAME, OXVARTYPE, OXVARVALUE
            from oxconfig
            where oxvarname = 'aLanguages'
                and oxshopid = 1
            limit 1;
        ";
        $database->execute($query);

        // making empty records to test
        foreach ($this->shopAwareTables as $sTableName) {
            if ('oxcontents' == $sTableName) {
                $query = 'replace into ' . $sTableName . ' ( oxid, oxshopid, oxloadid, oxcontent, oxcontent_1, oxcontent_2, oxcontent_3 ) values ( "' . Registry::getUtilsObject()->generateUID() . '", "' . $this->shopId . '", "' . \OxidEsales\Eshop\Core\UtilsObject::getInstance()->generateUID() . '", "", "", "", "" )';
            } else {
                $query = 'replace into ' . $sTableName . ' ( oxid, oxshopid ) values ( "' . Registry::getUtilsObject()->generateUID() . '", "' . $this->shopId . '" )';
            }
            $database->execute($query);
        }

        // making fake views
        foreach ($this->tablesWithTemporaryViews as $sTable) {
            $query = 'CREATE OR REPLACE SQL SECURITY INVOKER VIEW oxv_' . $sTable . '_' . $this->shopId . ' as select ' . $sTable . '.* from ' . $sTable;
            //echo "\n\n\n$sQ\n\n\n";
            $database->execute($query);
        }

        $query = 'CREATE OR REPLACE SQL SECURITY INVOKER VIEW oxv_oxcategories_' . $this->shopId . ' as select oxcategories.* from oxcategories';
        $database->execute($query);
    }

    /**
     * Tear down the fixture.
     */
    protected function tearDown(): void
    {
        $database = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();
        $query = 'select oxid from oxshops where oxid > 1 ';
        $shopIds = $database->getAll($query);
        $query = 'delete from oxshops where oxid > "1" ';
        $database->execute($query);

        $query = "
            delete from oxconfig
            where oxid = '_test_1645d4beee4fd0cd51' or oxid = '_test_c3c8f337276ebb616';
        ";
        $database->execute($query);

        // EE related deletes
        // deleting test records
        foreach ($this->shopAwareTables as $tableName) {
            $query = 'delete from ' . $tableName . ' where oxshopid > 1 ';
            $database->execute($query);
        }

        foreach ($shopIds as $aShopId) {
            // deleting test views if they exists
            foreach ($this->tablesWithTemporaryViews as $sMultishopTable) {
                $queryForView = 'drop view oxv_' . $sMultishopTable . '_' . $aShopId[0];
                try {
                    $database->execute($queryForView);
                } catch (Exception $e) {
                    // OK
                }
            }
        }

        $this->cleanUpTable('oxshops', 'oxname');

        parent::tearDown();
    }

    /**
     * Testing if shop was deleted. (FS#2595)
     *
     * Additionally for EE testing if related recordds
     * and views are removed
     */
    public function testDelete()
    {
        $database = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();

        $shop = oxNew(\OxidEsales\Eshop\Application\Model\Shop::class);
        $shop->load($this->shopId);
        $shop->setMultiShopTables($this->tablesWithTemporaryViews);
        try {
            $shop->delete();
        } catch (Exception $e) {
            echo 'Caught exception: ', $e->getMessage(), "\n";
        }

        // checking if shop is deleted
        $query = 'select count(*) from oxshops where oxid = "' . $this->shopId . '" ';
        $shopsCount = $database->getOne($query);
        if ($shopsCount > 0) {
            $this->fail('shop is not deleted from DB');
        }

        // checking if related records are still there
        foreach ($this->shopAwareTables as $tableName) {
            $query = 'select count(*) from ' . $tableName . ' where oxshopid = "' . $this->shopId . '" ';
            $shopsCount = $database->getOne($query);
            if ($shopsCount > 0) {
                $this->fail('shop (' . $tableName . ') related table info is not deleted');
            }
        }

        $isOk = false;

        // checking if views are still there
        foreach ($this->tablesWithTemporaryViews as $multishopTable) {
            $query = 'select 1 from oxv_' . $multishopTable . '_' . $this->shopId;
            try {
                $isOk = $database->getOne($query);
            } catch (Exception $e) {
            }
        }

        if ($isOk) {
            $this->fail('view oxv_' . $multishopTable . '_' . $this->shopId . ' is not deleted');
        }
    }

    /**
     * Trying to delete denied action by RR (EE only)
     */
    public function testDeleteDeniedByRR()
    {
        /** @var Shop|MockObject $oShop */
        $oShop = $this->getMock(\OxidEsales\Eshop\Application\Model\Shop::class, array('canDelete'));
        $oShop->expects($this->once())->method('canDelete')->will($this->returnValue(false));

        $this->assertFalse($oShop->delete('_testOrderId'));
    }

    /**
     * Testing oxShop::generateViews()
     */
    public function testGenerateViews()
    {
        $database = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();

        $shop = oxNew(\OxidEsales\Eshop\Application\Model\Shop::class);
        $shop->load($this->shopId);

        $languages = Registry::getLang()->getLanguageIds();
        $multiShopTables = $this->getConfig()->getConfigParam('aMultiShopTables');
        $shop->setMultiShopTables($multiShopTables);

        $multiLangTables = Registry::getLang()->getMultiLangTables();
        $tables = array_unique(array_merge($multiShopTables, $multiLangTables));

        // checking for views
        $views = array();
        foreach ($tables as $table) {
            $views[] = 'oxv_' . $table;
            if (in_array($table, $multiShopTables)) {
                $views[] = 'oxv_' . $table . '_' . $shop->getId();
            }

            if (in_array($table, $multiLangTables)) {
                foreach ($languages as $languageAbbr) {
                    $views[] = 'oxv_' . $table . '_' . $languageAbbr;
                }
            }

            if (in_array($table, $multiShopTables) && in_array($table, $multiLangTables)) {
                foreach ($languages as $languageAbbr) {
                    $views[] = 'oxv_' . $table . '_' . $shop->getId() . '_' . $languageAbbr;
                }
            }
        }

        // deleting views
        foreach ($views as $view) {
            $queryForView = 'drop view if exists ' . $view;
            $database->execute($queryForView);
        }

        // regenerate  shop views
        $this->assertTrue($shop->generateViews(false, null));

        $dbMetaDataHandler = oxNew(\OxidEsales\Eshop\Core\DbMetaDataHandler::class);

        // checking views
        foreach ($views as $view) {
            $this->assertTrue($dbMetaDataHandler->tableExists($view), 'View "' . $view . '" is not created');
        }
    }

    /**
     * @return array
     */
    public function providerGenerateViews_oxShopViewValidator_created_with_current_shopId()
    {
        return array(
            array(1, 1),
            array(9, 9),
        );
    }

    /**
     * Check if oxShopViewValidator created with shop id from oxShop object, not with active shop.
     *
     * Test for bug 0005625: amount of view tables does not depend on amount of languages per shop
     *
     * @param string $shopIdToLoad
     * @param string $sShopLoaded
     *
     * @dataProvider providerGenerateViews_oxShopViewValidator_created_with_current_shopId
     */
    public function testGenerateViewsCreatedWithCurrentShopId($shopIdToLoad, $sShopLoaded)
    {
        $shopViewValidator = $this->getMock(\OxidEsales\Eshop\Application\Model\ShopViewValidator::class, array('setShopId'));
        $shopViewValidator->expects($this->once())->method('setShopId')->with($sShopLoaded);

        oxTestModules::addModuleObject('oxShopViewValidator', $shopViewValidator);

        $shop = oxNew(\OxidEsales\Eshop\Application\Model\Shop::class);
        $shop->load($shopIdToLoad);

        $shop->generateViews();
    }

    /**
     * test if _getLanguageIds return languages when aLanguageParams is set in config table.
     */
    public function testGenerateViews_aLanguageParams_noException()
    {
        $database = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();
        $query = "
            delete from oxconfig
            where oxvarname = 'aLanguages'
                and oxshopid = 9;";
        $database->execute($query);

        /** @var oxShopViewValidator|MockObject $shopViewValidator */
        $shopViewValidator = $this->getMock(\OxidEsales\Eshop\Application\Model\ShopViewValidator::class, array('setShopId'));
        $shopViewValidator->expects($this->once())->method('setShopId')->with(9);

        oxTestModules::addModuleObject('oxShopViewValidator', $shopViewValidator);

        $shop = oxNew(\OxidEsales\Eshop\Application\Model\Shop::class);
        $shop->load(9);

        $shop->generateViews();
    }

    /**
     * test if _getLanguageIds return languages when aLanguages is set in config table.
     */
    public function testGenerateViews_aLanguages_noException()
    {
        $database = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();
        $query = "
            delete from oxconfig
            where oxvarname = 'aLanguageParams'
                and oxshopid = 9;";
        $database->execute($query);

        $shopViewValidator = $this->getMock(\OxidEsales\Eshop\Application\Model\ShopViewValidator::class, array('setShopId'));
        $shopViewValidator->expects($this->once())->method('setShopId')->with(9);

        oxTestModules::addModuleObject('oxShopViewValidator', $shopViewValidator);

        $shop = oxNew(\OxidEsales\Eshop\Application\Model\Shop::class);
        $shop->load(9);

        $shop->generateViews();
    }

    /**
     * Testing oxshop::generateViews() for removing old unused 'oxv_*' views
     */
    public function testGenerateViews_CheckRemovingUnnecessaryViews_ShouldBeRemoved()
    {
        $database = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();

        // creating view which has to be removed
        $database->execute('CREATE OR REPLACE SQL SECURITY INVOKER VIEW oxv_oxshops_zz AS SELECT * FROM oxshops');

        $shop = oxNew(\OxidEsales\Eshop\Application\Model\Shop::class);
        $shop->load($this->shopId);

        $multiShopTables = $this->getConfig()->getConfigParam('aMultiShopTables');
        $shop->setMultiShopTables($multiShopTables);

        $this->assertTrue($shop->generateViews(false, null));

        $dbMetaDataHandler = oxNew(\OxidEsales\Eshop\Core\DbMetaDataHandler::class);

        $this->assertFalse($dbMetaDataHandler->tableExists('oxv_oxshops_zz'), 'Old view "oxv_oxshops_zz" is not removed');
    }

    /**
     * Testing oxshop::generateViews() for leaving any other user created views after regeneration
     */
    public function testGenerateViews_CheckRemovingUserViews_ShouldNotBeRemoved()
    {
        $database = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();

        // creating view which has to stay
        $database->execute('CREATE OR REPLACE SQL SECURITY INVOKER VIEW usr_oxshops_xx AS SELECT * FROM oxshops');

        $shop = oxNew(\OxidEsales\Eshop\Application\Model\Shop::class);
        $shop->load($this->shopId);

        $multiShopTables = $this->getConfig()->getConfigParam('aMultiShopTables');
        $shop->setMultiShopTables($multiShopTables);

        $this->assertTrue($shop->generateViews(false, null));

        $dbMetaDataHandler = oxNew(\OxidEsales\Eshop\Core\DbMetaDataHandler::class);

        $this->assertTrue($dbMetaDataHandler->tableExists('usr_oxshops_xx'), 'User view "usr_oxshops_xx" was removed');

        $database->execute('drop view if exists usr_oxshops_xx');
    }

    /**
     * Testing oxshop::generateViews()
     */
    public function testGenerateViewsWithMultishopInherit()
    {
        $database = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();

        $shop = oxNew(\OxidEsales\Eshop\Application\Model\Shop::class);
        $shop->load($this->shopId);

        $multiShopTables = $this->getConfig()->getConfigParam('aMultiShopTables');
        $shop->setMultiShopTables($multiShopTables);

        // deleting test views
        foreach ($this->tablesWithTemporaryViews as $multishopTable) {
            $queryView = 'drop view oxv_' . $multishopTable . '_' . $this->shopId;
            $database->execute($queryView);
        }

        // letting shop object to generate views self
        $shop->generateViews(true, null);

        $dbMetaDataHandler = oxNew(\OxidEsales\Eshop\Core\DbMetaDataHandler::class);

        $this->assertTrue($dbMetaDataHandler->tableExists('oxv_oxcategories_' . $this->shopId), 'View "oxv_oxcategories_' . $this->shopId . '" is not created');
    }

    /**
     * Testing oxshop::generateViews() for creating and leaving views when different language exist in one subshop but not in main shop.
     *
     * Test for bug 0005625: amount of view tables does not depend on amount of languages per shop
     */
    public function testGenerateViews_CheckRemovingDifferentLanguageViews_ShouldNotBeRemoved()
    {
        $config = $this->getConfig();
        $config->setShopId(9);
        $config->saveShopConfVar('aarr', 'aLanguages', array('lt' => 'Lithuanian', 'de' => 'Deutsch'));
        $config->saveShopConfVar(
            'aarr', 'aLanguageParams', array(
                'de' => array(
                    'baseId' => 0,
                    'active' => "1",
                    'sort' => "1",
                ),
                'lt' => array(
                    'baseId' => 1,
                    'active' => "1",
                    'sort' => "2",
                ),
            )
        );

        $shopsToTest = array(9, 1);
        foreach ($shopsToTest as $shopId) {
            $shop = oxNew(\OxidEsales\Eshop\Application\Model\Shop::class);
            $shop->load($shopId);

            $multiShopTables = $this->getConfig()->getConfigParam('aMultiShopTables');
            $shop->setMultiShopTables($multiShopTables);

            $this->assertTrue($shop->generateViews(false, null));

            $dbMetaDataHandler = oxNew(\OxidEsales\Eshop\Core\DbMetaDataHandler::class);

            $this->assertTrue($dbMetaDataHandler->tableExists('oxv_oxshops_lt'), 'View "oxv_oxshops_lt" was not created or was removed');
            $this->assertTrue($dbMetaDataHandler->tableExists('oxv_oxdelivery_9_lt'), 'View "oxv_oxdelivery_9_lt" was not created or was removed');
        }
    }

    /**
     * Testing oxshop::_getViewWhere()
     */
    public function testGetViewWhere()
    {
        $shop = oxNew(\OxidEsales\Eshop\Application\Model\Shop::class);
        $shop->load($this->shopId);
        $where = $shop->UNITgetViewWhere('oxarticles');

        $compare = ' WHERE t2s.oxshopid = 9 ';
        $this->assertEquals($compare, $where);
    }

    /**
     * Testing oxshop::_getViewWhere()
     */
    public function testGetViewWhereIf66Subshop()
    {
        $shop = $this->getMock(\OxidEsales\Eshop\Application\Model\Shop::class, array('getNewShopId'));
        $shop->expects($this->once())->method('getNewShopId')->will($this->returnValue(66));
        $shop->oxshops__oxactive = new \OxidEsales\Eshop\Core\Field(1, \OxidEsales\Eshop\Core\Field::T_RAW);
        $shop->oxshops__oxname = new \OxidEsales\Eshop\Core\Field('Test shop7', \OxidEsales\Eshop\Core\Field::T_RAW);
        $shop->save();
        $where = $shop->UNITgetViewWhere('oxarticles');

        $compare = ' WHERE t2s.oxshopid = 66 ';
        $this->assertEquals($compare, $where);
    }

    /**
     * Testing oxshop::_getViewWhere()
     */
    public function testGetViewWhereIf66SubshopExtendsMainShop()
    {
        \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->Execute("insert into oxshops set oxid = 65, oxparentid = 1, oxisinherited = 1");
        $shop = oxNew(\OxidEsales\Eshop\Application\Model\Shop::class);
        $shop->load(65);
        $where = $shop->UNITgetViewWhere('oxarticles');

        $compare = ' WHERE t2s.oxshopid = 65 ';
        $this->assertEquals($compare, $where);
    }

    /**
     * Testing oxshop::_getViewWhere()
     */
    public function testGetViewWhereIfTableIsSet()
    {
        $config = $this->getConfig();
        $database = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();

        $checkTable = 't2s';

        $query = 'update oxshops set oxparentid = "' . $config->getBaseShopId() . '", oxisinherited = 1 where oxid = "' . $this->shopId . '" ';
        $database->execute($query);

        $shop = oxNew(\OxidEsales\Eshop\Application\Model\Shop::class);
        $shop->load($this->shopId);
        $where = $shop->UNITgetViewWhere($checkTable);

        $compare = " WHERE {$checkTable}.oxshopid = 9 ";
        $this->assertEquals($compare, $where);
    }

    public function testGetViewSelect()
    {
        $shop = oxNew(\OxidEsales\Eshop\Application\Model\Shop::class);
        $shop->load($this->shopId);

        $table = 'oxartextends';

        $select = $shop->UNITgetViewSelect($table, 0);
        $expect = 'oxartextends.oxid as oxid,oxartextends.oxlongdesc as oxlongdesc,oxartextends.oxtimestamp as oxtimestamp';
        $this->assertEquals(strtolower($expect), strtolower($select));

        $select = $shop->UNITgetViewSelect($table, 1);
        $expect = 'oxartextends.oxid as oxid,oxartextends.oxlongdesc_1 as oxlongdesc,oxartextends.oxtimestamp as oxtimestamp';
        $this->assertEquals(strtolower($expect), strtolower($select));
    }

    public function testGetViewSelectMultilang()
    {
        $table = 'oxarticle';
        $langTable = 'oxarticle_lang';

        $fields[$table] = array("oxid" => "oxarticle.oxid", "oxtitle" => "oxarticle.oxtitle");
        $fields[$langTable] = array("oxid" => "oxarticle_lang.oxid", "oxtitle_1" => "oxarticle_lang.oxtitle_1");

        /** @var DbMetaDataHandler|MockObject $metaData */
        $metaData = $this->getMock('\OxidEsales\EshopEnterprise\Core\DbMetaDataHandler', array('getAllMultiTables', 'getFields'));
        $metaData->expects($this->once())->method('getAllMultiTables')->with($table)->will($this->returnValue(array($langTable)));
        $metaData->expects($this->at(1))->method('getFields')->with($table)->will($this->returnValue($fields[$table]));
        $metaData->expects($this->at(2))->method('getFields')->with($langTable)->will($this->returnValue($fields[$langTable]));
        oxTestModules::addModuleObject('oxDbMetaDataHandler', $metaData);

        $shop = oxNew(\OxidEsales\Eshop\Application\Model\Shop::class);
        $shop->load($this->shopId);

        $select = $shop->UNITgetViewSelectMultilang($table);
        $expect = 'oxarticle.oxid,oxarticle.oxtitle,oxarticle_lang.oxtitle_1';
        $this->assertEquals(strtolower($expect), strtolower($select));
    }

    public function testGetViewJoinAllEmpty()
    {
        $shop = oxNew(\OxidEsales\Eshop\Application\Model\Shop::class);
        $shop->load($this->shopId);

        $table = 'oxartextends';

        $join = trim($shop->UNITgetViewJoinAll($table));
        $expect = '';
        $this->assertEquals($expect, $join);
    }

    public function testGetViewJoinAll()
    {
        oxTestModules::addFunction('oxDbMetaDataHandler', 'getAllMultiTables', '{ return array("OXID_1" => "oxartextends_set1","OXID_2" => "oxartextends_set2"); }');

        $shop = oxNew(\OxidEsales\Eshop\Application\Model\Shop::class);
        $shop->load($this->shopId);

        $table = 'oxartextends';

        $join = trim($shop->UNITgetViewJoinAll($table));
        $expect = 'LEFT JOIN oxartextends_set1 USING (OXID) LEFT JOIN oxartextends_set2 USING (OXID)';
        $this->assertEquals($expect, $join);
    }

    public function testGetViewJoinLangEmpty()
    {
        $shop = oxNew(\OxidEsales\Eshop\Application\Model\Shop::class);
        $shop->load($this->shopId);

        $table = 'oxartextends';

        $join = trim($shop->UNITgetViewJoinLang($table, 0));
        $expect = '';
        $this->assertEquals($expect, $join);
    }

    public function testGetViewJoinLang()
    {
        $shop = oxNew(\OxidEsales\Eshop\Application\Model\Shop::class);
        $shop->load($this->shopId);

        $table = 'oxartextends';

        $langPerTable = $this->getConfig()->getConfigParam("iLangPerTable");
        $langPerTable = $langPerTable ? $langPerTable : 8;

        $join = trim($shop->UNITgetViewJoinLang($table, $langPerTable));
        $expect = 'LEFT JOIN oxartextends_set1 USING (OXID)';
        $this->assertEquals($expect, $join);
    }

    /**
     * Testing oxshop::_getViewWhere()
     */
    public function testGetViewWhereIfIsMultishop()
    {
        $config = $this->getConfig();
        $database = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();

        $checkTable = 'oxcategories';

        $query = 'update oxshops set oxparentid = "' . $config->getBaseShopId() . '", oxisinherited = 1 where oxid = "' . $this->shopId . '" ';
        $database->execute($query);

        $shop = oxNew(\OxidEsales\Eshop\Application\Model\Shop::class);
        $shop->load($this->shopId);
        $shop->oxshops__oxismultishop = new \OxidEsales\Eshop\Core\Field(1, \OxidEsales\Eshop\Core\Field::T_RAW);
        $shop->setMultiShopInheritCategories(true);
        $where = $shop->UNITgetViewWhere($checkTable);

        $this->assertEquals(' WHERE 1 ', $where);
    }

    /**
     * Testing oxshop::_getViewWhere()
     */
    public function testGetViewWhereIfIsMultishopCatsNotInherited()
    {
        $config = $this->getConfig();
        $database = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();

        $checkTable = 'oxcategories';

        $query = 'update oxshops set oxparentid = "' . $config->getBaseShopId() . '", oxisinherited = 1 where oxid = "' . $this->shopId . '" ';
        $database->execute($query);

        $shop = oxNew(\OxidEsales\Eshop\Application\Model\Shop::class);
        $shop->load($this->shopId);
        $shop->oxshops__oxismultishop = new \OxidEsales\Eshop\Core\Field(1, \OxidEsales\Eshop\Core\Field::T_RAW);
        $where = $shop->UNITgetViewWhere($checkTable);

        $this->assertEquals(" WHERE t2s.oxshopid = 9 ", $where);
    }

    /**
     * Getting next shop id
     */
    public function testGetNewShopId()
    {
        $shop = oxNew(\OxidEsales\Eshop\Application\Model\Shop::class);
        $this->assertEquals(2, $shop->getNewShopId());
    }

    /**
     * Getting next shop id if some shops where deleted
     */
    public function testGetNewShopIdFromMiddle()
    {
        //id = 2
        $shop2 = oxNew(\OxidEsales\Eshop\Application\Model\Shop::class);
        $shop2->save();

        //id = 3
        $shop3 = oxNew(\OxidEsales\Eshop\Application\Model\Shop::class);
        $shop3->save();

        $shop2->setMultiShopTables(array());
        $shop2->delete();

        //taken from middle free number
        $shop = oxNew(\OxidEsales\Eshop\Application\Model\Shop::class);
        $this->assertEquals(2, $shop->getNewShopId());
    }

    /**
     * Testing max shop id value
     * Default value 128
     * Max value 256, stored in config parameter iMaxShopId
     */
    public function testMaxShopId()
    {
        // default value 128
        $oShop = oxNew(\OxidEsales\Eshop\Application\Model\Shop::class);
        $this->assertEquals(1500, $oShop->getMaxShopId());

        //default from config
        $this->getConfig()->setConfigParam('iMaxShopId', 1111);
        $oShop = oxNew(\OxidEsales\Eshop\Application\Model\Shop::class);
        $this->assertEquals(1111, $oShop->getMaxShopId());

        $this->getConfig()->setConfigParam('iMaxShopId', 3000);
        $oShop = oxNew(\OxidEsales\Eshop\Application\Model\Shop::class);
        $this->assertEquals(2000, $oShop->getMaxShopId());

        //test cases for setter 256
        $oShop = oxNew(\OxidEsales\Eshop\Application\Model\Shop::class);
        $oShop->setMaxShopId(131);
        $this->assertEquals(131, $oShop->getMaxShopId());

        $oShop->setMaxShopId(13);
        $this->assertEquals(13, $oShop->getMaxShopId());

        $oShop->setMaxShopId(3000);
        $this->assertEquals(2000, $oShop->getMaxShopId());
    }

    /**
     * Testing oxshop::getNewShopId(), if inserting more then iMaxShopId
     */
    public function testGetNewShopIdMoreThanMax()
    {
        $this->getConfig()->setConfigParam('iMaxShopId', 1);
        $oShop = oxNew(\OxidEsales\Eshop\Application\Model\Shop::class);

        $this->assertFalse($oShop->getNewShopId());
    }

    /**
     * Testing oxshop::_insert()
     */
    public function testInsert()
    {
        /** @var Shop|MockObject $oShop */
        $oShop = $this->getMock(\OxidEsales\Eshop\Application\Model\Shop::class, array('getNewShopId'));
        $oShop->expects($this->once())->method('getNewShopId')->will($this->returnValue(3));
        $oShop->oxshops__oxactive = new \OxidEsales\Eshop\Core\Field(1, \OxidEsales\Eshop\Core\Field::T_RAW);
        $oShop->oxshops__oxname = new \OxidEsales\Eshop\Core\Field('Test shop 3', \OxidEsales\Eshop\Core\Field::T_RAW);
        $oShop->save();
        $this->assertEquals(3, $oShop->getId());
    }

    /**
     * Testing oxshop::_insert()
     */
    public function testInsertAddNewFields()
    {
        /** @var Shop|MockObject $oShop */
        $oShop = $this->getMock(\OxidEsales\Eshop\Application\Model\Shop::class, array('getNewShopId'));
        $oShop->expects($this->once())->method('getNewShopId')->will($this->returnValue(4));
        $oShop->oxshops__oxactive = new \OxidEsales\Eshop\Core\Field(1, \OxidEsales\Eshop\Core\Field::T_RAW);
        $oShop->oxshops__oxname = new \OxidEsales\Eshop\Core\Field('Test shop 4', \OxidEsales\Eshop\Core\Field::T_RAW);
        $oShop->save();
        $this->assertEquals(4, $oShop->getId());

    }

    /**
     * Testing oxshop::_insert()
     */
    public function testInsertInAdmin()
    {
        /** @var Shop|MockObject $oShop */
        $oShop = $this->getMock(\OxidEsales\Eshop\Application\Model\Shop::class, array('getNewShopId'));
        $oShop->expects($this->once())->method('getNewShopId')->will($this->returnValue(5));
        $oShop->oxshops__oxactive = new \OxidEsales\Eshop\Core\Field(1, \OxidEsales\Eshop\Core\Field::T_RAW);
        $oShop->oxshops__oxname = new \OxidEsales\Eshop\Core\Field('Test shop 4', \OxidEsales\Eshop\Core\Field::T_RAW);
        $oShop->setId(-1);
        $oShop->save();
        $this->assertEquals(5, $oShop->getId());

    }

    /**
     * Check if during save when oxid is false it returns false for it
     */
    public function testInsertOxidIsFalse()
    {
        /** @var Shop|MockObject $oShop */
        $oShop = $this->getMock(\OxidEsales\Eshop\Application\Model\Shop::class, array('getNewShopId'));
        $oShop->expects($this->once())->method('getNewShopId')->will($this->returnValue(false));
        $oShop->oxshops__oxactive = new \OxidEsales\Eshop\Core\Field(1, \OxidEsales\Eshop\Core\Field::T_RAW);
        $oShop->oxshops__oxname = new \OxidEsales\Eshop\Core\Field('Test shop 4', \OxidEsales\Eshop\Core\Field::T_RAW);
        $oShop->setId(-1);
        $this->assertFalse($oShop->save());
    }

    /**
     * Testing multishop language cleanup functionality
     */
    public function testCleanLangSetTables()
    {
        $languageTables = $this->insertTestLanguage();

        $this->setConfigParam("iLangPerTable", 4);
        $oLang = $this->getMock(\OxidEsales\Eshop\Core\Language::class, array('getLanguageIds'));
        $oLang->expects($this->any())->method('getLanguageIds')->will($this->returnValue(array('0' => 'de', '1' => 'de', '2' => 'lt', '3' => 'ru', '4' => 'pl', '5' => 'cz')));
        oxTestModules::addModuleObject('oxLang', $oLang);

        $oShop = oxNew(\OxidEsales\Eshop\Application\Model\Shop::class);
        $oShop->setMultiShopTables($this->getConfig()->getConfigParam('aMultiShopTables'));
        $oShop->delete($this->shopId);

        // now checking if everything was cleaned up..
        $database = \OxidEsales\Eshop\Core\DatabaseProvider::getDb(\OxidEsales\Eshop\Core\DatabaseProvider::FETCH_MODE_ASSOC);
        foreach ($languageTables as $sTable) {
            $this->assertFalse((bool) $database->getOne("select 1 from {$sTable} where oxid = '_testRecordForShop{$this->shopId}'"), "Not cleaned {$sTable} table");
            $this->assertFalse((bool) $database->getOne("select 1 from {$sTable}_set1 where oxid = '_testRecordForShop{$this->shopId}'"), "Not cleaned {$sTable}_set1 table");
        }

        $this->deleteTestLanguage($languageTables);
    }

    public function testGetUrls()
    {
        $oShop = oxNew(\OxidEsales\Eshop\Application\Model\Shop::class);
        $oShop->load($this->shopId);
        $this->assertEquals(array($this->getConfig()->getShopUrl()), $oShop->getUrls());
    }

    public function testGetDefaultCategory()
    {
        $oShop = oxNew(\OxidEsales\Eshop\Application\Model\Shop::class);
        $oShop->load($this->shopId);
        $this->assertEquals('testDefaultCategory', $oShop->getDefaultCategory());
    }

    /**
     * Provides parameters and expected results for testMakeViewQuery
     */
    public function makeEEViewQueryParamProvider()
    {
        $sFields = 'OXID, OXTITLE';
        $aMockedFunctionReturns = array(
            '_getViewSelect'   => $sFields,
            '_getViewJoinAll'  => '',
            '_getViewJoinLang' => '',
            '_getViewWhere'    => ''
        );
        $sMockedJoinResult = " INNER JOIN oxarticles2shop as t2s ON t2s.oxmapobjectid=oxarticles.oxmapid ";
        $aTestData = array();
        $aTestData[] = array('oxarticles', 'de', true, 15, '', $aMockedFunctionReturns,
            'CREATE OR REPLACE SQL SECURITY INVOKER VIEW `oxv_oxarticles_15_de` AS SELECT ' .
            $sFields . ' FROM oxarticles' . $sMockedJoinResult);
        $aMockedFunctionReturns['_getViewWhere'] = ' WHERE 1';
        $aTestData[] = array('oxarticles', 'de', true, 15, ' WHERE 1', $aMockedFunctionReturns,
            'CREATE OR REPLACE SQL SECURITY INVOKER VIEW `oxv_oxarticles_15_de` AS SELECT ' .
            $sFields . ' FROM oxarticles' . $sMockedJoinResult . ' WHERE 1');

        return $aTestData;
    }

    /**
     * Check all the variations of oxShop::createViewQuery()
     *
     * @dataProvider makeEEViewQueryParamProvider
     */
    public function testMakeViewQueryEE($sTable, $sLang, $blMultishop, $iShopId, $sWhere, $aMockedFunctionReturns, $query)
    {
        /** @var Shop|MockObject $oShop */
        $oShop = $this->getMock(\OxidEsales\Eshop\Application\Model\Shop::class, array_keys($aMockedFunctionReturns));
        foreach ($aMockedFunctionReturns as $sFunction => $sReturnValue) {
            $oShop->expects($this->any())->method($sFunction)->will($this->returnValue($sReturnValue));
        }
        if ($iShopId) {
            $oShop->setId($iShopId);
        }
        $oShop->createViewQuery($sTable, array(0 => $sLang));
        $aQueries = $oShop->getQueries();
        $this->assertEquals(rtrim($query), rtrim($aQueries[1]));
    }

    /**
     * Test inheritance getter
     */
    public function testIsTableInherited()
    {
        $oShop = oxNew(\OxidEsales\Eshop\Application\Model\Shop::class);
        $this->getConfig()->setConfigParam('blMallInherit_oxarticles', true);
        $this->getConfig()->setConfigParam('blMallInherit_oxattributes', false);

        $this->assertTrue($oShop->UNITisTableInherited('oxarticles'));
        $this->assertFalse($oShop->UNITisTableInherited('oxattributes'));
        $this->assertFalse($oShop->UNITisTableInherited('oxcategories'));
    }

    /**
     * Tests update inheritance information.
     */
    public function testUpdateInheritanceInformation()
    {
        $sShopId = 'testShopId';
        $sParentShopId = 'testParentShopId';

        $aShopIds = array($sShopId);

        $sTableInherit = 'tableInherit';
        $sTableInheritNot = 'tableInheritNot';
        $aMultiShopTables = array($sTableInherit, $sTableInheritNot);
        $aWhiteList = array($sTableInherit, $sTableInheritNot);

        /** @var Element2ShopRelations|MockObject $oElement2ShopRelations */
        $oElement2ShopRelations = $this->getMock(\OxidEsales\Eshop\Core\Element2ShopRelations::class, array('setShopIds', 'setItemType', 'inheritFromShop', 'removeInheritedFromShop'), array($sTableInherit));
        $oElement2ShopRelations->expects($this->at(0))->method('setShopIds')->with($aShopIds);
        $oElement2ShopRelations->expects($this->at(1))->method('setItemType')->with($sTableInherit);
        $oElement2ShopRelations->expects($this->at(2))->method('inheritFromShop')->with($sParentShopId);
        $oElement2ShopRelations->expects($this->at(3))->method('setShopIds')->with($aShopIds);
        $oElement2ShopRelations->expects($this->at(4))->method('setItemType')->with($sTableInheritNot);
        $oElement2ShopRelations->expects($this->at(5))->method('removeInheritedFromShop')->with($sParentShopId);

        /** @var Shop|MockObject $oShop */
        $oShop = $this->getMock(\OxidEsales\Eshop\Application\Model\Shop::class, array('_getElement2ShopRelations', 'getMultiShopTables', '_isTableInherited', 'getInheritanceGroup'), array(), '', false);
        $oShop->expects($this->any())->method('_getElement2ShopRelations')->will($this->returnValue($oElement2ShopRelations));
        $oShop->expects($this->once())->method('getMultiShopTables')->will($this->returnValue($aMultiShopTables));
        $oShop->expects($this->at(1))->method('getInheritanceGroup')->with($sTableInherit)->will($this->returnValue($aShopIds));
        $oShop->expects($this->at(3))->method('_isTableInherited')->with($sTableInherit)->will($this->returnValue(true));
        $oShop->expects($this->at(4))->method('getInheritanceGroup')->with($sTableInheritNot)->will($this->returnValue($aShopIds));
        $oShop->expects($this->at(6))->method('_isTableInherited')->with($sTableInheritNot)->will($this->returnValue(false));

        $oShop->setId($sShopId);
        $oShop->oxshops__oxparentid = new \OxidEsales\Eshop\Core\Field($sParentShopId);

        $oShop->updateInheritance($aWhiteList);
    }

    /**
     * Tests update inheritance information.
     */
    public function testUpdateInheritanceInformationCheckWhitelist()
    {
        $sShopId = 'testShopId';
        $sParentShopId = 'testParentShopId';

        $aShopIds = array($sShopId);

        $sTableInherit = 'tableInherit';
        $sTableInheritNot = 'tableInheritNot';
        $aMultiShopTables = array($sTableInherit, $sTableInheritNot);
        $aWhiteList = array($sTableInheritNot);

        /** @var Element2ShopRelations|MockObject $oElement2ShopRelations */
        $oElement2ShopRelations = $this->getMock(\OxidEsales\Eshop\Core\Element2ShopRelations::class, array('setShopIds', 'setItemType', 'inheritFromShop', 'removeInheritedFromShop'), array($sTableInherit));
        $oElement2ShopRelations->expects($this->at(0))->method('setShopIds')->with($aShopIds);
        $oElement2ShopRelations->expects($this->at(1))->method('setItemType')->with($sTableInheritNot);
        $oElement2ShopRelations->expects($this->at(2))->method('removeInheritedFromShop')->with($sParentShopId);

        /** @var Shop|MockObject $oShop */
        $oShop = $this->getMock(\OxidEsales\Eshop\Application\Model\Shop::class, array('_getElement2ShopRelations', 'getMultiShopTables', '_isTableInherited', 'getInheritanceGroup'), array(), '', false);
        $oShop->expects($this->any())->method('_getElement2ShopRelations')->will($this->returnValue($oElement2ShopRelations));
        $oShop->expects($this->once())->method('getMultiShopTables')->will($this->returnValue($aMultiShopTables));

        $oShop->expects($this->at(1))->method('getInheritanceGroup')->with($sTableInheritNot)->will($this->returnValue($aShopIds));
        $oShop->expects($this->at(3))->method('_isTableInherited')->with($sTableInheritNot)->will($this->returnValue(false));

        $oShop->setId($sShopId);
        $oShop->oxshops__oxparentid = new \OxidEsales\Eshop\Core\Field($sParentShopId);

        $oShop->updateInheritance($aWhiteList);
    }

    /**
     * Tests update inheritance information for multishop type shops
     */
    public function testUpdateInheritanceInformationForMultishop()
    {
        $sShopId = 'testShopId';

        $aShopIds = array($sShopId);

        $sTableInherit = 'tableInherit';
        $sTableInheritNot = 'tableInheritNot';
        $aMultiShopTables = array($sTableInherit, $sTableInheritNot);

        /** @var Element2ShopRelations|MockObject $oElement2ShopRelations */
        $oElement2ShopRelations = $this->getMock(\OxidEsales\Eshop\Core\Element2ShopRelations::class, array('setShopIds', 'setItemType', 'inheritAllElements', 'removeAllElements'), array($sTableInherit));
        $oElement2ShopRelations->expects($this->at(0))->method('setShopIds')->with($aShopIds);
        $oElement2ShopRelations->expects($this->at(1))->method('setItemType')->with($sTableInherit);
        $oElement2ShopRelations->expects($this->at(2))->method('inheritAllElements');
        $oElement2ShopRelations->expects($this->at(3))->method('setShopIds')->with($aShopIds);
        $oElement2ShopRelations->expects($this->at(4))->method('setItemType')->with($sTableInheritNot);
        $oElement2ShopRelations->expects($this->at(5))->method('removeAllElements');

        /** @var Shop|MockObject $oShop */
        $oShop = $this->getMock(\OxidEsales\Eshop\Application\Model\Shop::class, array('_getElement2ShopRelations', 'getMultiShopTables', '_isTableInherited', 'getInheritanceGroup', 'isMultishop'), array(), '', false);
        $oShop->expects($this->any())->method('_getElement2ShopRelations')->will($this->returnValue($oElement2ShopRelations));
        $oShop->expects($this->once())->method('getMultiShopTables')->will($this->returnValue($aMultiShopTables));
        $oShop->expects($this->once())->method('isMultishop')->will($this->returnValue(true));
        $oShop->expects($this->at(2))->method('getInheritanceGroup')->with($sTableInherit)->will($this->returnValue($aShopIds));
        $oShop->expects($this->at(4))->method('_isTableInherited')->with($sTableInherit)->will($this->returnValue(true));
        $oShop->expects($this->at(5))->method('getInheritanceGroup')->with($sTableInheritNot)->will($this->returnValue($aShopIds));
        $oShop->expects($this->at(7))->method('_isTableInherited')->with($sTableInheritNot)->will($this->returnValue(false));

        $oShop->setId($sShopId);

        $oShop->updateInheritance();
    }

    public function testGetInheritanceGroup()
    {
        $table = 'oxarticles';

        $oShop = oxNew(\OxidEsales\Eshop\Application\Model\Shop::class);
        $oShop->load(1);
        $this->assertEquals(array(1), $oShop->getInheritanceGroup($table));

        // SubShop tree testing should be moved to integration tests
        // As currently the functionality is not executed due to serial restrictions

        // Inserting subshop
        $queries = array();
        $queries[] = "INSERT INTO oxshops (oxid, oxparentid, oxname) values (2, 1, '_testShop2')";
        $queries[] = "INSERT INTO oxshops (oxid, oxparentid, oxname) values (3, 1, '_testShop3')";
        $queries[] = "INSERT INTO oxshops (oxid, oxparentid, oxname) values (4, 1, '_testShop4')";
        $queries[] = "INSERT INTO oxshops (oxid, oxparentid, oxname) values (5, 2, '_testShop5')";
        $queries[] = "INSERT INTO oxshops (oxid, oxparentid, oxname) values (6, 2, '_testShop6')";
        $queries[] = "INSERT INTO oxshops (oxid, oxparentid, oxname) values (7, 3, '_testShop7')";
        $queries[] = "INSERT INTO oxshops (oxid, oxparentid, oxname) values (8, 3, '_testShop8')";

        $database = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();

        foreach ($queries as $query) {
            $database->execute($query);
        }

        $this->getConfig()->saveShopConfVar('bool', "blMallInherit_{$table}", 1, 2);
        $this->getConfig()->saveShopConfVar('bool', "blMallInherit_{$table}", 0, 3);
        $this->getConfig()->saveShopConfVar('bool', "blMallInherit_{$table}", 1, 4);
        $this->getConfig()->saveShopConfVar('bool', "blMallInherit_{$table}", 0, 5);
        $this->getConfig()->saveShopConfVar('bool', "blMallInherit_{$table}", 1, 6);
        $this->getConfig()->saveShopConfVar('bool', "blMallInherit_{$table}", 1, 7);
        $this->getConfig()->saveShopConfVar('bool', "blMallInherit_{$table}", 0, 8);

        $oShop = oxNew(\OxidEsales\Eshop\Application\Model\Shop::class);
        $oShop->load(1);

        $aExpectedShopIds = array(1, 2, 4, 6);
        $aShopIds = $oShop->getInheritanceGroup($table);

        sort($aExpectedShopIds);
        sort($aShopIds);

        $this->assertEquals($aExpectedShopIds, $aShopIds);
    }

    /**
     * Check MallInherit getter when it is set
     */
    public function testGetMallInheritWhenSet()
    {
        $oShop = oxNew(\OxidEsales\Eshop\Application\Model\Shop::class);
        $oShop->setMallInherit(array(1));

        $this->assertEquals(array(1), $oShop->getMallInherit());
    }

    /**
     * Check default MallInherit getter when it is not set
     */
    public function testGetMallInheritWhenNotSet()
    {
        $oShop = oxNew(\OxidEsales\Eshop\Application\Model\Shop::class);
        $this->assertEquals(array(), $oShop->getMallInherit());
    }

    /**
     * Test getter for is multishop type shop
     */
    public function testIsMultiShop()
    {
        $oShop = oxNew(\OxidEsales\Eshop\Application\Model\Shop::class);
        $oShop->load($this->shopId);
        $oShop->oxshops__oxismultishop = new \OxidEsales\Eshop\Core\Field(1);
        $this->assertEquals(1, $oShop->isMultiShop());
    }

    /**
     * Test getter for is multishop type shop
     */
    public function testIsSuperShop()
    {
        $oShop = oxNew(\OxidEsales\Eshop\Application\Model\Shop::class);
        $oShop->load($this->shopId);
        $oShop->oxshops__oxissupershop = new \OxidEsales\Eshop\Core\Field(1);
        $this->assertEquals(1, $oShop->isSuperShop());
    }

    /**
     * Test call to getMultishopTables when it's not set anywhere
     */
    public function testGetMultishopTablesDefaultNotSet()
    {
        $oShop = oxNew(\OxidEsales\Eshop\Application\Model\Shop::class);
        $this->getConfig()->setConfigParam('aMultiShopTables', array('table1', 'table2'));
        $this->assertEquals(array('table1', 'table2'), $oShop->getMultiShopTables());
    }

    /**
     * Inserts new test language tables
     *
     * @return array
     */
    protected function insertTestLanguage()
    {
        $languageTables = array();

        $database = \OxidEsales\Eshop\Core\DatabaseProvider::getDb(\OxidEsales\Eshop\Core\DatabaseProvider::FETCH_MODE_ASSOC);

        // collecting list of tables
        $query = "show tables like 'oxv\_%\_en'";

        $result = $database->select($query);
        if ($result != false && $result->count() > 0) {
            while (!$result->EOF) {
                $view = current($result->fields);
                $table = preg_replace("/(oxv\_)|((\_\d+)?\_en)/", "", $view);

                // saving name of tables..
                if (!in_array($table, $languageTables)) {
                    $languageTables[] = trim($table);
                }

                $result->fetchRow();
            }
        }

        // creating language set tables and inserting by one test record
        foreach ($languageTables as $position => $table) {
            $query = "show create table {$table}";
            $result = $database->select($query);

            // creating table
            $query = end($result->fields);
            if ((stripos($table, "oxartextends") === false && stripos($table, "oxshops") === false) &&
                !preg_match("/oxshopid/i", $query)
            ) {
                unset($languageTables[$position]);
                continue;
            }


            $query = str_replace($table, $table . "_set1", $query);
            $database->execute($query);
        }

        $shopId = $this->shopId;

        // inserting test records
        foreach ($languageTables as $table) {
            // do not insert data into shops table..
            if (stripos($table, "oxshops") !== false) {
                continue;
            }

            $queryForValues = "";
            $query = "show columns from {$table}";
            $result = $database->select($query);
            if ($result != false && $result->count() > 0) {
                while (!$result->EOF) {
                    $sValue = $result->fields["Default"];
                    $field = $result->fields["Field"];

                    // overwriting default values
                    if (stripos($field, "oxshopid") !== false) {
                        $sValue = $shopId;
                    }
                    if (stripos($field, "oxid") !== false) {
                        $sValue = "_testRecordForShop{$shopId}";
                    }


                    if ($queryForValues) {
                        $queryForValues .= ", ";
                    }
                    $queryForValues .= "'$sValue'";
                    $result->fetchRow();
                }
            }

            $database->execute("replace into {$table} values ({$queryForValues})");
            $database->execute("replace into {$table}_set1 values ({$queryForValues})");
        }

        return $languageTables;
    }

    /**
     * Removes test language tables
     *
     * @param array $languageTables
     */
    protected function deleteTestLanguage($languageTables)
    {
        $database = \OxidEsales\Eshop\Core\DatabaseProvider::getDb(\OxidEsales\Eshop\Core\DatabaseProvider::FETCH_MODE_ASSOC);
        foreach ($languageTables as $table) {
            $database->execute("drop table {$table}_set1");
            $database->execute("delete from {$table} where oxid like '_test%'");
        }
    }
}
