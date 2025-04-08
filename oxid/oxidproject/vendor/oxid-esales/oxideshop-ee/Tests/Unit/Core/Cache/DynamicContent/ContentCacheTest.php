<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */
namespace OxidEsales\EshopEnterprise\Tests\Unit\Core\Cache\DynamicContent;

use OxidEsales\EshopEnterprise\Application\Model\Contract\CacheBackendInterface;
use OxidEsales\EshopEnterprise\Core\Cache\DynamicContent\ContentCache;
use OxidEsales\EshopEnterprise\Core\Cache\DynamicContent\Connector\DefaultCacheConnector;
use OxidEsales\EshopCommunity\Internal\Container\ContainerFactory;
use \oxDb;
use \oxUBase;
use OxidEsales\Eshop\Core\Registry;
use \oxViewConfig;
use \oxSystemComponentException;

use \PHPUnit\Framework\MockObject\MockObject;
use \oxTestModules;

/**
 * Class for invalid backend imitation.
 * @package OxidEsales\Tests\Unit\Enterprise\Core\Cache\DynamicContent
 */
class SimpleNotValidBackend
{
}

/**
 * Class for valid backend imitation.
 * @package OxidEsales\Tests\Unit\Enterprise\Core\Cache\DynamicContent
 */
class SimpleValidBackend extends DefaultCacheConnector implements CacheBackendInterface
{
    public static $valid = false;

    public static function isAvailable()
    {
        return self::$valid;
    }
}

/**
 * @package OxidEsales\Tests\Unit\Enterprise\Core\Cache\DynamicContent
 */
class ContentCacheTest extends \oxUnitTestCase
{
    private $_sOrigTheme;

    /**
     * Initialize the fixture.
     *
     * @return null
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->_sOrigTheme = $this->getConfig()->getConfigParam('sTheme');
        $this->getConfig()->setConfigParam('sTheme', 'azure');
    }

    /**
     * Tear down the fixture.
     *
     * @return null
     */
    protected function tearDown(): void
    {
        $this->getConfig()->setConfigParam('sTheme', $this->_sOrigTheme);

        // cleaning up cache table
        \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute('delete from oxcache');

        // removing cache files
        $sFilePath = $this->getConfig()->getConfigParam('sCompileDir') . "/_*.cache";
        $aPathes = glob($sFilePath);
        if (is_array($aPathes)) {
            foreach ($aPathes as $sFilename) {
                // delete all the files
                @unlink($sFilename);
            }
        }
        $this->getSession()->setVariable('sess_stoken', null);
        parent::tearDown();
    }

    /**
     * Testing default setters/getters
     */
    public function testSetCacheLifetimeAndGetCacheLifeTime()
    {
        $oCache = oxNew(\OxidEsales\Eshop\Core\Cache\DynamicContent\ContentCache::class);
        $oCache->setCacheLifetime(111);
        $this->assertEquals(111, $oCache->getCacheLifeTime());
    }

    public function testSetCachableClasses()
    {
        $oCache = $this->getProxyClass('oxCache');
        $oCache->setCachableClasses(array(111));
        $this->assertEquals(array(111), $oCache->getNonPublicVar('_aCachableClasses'));
    }

    /**
     * Testing if is cachable checker
     */
    public function testIsViewCacheable()
    {
        $oCache = $this->getProxyClass('oxcache');
        $oCache->setCachableClasses(array('details'));
        $this->assertFalse($oCache->isViewCacheable('xxx'));
        $this->assertTrue($oCache->isViewCacheable('details'));
    }

    /**
     * Testing cache setter and getter
     */
    public function testPutAndGet()
    {
        $oBackend = oxNew(\OxidEsales\Eshop\Core\Cache\DynamicContent\Connector\DefaultCacheConnector::class);
        $oBackend->cacheSetTTL(10);
        /** @var ContentCache|MockObject $oCache */
        $oCache = $this->getMock(\OxidEsales\Eshop\Core\Cache\DynamicContent\ContentCache::class, array('_getBackend', '_cleanSensitiveData'));
        $oCache->expects($this->any())->method('_getBackend')->will($this->returnValue($oBackend));
        $oCache->expects($this->once())->method('_cleanSensitiveData')
            ->with($this->equalTo('yyy'))
            ->will($this->returnValue('yyy_clean'));

        $oCache->put('xxx', 'yyy', 'zzz');
        $this->assertEquals('yyy_clean', $oCache->get('xxx'));
    }

    // simple test if put calls all needed func.
    public function testPut()
    {
        $oBackend = oxNew(\OxidEsales\Eshop\Core\Cache\DynamicContent\Connector\DefaultCacheConnector::class);
        $oBackend->cacheSetTTL(10);

        /** @var ContentCache|MockObject $oCache */
        $oCache = $this->getMock(\OxidEsales\Eshop\Core\Cache\DynamicContent\ContentCache::class, array('_addId', '_getBackend'));
        $oCache->expects($this->once())->method('_addId')->with($this->equalTo('xxx'), $this->equalTo(strlen('yyy')), $this->equalTo('zzz'));
        $oCache->expects($this->once())->method('_getBackend')->will($this->returnValue($oBackend));
        $oCache->put('xxx', 'yyy', 'zzz');
    }

