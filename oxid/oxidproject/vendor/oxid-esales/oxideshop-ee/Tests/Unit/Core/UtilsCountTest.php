<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Tests\Unit\Core;

use OxidEsales\EshopEnterprise\Core\UtilsCount;
use OxidEsales\Eshop\Core\Registry;
use \oxField;
use \oxArticle;
use \oxCategory;
use \oxDb;

class UtilsCountTest extends \oxUnitTestCase
{
    /**
     * Initialize the fixture.
     *
     * @return null
     */
    protected function setUp(): void
    {
        parent::setUp();
        $oPriceCat = new \OxidEsales\Eshop\Application\Model\Category();
        $oPriceCat->oxcategories__oxactive = new \OxidEsales\Eshop\Core\Field(1, \OxidEsales\Eshop\Core\Field::T_RAW);
        $oPriceCat->oxcategories__oxparentid = new \OxidEsales\Eshop\Core\Field("oxrootid", \OxidEsales\Eshop\Core\Field::T_RAW);
        $oPriceCat->oxcategories__oxshopid = new \OxidEsales\Eshop\Core\Field($this->getConfig()->getBaseShopId(), \OxidEsales\Eshop\Core\Field::T_RAW);
        $oPriceCat->oxcategories__oxtitle = new \OxidEsales\Eshop\Core\Field("Price Cat 1", \OxidEsales\Eshop\Core\Field::T_RAW);
        $oPriceCat->oxcategories__oxpricefrom = new \OxidEsales\Eshop\Core\Field(1, \OxidEsales\Eshop\Core\Field::T_RAW);
        $oPriceCat->oxcategories__oxpriceto = new \OxidEsales\Eshop\Core\Field(100, \OxidEsales\Eshop\Core\Field::T_RAW);
        $oPriceCat->save();

        $this->aCats[$oPriceCat->getId()] = $oPriceCat;

        $oPriceCat = new \OxidEsales\Eshop\Application\Model\Category();
        $oPriceCat = new \OxidEsales\Eshop\Application\Model\Category();
        $oPriceCat->oxcategories__oxactive = new \OxidEsales\Eshop\Core\Field(1, \OxidEsales\Eshop\Core\Field::T_RAW);
        $oPriceCat->oxcategories__oxparentid = new \OxidEsales\Eshop\Core\Field("oxrootid", \OxidEsales\Eshop\Core\Field::T_RAW);
        $oPriceCat->oxcategories__oxshopid = new \OxidEsales\Eshop\Core\Field($this->getConfig()->getBaseShopId(), \OxidEsales\Eshop\Core\Field::T_RAW);
        $oPriceCat->oxcategories__oxtitle = new \OxidEsales\Eshop\Core\Field("Price Cat 2", \OxidEsales\Eshop\Core\Field::T_RAW);
        $oPriceCat->oxcategories__oxpricefrom = new \OxidEsales\Eshop\Core\Field(1, \OxidEsales\Eshop\Core\Field::T_RAW);
        $oPriceCat->oxcategories__oxpriceto = new \OxidEsales\Eshop\Core\Field(100, \OxidEsales\Eshop\Core\Field::T_RAW);
        $oPriceCat->save();

        $this->aCats[$oPriceCat->getId()] = $oPriceCat;

        $this->getConfig()->setGlobalParameter('aLocalVendorCache', null);
        Registry::getUtils()->toFileCache('aLocalVendorCache', '');
        Registry::getUtils()->toFileCache('aLocalCatCache', '');

        $oCache = oxNew(\OxidEsales\Eshop\Core\Cache\DynamicContent\ContentCache::class);
        $oCache->reset();
    }

    protected function tearDown(): void
    {
        foreach ($this->aCats as $oCat) {
            $oCat->delete();
        }

        $this->getConfig()->setGlobalParameter('aLocalVendorCache', null);
        Registry::getUtils()->toFileCache('aLocalVendorCache', '');
        Registry::getUtils()->toFileCache('aLocalCatCache', '');

        Registry::getUtils()->oxResetFileCache();

        $oCache = oxNew(\OxidEsales\Eshop\Core\Cache\DynamicContent\ContentCache::class);
        $oCache->reset();

        // deleting test articles
        $oArticle = new \OxidEsales\Eshop\Application\Model\Article;
        $oArticle->delete('testarticle1');
        $oArticle->delete('testarticle2');
        $oArticle->delete('_testArticle');

        $this->cleanUpTable('oxarticles');

        parent::tearDown();
    }


    public function testSetPriceCatArticleCountWhenPriceFrom0To1AndDbContainsProductWhichPriceIs0()
    {
        $oArticle = new \OxidEsales\Eshop\Application\Model\Article();
        $oArticle->setId("_testArticle");
        $oArticle->oxarticles__oxshopid = new \OxidEsales\Eshop\Core\Field($this->getConfig()->getBaseShopId());
        $oArticle->oxarticles__oxactive = new \OxidEsales\Eshop\Core\Field(1);
        $oArticle->oxarticles__oxvarminprice = new \OxidEsales\Eshop\Core\Field(0);
        $oArticle->save();

        $oUtilsCount = new UtilsCount();

        $this->assertEquals(1, $oUtilsCount->setPriceCatArticleCount(array(), 'xxx', 'xxx', 0, 1));
    }

