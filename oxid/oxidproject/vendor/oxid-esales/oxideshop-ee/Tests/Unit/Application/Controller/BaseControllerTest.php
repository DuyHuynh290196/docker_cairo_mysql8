<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */
namespace OxidEsales\EshopEnterprise\Tests\Unit\Application\Controller;

require_once TEST_LIBRARY_HELPERS_PATH . 'oxUBaseHelper.php';

use oxDb;
use OxidEsales\Eshop\Core\Registry;
use oxUBaseHelper;
use StdClass;

class BaseControllerTest extends \OxidEsales\TestingLibrary\UnitTestCase
{
    /**
     * Initialize the fixture.
     */
    protected function setUp(): void
    {
        oxUBaseHelper::resetComponentNames();

        // adding article to recommendList
        $sQ = 'replace into oxrecommlists ( oxid, oxuserid, oxtitle, oxdesc, oxshopid ) values ( "testlist", "oxdefaultadmin", "oxtest", "oxtest", "' . $this->getConfig()->getShopId() . '" ) ';
        \OxidEsales\Eshop\Core\DatabaseProvider::getDB()->execute($sQ);

        parent::setUp();
    }

    /**
     * Tear down the fixture.
     */
    protected function tearDown(): void
    {
        \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute('delete from oxrecommlists where oxid like "testlist%" ');
        \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute('delete from oxseologs ');
        \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute('delete from oxseo where oxtype != "static"');

        \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute('delete from oxcontents where oxloadid = "_testKeywordsIdentId" ');

        $oUBase = oxNew(\OxidEsales\Eshop\Application\Controller\FrontendController::class);
        $oUBase->getSession()->setBasket(null);

        oxUBaseHelper::resetComponentNames();

        parent::tearDown();
    }

    /*
     * Test getting view ID without some params
     */
    public function testGetViewId()
    {
        $config = $this->getConfig();
        $sShopURL = $config->getShopUrl();
        $sShopID = $config->getShopId();

        $oSession = $this->getMock('stdclass', array('getId'));
        $oSession->expects($this->once())->method('getId')->will($this->returnValue(''));

        $oRights = $this->getMock(\OxidEsales\Eshop\Application\Model\Rights::class, array('getViewRights', 'getUserGroupIndex'));
        $oRights->expects($this->exactly(2))->method('getViewRights')->will($this->returnValue(array('xxx')));
        $oRights->expects($this->exactly(2))->method('getUserGroupIndex')->will($this->returnValue(array('yyy')));

        $oView = $this->getMock(\OxidEsales\Eshop\Application\Controller\FrontendController::class, array('getRights', 'isAdmin', 'getSession'));
        $oView->expects($this->once())->method('getRights')->will($this->returnValue($oRights));
        $oView->expects($this->once())->method('isAdmin')->will($this->returnValue(false));
        $oView->expects($this->once())->method('getSession')->will($this->returnValue($oSession));

        $this->assertEquals("ox|$sShopURL|$sShopID|0|0|0|start||||" . md5(serialize(array('xxx')) . serialize(array('yyy'))) . "|1|0|0", $oView->getViewId());

        // and caching
        Registry::getLang()->setBaseLanguage(1);
        $this->assertEquals("ox|$sShopURL|$sShopID|0|0|0|start||||" . md5(serialize(array('xxx')) . serialize(array('yyy'))) . "|1|0|0", $oView->getViewId());

        $oSession = $this->getMock('stdclass', array('getId'));
        $oSession->expects($this->any())->method('getId')->will($this->returnValue('asd'));

        $oView = $this->getMock(\OxidEsales\Eshop\Application\Controller\FrontendController::class, array('getRights', 'isAdmin', 'getSession'));
        $oView->expects($this->once())->method('getRights')->will($this->returnValue($oRights));
        $oView->expects($this->once())->method('isAdmin')->will($this->returnValue(false));
        $oView->expects($this->once())->method('getSession')->will($this->returnValue($oSession));

        $this->assertEquals("ox|$sShopURL|$sShopID|1|1|0|start||||" . md5(serialize(array('xxx')) . serialize(array('yyy'))) . "|1|0|0", $oView->getViewId());
    }