    // simple testing if get calls all needed func.
    public function testGet()
    {
        $oBackend = oxNew(\OxidEsales\Eshop\Core\Cache\DynamicContent\Connector\DefaultCacheConnector::class);
        $oBackend->cacheSetTTL(10);

        /** @var ContentCache|MockObject $oCache */
        $oCache = $this->getMock(\OxidEsales\Eshop\Core\Cache\DynamicContent\ContentCache::class, array('_loadInfo', '_addHit', '_getBackend'));
        $oCache->expects($this->once())->method('_addHit')->with($this->equalTo('xxx'));
        $oCache->expects($this->any())->method('_getBackend')->will($this->returnValue($oBackend));

        $oCache->put('xxx', 'yyy', 'zzz');
        $this->assertEquals('yyy', $oCache->get('xxx'));
        $this->assertFalse($oCache->get('yyy'));
    }

    /**
     * Testing cache oxid getter
     */
    public function testGetCacheId()
    {
        $oCache = oxNew(\OxidEsales\Eshop\Core\Cache\DynamicContent\ContentCache::class);
        $oCache->put('xxx', 'yyy', 'zzz');

        // non expired
        $this->assertEquals(md5('xxx'), $oCache->getCacheId('xxx'));

        // simulating expired
        \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->Execute('update oxcache set oxexpire = ' . time() . ' where oxid = "' . md5('xxx') . '"');

        $this->assertFalse($oCache->getCacheId('xxx'));
    }

    /**
     * testing cache removal
     */
    public function testRemove()
    {
        $oBackend = oxNew(\OxidEsales\Eshop\Core\Cache\DynamicContent\Connector\DefaultCacheConnector::class);
        $oBackend->cacheSetTTL(10);
        /** @var ContentCache|MockObject $oCache */
        $oCache = $this->getMock(\OxidEsales\Eshop\Core\Cache\DynamicContent\ContentCache::class, array('_getBackend'));
        $oCache->expects($this->any())->method('_getBackend')->will($this->returnValue($oBackend));

        $oCache->put('xxx', 'yyy', 'zzz');
        $this->assertEquals('yyy', $oCache->get('xxx'));

        // no checking if removal works..
        $oCache->remove('xxx');
        $this->assertFalse($oCache->get('xxx'));
    }

    // checking if all neede func. is executed
    public function testRemoveFunc()
    {
        /** @var ContentCache|MockObject $oCache */
        $oCache = $this->getMock(\OxidEsales\Eshop\Core\Cache\DynamicContent\ContentCache::class, array('_removeId'));
        $oCache->expects($this->once())->method('_removeId')->with($this->equalTo('xxx'));

        // testing ..
        $oCache->remove('xxx');
    }

    /**
     * Testing how objects are cached and restored from cache
     */
    public function testPutObject()
    {
        $oObject = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);

        $oBackend = oxNew(\OxidEsales\Eshop\Core\Cache\DynamicContent\Connector\DefaultCacheConnector::class);
        $oBackend->cacheSetTTL(10);

        /** @var ContentCache|MockObject $oCache */
        $oCache = $this->getMock(\OxidEsales\Eshop\Core\Cache\DynamicContent\ContentCache::class, array('put', '_getBackend'));
        $oCache->expects($this->once())->method('put')->with($this->equalTo('xxx'), $this->equalTo(serialize($oObject)), 'zzz');
        $oCache->expects($this->any())->method('_getBackend')->will($this->returnValue($oBackend));

