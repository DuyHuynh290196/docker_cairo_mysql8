<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Tests\Unit\Model;

require_once TEST_LIBRARY_HELPERS_PATH . 'oxCacheHelper.php';

use oxField;
use oxDb;
use oxTestModules;
use stdClass;
use Exception;
use oxUtilsObject;
use OxidEsales\Eshop\Core\Registry;

class ArticleTest extends \oxUnitTestCase
{
    /**
     * Initialize the fixture.
     *
     * @return null
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->getConfig()->setConfigParam('blUseTimeCheck', true);
    }

    /**
     * Tear down the fixture.
     *
     * @return null
     */
    protected function tearDown(): void
    {
        $this->cleanUpTable('oxarticles');
        $this->cleanUpTable('oxprice2article', 'oxartid');

        $database = \OxidEsales\Eshop\Core\DatabaseProvider::getInstance();
        $database->flushTableDescriptionCache();

        parent::tearDown();
    }

    /**
     * @param string       $sId
     * @param string|false $sVariantId
     *
     * @return oxArticle
     */
    private function _createArticle($sId = '_testArt', $sVariantId = false) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $oArticle = $this->getProxyClass('oxArticle');
        $oArticle->setAdminMode(null);
        $oArticle->setId($sId);
        $oArticle->oxarticles__oxprice = new \OxidEsales\Eshop\Core\Field(15.5, \OxidEsales\Eshop\Core\Field::T_RAW);
        $oArticle->oxarticles__oxshopid = new \OxidEsales\Eshop\Core\Field($this->getConfig()->getBaseShopId(), \OxidEsales\Eshop\Core\Field::T_RAW);
        $oArticle->oxarticles__oxtitle = new \OxidEsales\Eshop\Core\Field("test", \OxidEsales\Eshop\Core\Field::T_RAW);
        $oArticle->save();

        if ($sVariantId) {
            $this->_createVariant($sVariantId, $sId);
        }