    public function testGetViewResetId()
    {
        $oCategory = $this->getMock(\OxidEsales\Eshop\Application\Model\Category::class, array('getId'));
        $oCategory->expects($this->once())->method('getId')->will($this->returnValue('xxx'));

        $oView = $this->getMock(\OxidEsales\Eshop\Application\Controller\FrontendController::class, array('getClassName'));
        $oView->expects($this->once())->method('getClassName')->will($this->returnValue('xxx'));
        $oView->setActiveCategory($oCategory);

        $this->assertEquals("ox|cid=xxx|cl=xxx", $oView->getViewResetId());
    }

    public function testGetViewResetIdWithIncorrectCategoryObject()
    {
        $oCategory = new StdClass();

        $oView = $this->getMock(\OxidEsales\Eshop\Application\Controller\FrontendController::class, array('getClassName'));
        $oView->expects($this->once())->method('getClassName')->will($this->returnValue('xxx'));
        $oView->setActiveCategory($oCategory);

        $this->assertEquals("ox|cid=-|cl=xxx", $oView->getViewResetId());
    }

    /*
     * Test getting view ID with some additional params
     */
    public function testGetViewIdWithOtherParams()
    {
        $myConfig = $this->getConfig();

        Registry::getLang()->setBaseLanguage(1);
        $this->setRequestParameter('currency', '1');
        $this->setRequestParameter('cl', 'details');
        $this->setRequestParameter('fnc', 'dsd');
        $this->setSessionParam("usr", 'oxdefaultadmin');

        $oView = oxNew(\OxidEsales\Eshop\Application\Controller\FrontendController::class);
        $oView->getViewId();

        $sShopURL = $myConfig->getShopUrl();
        $sShopID = $myConfig->getShopId();

        $oRights = $this->getMock(\OxidEsales\Eshop\Application\Model\Rights::class, array('getViewRights', 'getUserGroupIndex'));
        $oRights->expects($this->once())->method('getViewRights')->will($this->returnValue(array('xxx')));
        $oRights->expects($this->once())->method('getUserGroupIndex')->will($this->returnValue(array('yyy')));

        $oView = $this->getMock(\OxidEsales\Eshop\Application\Controller\FrontendController::class, array('getRights', 'isAdmin'));
        $oView->expects($this->once())->method('getRights')->will($this->returnValue($oRights));
        $oView->expects($this->once())->method('isAdmin')->will($this->returnValue(false));

        $this->assertEquals("ox|$sShopURL|$sShopID|0|1|1|details|dsd|oxdefaultadmin||" . md5(serialize(array('xxx')) . serialize(array('yyy'))) . "|1|0|0", $oView->getViewId());
    }

    /*
     * Test getting view ID with SSL enabled
     */
    public function testGetViewIdWithSSL()
    {
        $config = $this->getConfig();
        $config->setIsSsl(true);

        $oView = oxNew(\OxidEsales\Eshop\Application\Controller\FrontendController::class);
        $oView->getViewId();

        $sShopURL = $config->getShopUrl();
        $sShopID = $config->getShopId();

        $oRights = $this->getMock(\OxidEsales\Eshop\Application\Model\Rights::class, array('getViewRights', 'getUserGroupIndex'));
        $oRights->expects($this->once())->method('getViewRights')->will($this->returnValue(array('xxx')));
        $oRights->expects($this->once())->method('getUserGroupIndex')->will($this->returnValue(array('yyy')));

        $oView = $this->getMock(\OxidEsales\Eshop\Application\Controller\FrontendController::class, array('getRights', 'isAdmin'));
        $oView->expects($this->once())->method('getRights')->will($this->returnValue($oRights));
        $oView->expects($this->once())->method('isAdmin')->will($this->returnValue(false));

        $this->assertEquals("ox|$sShopURL|$sShopID|0|0|0|start||||" . md5(serialize(array('xxx')) . serialize(array('yyy'))) . "|1|0|0|ssl", $oView->getViewId());
    }