    public function testGetCatArticleCount()
    {
        $this->assertEquals('0', Registry::getUtilsCount()->GetCatArticleCount('', true));
        $this->assertEquals('1', Registry::getUtilsCount()->GetCatArticleCount('30e44ab8338d7bf06.79655612', true));

        Registry::getUtils()->oxResetFileCache();
        $this->assertEquals('0', Registry::getUtilsCount()->GetCatArticleCount('', true));
        $this->assertEquals('1', Registry::getUtilsCount()->GetCatArticleCount('30e44ab8338d7bf06.79655612', true));
    }

    public function testGetVendorArticleCount()
    {
        $myUtilsTest = new UtilsCount();

        $aCache = $myUtilsTest->UNITgetVendorCache();

        $sRet = Registry::getUtilsCount()->setVendorArticleCount($aCache, 'd2e44d9b32fd2c224.65443178', $myUtilsTest->UNITgetUserViewId(), true);
        $sCount = Registry::getUtilsCount()->getVendorArticleCount('d2e44d9b32fd2c224.65443178', true, true);

        $this->assertEquals($sRet, $sCount);
        $this->assertTrue($sRet > 0);
    }

    public function testGetManufacturerArticleCount()
    {
        $myUtilsTest = new UtilsCount();

        $aCache = $myUtilsTest->UNITgetManufacturerCache();

        $sRet = Registry::getUtilsCount()->setManufacturerArticleCount($aCache, '2536d76675ebe5cb777411914a2fc8fb', $myUtilsTest->UNITgetUserViewId(), true);
        $sCount = Registry::getUtilsCount()->getManufacturerArticleCount('2536d76675ebe5cb777411914a2fc8fb', true, true);

        $this->assertEquals($sRet, $sCount);
        $this->assertTrue($sRet > 0);
    }

    public function testSetCatArticleCount()
    {
        $myUtilsTest = new UtilsCount();
        $sRetSet = Registry::getUtilsCount()->setCatArticleCount(array(), '30e44ab8338d7bf06.79655612', $myUtilsTest->UNITgetUserViewId(), true);
        $sRetGet = Registry::getUtilsCount()->getCatArticleCount('30e44ab8338d7bf06.79655612', true);

        $this->assertEquals($sRetSet, $sRetGet);
        $this->assertEquals($sRetSet, 1);
    }

    public function testSetPriceCatArticleCount()
    {
        $myUtilsTest = new UtilsCount();

        $sRetSet = Registry::getUtilsCount()->setPriceCatArticleCount(array(), '30e44ab8338d7bf06.79655612', $myUtilsTest->UNITgetUserViewId(), 10, 100);
        $sRetGet = Registry::getUtilsCount()->getPriceCatArticleCount('30e44ab8338d7bf06.79655612', 10, 100, true);
        $this->assertEquals($sRetSet, $sRetGet);
        $this->assertEquals(48, $sRetSet);
    }

    public function testSetVendorArticleCount()
    {
        $myUtilsTest = new UtilsCount();
        $aCache = null;
        $sCatId = 'root';
        $sActIdent = null;

        // always return 0 if $sCatId ='root'
        $this->assertEquals(Registry::getUtilsCount()->setVendorArticleCount($aCache, $sCatId, $sActIdent, true), 0);
        Registry::getUtils()->oxResetFileCache();

        $aCache = $myUtilsTest->UNITgetVendorCache();
        $sVendorID = 'd2e44d9b31fcce448.08890330'; //Hersteller 1 from Demodata
        $sCatId = $sVendorID;
        $sActIdent = $myUtilsTest->UNITgetUserViewId();
        //echo "\n->".setVendorArticleCount($aCache, $sCatId, $sActIdent)."<-";
        $this->assertEquals(Registry::getUtilsCount()->setVendorArticleCount($aCache, $sCatId, $sActIdent, true), 14);
    }

    public function testSetManufacturerArticleCount()
    {
        $myUtilsTest = new UtilsCount();
        $aCache = null;
        $sCatId = 'root';
        $sActIdent = null;

        // always return 0 if $sCatId ='root'
        $this->assertEquals(Registry::getUtilsCount()->setManufacturerArticleCount($aCache, $sCatId, $sActIdent, true), 0);
        Registry::getUtils()->oxResetFileCache();

        $aCache = $myUtilsTest->UNITgetManufacturerCache();
        $sManufacturerID = '88a996f859f94176da943f38ee067984'; //Hersteller 1 from Demodata
        $sCatId = $sManufacturerID;
        $sActIdent = $myUtilsTest->UNITgetUserViewId();
        //echo "\n->".setManufacturerArticleCount($aCache, $sCatId, $sActIdent)."<-";
        $this->assertEquals(Registry::getUtilsCount()->setManufacturerArticleCount($aCache, $sCatId, $sActIdent, true), 14);
    }

    public function testZeroArtManufaturerCache()
    {
        $myUtilsTest = $this->getMock('\OxidEsales\EshopEnterprise\Core\UtilsCount', array('_setManufacturerCache'));
        $myUtilsTest->expects($this->once())->method('_setManufacturerCache')->with(
            $this->equalTo(
                array(
                    '_testManufacturerId' =>
                        array(
                            '2fb5911b89dddda329c256f56d1f60c5' => '0',
                        ),
                )
            )
        );

        Registry::getUtils()->oxResetFileCache();
        $oDb = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();
        $oDb->execute('replace INTO `oxmanufacturers` (`OXID`, `OXSHOPID`) VALUES ("_testManufacturerId", 1);');

        $sActIdent = $myUtilsTest->UNITgetUserViewId();
        $iCount = $myUtilsTest->setManufacturerArticleCount(array(), '_testManufacturerId', $sActIdent);

        $this->assertSame(0, $iCount);
    }
}