        return $oArticle;
    }

    /**
     * @param string $sId
     * @param string $sParentId
     *
     * @return oxArticle
     */
    private function _createVariant($sId = '_testVar', $sParentId = '_testArt') // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $oVariant = $this->getProxyClass('oxarticle');
        $oVariant->setEnableMultilang(false);
        $oVariant->setAdminMode(null);
        $oVariant->setId($sId);
        $oVariant->oxarticles__oxprice = new \OxidEsales\Eshop\Core\Field(12.2, \OxidEsales\Eshop\Core\Field::T_RAW);
        $oVariant->oxarticles__oxshopid = new \OxidEsales\Eshop\Core\Field($this->getConfig()->getBaseShopId(), \OxidEsales\Eshop\Core\Field::T_RAW);
        $oVariant->oxarticles__oxparentid = new \OxidEsales\Eshop\Core\Field($sParentId, \OxidEsales\Eshop\Core\Field::T_RAW);
        $oVariant->oxarticles__oxtitle = new \OxidEsales\Eshop\Core\Field("test", \OxidEsales\Eshop\Core\Field::T_RAW);
        $oVariant->oxarticles__oxtitle_1 = new \OxidEsales\Eshop\Core\Field("testEng", \OxidEsales\Eshop\Core\Field::T_RAW);

        $oVariant->save();

        return $oVariant;
    }

    /**
     * Test assign access rigts.
     */
    public function testAssignAccessRights()
    {
        $oArticle = $this->getMock(\OxidEsales\Eshop\Application\Model\Article::class, array('getRights', 'canBuy'));
        $oArticle->expects($this->at(0))->method('getRights')->will($this->returnValue(true));
        $oArticle->expects($this->at(1))->method('canBuy')->will($this->returnValue(true));
        $oArticle->UNITassignAccessRights();
        $this->assertFalse($oArticle->_blNotBuyable);
    }

    /**
     * Test assign access rigts, when no rights are set.
     */
    public function testAssignAccessRightsNoRights()
    {
        $oArticle = $this->getMock(\OxidEsales\Eshop\Application\Model\Article::class, array('getRights', 'canBuy'));
        $oArticle->expects($this->once())->method('getRights')->will($this->returnValue(null));
        $oArticle->expects($this->never())->method('canBuy');
        $oArticle->UNITassignAccessRights();
        $this->assertFalse($oArticle->_blNotBuyable);
    }

    /**
     * Test reset cache.
     */
    public function testResetCache()
    {
        $this->_createArticle('_testArt', '_testVar');

        oxAddClassMOdule('oxCacheHelper', 'oxcache');

        $aCategoryIds = array('cat1id', 'cat2id');

        $aResetOn = array('_testArt2'  => 'anid',
                          '_testArt'   => 'anid',
                          'cat1id'     => 'cid',
                          'cat2id'     => 'cid',
                          'article1id' => 'anid',
                          'article2id' => 'anid'
        );

        $oConfig = $this->getMock(\OxidEsales\Eshop\Core\Config::class, array('getConfigParam'));
        $oConfig->expects($this->at(0))->method('getConfigParam')->with($this->equalTo('blUseContentCaching'))->will($this->returnValue(true));
        $oConfig->expects($this->at(1))->method('getConfigParam')->with($this->equalTo('blUseStock'))->will($this->returnValue(true));
        $oConfig->expects($this->at(2))->method('getConfigParam')->with($this->equalTo('sStockWarningLimit'))->will($this->returnValue(10));
        $oConfig->expects($this->at(3))->method('getConfigParam')->with($this->equalTo('bl_perfLoadSimilar'))->will($this->returnValue(true));
        $oConfig->expects($this->at(4))->method('getConfigParam')->with($this->equalTo('bl_perfLoadCrossselling'))->will($this->returnValue(true));
        $oConfig->expects($this->at(5))->method('getConfigParam')->with($this->equalTo('bl_perfLoadAccessoires'))->will($this->returnValue(true));

        // data preparation simulation
        $oO2A = oxNew(\OxidEsales\Eshop\Core\Model\BaseModel::class);
        $oO2A->init('oxobject2attribute');
        $oO2A->oxobject2attribute__oxobjectid = new \OxidEsales\Eshop\Core\Field('_testArt', \OxidEsales\Eshop\Core\Field::T_RAW);
        $oO2A->oxobject2attribute__oxattrid = new \OxidEsales\Eshop\Core\Field('cat1id', \OxidEsales\Eshop\Core\Field::T_RAW);
        $oO2A->save();

        $oO2A = oxNew(\OxidEsales\Eshop\Core\Model\BaseModel::class);
        $oO2A->init('oxobject2attribute');
        $oO2A->oxobject2attribute__oxobjectid = new \OxidEsales\Eshop\Core\Field('_testArt2', \OxidEsales\Eshop\Core\Field::T_RAW);
        $oO2A->oxobject2attribute__oxattrid = new \OxidEsales\Eshop\Core\Field('cat1id', \OxidEsales\Eshop\Core\Field::T_RAW);
        $oO2A->save();

        $oA2A = oxNew(\OxidEsales\Eshop\Core\Model\BaseModel::class);
        $oA2A->init('oxaccessoire2article');
        $oA2A->oxaccessoire2article__oxobjectid = new \OxidEsales\Eshop\Core\Field('_testArt', \OxidEsales\Eshop\Core\Field::T_RAW);
        $oA2A->oxaccessoire2article__oxarticlenid = new \OxidEsales\Eshop\Core\Field('_testArt', \OxidEsales\Eshop\Core\Field::T_RAW);
        $oA2A->save();

        $oO2A = oxNew(\OxidEsales\Eshop\Core\Model\BaseModel::class);
        $oO2A->init('oxobject2article');
        $oO2A->oxobject2article__oxobjectid = new \OxidEsales\Eshop\Core\Field('article1id', \OxidEsales\Eshop\Core\Field::T_RAW);
        $oO2A->oxobject2article__oxarticlenid = new \OxidEsales\Eshop\Core\Field('_testArt', \OxidEsales\Eshop\Core\Field::T_RAW);
        $oO2A->save();

        $oO2A = oxNew(\OxidEsales\Eshop\Core\Model\BaseModel::class);
        $oO2A->init('oxobject2article');
        $oO2A->oxobject2article__oxobjectid = new \OxidEsales\Eshop\Core\Field('_testArt', \OxidEsales\Eshop\Core\Field::T_RAW);
        $oO2A->oxobject2article__oxarticlenid = new \OxidEsales\Eshop\Core\Field('article2id', \OxidEsales\Eshop\Core\Field::T_RAW);
        $oO2A->save();

        // article preparation
        $oArticle = $this->getMock(\OxidEsales\Eshop\Application\Model\Article::class, array('isAdmin', 'getCategoryIds'));
        $oArticle->expects($this->any())->method('isAdmin')->will($this->returnValue(false));
        $oArticle->expects($this->any())->method('getCategoryIds')->will($this->returnValue($aCategoryIds));

        $oArticle->load('_testArt');
        $oArticle->_iStockStatus = 0;
        $oArticle->oxarticles__oxstock = new \OxidEsales\Eshop\Core\Field(5, \OxidEsales\Eshop\Core\Field::T_RAW);
        $oArticle->oxarticles__oxstockflag = new \OxidEsales\Eshop\Core\Field(1, \OxidEsales\Eshop\Core\Field::T_RAW);
        $oArticle->setConfig($oConfig);

        try {
            $oArticle->UNITresetCache();
        } catch (Exception $oEx) {
            $this->assertEquals($aResetOn, unserialize($oEx->getMessage()));

            return;
        }
        $this->fail('error testing testResetCacheActionArticle');
    }

    /**
     * Test reset cache when use content cache is disabled.
     */
    public function testResetCacheContentCacheDisabled()
    {
        $oConfig = $this->getMock(\OxidEsales\Eshop\Core\Config::class, array('getConfigParam'));
        $oConfig->expects($this->at(0))->method('getConfigParam')->with($this->equalTo('blUseContentCaching'))->will($this->returnValue(false));

        // article preparation
        $oArticle = $this->getMock(\OxidEsales\Eshop\Application\Model\Article::class, array('isAdmin', 'getCategoryIds'));
        $oArticle->expects($this->any())->method('isAdmin')->will($this->returnValue(false));

        $oArticle->load('_testArt');
        $oArticle->setConfig($oConfig);

        $this->assertEquals(null, $oArticle->UNITresetCache());
    }

    /**
     * Test update is denied by rights&roles.
     */
    public function testUpdateDeniedByRR()
    {
        $oArticle = $this->getMock(\OxidEsales\Eshop\Application\Model\Article::class, array('canUpdate', '_createUpdateStr', '_createInsertStr', 'SkipSaveFields'));
        $oArticle->expects($this->once())->method('canUpdate')->will($this->returnValue(false));
        $oArticle->expects($this->never())->method('_createUpdateStr');
        $oArticle->expects($this->never())->method('_createInsertStr');
        $oArticle->expects($this->never())->method('SkipSaveFields');

        $this->assertFalse($oArticle->UNITupdate());
    }

    /**
     * Test assign is denied by rights&roles.
     */
    public function testAssignDeniedByRR()
    {
        $oArticle = $this->getMock(\OxidEsales\Eshop\Application\Model\Article::class, array('canRead', '_assignLinks', '_assignParentFieldValues'), array(), '', false);
        $oArticle->expects($this->once())->method('canRead')->will($this->returnValue(false));
        $oArticle->expects($this->never())->method('_assignLinks');
        $oArticle->expects($this->never())->method('_assignParentFieldValues');

        $this->assertFalse($oArticle->assign(array('xxx')));
    }

    /**
     * Test delete is denied by rights&roles.
     */
    public function  testDeleteWithDeniedByRR()
    {
        $oArticle = $this->getMock(\OxidEsales\Eshop\Application\Model\Article::class, array('canDelete', 'load', '_deletePics'));
        $oArticle->expects($this->any())->method('canDelete')->will($this->returnValue(false));
        $oArticle->expects($this->never())->method('load');
        $oArticle->expects($this->never())->method('_deletePics');

        // now deleting and checking for records in DB
        $this->assertFalse($oArticle->delete("_test"));
    }

    /**
     * Test can do with missing id, parent method will return false.
     */
    public function testCanDoMissingIdParentMethodWillReturnFalse()
    {
        $oArticle = $this->getMock(\OxidEsales\Eshop\Application\Model\Article::class, array('isAdmin', 'getRights'));
        $oArticle->expects($this->any())->method('isAdmin')->will($this->returnValue(false));
        $oArticle->expects($this->any())->method('getRights')->will($this->returnValue(true));
        $this->assertFalse($oArticle->canDo(null, 1));
    }

    /**
     * Test can do parent method will return true.
     */
    public function testCanDoParentReturnsTrue()
    {
        $oArticle = $this->getMock(\OxidEsales\Eshop\Application\Model\Article::class, array('isAdmin'));
        $oArticle->expects($this->any())->method('isAdmin')->will($this->returnValue(false));

        $this->assertTrue($oArticle->canDo('1661-01', 1));
    }

    /**
     * Test can do parent product can do returns false.
     */
    public function testCanDoParentProductCanDoReturnsFalse()
    {
        $oRights = $this->getMock(\OxidEsales\Eshop\Application\Model\Rights::class, array('hasObjectRights'));
        $oRights->expects($this->any())->method('hasObjectRights')->will($this->onConsecutiveCalls(true, false));

        $oArticle = $this->getMock(\OxidEsales\Eshop\Application\Model\Article::class, array('isAdmin', 'getRights'));
        $oArticle->expects($this->any())->method('isAdmin')->will($this->returnValue(false));
        $oArticle->expects($this->any())->method('getRights')->will($this->returnValue($oRights));

        $this->assertFalse($oArticle->canDo('1661-01', 1));
    }

    /**
     * Test can update field is derived but field not in multishop.
     */
    public function testcanUpdateFieldIsDerivedButFieldNotInMultishop()
    {
        $this->getConfig()->setConfigParam('aMultishopArticleFields', array('oxprice', 'oxtitle'));

        $oArticle = $this->getMock(\OxidEsales\Eshop\Application\Model\Article::class, array('isDerived', 'isAdmin'));
        $oArticle->expects($this->any())->method('isDerived')->will($this->returnValue(true));
        $oArticle->expects($this->any())->method('isAdmin')->will($this->returnValue(false));

        $this->assertFalse($oArticle->canUpdateField('oxtprice'));
    }

    /**
     * Test can update field is not derived.
     */
    public function testcanUpdateFieldIsNotDerived()
    {
        $oArticle = $this->getMock(\OxidEsales\Eshop\Application\Model\Article::class, array('isDerived', 'isAdmin'));
        $oArticle->expects($this->any())->method('isDerived')->will($this->returnValue(false));
        $oArticle->expects($this->any())->method('isAdmin')->will($this->returnValue(false));

        $this->assertTrue($oArticle->canUpdateField('oxprice'));
    }

    /**
     * Provides configuration parameters for aMultishopArticleFields,
     * when the shop is a subshop,
     * and expected result.
     *
     * @return array
     */
    public function configurationProvider()
    {
        return array(
            array(array(), false),
            array(array('OXSHORTDESC'), true),
        );
    }

    /**
     * Test if user can update any field.
     *
     * @dataProvider configurationProvider
     */
    public function testCanUpdateAnyField($aMultishopArticleFields, $blExpected)
    {
        $this->getConfig()->setConfigParam('blMallCustomPrice', true);
        $this->getConfig()->setConfigParam('aMultishopArticleFields', $aMultishopArticleFields);
        $oArticle = $this->getMock(\OxidEsales\Eshop\Application\Model\Article::class, array('isDerived'));
        $oArticle->expects($this->any())->method('isDerived')->will($this->returnValue(true));

        $this->assertEquals($blExpected, $oArticle->canUpdateAnyField());
    }

    /**
     * Test can view in admin.
     */
    public function testCanViewIsAdmin()
    {
        $oArticle = $this->getMock(\OxidEsales\Eshop\Application\Model\Article::class, array('canDo', 'isAdmin'));
        $oArticle->expects($this->never())->method('canDo');
        $oArticle->expects($this->any())->method('isAdmin')->will($this->returnValue(true));

        $this->assertTrue($oArticle->canView());
    }

    /**
     * Test can view with disabled rights&roles.
     */
    public function testCanViewRRisOff()
    {
        $oArticle = $this->getMock(\OxidEsales\Eshop\Application\Model\Article::class, array('canDo', 'getRights'));
        $oArticle->expects($this->never())->method('canDo');
        $oArticle->expects($this->any())->method('getRights')->will($this->returnValue(null));

        $this->assertTrue($oArticle->canView());
    }

    /**
     * Test can view with enabled rights&roles not in admin.
     */
    public function testCanViewRRisOnAnNonAdmin()
    {
        $oArticle = $this->getMock(\OxidEsales\Eshop\Application\Model\Article::class, array('canDo', 'isAdmin', 'getRights'));
        $oArticle->expects($this->once())->method('canDo')->will($this->returnValue(true));
        $oArticle->expects($this->any())->method('isAdmin')->will($this->returnValue(false));
        $oArticle->expects($this->once())->method('getRights')->will($this->returnValue(oxNew(\OxidEsales\Eshop\Application\Model\Rights::class)));

        $this->assertTrue($oArticle->canView());
    }

    /**
     * Test can buy in admin.
     */
    public function testCanBuyIsAdmin()
    {
        $oArticle = $this->getMock(\OxidEsales\Eshop\Application\Model\Article::class, array('canDo', 'isAdmin'));
        $oArticle->expects($this->never())->method('canDo');
        $oArticle->expects($this->any())->method('isAdmin')->will($this->returnValue(true));

        $this->assertTrue($oArticle->canBuy());
    }

    /**
     * Test can buy with disabled rights&roles.
     */
    public function testCanBuyRRisOff()
    {
        $oArticle = $this->getMock(\OxidEsales\Eshop\Application\Model\Article::class, array('canDo', 'getRights'));
        $oArticle->expects($this->never())->method('canDo');
        $oArticle->expects($this->any())->method('getRights')->will($this->returnValue(null));

        $this->assertTrue($oArticle->canBuy());
    }

    /**
     * Test can buy with enabled rights&roles not in admin.
     */
    public function testCanBuyRRisOnAnNonAdmin()
    {
        $oArticle = $this->getMock(\OxidEsales\Eshop\Application\Model\Article::class, array('canDo', 'isAdmin', 'getRights'));
        $oArticle->expects($this->once())->method('canDo')->will($this->returnValue(true));
        $oArticle->expects($this->any())->method('isAdmin')->will($this->returnValue(false));
        $oArticle->expects($this->once())->method('getRights')->will($this->returnValue(oxNew(\OxidEsales\Eshop\Application\Model\Rights::class)));

        $this->assertTrue($oArticle->canBuy());
    }

    /**
     * Test is visible denied by rights&roles.
     */
    public function testIsVisibleDeniedByRR()
    {
        $oArticle = $this->getMock(\OxidEsales\Eshop\Application\Model\Article::class, array('canView'));
        $oArticle->expects($this->once())->method('canView')->will($this->returnValue(false));

        $this->assertFalse($oArticle->isVisible());
    }

    /**
     * Test get article long description denied by rights&roles.
     */
    public function testGetLongDescriptionByRR()
    {
        $oArticle = $this->getMock(\OxidEsales\Eshop\Application\Model\Article::class, array('canReadField'));
        $oArticle->expects($this->once())->method('canReadField')->will($this->returnValue(false));

        $oField = $oArticle->getLongDescription();
        $this->assertNull($oField->value);
    }

    /**
     * Test set article long description denied by rights&roles.
     */
    public function testSetArticleLongDescByRR()
    {
        $oArticle = $this->getMock(\OxidEsales\Eshop\Application\Model\Article::class, array('canUpdateField', 'getLanguage'), array(), '', false);
        $oArticle->expects($this->once())->method('canUpdateField')->will($this->returnValue(false));
        $oArticle->expects($this->never())->method('getLanguage');

        $this->assertFalse($oArticle->setArticleLongDesc("test"));
    }

    /**
     * Test long descriptio saving for subshop fields, skips main shop.
     */
    public function testLongDescSavingForSubshopFields_skipsMainShop()
    {
        $this->getConfig()->setConfigParam('aMultishopArticleFields', array("OXLONGDESC"));
        $oA = $this->getMock(\OxidEsales\Eshop\Application\Model\Article::class, array('_saveArtLongDesc'));
        $oA->expects($this->never())->method('_saveArtLongDesc');
        $oA->oxarticles__oxshopid = new \OxidEsales\Eshop\Core\Field(2);
        $oA->setArticleLongDesc("test");
    }

    /**
     * Test long descriptio saving for subshop fields, save raw value.
     */
    public function testLongDescSavingForSubshopFields_savesRawValue()
    {
        oxTestModules::addFunction("oxUtils", "fromFileCache", "{return false;}");

        $database = \OxidEsales\Eshop\Core\DatabaseProvider::getInstance();
        $database->flushTableDescriptionCache();

        \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute("alter table oxfield2shop add column `oxlongdesc` text NOT NULL");
        \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute("alter table oxfield2shop add column `oxlongdesc_1` text NOT NULL");
        \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute("alter table oxfield2shop add column `oxlongdesc_2` text NOT NULL");
        \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute("alter table oxfield2shop add column `oxlongdesc_3` text NOT NULL");
        \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute("CREATE OR REPLACE SQL SECURITY INVOKER VIEW oxv_oxfield2shop_de AS SELECT OXID,OXARTID,OXSHOPID,OXPRICE,OXPRICEA,OXPRICEB,OXPRICEC, OXUPDATEPRICE, OXUPDATEPRICEA, OXUPDATEPRICEB, OXUPDATEPRICEC, OXUPDATEPRICETIME,OXLONGDESC,OXTIMESTAMP FROM oxfield2shop");
        \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute("CREATE OR REPLACE SQL SECURITY INVOKER VIEW oxv_oxfield2shop_en AS SELECT OXID,OXARTID,OXSHOPID,OXPRICE,OXPRICEA,OXPRICEB,OXPRICEC, OXUPDATEPRICE, OXUPDATEPRICEA, OXUPDATEPRICEB, OXUPDATEPRICEC, OXUPDATEPRICETIME,OXLONGDESC_1 as OXLONGDESC,OXTIMESTAMP FROM oxfield2shop");
        \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute("CREATE OR REPLACE SQL SECURITY INVOKER VIEW oxv_oxarticles_2 AS SELECT oxarticles.* FROM oxarticles INNER JOIN oxarticles2shop AS t2s ON t2s.OXMAPOBJECTID = oxarticles.OXMAPID WHERE t2s.OXSHOPID = 2");
        \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute("CREATE OR REPLACE SQL SECURITY INVOKER VIEW oxv_oxarticles_2_de AS SELECT OXID,OXMAPID,oxarticles.OXSHOPID,OXPARENTID,OXACTIVE,OXACTIVEFROM,OXACTIVETO,OXARTNUM,OXEAN,OXDISTEAN,OXMPN,OXTITLE,OXSHORTDESC,OXPRICE,OXBLFIXEDPRICE,OXPRICEA,OXPRICEB,OXPRICEC,OXBPRICE,OXTPRICE,OXUNITNAME,OXUNITQUANTITY,OXEXTURL,OXURLDESC,OXURLIMG,OXVAT,OXTHUMB,OXICON,OXPIC1,OXPIC2,OXPIC3,OXPIC4,OXPIC5,OXPIC6,OXPIC7,OXPIC8,OXPIC9,OXPIC10,OXPIC11,OXPIC12,OXWEIGHT,OXSTOCK,OXSTOCKFLAG,OXSTOCKTEXT,OXNOSTOCKTEXT,OXDELIVERY,OXINSERT,oxarticles.OXTIMESTAMP,OXLENGTH,OXWIDTH,OXHEIGHT,OXFILE,OXSEARCHKEYS,OXTEMPLATE,OXQUESTIONEMAIL,OXISSEARCH,OXISCONFIGURABLE,OXVARNAME,OXVARSTOCK,OXVARCOUNT,OXVARSELECT,OXVARMINPRICE,OXVARMAXPRICE,OXFOLDER,OXSUBCLASS,OXSORT,OXSOLDAMOUNT,OXNONMATERIAL,OXFREESHIPPING,OXREMINDACTIVE,OXREMINDAMOUNT,OXAMITEMID,OXAMTASKID,OXVENDORID,OXMANUFACTURERID,OXSKIPDISCOUNTS,OXORDERINFO,OXPIXIEXPORT,OXPIXIEXPORTED,OXVPE,OXRATING,OXRATINGCNT,OXMINDELTIME,OXMAXDELTIME,OXDELTIMEUNIT,OXUPDATEPRICE, OXUPDATEPRICEA, OXUPDATEPRICEB, OXUPDATEPRICEC, OXUPDATEPRICETIME, OXISDOWNLOADABLE, OXSHOWCUSTOMAGREEMENT FROM oxarticles INNER JOIN oxarticles2shop AS t2s ON t2s.OXMAPOBJECTID = oxarticles.OXMAPID WHERE t2s.OXSHOPID = 2");
        \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute("CREATE OR REPLACE SQL SECURITY INVOKER VIEW oxv_oxarticles_2_en AS SELECT OXID,OXMAPID,oxarticles.OXSHOPID,OXPARENTID,OXACTIVE,OXACTIVEFROM,OXACTIVETO,OXARTNUM,OXEAN,OXDISTEAN,OXMPN,OXTITLE_1 AS OXTITLE,OXSHORTDESC_1 AS OXSHORTDESC,OXPRICE,OXBLFIXEDPRICE,OXPRICEA,OXPRICEB,OXPRICEC,OXBPRICE,OXTPRICE,OXUNITNAME,OXUNITQUANTITY,OXEXTURL,OXURLDESC_1 AS OXURLDESC,OXURLIMG,OXVAT,OXTHUMB,OXICON,OXPIC1,OXPIC2,OXPIC3,OXPIC4,OXPIC5,OXPIC6,OXPIC7,OXPIC8,OXPIC9,OXPIC10,OXPIC11,OXPIC12,OXWEIGHT,OXSTOCK,OXSTOCKFLAG,OXSTOCKTEXT_1 AS OXSTOCKTEXT,OXNOSTOCKTEXT_1 AS OXNOSTOCKTEXT,OXDELIVERY,OXINSERT,oxarticles.OXTIMESTAMP,OXLENGTH,OXWIDTH,OXHEIGHT,OXFILE,OXSEARCHKEYS_1 AS OXSEARCHKEYS,OXTEMPLATE,OXQUESTIONEMAIL,OXISSEARCH,OXISCONFIGURABLE,OXVARNAME_1 AS OXVARNAME,OXVARSTOCK,OXVARCOUNT,OXVARSELECT_1 AS OXVARSELECT,OXVARMINPRICE,OXVARMAXPRICE,OXFOLDER,OXSUBCLASS,OXSORT,OXSOLDAMOUNT,OXNONMATERIAL,OXFREESHIPPING,OXREMINDACTIVE,OXREMINDAMOUNT,OXAMITEMID,OXAMTASKID,OXVENDORID,OXMANUFACTURERID,OXSKIPDISCOUNTS,OXORDERINFO,OXPIXIEXPORT,OXPIXIEXPORTED,OXVPE,OXRATING,OXRATINGCNT,OXMINDELTIME,OXMAXDELTIME,OXDELTIMEUNIT,OXUPDATEPRICE, OXUPDATEPRICEA, OXUPDATEPRICEB, OXUPDATEPRICEC, OXUPDATEPRICETIME, OXISDOWNLOADABLE, OXSHOWCUSTOMAGREEMENT FROM oxarticles INNER JOIN oxarticles2shop AS t2s ON t2s.OXMAPOBJECTID = oxarticles.OXMAPID WHERE t2s.OXSHOPID = 2");
        try {
            $oArticle = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
            if ($oArticle->load('test_SubshopFields_savesRawValue')) {
                $oArticle->delete();
            }
            \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute('delete from oxarticles where oxid="test_SubshopFields_savesRawValue"');


            $this->getConfig()->setConfigParam('aMultishopArticleFields', array("OXLONGDESC"));

            // insert article
            $oArticle = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
            $oArticle->assign(array('OXID' => 'test_SubshopFields_savesRawValue'));
            $oArticle->setArticleLongDesc('lalaal&!<b><');
            $oArticle->save();

            \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute('update oxarticles set oxshopid=2 where oxid="test_SubshopFields_savesRawValue"');
            $oArticle->assignToShop(2);
            $this->assertEquals(0, \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->getOne('select count(*) from oxfield2shop where oxartid="test_SubshopFields_savesRawValue" and oxshopid=1'));

            // update article
            $oArticle = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
            $oArticle->load('test_SubshopFields_savesRawValue');
            $oArticle->assign(array('OXID' => 'test_SubshopFields_savesRawValue'));
            $oArticle->setArticleLongDesc('lalaal&!<b><');
            $oArticle->save();

            $this->assertEquals('lalaal&!<b><', \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->getOne('select oxlongdesc from oxfield2shop where oxartid="test_SubshopFields_savesRawValue" and oxshopid=1'));

            // delete article
            $oArticle = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
            $this->assertTrue($oArticle->load('test_SubshopFields_savesRawValue'));
            $this->assertEquals('lalaal&!<b><', $oArticle->getLongDescription()->getRawValue());

            // load in 2 shop
            $this->getConfig()->setShopId(2);
            $oArticle = $this->getMock(\OxidEsales\Eshop\Application\Model\Article::class, array('_assignPrices'));
            $oArticle->expects($this->any())->method('_assignPrices')->will($this->returnValue(null));
            $this->assertTrue($oArticle->load('test_SubshopFields_savesRawValue'));
            $this->assertEquals('lalaal&!<b><', $oArticle->getLongDescription()->getRawValue());

            $oArticle->delete();
            $this->getConfig()->setShopId(1);
        } catch (Exception $e) {
            \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute("alter table oxfield2shop drop column `oxlongdesc`");
            \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute("alter table oxfield2shop drop column `oxlongdesc_1`");
            \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute("alter table oxfield2shop drop column `oxlongdesc_2`");
            \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute("alter table oxfield2shop drop column `oxlongdesc_3`");
            \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute("DROP VIEW `oxv_oxarticles_2`");
            \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute("DROP VIEW `oxv_oxarticles_2_de`");
            \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute("DROP VIEW `oxv_oxarticles_2_en`");
            \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute("CREATE OR REPLACE SQL SECURITY INVOKER VIEW oxv_oxfield2shop_de AS SELECT OXID,OXARTID,OXSHOPID,OXPRICE,OXPRICEA,OXPRICEB,OXPRICEC, OXUPDATEPRICE, OXUPDATEPRICEA, OXUPDATEPRICEB, OXUPDATEPRICEC, OXUPDATEPRICETIME, OXTIMESTAMP FROM oxfield2shop");
            \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute("CREATE OR REPLACE SQL SECURITY INVOKER VIEW oxv_oxfield2shop_en AS SELECT OXID,OXARTID,OXSHOPID,OXPRICE,OXPRICEA,OXPRICEB,OXPRICEC, OXUPDATEPRICE, OXUPDATEPRICEA, OXUPDATEPRICEB, OXUPDATEPRICEC, OXUPDATEPRICETIME, OXTIMESTAMP FROM oxfield2shop");

            throw $e;
        }
        \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute("alter table oxfield2shop drop column `oxlongdesc`");
        \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute("alter table oxfield2shop drop column `oxlongdesc_1`");
        \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute("alter table oxfield2shop drop column `oxlongdesc_2`");
        \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute("alter table oxfield2shop drop column `oxlongdesc_3`");
        \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute("DROP VIEW `oxv_oxarticles_2`");
        \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute("DROP VIEW `oxv_oxarticles_2_de`");
        \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute("DROP VIEW `oxv_oxarticles_2_en`");
        \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute("CREATE OR REPLACE SQL SECURITY INVOKER VIEW oxv_oxfield2shop_de AS SELECT OXID,OXARTID,OXSHOPID,OXPRICE,OXPRICEA,OXPRICEB,OXPRICEC, OXUPDATEPRICE, OXUPDATEPRICEA, OXUPDATEPRICEB, OXUPDATEPRICEC, OXUPDATEPRICETIME, OXTIMESTAMP FROM oxfield2shop");
        \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute("CREATE OR REPLACE SQL SECURITY INVOKER VIEW oxv_oxfield2shop_en AS SELECT OXID,OXARTID,OXSHOPID,OXPRICE,OXPRICEA,OXPRICEB,OXPRICEC, OXUPDATEPRICE, OXUPDATEPRICEA, OXUPDATEPRICEB, OXUPDATEPRICEC, OXUPDATEPRICETIME, OXTIMESTAMP FROM oxfield2shop");
    }

    /**
     * Test multilanguage saving for subshop fields, obj disabled ml.
     */
    public function testMultilangSavingForSubshopFields_objDisabledMl()
    {
        oxTestModules::addFunction("oxUtils", "fromFileCache", "{return false;}");

        $database = \OxidEsales\Eshop\Core\DatabaseProvider::getInstance();
        $database->flushTableDescriptionCache();

        \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute("alter table oxfield2shop add column `OXSHORTDESC` varchar(255) NOT NULL default ''");
        \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute("alter table oxfield2shop add column `OXSHORTDESC_1` varchar(255) NOT NULL default ''");
        \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute("alter table oxfield2shop add column `OXSHORTDESC_2` varchar(255) NOT NULL default ''");
        \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute("alter table oxfield2shop add column `OXSHORTDESC_3` varchar(255) NOT NULL default ''");
        \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute("CREATE OR REPLACE SQL SECURITY INVOKER VIEW oxv_oxfield2shop AS SELECT OXID,OXARTID,OXSHOPID,OXPRICE,OXPRICEA,OXPRICEB,OXPRICEC, OXUPDATEPRICE, OXUPDATEPRICEA, OXUPDATEPRICEB, OXUPDATEPRICEC, OXUPDATEPRICETIME,OXSHORTDESC,OXSHORTDESC_1,OXSHORTDESC_2,OXSHORTDESC_3,OXTIMESTAMP FROM oxfield2shop");
        \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute("CREATE OR REPLACE SQL SECURITY INVOKER VIEW oxv_oxarticles_2 AS SELECT oxarticles.* FROM oxarticles INNER JOIN oxarticles2shop AS t2s ON t2s.OXMAPOBJECTID = oxarticles.OXMAPID WHERE t2s.OXSHOPID = 2");
        \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute("CREATE OR REPLACE SQL SECURITY INVOKER VIEW oxv_oxarticles_2_de AS SELECT OXID,OXMAPID,oxarticles.OXSHOPID,OXPARENTID,OXACTIVE,OXACTIVEFROM,OXACTIVETO,OXARTNUM,OXEAN,OXDISTEAN,OXMPN,OXTITLE,OXSHORTDESC,OXPRICE,OXBLFIXEDPRICE,OXPRICEA,OXPRICEB,OXPRICEC,OXBPRICE,OXTPRICE,OXUNITNAME,OXUNITQUANTITY,OXEXTURL,OXURLDESC,OXURLIMG,OXVAT,OXTHUMB,OXICON,OXPIC1,OXPIC2,OXPIC3,OXPIC4,OXPIC5,OXPIC6,OXPIC7,OXPIC8,OXPIC9,OXPIC10,OXPIC11,OXPIC12,OXWEIGHT,OXSTOCK,OXSTOCKFLAG,OXSTOCKTEXT,OXNOSTOCKTEXT,OXDELIVERY,OXINSERT,oxarticles.OXTIMESTAMP,OXLENGTH,OXWIDTH,OXHEIGHT,OXFILE,OXSEARCHKEYS,OXTEMPLATE,OXQUESTIONEMAIL,OXISSEARCH,OXISCONFIGURABLE,OXVARNAME,OXVARSTOCK,OXVARCOUNT,OXVARSELECT,OXVARMINPRICE,OXVARMAXPRICE,OXFOLDER,OXSUBCLASS,OXSORT,OXSOLDAMOUNT,OXNONMATERIAL,OXFREESHIPPING,OXREMINDACTIVE,OXREMINDAMOUNT,OXAMITEMID,OXAMTASKID,OXVENDORID,OXMANUFACTURERID,OXSKIPDISCOUNTS,OXORDERINFO,OXPIXIEXPORT,OXPIXIEXPORTED,OXVPE,OXRATING,OXRATINGCNT,OXMINDELTIME,OXMAXDELTIME,OXDELTIMEUNIT,OXUPDATEPRICE, OXUPDATEPRICEA, OXUPDATEPRICEB, OXUPDATEPRICEC, OXUPDATEPRICETIME, OXISDOWNLOADABLE, OXSHOWCUSTOMAGREEMENT FROM oxarticles INNER JOIN oxarticles2shop AS t2s ON t2s.OXMAPOBJECTID = oxarticles.OXMAPID WHERE t2s.OXSHOPID = 2");
        \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute("CREATE OR REPLACE SQL SECURITY INVOKER VIEW oxv_oxarticles_2_en AS SELECT OXID,OXMAPID,oxarticles.OXSHOPID,OXPARENTID,OXACTIVE,OXACTIVEFROM,OXACTIVETO,OXARTNUM,OXEAN,OXDISTEAN,OXMPN,OXTITLE_1 AS OXTITLE,OXSHORTDESC_1 AS OXSHORTDESC,OXPRICE,OXBLFIXEDPRICE,OXPRICEA,OXPRICEB,OXPRICEC,OXBPRICE,OXTPRICE,OXUNITNAME,OXUNITQUANTITY,OXEXTURL,OXURLDESC_1 AS OXURLDESC,OXURLIMG,OXVAT,OXTHUMB,OXICON,OXPIC1,OXPIC2,OXPIC3,OXPIC4,OXPIC5,OXPIC6,OXPIC7,OXPIC8,OXPIC9,OXPIC10,OXPIC11,OXPIC12,OXWEIGHT,OXSTOCK,OXSTOCKFLAG,OXSTOCKTEXT_1 AS OXSTOCKTEXT,OXNOSTOCKTEXT_1 AS OXNOSTOCKTEXT,OXDELIVERY,OXINSERT,oxarticles.OXTIMESTAMP,OXLENGTH,OXWIDTH,OXHEIGHT,OXFILE,OXSEARCHKEYS_1 AS OXSEARCHKEYS,OXTEMPLATE,OXQUESTIONEMAIL,OXISSEARCH,OXISCONFIGURABLE,OXVARNAME_1 AS OXVARNAME,OXVARSTOCK,OXVARCOUNT,OXVARSELECT_1 AS OXVARSELECT,OXVARMINPRICE,OXVARMAXPRICE,OXFOLDER,OXSUBCLASS,OXSORT,OXSOLDAMOUNT,OXNONMATERIAL,OXFREESHIPPING,OXREMINDACTIVE,OXREMINDAMOUNT,OXAMITEMID,OXAMTASKID,OXVENDORID,OXMANUFACTURERID,OXSKIPDISCOUNTS,OXORDERINFO,OXPIXIEXPORT,OXPIXIEXPORTED,OXVPE,OXRATING,OXRATINGCNT,OXMINDELTIME,OXMAXDELTIME,OXDELTIMEUNIT,OXUPDATEPRICE, OXUPDATEPRICEA, OXUPDATEPRICEB, OXUPDATEPRICEC, OXUPDATEPRICETIME, OXISDOWNLOADABLE, OXSHOWCUSTOMAGREEMENT FROM oxarticles INNER JOIN oxarticles2shop AS t2s ON t2s.OXMAPOBJECTID = oxarticles.OXMAPID WHERE t2s.OXSHOPID = 2");

        try {
            $this->getConfig()->setConfigParam('aMultishopArticleFields', array("oxshortdesc"));
            // add article to first shop
            $oArticle = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
            $oArticle->setEnableMultilang(false);
            // insert article
            $oArticle->assign(array('OXID' => 'test_rrasdd', 'oxshortdesc_1' => 'lalaal'));
            $oArticle->save();
            // add article to second shop
            $oArticle->assignToShop(2);

            $this->getConfig()->setShopId(2);
            // insert field 2 shop
            \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute("delete from oxfield2shop where oxartid = 'test_rrasdd'");

            $oArticle = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
            $oArticle->setEnableMultilang(false);
            $this->assertTrue($oArticle->load('test_rrasdd'));
            $this->assertEquals('lalaal', $oArticle->oxarticles__oxshortdesc_1->value);


            // update field 2 shop
            $oArticle->assign(array('OXID' => 'test_rrasdd', 'oxshortdesc_1' => 'llaa'));
            $oArticle->save();

            // test
            $oArticle = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
            $oArticle->setEnableMultilang(false);
            $this->assertTrue($oArticle->load('test_rrasdd'));
            $this->assertEquals('llaa', $oArticle->oxarticles__oxshortdesc_1->value);


            $oArticle->oxarticles__oxshortdesc_1 = new \OxidEsales\Eshop\Core\Field('llb');
            $oArticle->save();

            $oArticle = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
            $oArticle->setEnableMultilang(false);
            $this->assertTrue($oArticle->load('test_rrasdd'));
            $this->assertEquals('llb', $oArticle->oxarticles__oxshortdesc_1->value);


            // check original
            $this->getConfig()->setShopId(1);

            $oArticle = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
            $oArticle->setEnableMultilang(false);
            $this->assertTrue($oArticle->load('test_rrasdd'));
            $this->assertEquals('lalaal', $oArticle->oxarticles__oxshortdesc_1->value);


            $oArticle->delete();


        } catch (Exception $e) {
            \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute("alter table oxfield2shop drop column `OXSHORTDESC`");
            \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute("alter table oxfield2shop drop column `OXSHORTDESC_1`");
            \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute("alter table oxfield2shop drop column `OXSHORTDESC_2`");
            \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute("alter table oxfield2shop drop column `OXSHORTDESC_3`");
            \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute("DROP VIEW `oxv_oxarticles_2`");
            \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute("DROP VIEW `oxv_oxarticles_2_de`");
            \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute("DROP VIEW `oxv_oxarticles_2_en`");
            throw $e;
        }
        \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute("alter table oxfield2shop drop column `OXSHORTDESC`");
        \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute("alter table oxfield2shop drop column `OXSHORTDESC_1`");
        \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute("alter table oxfield2shop drop column `OXSHORTDESC_2`");
        \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute("alter table oxfield2shop drop column `OXSHORTDESC_3`");
        \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute("DROP VIEW `oxv_oxarticles_2`");
        \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute("DROP VIEW `oxv_oxarticles_2_de`");
        \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute("DROP VIEW `oxv_oxarticles_2_en`");
        \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute("CREATE OR REPLACE SQL SECURITY INVOKER VIEW oxv_oxfield2shop AS SELECT OXID,OXARTID,OXSHOPID,OXPRICE,OXPRICEA,OXPRICEB,OXPRICEC, OXUPDATEPRICE, OXUPDATEPRICEA, OXUPDATEPRICEB, OXUPDATEPRICEC, OXUPDATEPRICETIME, OXTIMESTAMP FROM oxfield2shop");
    }

    public function testFogBugEntry2179()
    {
        $oS1 = new stdClass();
        $oS1->name = 'L';
        $oS1->value = false;

        $oS2 = new stdClass();
        $oS2->name = 'M';
        $oS2->value = false;

        $oS3 = new stdClass();
        $oS3->name = 'S';
        $oS3->value = false;

        $aExpList = array(array($oS1, $oS2, $oS3, 'name' => 'selection list A'));
        // inserting selection lists
        $oSel = oxNew(\OxidEsales\Eshop\Core\Model\BaseModel::class);
        $oSel->init("oxselectlist");
        $oSel->setId("_testSel1");
        $oSel->oxselectlist__oxshopid = new \OxidEsales\Eshop\Core\Field(1);
        $oSel->oxselectlist__oxtitle = new \OxidEsales\Eshop\Core\Field("selection list A");
        $oSel->oxselectlist__oxtitle_1 = new \OxidEsales\Eshop\Core\Field("selection list A");
        $oSel->oxselectlist__oxvaldesc = new \OxidEsales\Eshop\Core\Field("L__@@M__@@S__@@");
        $oSel->oxselectlist__oxvaldesc_1 = new \OxidEsales\Eshop\Core\Field("L__@@M__@@S__@@");
        $oSel->save();

        $this->setShopId(2);
        $oSel = oxNew(\OxidEsales\Eshop\Core\Model\BaseModel::class);
        $oSel->init("oxselectlist");
        $oSel->setId("_testSel2");
        $oSel->oxselectlist__oxshopid = new \OxidEsales\Eshop\Core\Field(1);
        $oSel->oxselectlist__oxtitle = new \OxidEsales\Eshop\Core\Field("selection list B");
        $oSel->oxselectlist__oxtitle_1 = new \OxidEsales\Eshop\Core\Field("selection list B");
        $oSel->oxselectlist__oxvaldesc = new \OxidEsales\Eshop\Core\Field("Blue__@@Green__@@Red__@@");
        $oSel->oxselectlist__oxvaldesc_1 = new \OxidEsales\Eshop\Core\Field("Blue__@@Green__@@Red__@@");
        $oSel->save();
        $this->setShopId(1);

        // assigning to products
        $oO2S = oxNew(\OxidEsales\Eshop\Core\Model\BaseModel::class);
        $oO2S->init("oxobject2selectlist");
        $oO2S->setId("_testo2s1");
        $oO2S->oxobject2selectlist__oxobjectid = new \OxidEsales\Eshop\Core\Field("1126");
        $oO2S->oxobject2selectlist__oxselnid = new \OxidEsales\Eshop\Core\Field("_testSel1");
        $oO2S->oxobject2selectlist__oxsort = new \OxidEsales\Eshop\Core\Field(1);
        $oO2S->save();

        $oO2S = oxNew(\OxidEsales\Eshop\Core\Model\BaseModel::class);
        $oO2S->init("oxobject2selectlist");
        $oO2S->setId("_testo2s2");
        $oO2S->oxobject2selectlist__oxobjectid = new \OxidEsales\Eshop\Core\Field("1126");
        $oO2S->oxobject2selectlist__oxselnid = new \OxidEsales\Eshop\Core\Field("_testSel2");
        $oO2S->oxobject2selectlist__oxsort = new \OxidEsales\Eshop\Core\Field(2);
        $oO2S->save();

        // loading product
        $oProduct = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
        $oProduct->load("1126");
        $aList = $oProduct->getSelectLists(time());
        $this->addTableForCleanup("oxselectlist");
        $this->addTableForCleanup("oxselectlist2shop");

        $this->assertTrue(is_array($aList) && count($aList) == 1);
        $this->assertEquals($aExpList, $aList);
    }

    /**
     * Test for bug #5321
     */
    public function testLoadArticleFromInActiveShopWhenSharedBasketDisabled()
    {
        $iShopId = 2;

        $oProduct = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
        $oProduct->setId('_testArticle');
        $oProduct->oxarticles__oxprice = new \OxidEsales\Eshop\Core\Field(15.5);
        $oProduct->oxarticles__oxshopid = new \OxidEsales\Eshop\Core\Field(1);
        $oProduct->oxarticles__oxtitle = new \OxidEsales\Eshop\Core\Field("test");
        $oProduct->save();

        \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute("CREATE OR REPLACE SQL SECURITY INVOKER VIEW oxv_oxarticles_{$iShopId}_de AS SELECT OXID,OXMAPID,oxarticles.OXSHOPID,OXPARENTID,OXACTIVE,OXACTIVEFROM,OXACTIVETO,OXARTNUM,OXEAN,OXDISTEAN,OXMPN,OXTITLE_1 AS OXTITLE,OXSHORTDESC_1 AS OXSHORTDESC,OXPRICE,OXBLFIXEDPRICE,OXPRICEA,OXPRICEB,OXPRICEC,OXBPRICE,OXTPRICE,OXUNITNAME,OXUNITQUANTITY,OXEXTURL,OXURLDESC_1 AS OXURLDESC,OXURLIMG,OXVAT,OXTHUMB,OXICON,OXPIC1,OXPIC2,OXPIC3,OXPIC4,OXPIC5,OXPIC6,OXPIC7,OXPIC8,OXPIC9,OXPIC10,OXPIC11,OXPIC12,OXWEIGHT,OXSTOCK,OXSTOCKFLAG,OXSTOCKTEXT_1 AS OXSTOCKTEXT,OXNOSTOCKTEXT_1 AS OXNOSTOCKTEXT,OXDELIVERY,OXINSERT,oxarticles.OXTIMESTAMP,OXLENGTH,OXWIDTH,OXHEIGHT,OXFILE,OXSEARCHKEYS_1 AS OXSEARCHKEYS,OXTEMPLATE,OXQUESTIONEMAIL,OXISSEARCH,OXISCONFIGURABLE,OXVARNAME_1 AS OXVARNAME,OXVARSTOCK,OXVARCOUNT,OXVARSELECT_1 AS OXVARSELECT,OXVARMINPRICE,OXVARMAXPRICE,OXFOLDER,OXSUBCLASS,OXSORT,OXSOLDAMOUNT,OXNONMATERIAL,OXFREESHIPPING,OXREMINDACTIVE,OXREMINDAMOUNT,OXAMITEMID,OXAMTASKID,OXVENDORID,OXMANUFACTURERID,OXSKIPDISCOUNTS,OXORDERINFO,OXPIXIEXPORT,OXPIXIEXPORTED,OXVPE,OXRATING,OXRATINGCNT,OXMINDELTIME,OXMAXDELTIME,OXDELTIMEUNIT,OXUPDATEPRICE, OXUPDATEPRICEA, OXUPDATEPRICEB, OXUPDATEPRICEC, OXUPDATEPRICETIME, OXISDOWNLOADABLE, OXSHOWCUSTOMAGREEMENT FROM oxarticles INNER JOIN oxarticles2shop AS t2s ON t2s.OXMAPOBJECTID = oxarticles.OXMAPID WHERE t2s.OXSHOPID = {$iShopId}");

        $oConfig = $this->getConfig();
        $oConfig->setConfigParam('blMallSharedBasket', false);
        $oConfig->setShopId($iShopId);

        $oLoadedProduct = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
        $this->assertFalse($oLoadedProduct->load("_testArticle"));
    }

    /**
     * Test for bug #5321
     */
    public function testLoadArticleFromInActiveShopWhenSharedBasketEnabled()
    {
        $oProduct = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
        $oProduct->setId('_testArticle');
        $oProduct->oxarticles__oxprice = new \OxidEsales\Eshop\Core\Field(15.5);
        $oProduct->oxarticles__oxshopid = new \OxidEsales\Eshop\Core\Field(1);
        $oProduct->oxarticles__oxtitle = new \OxidEsales\Eshop\Core\Field("test");
        $oProduct->save();

        $oConfig = $this->getConfig();
        $oConfig->setConfigParam('blMallSharedBasket', true);
        $oConfig->setShopId(2);

        $oP = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
        $oP->load("_testArticle");

        $this->assertEquals(15.5, $oP->oxarticles__oxprice->value);
        $this->assertEquals('test', $oP->oxarticles__oxtitle->value);
    }

    public function testUnassignFromShop()
    {
        $oField2Shop = $this->getMock(\OxidEsales\Eshop\Application\Model\Field2Shop::class, array("cleanMultishopFields"));
        $oField2Shop->expects($this->once())->method("cleanMultishopFields")->with($this->equalTo(5));

        $oArticle = $this->getMock(\OxidEsales\Eshop\Application\Model\Article::class, array('_getField2Shop'));
        $oArticle->expects($this->once())->method("_getField2Shop")->will($this->returnValue($oField2Shop));
        $oArticle->unassignFromShop(5);
    }
    /**
     * Test field 2 shop usage.
     */
    public function testField2ShopUsage()
    {
        $iActShopId = (int) $this->getConfig()->getShopId();
        $iArtShopId = $iActShopId + 1;
        $sArtId = '_testArticleId';

        $oDb = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();
        $oDb->execute("insert into oxfield2shop (oxid, oxartid, oxshopid, oxprice) values ('_testRecord', '{$sArtId}', '{$iActShopId}', '999')");

        $oArticle = oxNew(\OxidEsales\Eshop\Core\Model\BaseModel::class);
        $oArticle->init("oxarticles");
        $oArticle->setId($sArtId);
        $oArticle->oxarticles__oxactive = new \OxidEsales\Eshop\Core\Field(1);
        $oArticle->oxarticles__oxshopid = new \OxidEsales\Eshop\Core\Field($iArtShopId);
        $oArticle->oxarticles__oxprice = new \OxidEsales\Eshop\Core\Field(666);
        $oArticle->save();

        // testing if base price if fine
        $oArticle = oxNew(\OxidEsales\Eshop\Core\Model\BaseModel::class);
        $oArticle->init("oxarticles");
        $oArticle->load($sArtId);
        $this->assertEquals(666, $oArticle->oxarticles__oxprice->value);

        // testing if price is takent from oxfield2shop table
        $oArticle = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
        $oArticle->load($sArtId);
        $this->assertEquals(999, $oArticle->oxarticles__oxprice->value);

        // testing if price values remained after setting it
        $oArticle->oxarticles__oxprice->setValue(333);
        $oArticle->save();
        $this->assertEquals(333, $oArticle->oxarticles__oxprice->value);

        // testing if price values remained after loadin
        $oArticle = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
        $oArticle->load($sArtId);
        $this->assertEquals(333, $oArticle->oxarticles__oxprice->value);

        // testing if main shop value was left untouched
        $oArticle = oxNew(\OxidEsales\Eshop\Core\Model\BaseModel::class);
        $oArticle->init("oxarticles");
        $oArticle->load($sArtId);
        $this->assertEquals(666, $oArticle->oxarticles__oxprice->value);
    }

    /**
     * Test allow derived update custom mall price when shop ids doesn't with match multishop article fields setup.
     */
    public function testAllowDerivedUpdateCustomMallPriceShopIdsDoesNtMatchMultishopArticleFieldssetup(): void
    {
        $this->getConfig()->setConfigParam('blMallCustomPrice', 1);
        $this->getConfig()->setConfigParam('aMultishopArticleFields', array(1));

        $oArticle = $this->getMock(\OxidEsales\Eshop\Application\Model\Article::class, array('isDerived'));
        $oArticle->expects($this->never())->method('isDerived');
        $oArticle->oxarticles__oxshopid = new \OxidEsales\Eshop\Core\Field('test');

        $this->assertTrue($oArticle->allowDerivedUpdate());
    }

    /**
     * Test allow derived update custom mall price off.
     */
    public function testAllowDerivedUpdateCustomMallPriceOff()
    {
        $this->getConfig()->setConfigParam('blMallCustomPrice', 0);

        $oArticle = $this->getMock(\OxidEsales\Eshop\Application\Model\Article::class, array('isDerived'));
        $oArticle->expects($this->once())->method('isDerived')->will($this->returnValue(true));
        $this->assertFalse($oArticle->allowDerivedUpdate());
    }

    /**
     * Test can update field derived update not allowed.
     */
    public function testCanUpdateFieldDerivedUpdateNotAllowed()
    {
        $oArticle = $this->getMock(\OxidEsales\Eshop\Application\Model\Article::class, array('isDerived', 'isAdmin', 'getRights'));
        $oArticle->expects($this->once())->method('isDerived')->will($this->returnValue(true));
        $oArticle->expects($this->any())->method('isAdmin')->will($this->returnValue(true));
        $oArticle->expects($this->never())->method('getRights');

        $this->assertFalse($oArticle->canUpdateField('xxx'));
    }

    /**
     * Test can update field derived update allowed.
     */
    public function testCanUpdateFieldDerivedUpdateAllowed()
    {
        $oRights = $this->getMock(\OxidEsales\Eshop\Core\AdminRights::class, array('hasRights'));
        $oRights->expects($this->once())->method('hasRights')->will($this->returnValue(true));

        $oArticle = $this->getMock(\OxidEsales\Eshop\Application\Model\Article::class, array('isDerived', 'isAdmin', 'getRights'));
        $oArticle->expects($this->once())->method('isDerived')->will($this->returnValue(false));
        $oArticle->expects($this->any())->method('isAdmin')->will($this->returnValue(true));
        $oArticle->expects($this->once())->method('getRights')->will($this->returnValue($oRights));

        $this->assertTrue($oArticle->canUpdateField('xxx'));
    }

    /**
     * Test canupdate field is derived but field inmultishop.
     */
    public function testcanUpdateFieldIsDerivedButFieldInMultishop()
    {
        $this->getConfig()->setConfigParam('aMultishopArticleFields', array('OXPRICE', 'OXTITLE'));

        $oArticle = $this->getMock(\OxidEsales\Eshop\Application\Model\Article::class, array('isDerived', 'isAdmin'), array(), '', false);
        $oArticle->expects($this->any())->method('isDerived')->will($this->returnValue(true));
        $oArticle->expects($this->any())->method('isAdmin')->will($this->returnValue(false));

        $this->assertTrue($oArticle->canUpdateField('oxprice'));
        $this->assertTrue($oArticle->canUpdateField('oxtitle'));
        $this->assertFalse($oArticle->canUpdateField('oxlongdesc'));
    }

    /**
     * Rest reset cache has similar more than limit.
     */
    public function testResetCacheHasSimilarMoreThanLimit()
    {
        $this->_createArticle('_testArt');

        oxAddClassModule('oxCacheHelper', 'oxcache');

        oxTestModules::addFunction("oxutils", "seoIsActive", "{return false;}");
        oxTestModules::addFunction("oxutils", "isSearchEngine", "{return true;}");

        $this->getConfig()->setConfigParam('blUseStock', true);
        $this->getConfig()->setConfigParam('sStockWarningLimit', 10);
        $this->getConfig()->setConfigParam('bl_perfLoadSimilar', true);
        $this->getConfig()->setConfigParam('blUseContentCaching', true);

        /** @var \OxidEsales\EshopEnterprise\Application\Model\Article|MockObject $oArticle */
        $oArticle = $this->getMock(\OxidEsales\Eshop\Application\Model\Article::class, array('isAdmin', 'getCategoryIds', '_assignAccessRights'));
        $oArticle->expects($this->any())->method('isAdmin')->will($this->returnValue(false));
        $oArticle->expects($this->never())->method('getCategoryIds');
        $oArticle->expects($this->once())->method('_assignAccessRights');

        $oArticle->load('_testArt');
        $oArticle->_iStockStatus = 0;
        $oArticle->_iMaxSimilarForCacheReset = -1;
        $oArticle->oxarticles__oxstock = new \OxidEsales\Eshop\Core\Field(5, \OxidEsales\Eshop\Core\Field::T_RAW);
        $oArticle->oxarticles__oxstockflag = new \OxidEsales\Eshop\Core\Field(1, \OxidEsales\Eshop\Core\Field::T_RAW);

        try {
            $oArticle->UNITresetCache();
        } catch (Exception $oEx) {
            $this->assertEquals($oEx->getCode(), 111, $oEx->getMessage());

            return;
        }
        $this->fail('error testing testResetCacheActionArticle');
    }

    /**
     * Testing action article cache reset.
     */
    public function testResetCacheActionArticle()
    {
        $this->_createArticle('_testArt');

        oxAddClassMOdule('oxCacheHelper', 'oxcache');

        oxTestModules::addFunction("oxutils", "seoIsActive", "{return false;}");
        oxTestModules::addFunction("oxutils", "isSearchEngine", "{return true;}");

        $this->getConfig()->setConfigParam('blUseStock', true);
        $this->getConfig()->setConfigParam('blUseContentCaching', true);

        // assigning test action
        $oAction = oxNew(\OxidEsales\Eshop\Core\Model\BaseModel::class);
        $oAction->init('oxactions2article');
        $oAction->oxactions2article__oxshopid = new \OxidEsales\Eshop\Core\Field($this->getConfig()->getBaseShopId(), \OxidEsales\Eshop\Core\Field::T_RAW);
        $oAction->oxactions2article__oxactionid = new \OxidEsales\Eshop\Core\Field('oxstart', \OxidEsales\Eshop\Core\Field::T_RAW);
        $oAction->oxactions2article__oxartid = new \OxidEsales\Eshop\Core\Field('_testArt', \OxidEsales\Eshop\Core\Field::T_RAW);
        $oAction->save();

        $aMethods = array('isAdmin', 'getCategoryIds', '_assignAccessRights');

        $oArticle = $this->getMock(\OxidEsales\Eshop\Application\Model\Article::class, $aMethods);
        $oArticle->expects($this->any())->method('isAdmin')->will($this->returnValue(false));
        $oArticle->expects($this->never())->method('getCategoryIds');
        $oArticle->expects($this->once())->method('_assignAccessRights');

        $oArticle->load('_testArt');

        try {
            $oArticle->UNITresetCache('_testArt');
        } catch (Exception $oEx) {
            $this->assertEquals($oEx->getCode(), 111);

            return;
        }
        $this->fail('error testing testResetCacheActionArticle');
    }

    /**
     * Test update ee
     */
    public function testUpdateEE()
    {
        $sArtID = "_testArt";
        $oArticle = $this->_createArticle($sArtID, false);

        $oArticle->oxarticles__oxshopid = new \OxidEsales\Eshop\Core\Field('2', \OxidEsales\Eshop\Core\Field::T_RAW);
        $this->assertTrue($oArticle->UNITupdate());

        $sSelect = "select oxartid from oxfield2shop where oxshopid = 1 and oxartid = '_testArt'";
        $sArtIDFromDB = \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->getOne($sSelect);
        $this->assertEquals($sArtID, $sArtIDFromDB);

        $oArticle->oxarticles__oxpriceb->setValue(98.76);
        $oArticle->oxarticles__oxtitle = new \OxidEsales\Eshop\Core\Field('test2');
        $this->assertTrue($oArticle->UNITupdate());

        $sSelect = "select oxtitle from oxarticles where oxid = '$sArtID'";
        $sTitle = \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->getOne($sSelect);
        $this->assertEquals('test', $sTitle);

        $sSelect = "select OXPRICEB from oxfield2shop where oxshopid = 1 and oxartid = '$sArtID'";
        $sPriceB = \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->getOne($sSelect);
        $this->assertEquals(98.76, $sPriceB);
    }

    /**
     * Test update not allowed.
     */
    public function testUpdateNotAllowed()
    {
        $oArticle = $this->getMock(\OxidEsales\Eshop\Application\Model\Article::class, array('canUpdate'));
        $oArticle->expects($this->any())->method('canUpdate')->will($this->returnValue(false));
        $oArticle->load("_testArt");
        $this->assertFalse($oArticle->UNITupdate());
    }

    /**
     * Test assign can't read field.
     */
    public function testAssignCantReadField()
    {
        $oArticle = $this->getMock(\OxidEsales\Eshop\Application\Model\Article::class, array('canReadField'));
        $oArticle->expects($this->any())->method('canReadField')->will($this->returnValue(false));
        $sArtID = '_testArt';
        $oArticle->load($sArtID);
        $dbRecord = array();
        $dbRecord['oxarticles__oxlongdesc'] = 'LongDesc';
        $dbRecord['oxarticles__oxtitle'] = 'test2';
        $oArticle->assign($dbRecord);
        $this->assertEquals('', $oArticle->oxarticles__oxlongdesc->value);
    }

    /**
     * Test assign not allowed.
     */
    public function testAssignNotAllowed()
    {
        $oArticle = $this->getMock(\OxidEsales\Eshop\Application\Model\Article::class, array('canRead'));
        $oArticle->expects($this->any())->method('canRead')->will($this->returnValue(false));
        $this->assertFalse($oArticle->assign(null));
    }

    /**
     * Test get sql active snippet for core table.
     */
    public function testGetSqlActiveSnippetForCoreTable()
    {
        $this->getConfig()->setConfigParam('blUseStock', false);

        $iCurrTime = 1453734000;
        oxTestModules::addFunction("oxUtilsDate", "getRequestTime", "{ return $iCurrTime; }");

        $sTable = 'oxarticles';
        $sDate = date('Y-m-d H:i:s', $iCurrTime);
        $sExpSelect = "(  (   $sTable.oxactive = 1  and $sTable.oxhidden = 0  or  ( $sTable.oxactivefrom < '$sDate' and $sTable.oxactiveto > '$sDate' ) )  ) ";
        $oArticle = $this->_createArticle('_testArt');
        $oArticle->setAdminMode(true);
        $sSelect = $oArticle->getSqlActiveSnippet(true);
        $this->assertEquals($sExpSelect, $sSelect);
    }

    /**
     * Test get sql active snippet rr.
     */
    public function testGetSqlActiveSnippetRR()
    {
        $iCurrTime = 1453734000;
        oxTestModules::addFunction("oxUtilsDate", "getRequestTime", "{ return $iCurrTime; }");

        $this->getConfig()->setConfigParam('blUseStock', false);
        $sTable = 'oxarticles';
        $sDate = date('Y-m-d H:i:s', $iCurrTime);
        $sExpSelect = "(  (   $sTable.oxactive = 1  and oxarticles.oxhidden = 0  or  ( $sTable.oxactivefrom < '$sDate' and $sTable.oxactiveto > '$sDate' ) ) ";
        $sExpSelect .= " and ( ( ( select oxobjectrights.oxobjectid from oxobjectrights where oxobjectrights.oxobjectid = $sTable.oxid ";
        $sExpSelect .= "and oxobjectrights.oxaction = 1 limit 1 ) is null ) or (( select oxobjectrights.oxobjectid from oxobjectrights ";
        $sExpSelect .= "where oxobjectrights.oxobjectid = $sTable.oxid and oxobjectrights.oxaction = 1 and  ";
        $sExpSelect .= "( oxobjectrights.oxgroupidx & 1 and oxobjectrights.oxoffset = 1 )  |  ( oxobjectrights.oxgroupidx & 4 ";
        $sExpSelect .= "and oxobjectrights.oxoffset = 2 )  limit 1 ) is not null  ) )  ) ";
        $oRights = $this->getMock(\OxidEsales\Eshop\Core\AdminRights::class, array('getUserGroupIndex'));
        $oRights->expects($this->any())->method('getUserGroupIndex')->will($this->returnValue(array(1 => 1, 2 => 4)));
        $oArticle = $this->getMock(\OxidEsales\Eshop\Application\Model\Article::class, array('getRights'));
        $oArticle->expects($this->any())->method('getRights')->will($this->returnValue($oRights));
        $sSelect = $oArticle->getSqlActiveSnippet(true);
        $this->assertEquals($sExpSelect, $sSelect);
    }

    /**
     * Test get article category when first assigned is denied by rights&roles.
     */
    public function testGetCategoryFirstAssignedIsDeniedByRR()
    {
        $sRRCatId = "30e44ab8593023055.23928895";
        $sCatId = "30e44ab83fdee7564.23264141";


        $iAction = 1;

        // adding
        $aGroups = array(1, 2, 3);

        $aIndexes = array();
        foreach ($aGroups as $iRRIdx) {
            $iOffset = ( int ) ($iRRIdx / 31);
            $iBitMap = 1 << ($iRRIdx % 31);

            // summing indexes
            if (!isset($aIndexes[$iOffset])) {
                $aIndexes[$iOffset] = $iBitMap;
            } else {
                $aIndexes[$iOffset] = $aIndexes [$iOffset] | $iBitMap;
            }
        }

        // iterating through indexes and applying to (sub)categories R&R
        foreach ($aIndexes as $iOffset => $sIdx) {
            // processing category
            $sRRId = Registry::getUtilsObject()->generateUID();
            $sQ = "insert into oxobjectrights (oxid, oxobjectid, oxgroupidx, oxoffset, oxaction) ";
            $sQ .= "values ('_" . $sRRId . "', '$sRRCatId', $sIdx, $iOffset,  $iAction ) on duplicate key update oxgroupidx = (oxgroupidx | $sIdx ) ";
            \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->Execute($sQ);
        }

        $oArticle = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
        $oArticle->setAdminMode(false);
        $oArticle->load('1127');
        $oCategory = $oArticle->getCategory();

        $this->assertNotNull($oCategory);
        $this->assertNotEquals($sRRCatId, $oCategory->getId());
        $this->assertEquals($sCatId, $oCategory->getId());
    }

    /**
     * Test amount price loading interchange articles.
     */
    public function testAmountPricesLoadingInterchangeArticles()
    {
        $oArticle = $this->_createArticle('_testArt');

        $this->getConfig()->setConfigParam('blMallInterchangeArticles', true);
        $sSql = "insert into oxprice2article (oxid, oxartid, oxshopid, oxaddabs, oxamount, oxamountto)";
        $sSql .= " values ('test1', '_testArt', '2', 5.5, 10, 99999 )";
        \OxidEsales\Eshop\Core\DatabaseProvider::getDB()->execute($sSql);
        $sSql = "insert into oxprice2article (oxid, oxartid, oxshopid, oxaddabs, oxamount, oxamountto)";
        $sSql .= " values ('test2', '_testArt', '2', 4.5, 5, 10 )";
        \OxidEsales\Eshop\Core\DatabaseProvider::getDB()->execute($sSql);

        //calling getBasePrice() because can't test protected functions with passed by reference arguments
        $dBasePrice = $oArticle->getBasePrice(12);
        $this->assertEquals(5.5, $dBasePrice);
    }

    /**
     * Test amount price loading interchange articles.
     */
    public function testAmountPricesLoadingInterchangeArticlesMallAddition()
    {
        $oArticle = $this->_createArticle('_testArt');

        $this->getConfig()->setConfigParam('iMallPriceAddition', 17.5);
        $this->getConfig()->setConfigParam('blMallPriceAdditionPercent', false);
        $this->getConfig()->setConfigParam('blMallInterchangeArticles', true);
        $sSql = "insert into oxprice2article (oxid, oxartid, oxshopid, oxaddabs, oxamount, oxamountto)";
        $sSql .= " values ('test1', '_testArt', '2', 5.5, 10, 99999 )";
        \OxidEsales\Eshop\Core\DatabaseProvider::getDB()->execute($sSql);
        $sSql = "insert into oxprice2article (oxid, oxartid, oxshopid, oxaddabs, oxamount, oxamountto)";
        $sSql .= " values ('test2', '_testArt', '2', 4.5, 5, 10 )";
        \OxidEsales\Eshop\Core\DatabaseProvider::getDB()->execute($sSql);

        //calling getBasePrice() because can't test protected functions with passed by reference arguments
        $dBasePrice = $oArticle->getBasePrice(12);
        $this->assertEquals(23.00, $dBasePrice);

        $aAmountPrices = $oArticle->loadAmountPriceInfo();

        $this->assertEquals('23,00', $aAmountPrices['test1']->fbrutprice);
    }

    /**
     * Test amount price loading with mall addition.
     */
    public function testAmountPricesLoadingMallAddition()
    {
        $oArticle = $this->_createArticle('_testArt');

        $sShopId = $this->getConfig()->getShopId();
        $this->getConfig()->setConfigParam('iMallPriceAddition', 17.5);
        $this->getConfig()->setConfigParam('blMallPriceAdditionPercent', false);
        $sSql = "insert into oxprice2article (oxid, oxartid, oxshopid, oxaddabs, oxamount, oxamountto)";
        $sSql .= " values ('test1', '_testArt', '$sShopId', 5.5, 10, 99999 )";
        \OxidEsales\Eshop\Core\DatabaseProvider::getDB()->execute($sSql);
        $sSql = "insert into oxprice2article (oxid, oxartid, oxshopid, oxaddabs, oxamount, oxamountto)";
        $sSql .= " values ('test2', '_testArt', '$sShopId', 4.5, 5, 10 )";
        \OxidEsales\Eshop\Core\DatabaseProvider::getDB()->execute($sSql);

        //calling getBasePrice() because can't test protected functions with passed by reference arguments
        $dBasePrice = $oArticle->getBasePrice(12);
        $this->assertEquals(23.00, $dBasePrice);

        $aAmountPrices = $oArticle->loadAmountPriceInfo();
        $this->assertEquals('23,00', $aAmountPrices['test1']->fbrutprice);
    }

    /**
     * Test amount price loading for variants.
     */
    public function testAmountPricesLoadingForVariantsMallAddition()
    {
        $this->_createArticle('_testArt');
        $oVariant = $this->_createVariant('_testVar', '_testArt');

        $this->getConfig()->setConfigParam('iMallPriceAddition', 17.5);
        $this->getConfig()->setConfigParam('blMallPriceAdditionPercent', false);
        $this->getConfig()->setConfigParam('blVariantInheritAmountPrice', true);
        $sShopId = $this->getConfig()->getShopId();
        $sSql = "insert into oxprice2article (oxid, oxartid, oxshopid, oxaddperc, oxamount, oxamountto)";
        $sSql .= " values ('test1', '_testArt', '" . $sShopId . "', 10, 11, 99999999 )";
        \OxidEsales\Eshop\Core\DatabaseProvider::getDB()->execute($sSql);
        $sSql = "insert into oxprice2article (oxid, oxartid, oxshopid, oxaddperc, oxamount, oxamountto)";
        $sSql .= " values ('test2', '_testArt', '" . $sShopId . "', 9, 5, 10 )";
        \OxidEsales\Eshop\Core\DatabaseProvider::getDB()->execute($sSql);

        //calling getBasePrice() because can't test protected functions with passed by reference arguments
        $oVariant->setAdminMode(null);
        $oVariant->load('_testVar');

        $dBasePrice = $oVariant->getBasePrice(12);
        $this->assertEquals(28.48, $dBasePrice);

        $aAmountPrices = $oVariant->loadAmountPriceInfo();
        $this->assertEquals('28,48', $aAmountPrices['test1']->fbrutprice);
    }

    /**
     * Test save custom price.
     */
    public function testSaveCustomPrice()
    {
        $oArticle = $this->_createArticle('_testArt');
        $this->getConfig()->setConfigParam('blMallCustomPrice', true);
        $oArticle->oxarticles__oxshopid = new \OxidEsales\Eshop\Core\Field(2, \OxidEsales\Eshop\Core\Field::T_RAW);
        $oArticle->oxarticles__oxprice = new \OxidEsales\Eshop\Core\Field(25, \OxidEsales\Eshop\Core\Field::T_RAW);
        $oArticle->save();
        $this->assertEquals(25, \OxidEsales\Eshop\Core\DatabaseProvider::getDB()->getOne("select oxprice from oxfield2shop where oxartid = '_testArt' "));
    }

    /**
     * Test check for vpe (packing units) .
     */
    public function testCheckForVpe()
    {
        $oArticle = $this->_createArticle('_testArt');
        $oArticle->oxarticles__oxvpe = new \OxidEsales\Eshop\Core\Field(2, \OxidEsales\Eshop\Core\Field::T_RAW);
        $oArticle->save();
        $this->assertEquals(4, $oArticle->checkForVpe(3));
    }

    /**
     * Test check for vpe (packing units) when not set .
     */
    public function testCheckForVpeNotSet()
    {
        $this->assertEquals(3, $this->_createArticle('_testArt')->checkForVpe(3));
    }

    /**
     * Test set shop values
     */
    public function testSetShopValues()
    {
        $oArticle = $this->_createArticle('_testArt');
        \OxidEsales\Eshop\Core\DatabaseProvider::getDB()->execute("insert into oxfield2shop (oxartid, oxprice, oxshopid) values ('_testArt', 25, 1 )");
        $oArticle->load('_testArt');
        $oArticle->oxarticles__oxshopid = new \OxidEsales\Eshop\Core\Field(2, \OxidEsales\Eshop\Core\Field::T_RAW);
        $oArticle->oxarticles__oxprice = new \OxidEsales\Eshop\Core\Field(20, \OxidEsales\Eshop\Core\Field::T_RAW);
        $oArticle->UNITsetShopValues($oArticle);
        $this->assertEquals(25, $oArticle->oxarticles__oxprice->value);
    }

    /**
     * Test get category id's - adding price categories to list.
     */
    public function testGetCategoryIds_adsPriceCategoriesToList()
    {
        $oCategory = $this->getMock(\OxidEsales\Eshop\Application\Model\Category::class, array('canView'));
        $oCategory->expects($this->any())->method('canView')->will($this->returnValue(true));
        oxTestModules::addModuleObject("oxCategory", $oCategory);

        $oObj1 = oxNew(\OxidEsales\Eshop\Application\Model\Category::class);
        $oObj1->setId("_testCat1");
        $oObj1->oxcategories__oxparentid = new \OxidEsales\Eshop\Core\Field("oxrootid", \OxidEsales\Eshop\Core\Field::T_RAW);
        $oObj1->oxcategories__oxactive = new \OxidEsales\Eshop\Core\Field("1", \OxidEsales\Eshop\Core\Field::T_RAW);
        $oObj1->save();

        $oObj2 = oxNew(\OxidEsales\Eshop\Application\Model\Category::class);
        $oObj2->setId("_testCat2");
        $oObj2->oxcategories__oxparentid = new \OxidEsales\Eshop\Core\Field("oxrootid", \OxidEsales\Eshop\Core\Field::T_RAW);
        $oObj2->oxcategories__oxactive = new \OxidEsales\Eshop\Core\Field("1", \OxidEsales\Eshop\Core\Field::T_RAW);
        $oObj2->oxcategories__oxpricefrom = new \OxidEsales\Eshop\Core\Field(100);
        $oObj2->oxcategories__oxpriceto = new \OxidEsales\Eshop\Core\Field(200);
        $oObj2->save();

        $sQ = "insert into oxobject2category set oxid = '_testArt1Cat', oxcatnid = '_testCat1', oxobjectid = '_testArt'";
        $this->addToDatabase($sQ, 'oxobject2category');
        $this->addTableForCleanup('oxcategories');

        $oArticle = $this->_createArticle('_testArt');
        $oArticle->oxarticles__oxprice = new \OxidEsales\Eshop\Core\Field(99);

        // price cat should be skipped
        $this->assertEquals(array("_testCat1"), $oArticle->getCategoryIds(false, true));

        // price cat should be inlcuded (M:1598)
        $oArticle->oxarticles__oxprice = new \OxidEsales\Eshop\Core\Field(101);
        $this->assertEquals(array("_testCat1", "_testCat2"), $oArticle->getCategoryIds(false, true));
    }
}