        $oCache->putObject('xxx', $oObject, 'zzz');
    }

    public function testPutObjectAndGetObject()
    {
        $oObject = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);

        $oBackend = oxNew(\OxidEsales\Eshop\Core\Cache\DynamicContent\Connector\DefaultCacheConnector::class);
        $oBackend->cacheSetTTL(10);
        /** @var ContentCache|MockObject $oCache */
        $oCache = $this->getMock(\OxidEsales\Eshop\Core\Cache\DynamicContent\ContentCache::class, array('_getBackend'));
        $oCache->expects($this->any())->method('_getBackend')->will($this->returnValue($oBackend));

        $oCache->putObject('xxx', $oObject, 'zzz');

        $this->assertEquals($oObject, $oCache->getObject('xxx'));
    }

    public function testGetObjectNonExisting()
    {
        $oCache = oxNew(\OxidEsales\Eshop\Core\Cache\DynamicContent\ContentCache::class);
        $this->assertFalse($oCache->getObject('xxx'));
    }

    public function testGetObject()
    {
        $oObject = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);

        /** @var ContentCache|MockObject $oCache */
        $oCache = $this->getMock(\OxidEsales\Eshop\Core\Cache\DynamicContent\ContentCache::class, array('get'));
        $oCache->expects($this->once())->method('get')->with($this->equalTo('xxx'))->will($this->returnValue(serialize($oObject)));

        $this->assertEquals($oObject, $oCache->getObject('xxx'));
    }

    /**
     * Testing how full cache cleanup works
     */
    public function testReset()
    {
        $this->getConfig()->setConfigParam('blUseContentCaching', true);

        $filePath = $this->getConfig()->getConfigParam('sCompileDir') . "/oxcachtetest.txt";
        file_put_contents($filePath, 'cache reset test');
        $this->assertTrue(file_exists($filePath));

        // adding some trash into cache table
        $sShopId = $this->getConfig()->getBaseShopId();
        $oDb = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();
        for ($i = 0; $i < 10; $i++) {
            $oDb->Execute("insert into oxcache (`OXID`, `OXSHOPID`, `OXEXPIRE`, `OXRESETON`, `OXSIZE`, `OXHITS`) values ( '$i', '$sShopId', '0', '', '0', '0' )");
        }

        $oCache = oxNew(\OxidEsales\Eshop\Core\Cache\DynamicContent\ContentCache::class);

        // checking
        $oCache->reset();
        $this->assertEquals(0, $oDb->getOne('select count(*) from oxcache'));

        $sFilePath = $this->getConfig()->getConfigParam('sCompileDir') . "/oxcachtetest*.txt";
        $aPathes = glob($sFilePath);
        if (is_array($aPathes)) {
            foreach ($aPathes as $sFilename) {
                // delete all the files
                $this->fail('error while resetting cache');
            }
        }
    }

    /**
     * Testing only content cache cleanup
     */
    public function testResetOnlyCC()
    {
        $myConfig = $this->getConfig();
        $myConfig->setConfigParam('blUseContentCaching', true);

        // adding some trash into cache table
        $sShopId = $myConfig->getBaseShopId();
        $oDb = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();
        for ($i = 0; $i < 10; $i++) {
            $oDb->execute("insert into oxcache (`OXID`, `OXSHOPID`, `OXEXPIRE`, `OXRESETON`, `OXSIZE`, `OXHITS`)  values ( '$i', '$sShopId', '0', '', '0', '0' )");
        }

        $oUtils = Registry::getUtils();
        $aFiles = array('langcache_1_a', 'langcache_1_b', 'langcache_1_c');
        foreach ($aFiles as $sFile) {
            $oUtils->setLangCache($sFile, array($sFile));
        }

        $sFilePath = $this->getConfig()->getConfigParam('sCompileDir') . "/*.txt";
        $oCache = oxNew(\OxidEsales\Eshop\Core\Cache\DynamicContent\ContentCache::class);

        $aTmpBefore = glob($sFilePath);

        // checking
        $oCache->reset(false);
        $this->assertEquals(0, $oDb->getOne('select count(*) from oxcache'));

        $aTmpAfter = glob($sFilePath);

        $this->assertSame($aTmpAfter, $aTmpBefore, "Temp dir must stay untouched");

    }

    /**
     * Testing content cache cleanup in case of enabled content cache (blUseContentCaching = true).
     */
    public function testResetWithCCEnabled()
    {
        $myConfig = $this->getConfig();
        $myConfig->setConfigParam('blUseContentCaching', true);

        // adding some trash into cache table plus related files
        $shopId = $myConfig->getBaseShopId();
        $database = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();
        $cacheFiles = array();
        for ($i = 0; $i < 10; $i++) {
            $uid = substr_replace(Registry::getUtilsObject()->generateUID(), '_', 0,1);
            $query = "insert into oxcache (`OXID`, `OXSHOPID`, `OXEXPIRE`, `OXRESETON`, `OXSIZE`, `OXHITS`)  values " .
                     "( '$uid', '$shopId', '0', '', '0', '0' )";
            $database->execute($query);

            $filePath = $this->getConfig()->getConfigParam('sCompileDir') . "/{$uid}_oxcachtetest.cache";
            file_put_contents($filePath, $uid . ' cache reset test');
            $this->assertTrue(file_exists($filePath));
            $cacheFiles[] = $filePath;
        }

        // check if database entries and related files are gone after cache reset.
        $cache = oxNew(\OxidEsales\Eshop\Core\Cache\DynamicContent\ContentCache::class);
        $cache->reset();
        $this->assertEquals(0, $database->getOne('select count(*) from oxcache'));

        foreach ($cacheFiles as $file) {
            $this->assertFalse(file_exists($file), 'cache file should have been deleted');
        }
    }

    /**
     * Testing content cache cleanup in case of disabled content cache (blUseContentCaching = false).
     */
    public function testResetWithCCDisabled()
    {
        $myConfig = $this->getConfig();
        $myConfig->setConfigParam('blUseContentCaching', false);

        // cleaning up cache table
        \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute('delete from oxcache');

        // adding some trash into cache table
        $shopId = $myConfig->getBaseShopId();
        $database = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();
        for ($i = 0; $i < 10; $i++) {
            $uid = substr_replace(Registry::getUtilsObject()->generateUID(), '_', 0,1);
            $query = "insert into oxcache (`OXID`, `OXSHOPID`, `OXEXPIRE`, `OXRESETON`, `OXSIZE`, `OXHITS`)  values " .
                     "( '$uid', '$shopId', '0', '', '0', '0' )";
            $database->execute($query);
        }

        // checking that no calls to oxcache are done when content cache is disabled.
        $cache = oxNew(\OxidEsales\Eshop\Core\Cache\DynamicContent\ContentCache::class);
        $cache->reset();
        $this->assertEquals(10, $database->getOne('select count(*) from oxcache'));
    }


    /**
     * testing how cache reset by conditions works
     */
    public function testResetOn()
    {
        // preparing some cache with reset conditions ..
        $sShopId = $this->getConfig()->getBaseShopId();
        $oDb = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();
        for ($i = 0; $i < 10; $i++) {
            $oDb->Execute("insert into oxcache (`OXID`, `OXSHOPID`, `OXEXPIRE`, `OXRESETON`, `OXSIZE`, `OXHITS`)  values ( '$i', '$sShopId', " . time() . " + 3600, '|xid=$i', '0', '0' )");
        }

        $oCache = oxNew(\OxidEsales\Eshop\Core\Cache\DynamicContent\ContentCache::class);

        // resetting
        $aResetOn = array(0 => 'xid', 1 => 'xid', 2 => 'xid');
        $oCache->resetOn($aResetOn);

        // checking
        $this->assertEquals(7, $oDb->getOne('select count(*) from oxcache'));
    }

    /**
     * Testing cache size getter
     */
    public function testGetTotalCacheSize()
    {
        // preparing some cache ..
        $sShopId = $this->getConfig()->getBaseShopId();
        $oDb = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();
        for ($i = 0; $i < 10; $i++) {
            $oDb->Execute("insert into oxcache (`OXID`, `OXSHOPID`, `OXEXPIRE`, `OXRESETON`, `OXSIZE`, `OXHITS`)  values ( '$i', '$sShopId', " . time() . " + 3600, 'xid=$i', '10', '0' )");
        }

        for ($i = 0; $i < 10; $i++) {
            $oDb->Execute("insert into oxcache (`OXID`, `OXSHOPID`, `OXEXPIRE`, `OXRESETON`, `OXSIZE`, `OXHITS`)  values ( '" . ($i + 10) . "', '$sShopId', " . time() . " - 3600, 'xid=$i', '20', '0' )");
        }

        // testing
        $oCache = oxNew(\OxidEsales\Eshop\Core\Cache\DynamicContent\ContentCache::class);
        $this->assertEquals(100, $oCache->getTotalCacheSize());
        $this->assertEquals(200, $oCache->getTotalCacheSize(true));
    }

    /**
     * Testing cache count getter
     */
    public function testGetTotalCacheCount()
    {
        // preparing some cache ..
        $sShopId = $this->getConfig()->getBaseShopId();
        $oDb = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();
        for ($i = 0; $i < 5; $i++) {
            $oDb->Execute("insert into oxcache (`OXID`, `OXSHOPID`, `OXEXPIRE`, `OXRESETON`, `OXSIZE`, `OXHITS`)  values ( '$i', '$sShopId', " . time() . " + 3600, 'xid=$i', '10', '0' )");
        }

        for ($i = 0; $i < 10; $i++) {
            $oDb->Execute("insert into oxcache (`OXID`, `OXSHOPID`, `OXEXPIRE`, `OXRESETON`, `OXSIZE`, `OXHITS`)  values ( '" . ($i + 10) . "', '$sShopId', " . time() . " - 3600, 'xid=$i', '20', '0' )");
        }
        // testing
        $oCache = oxNew(\OxidEsales\Eshop\Core\Cache\DynamicContent\ContentCache::class);
        $this->assertEquals(5, $oCache->getTotalCacheCount());
        $this->assertEquals(10, $oCache->getTotalCacheCount(true));
    }

    /**
     * Testing cache hits getter
     */
    public function testGetTotalCacheHits()
    {
        // preparing some cache ..
        $sShopId = $this->getConfig()->getBaseShopId();
        $oDb = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();
        for ($i = 0; $i < 5; $i++) {
            $oDb->Execute("insert into oxcache (`OXID`, `OXSHOPID`, `OXEXPIRE`, `OXRESETON`, `OXSIZE`, `OXHITS`)  values ( '$i', '$sShopId', " . time() . " + 3600, 'xid=$i', '10', '$i' )");
        }

        for ($i = 0; $i < 10; $i++) {
            $oDb->Execute("insert into oxcache (`OXID`, `OXSHOPID`, `OXEXPIRE`, `OXRESETON`, `OXSIZE`, `OXHITS`)  values ( '" . ($i + 5) . "', '$sShopId', " . time() . " - 3600, 'xid=$i', '20', '$i' )");
        }
        // testing
        $oCache = oxNew(\OxidEsales\Eshop\Core\Cache\DynamicContent\ContentCache::class);
        $this->assertEquals(10, $oCache->getTotalCacheHits());
        $this->assertEquals(45, $oCache->getTotalCacheHits(true));
    }

    /**
     * Testing cache processor
     */
    public function testProcessCache()
    {
        ContainerFactory::resetContainer();

        $this->getSession()->setId("dfg");
        // preparing data
        $this->getSession()->setVariable('aFiltcompproducts', array('2363'));
        $this->getSession()->setVariable('aHistoryArticles', array('2275', '2363'));
        $this->getSession()->setVariable('sess_stoken', '12345678');

        $oSession = $this->getMock(\OxidEsales\Eshop\Core\Session::class, array("getId", "isSidNeeded"));
        $oSession->expects($this->any())->method('getId')->will($this->returnValue("dfg"));
        $oSession->expects($this->any())->method('isSidNeeded')->will($this->returnValue(true));

        $sCompareLinksTpl = 'widget/product/compare_links.tpl';

        // preparing fixtures
        $sCache = "Data:<a href=\"index.php?sid=[__SESSION_ID_PLACEHOLDER__]\"><input type=\"hidden\" name=\"stoken\" value=\"[__SESSION_STOKEN_PLACEHOLDER__]\"><input type=\"hidden\" name=\"force_sid\" value=\"[__SESSION_ID_PLACEHOLDER__]\">";
        $sCache .= "<oxid_dynamic> file='" . base64_encode($sCompareLinksTpl) . "' type='" . base64_encode('compare') . "' aid='" . base64_encode('2363') . "'</oxid_dynamic>";
        $sCache .= "<oxid_dynamic> file='" . base64_encode($sCompareLinksTpl) . "' type='" . base64_encode('compare') . "' aid='" . base64_encode('2275') . "'</oxid_dynamic>";
        $sCache .= 'sid=[__SESSION_ID_PLACEHOLDER__] stoken=[__SESSION_STOKEN_PLACEHOLDER__]';
        $sCache .= '<a href="http://testShopUrl/[__SESSION_FULL_ID_QUE_PLACEHOLDER__]">TestLink1</a>';
        $sCache .= '<a href="http://testShopUrl/?param1=value1[__SESSION_FULL_ID_AMP_PLACEHOLDER__]">TestLink2</a>';

        $oSmarty = Registry::getUtilsView()->getSmarty(true);
        $oSmarty->assign('oView', new \OxidEsales\Eshop\Application\Controller\FrontendController);
        $oSmarty->assign('oViewConf', new \OxidEsales\Eshop\Core\ViewConfig);

        $sOutput = "Data:<a href=\"index.php?sid=" . Registry::getSession()->getId() . "\"><input type=\"hidden\" name=\"stoken\" value=\"" . Registry::getSession()->getSessionChallengeToken() . "\"><input type=\"hidden\" name=\"force_sid\" value=\"" . Registry::getSession()->getId() . "\">";

        $oSmarty->assign('_compare_aid', '2363');
        $sOutput .= $oSmarty->fetch($sCompareLinksTpl);

        $oSmarty->assign('_compare_aid', '2275');
        $sOutput .= $oSmarty->fetch($sCompareLinksTpl);

        $sOutput .= 'sid=dfg stoken=12345678';
        $sOutput .= '<a href="http://testShopUrl/?force_sid=' . Registry::getSession()->getId() . '">TestLink1</a>' . '<a href="http://testShopUrl/?param1=value1&amp;force_sid=' . Registry::getSession()->getId() . '">TestLink2</a>';

        $oSmarty = Registry::getUtilsView()->getSmarty(true);
        $oSmarty->assign('oView', new \OxidEsales\Eshop\Application\Controller\FrontendController);
        $oSmarty->assign('oViewConf', new \OxidEsales\Eshop\Core\ViewConfig);

        /** @var ContentCache|MockObject $oCache */
        $oCache = $this->getMock(\OxidEsales\Eshop\Core\Cache\DynamicContent\ContentCache::class, array("getSession"));
        $oCache->expects($this->any())->method('getSession')->will($this->returnValue($oSession));

        $this->assertEquals($sOutput, $oCache->processCache($sCache));
    }

    /**
     * Testing cache id setter
     */
    public function testAddId()
    {
        // preparing some cache ..
        $this->getConfig()->getBaseShopId();

        $oCache = oxNew(\OxidEsales\Eshop\Core\Cache\DynamicContent\ContentCache::class);
        $oCache->UNITaddId('xxx', 100, 'xid=1');

        $this->assertEquals(md5('xxx'), \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->getOne('select oxid from oxcache where oxreseton="xid=1"'));
    }

    /**
     * Testing cache hit setter
     */
    public function testAddHit()
    {
        // preparing some cache ..
        $sShopId = $this->getConfig()->getBaseShopId();
        \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->Execute("insert into oxcache (`OXID`, `OXSHOPID`, `OXEXPIRE`, `OXRESETON`, `OXSIZE`, `OXHITS`)  values (  '" . md5('xxx') . "', '$sShopId', " . time() . " + 3600, 'xid=1', '10', '1' )");

        $iProductive = $this->getConfig()->getActiveShop()->oxshops__oxproductive->value;
        $this->getConfig()->getActiveShop()->oxshops__oxproductive->value = 0;

        $oCache = oxNew(\OxidEsales\Eshop\Core\Cache\DynamicContent\ContentCache::class);
        $oCache->UNITaddHit('xxx');

        $this->getConfig()->getActiveShop()->oxshops__oxproductive->value = $iProductive;

        $this->assertEquals('2', \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->getOne('select oxhits from oxcache where oxid="' . md5('xxx') . '"'));
    }

    /**
     * Testing cache hit setter
     */
    public function testAddHitForProductive()
    {
        // preparing some cache ..
        $sShopId = $this->getConfig()->getBaseShopId();
        \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->Execute("insert into oxcache (`OXID`, `OXSHOPID`, `OXEXPIRE`, `OXRESETON`, `OXSIZE`, `OXHITS`) values ( '" . md5('xxx') . "', '$sShopId', " . time() . " + 3600, 'xid=1', '10', '1' )");

        $iProductive = $this->getConfig()->getActiveShop()->oxshops__oxproductive->value;
        $this->getConfig()->getActiveShop()->oxshops__oxproductive->value = 1;

        $oCache = oxNew(\OxidEsales\Eshop\Core\Cache\DynamicContent\ContentCache::class);
        $oCache->UNITaddHit('xxx');

        $this->getConfig()->getActiveShop()->oxshops__oxproductive->value = $iProductive;

        $this->assertEquals('1', \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->getOne('select oxhits from oxcache where oxid="' . md5('xxx') . '"'));
    }

    /**
     * Testing cache id removal
     */
    public function testRemoveId()
    {
        // preparing some cache ..
        $sShopId = $this->getConfig()->getBaseShopId();
        \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->Execute("insert into oxcache (`OXID`, `OXSHOPID`, `OXEXPIRE`, `OXRESETON`, `OXSIZE`, `OXHITS`) values ( '" . md5('xxx') . "', '$sShopId', " . time() . " + 3600, 'xid=1', '10', '1' )");

        $this->assertTrue((bool) \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->getOne('select 1 from oxcache where oxid="' . md5('xxx') . '"'));

        $oCache = oxNew(\OxidEsales\Eshop\Core\Cache\DynamicContent\ContentCache::class);
        $oCache->UNITremoveId('xxx');

        $this->assertFalse((bool) \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->getOne('select 1 from oxcache where oxid="' . md5('xxx') . '"'));
    }

    public function test_getBackend()
    {
        oxTestModules::addFunction('oxCacheBackendDefault', 'getVar', '{$v=$aA[0]; return $this->$v;}');
        oxTestModules::addFunction('oxCacheBackendDefault', 'cacheSetTTL($iTimeToLive)', '{return $this->ttl = $iTimeToLive;}');

        /** @var ContentCache|MockObject $oCache */
        $oCache = $this->getMock(\OxidEsales\Eshop\Core\Cache\DynamicContent\ContentCache::class, array('getSelectedBackend', '_getAllBackends', 'getCacheLifeTime'));
        $oCache->expects($this->once())->method('getSelectedBackend')->will($this->returnValue('OXtest'));
        $oCache->expects($this->once())->method('_getAllBackends')->will($this->returnValue(array('OXtest' => 'oxCacheBackendDefault')));
        $oCache->expects($this->once())->method('getCacheLifeTime')->will($this->returnValue(9595));

        $oBackend = $oCache->UNITgetBackend();
        $this->assertTrue($oBackend instanceof DefaultCacheConnector);
        $this->assertEquals(9595, $oBackend->getVar('ttl'));
        $oBackend->same = true;

        $oBackend = $oCache->UNITgetBackend();
        $this->assertTrue($oBackend instanceof DefaultCacheConnector);
        $this->assertEquals(9595, $oBackend->getVar('ttl'));
        $this->assertTrue($oBackend->same);
    }

    public function test_getAllBackends_nocfg()
    {
        $oConfig = $this->getMock(\OxidEsales\Eshop\Core\Config::class, array('getConfigParam'));
        $oConfig->expects($this->once())->method('getConfigParam')
            ->with($this->equalTo('aUserCacheBackends'))
            ->will($this->returnValue(null));

        /** @var ContentCache|MockObject $oCache */
        $oCache = $this->getMock(\OxidEsales\Eshop\Core\Cache\DynamicContent\ContentCache::class, array('getConfig'));
        $oCache->expects($this->once())->method('getConfig')->will($this->returnValue($oConfig));
        $this->assertEquals(
            array(
                 'ZS_SHM'  => '\OxidEsales\EshopEnterprise\Core\Cache\DynamicContent\Connector\ZendShmCacheConnector',
                 'ZS_DISK' => '\OxidEsales\EshopEnterprise\Core\Cache\DynamicContent\Connector\ZendDiskCacheConnector',
                 'OXID'    => '\OxidEsales\EshopEnterprise\Core\Cache\DynamicContent\Connector\DefaultCacheConnector',
            ), $oCache->UNITgetAllBackends()
        );
    }

    public function test_getAllBackends_withcfg()
    {
        $oConfig = $this->getMock(\OxidEsales\Eshop\Core\Config::class, array('getConfigParam'));
        $oConfig->expects($this->once())->method('getConfigParam')
            ->with($this->equalTo('aUserCacheBackends'))
            ->will($this->returnValue(array('test' => 'lalala')));

        /** @var ContentCache|MockObject $oCache */
        $oCache = $this->getMock(\OxidEsales\Eshop\Core\Cache\DynamicContent\ContentCache::class, array('getConfig'));
        $oCache->expects($this->once())->method('getConfig')->will($this->returnValue($oConfig));
        $this->assertEquals(
            array(
                 'test'    => 'lalala',
                 'ZS_SHM'  => '\OxidEsales\EshopEnterprise\Core\Cache\DynamicContent\Connector\ZendShmCacheConnector',
                 'ZS_DISK' => '\OxidEsales\EshopEnterprise\Core\Cache\DynamicContent\Connector\ZendDiskCacheConnector',
                 'OXID'    => '\OxidEsales\EshopEnterprise\Core\Cache\DynamicContent\Connector\DefaultCacheConnector',
            ), $oCache->UNITgetAllBackends()
        );

        // cached = will not increment mock metd. count
        $this->assertEquals(
            array(
                 'test'    => 'lalala',
                 'ZS_SHM'  => '\OxidEsales\EshopEnterprise\Core\Cache\DynamicContent\Connector\ZendShmCacheConnector',
                 'ZS_DISK' => '\OxidEsales\EshopEnterprise\Core\Cache\DynamicContent\Connector\ZendDiskCacheConnector',
                 'OXID'    => '\OxidEsales\EshopEnterprise\Core\Cache\DynamicContent\Connector\DefaultCacheConnector',
            ), $oCache->UNITgetAllBackends()
        );
    }

    public function testgetSelectedBackend_selected()
    {
        $oConfig = $this->getMock(\OxidEsales\Eshop\Core\Config::class, array('getConfigParam'));
        $oConfig->expects($this->once())->method('getConfigParam')
            ->with($this->equalTo('sCacheBackend'))
            ->will($this->returnValue('asdsda'));

        /** @var ContentCache|MockObject $oCache */
        $oCache = $this->getMock(\OxidEsales\Eshop\Core\Cache\DynamicContent\ContentCache::class, array('getConfig', 'isBackendAvailable', '_getFirstSuitedBackend'));
        $oCache->expects($this->once())->method('getConfig')->will($this->returnValue($oConfig));
        $oCache->expects($this->once())->method('isBackendAvailable')
            ->with($this->equalTo('asdsda'))
            ->will($this->returnValue(true));
        $oCache->expects($this->never())->method('_getFirstSuitedBackend');

        $this->assertEquals('asdsda', $oCache->getSelectedBackend());
    }

    public function testgetSelectedBackend_selectedWrong()
    {
        $oConfig = $this->getMock(\OxidEsales\Eshop\Core\Config::class, array('getConfigParam'));
        $oConfig->expects($this->once())->method('getConfigParam')
            ->with($this->equalTo('sCacheBackend'))
            ->will($this->returnValue('asdsda'));

        /** @var ContentCache|MockObject $oCache */
        $oCache = $this->getMock(\OxidEsales\Eshop\Core\Cache\DynamicContent\ContentCache::class, array('getConfig', 'isBackendAvailable', '_getFirstSuitedBackend'));
        $oCache->expects($this->once())->method('getConfig')->will($this->returnValue($oConfig));
        $oCache->expects($this->once())->method('isBackendAvailable')
            ->with($this->equalTo('asdsda'))
            ->will($this->returnValue(false));
        $oCache->expects($this->once())->method('_getFirstSuitedBackend')->will($this->returnValue('xxx'));;

        $this->assertEquals('xxx', $oCache->getSelectedBackend());
    }

    public function testgetSelectedBackend_NotSelected()
    {
        $oConfig = $this->getMock(\OxidEsales\Eshop\Core\Config::class, array('getConfigParam'));
        $oConfig->expects($this->once())->method('getConfigParam')
            ->with($this->equalTo('sCacheBackend'))
            ->will($this->returnValue(null));

        /** @var ContentCache|MockObject $oCache */
        $oCache = $this->getMock(\OxidEsales\Eshop\Core\Cache\DynamicContent\ContentCache::class, array('getConfig', 'isBackendAvailable', '_getFirstSuitedBackend'));
        $oCache->expects($this->once())->method('getConfig')->will($this->returnValue($oConfig));
        $oCache->expects($this->never())->method('isBackendAvailable');
        $oCache->expects($this->once())->method('_getFirstSuitedBackend')->will($this->returnValue('xxx'));;

        $this->assertEquals('xxx', $oCache->getSelectedBackend());
    }

    public function test_getFirstSuitedBackend_found()
    {
        /** @var ContentCache|MockObject $oCache */
        $oCache = $this->getMock(\OxidEsales\Eshop\Core\Cache\DynamicContent\ContentCache::class, array('_getAllBackends', 'isBackendAvailable'));
        $oCache->expects($this->once())->method('_getAllBackends')->will($this->returnValue(array('x1' => 'asd', 'x2' => 'asdaa', 'x3' => 'asdd')));
        $oCache->expects($this->exactly(2))->method('isBackendAvailable')
            ->will($this->onConsecutiveCalls($this->returnValue(false), $this->returnValue(true)));

        $this->assertEquals('x2', $oCache->UNITgetFirstSuitedBackend());
    }

    public function test_getFirstSuitedBackend_notFound()
    {
        /** @var ContentCache|MockObject $oCache */
        $oCache = $this->getMock(\OxidEsales\Eshop\Core\Cache\DynamicContent\ContentCache::class, array('_getAllBackends', 'isBackendAvailable'));
        $oCache->expects($this->once())->method('_getAllBackends')->will($this->returnValue(array('x1' => 'asd', 'x2' => 'asdaa', 'x3' => 'asdd')));
        $oCache->expects($this->exactly(3))->method('isBackendAvailable')
            ->will($this->onConsecutiveCalls($this->returnValue(false), $this->returnValue(false), $this->returnValue(false)));

        try {
            $oCache->UNITgetFirstSuitedBackend();
        } catch (oxSystemComponentException $e) {
            $this->assertEquals('oxCache', $e->getComponent());

            return;
        }
        $this->fail('exception not thrown');
    }

    public function testisBackendAvailable()
    {
        $backends = array(
            'x1' => '\OxidEsales\EshopEnterprise\Tests\Unit\Core\Cache\DynamicContent\SimpleNotValidBackend',
            'x2' => '\OxidEsales\EshopEnterprise\Tests\Unit\Core\Cache\DynamicContent\SimpleValidBackend',
            'x3' => 'asdd'
        );
        /** @var ContentCache|MockObject $oCache */
        $oCache = $this->getMock(\OxidEsales\Eshop\Core\Cache\DynamicContent\ContentCache::class, array('_getAllBackends'));
        $oCache->expects($this->any())->method('_getAllBackends')->will($this->returnValue($backends));

        $this->assertFalse($oCache->isBackendAvailable('x3asd'));
        $this->assertFalse($oCache->isBackendAvailable('x3'));
        $this->assertFalse($oCache->isBackendAvailable('x1'));
        SimpleValidBackend::$valid = false;
        $this->assertFalse($oCache->isBackendAvailable('x2'));
        SimpleValidBackend::$valid = true;
        $this->assertTrue($oCache->isBackendAvailable('x2'));
    }

    public function testGetAvailableBackends()
    {
        /** @var ContentCache|MockObject $oCache */
        $oCache = $this->getMock(\OxidEsales\Eshop\Core\Cache\DynamicContent\ContentCache::class, array('_getAllBackends', 'isBackendAvailable'));
        $oCache->expects($this->any())->method('_getAllBackends')->will($this->returnValue(array('x1' => 'simpleNotValidBackend', 'x2' => 'simpleValidBackend', 'x3' => 'asdd')));
        $oCache->expects($this->exactly(3))->method('isBackendAvailable')
            ->will($this->onConsecutiveCalls($this->returnValue(false), $this->returnValue(true), $this->returnValue(false)));

        $this->assertEquals(array('x2'), $oCache->getAvailableBackends());

        /** @var ContentCache|MockObject $oCache */
        $oCache = $this->getMock(\OxidEsales\Eshop\Core\Cache\DynamicContent\ContentCache::class, array('_getAllBackends', 'isBackendAvailable'));
        $oCache->expects($this->any())->method('_getAllBackends')->will($this->returnValue(array('x1' => 'simpleNotValidBackend', 'x2' => 'simpleValidBackend', 'x3' => 'asdd')));
        $oCache->expects($this->exactly(3))->method('isBackendAvailable')
            ->will($this->onConsecutiveCalls($this->returnValue(false), $this->returnValue(true), $this->returnValue(true)));

        $this->assertEquals(array('x2', 'x3'), $oCache->getAvailableBackends());
    }

    public function testCleanSensitiveData()
    {
        $oSess = $this->getMock('stdClass', array('getId'));
        $oSess->expects($this->any())->method('getId')->will($this->returnValue('6516800086adef0'));

        /** @var ContentCache|MockObject $oCache */
        $oCache = $this->getMock(\OxidEsales\Eshop\Core\Cache\DynamicContent\ContentCache::class, array('getSession'));
        $oCache->expects($this->any())->method('getSession')->will($this->returnValue($oSess));

        $this->assertEquals('session is "[__SESSION_ID_PLACEHOLDER__]" and url for it is sid=[__SESSION_ID_PLACEHOLDER__]&ampotherinfo=123', $oCache->UNITcleanSensitiveData('session is "6516800086adef0" and url for it is sid=6516800086adef0&ampotherinfo=123'));


        $this->getSession()->setVariable('sess_stoken', '681efff6');

        $this->assertEquals('[__SESSION_ID_PLACEHOLDER__]:begin - session token is "[__SESSION_STOKEN_PLACEHOLDER__]" and url for it is sid=[__SESSION_ID_PLACEHOLDER__]&amp;stoken=[__SESSION_STOKEN_PLACEHOLDER__]&amp;otherinfo=123 - end:[__SESSION_STOKEN_PLACEHOLDER__]', $oCache->UNITcleanSensitiveData('6516800086adef0:begin - session token is "681efff6" and url for it is sid=6516800086adef0&amp;stoken=681efff6&amp;otherinfo=123 - end:681efff6'));
    }

    /**
     * testing oxCache::_appendSidPlaceholder() if it appends the force_sid placeholder correctly
     */
    public function testAppendSidPlaceholder()
    {
        $sShopUrl = "http://testShopUrl/";
        $sShopSslUrl = "http://testShopSslUrl/";

        // values and results
        $sHtml = '
             <p>Test string</p>
             <a href="' . $sShopUrl . '"></a>
             <a href="' . $sShopUrl . '?"></a>
             <a href="' . $sShopUrl . '?param1=value1"></a>
             <a href="' . $sShopUrl . '?param1=value1&"></a>
             <a href="' . $sShopSslUrl . '?param1=value1&amp;"></a>
             <a href="' . $sShopSslUrl . '?param1=value1&amp;force_sid=XXX&amp;param2=value2"></a>
             <a href="' . $sShopSslUrl . '?param1=value1#testLink"></a>
             <A href="' . $sShopUrl . '#testLink"></A>
             <p>Test string end</p>
             ';

        $sResult = '
             <p>Test string</p>
             <a href="' . $sShopUrl . '[__SESSION_FULL_ID_QUE_PLACEHOLDER__]"></a>
             <a href="' . $sShopUrl . '[__SESSION_FULL_ID_QUE_PLACEHOLDER__]"></a>
             <a href="' . $sShopUrl . '?param1=value1[__SESSION_FULL_ID_AMP_PLACEHOLDER__]"></a>
             <a href="' . $sShopUrl . '?param1=value1[__SESSION_FULL_ID_AMP_PLACEHOLDER__]"></a>
             <a href="' . $sShopSslUrl . '?param1=value1[__SESSION_FULL_ID_AMP_PLACEHOLDER__]"></a>
             <a href="' . $sShopSslUrl . '?param1=value1&amp;param2=value2[__SESSION_FULL_ID_AMP_PLACEHOLDER__]"></a>
             <a href="' . $sShopSslUrl . '?param1=value1[__SESSION_FULL_ID_AMP_PLACEHOLDER__]#testLink"></a>
             <A href="' . $sShopUrl . '[__SESSION_FULL_ID_QUE_PLACEHOLDER__]#testLink"></A>
             <p>Test string end</p>
            ';

        $oConfig = $this->getMock(\OxidEsales\Eshop\Core\Config::class, array('getShopUrl', 'getSslShopUrl'));
        $oConfig->expects($this->any())->method('getShopUrl')->will($this->returnValue($sShopUrl));
        $oConfig->expects($this->any())->method('getSslShopUrl')->will($this->returnValue($sShopSslUrl));

        $oCache = $this->getMock(\OxidEsales\Eshop\Core\Cache\DynamicContent\ContentCache::class, array("getConfig"));
        $oCache->expects($this->any())->method('getConfig')->will($this->returnValue($oConfig));

        $this->assertEquals(trim($sResult), trim($oCache->UNITappendSidPlaceholder($sHtml)));
    }
}