    /*
     * Testing init components when view is component. Component init should not be called.
     */
    public function testInitComponentsWhenComponentIsCached()
    {
        $oView = $this->getMock(\OxidEsales\Eshop\Application\Controller\FrontendController::class, array("_getComponentNames"));
        $oView->expects($this->once())->method('_getComponentNames')->will($this->returnValue(array("oxUBaseHelper" => false)));
        $oView->setIsCallForCache(true);
        $oView->init();

        $aComponents = $oView->getComponents();
        $this->assertEquals(1, count($aComponents));
        $this->assertFalse($aComponents['oxUBaseHelper']->initWasCalled);
    }

    /*
     * Testing initiates all non cacheable components
     */
    public function testInitNonCacheableComponents()
    {
        $sHead = 'test' . md5(uniqid(rand(), true));

        $aCommonComponents = array(
            "{$sHead}_testNonCacheAble" => true,
            "{$sHead}_testCacheAble"    => false,
        );
        // emulating classes ..
        foreach ($aCommonComponents as $sClassName => $sValue) {
            eval("class $sClassName extends oxUbase {}");
        }

        $oView = $this->getMock(\OxidEsales\Eshop\Application\Controller\FrontendController::class, array("_getComponentNames"));
        $oView->expects($this->once())->method('_getComponentNames')->will($this->returnValue($aCommonComponents));
        $oView->initNonCacheableComponents();

        $aComponents = $oView->getComponents();
        $this->assertEquals(1, count($aComponents));
        $this->assertTrue(isset($aComponents["{$sHead}_testNonCacheAble"]));
    }

    /*
     * Testing initiates all non cacheable components when component is cachable
     */
    public function testInitNonCacheableComponentsWhenComponentIsCachable()
    {
        $oView = $this->getProxyClass("oxUbase");
        $aCommonComponents = array("oxUBaseHelper" => false);
        $oView->setNonPublicVar('_aComponentNames', $aCommonComponents);

        $oView->initNonCacheableComponents();

        $aComponents = $oView->getComponents();
        $this->assertEquals(0, count($aComponents));
    }

    /*
     * Test rendering non cacheable components
     */
    public function testRenderNonCacheableComponents()
    {
        $oViewComponent = $this->getMock('oxUBaseHelper', array('render'));

        $oViewComponent->expects($this->once())
            ->method('render');

        $oView = $this->getMock(\OxidEsales\Eshop\Application\Controller\FrontendController::class, array("_getComponentNames"));
        $oView->expects($this->once())->method('_getComponentNames')->will($this->returnValue(array("oxUBaseHelper" => true)));

        $aViewData['oxUBaseHelper'] = $oViewComponent;
        $oView->setComponents($aViewData);

        $oView->renderNonCacheableComponents();
    }

    /*
     * Testing initiates all cacheable components
     */
    public function testInitCacheableComponents()
    {
        $oViewComponent = $this->getMock('oxUBaseHelper', array('init'));
        $oViewComponent->expects($this->once())->method('init');

        $oView = $this->getMock(\OxidEsales\Eshop\Application\Controller\FrontendController::class, array("_getComponentNames"));
        $oView->expects($this->once())->method('_getComponentNames')->will($this->returnValue(array("oxUBaseHelper" => false)));

        $aViewData['oxUBaseHelper'] = $oViewComponent;
        $oView->setComponents($aViewData);

        $oView->initCacheableComponents();
    }

    /*
     * Testing initiates all cacheable components when component is not cachable
     */
    public function testInitCacheableComponentsWhenComponentIsNotCachable()
    {
        $oViewComponent = $this->getMock('oxUBaseHelper', array('init'));
        $oViewComponent->expects($this->never())->method('init');

        $oView = $this->getMock(\OxidEsales\Eshop\Application\Controller\FrontendController::class, array("_getComponentNames"));
        $oView->expects($this->once())->method('_getComponentNames')->will($this->returnValue(array("oxUBaseHelper" => true)));

        $aViewData['oxUBaseHelper'] = $oViewComponent;
        $oView->setComponents($aViewData);

        $oView->initCacheableComponents();
    }

    public function testCanCache()
    {
        $oObj = oxNew(\OxidEsales\Eshop\Application\Controller\FrontendController::class);
        $this->assertTrue($oObj->canCache());
    }
}
