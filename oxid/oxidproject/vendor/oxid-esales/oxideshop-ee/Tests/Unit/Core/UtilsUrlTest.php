<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Tests\Unit\Core;

use OxidEsales\EshopEnterprise\Core\UtilsUrl;
use \oxDb;
use \oxTestModules;
use OxidEsales\Eshop\Core\Registry;
use \oxbase;
use \oxField;

class UtilsUrlTest extends \oxUnitTestCase
{
    private $mockClassName = '\OxidEsales\EshopEnterprise\Core\UtilsUrl';

    /**
     * Initialize the fixture.
     *
     * @return null
     */

    /**
     * Tear down the fixture.
     *
     * @return null
     */
    protected function tearDown(): void
    {
        \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute('delete from oxshops where oxid = "999"');
        $this->getConfig()->setShopId(null);

        parent::tearDown();
    }

    /**
     * UtilsUrl::prepareCanonicalUrl() test case
     *
     * @return null
     */
    public function testPrepareCanonicalUrl()
    {
        oxTestModules::addFunction('oxUtils', 'seoIsActive', '{return false;}');
        $this->getConfig()->setConfigParam("sDefaultLang", 9);
        $iLang = Registry::getLang()->getBaseLanguage();

        $sExpUrl = "shop.com/index.php?param1=value1&amp;bonusid=111";

        $this->getConfig()->setShopId(999);
        $this->getConfig()->setConfigParam("iMallMode", true);
        $this->getConfig()->setConfigParam("sMallShopURL", false);

        $oShop = new \OxidEsales\Eshop\Core\Model\BaseModel();
        $oShop->init('oxshops');
        $oShop->setId('999');
        $oShop->oxshops__oxactive = new \OxidEsales\Eshop\Core\Field(1);
        $oShop->save();

        $sExpUrl .= "&amp;lang={$iLang}";
        $sExpUrl .= "&amp;shp=999";

        $oUtils = new UtilsUrl();
        $this->assertEquals($sExpUrl, $oUtils->prepareCanonicalUrl("shop.com/index.php?param1=value1&amp;bonusid=111&amp;sid=1234"));
    }

    public function testGetBaseAddUrlParams()
    {
        $aBaseParams['shp'] = 1;

        $oConfig = $this->getMock(\OxidEsales\Eshop\Core\Config::class, array("mustAddShopIdToRequest"));
        $oConfig->expects($this->once())->method('mustAddShopIdToRequest')->will($this->returnValue(true));

        $oUtils = $this->getMock($this->mockClassName, array("getConfig"));
        $oUtils->expects($this->once())->method('getConfig')->will($this->returnValue($oConfig));
        $this->assertEquals($aBaseParams, $oUtils->getBaseAddUrlParams());
    }

    public function testPrepareUrlForNoSession()
    {
        oxTestModules::addFunction('oxUtils', 'seoIsActive', '{return false;}');
        oxTestModules::addFunction('oxLang', 'getBaseLanguage', '{return 3;}');

        $sShopId = '&amp;shp=1';
        $this->assertEquals('sdf?lang=1' . $sShopId, Registry::getUtilsUrl()->prepareUrlForNoSession('sdf?sid=111&lang=1'));
        $this->assertEquals('sdf?a&lang=1' . $sShopId, Registry::getUtilsUrl()->prepareUrlForNoSession('sdf?sid=111&a&lang=1'));
        $this->assertEquals('sdf?a&amp;lang=1' . $sShopId, Registry::getUtilsUrl()->prepareUrlForNoSession('sdf?sid=111&a&amp;lang=1'));
        $this->assertEquals('sdf?a&&amp;lang=3' . $sShopId, Registry::getUtilsUrl()->prepareUrlForNoSession('sdf?sid=111&a&'));
        $this->assertEquals('sdf?lang=3' . $sShopId, Registry::getUtilsUrl()->prepareUrlForNoSession('sdf'));

        $sShopId = '&amp;shp=5';
        $this->getConfig()->setShopId(5);

        $this->assertEquals('sdf?lang=3' . $sShopId, Registry::getUtilsUrl()->prepareUrlForNoSession('sdf?sid=asd'));
        $this->assertEquals('sdf?lang=2' . $sShopId, Registry::getUtilsUrl()->prepareUrlForNoSession('sdf?sid=das&lang=2'));
        $this->assertEquals('sdf?lang=2&shp=3', Registry::getUtilsUrl()->prepareUrlForNoSession('sdf?lang=2&sid=fs&amp;shp=3'));
        $this->assertEquals('sdf?shp=2&amp;lang=2', Registry::getUtilsUrl()->prepareUrlForNoSession('sdf?shp=2&amp;lang=2'));
        $this->assertEquals('sdf?shp=2&amp;lang=3', Registry::getUtilsUrl()->prepareUrlForNoSession('sdf?shp=2'));

        $this->assertEquals('sdf?lang=1' . $sShopId, Registry::getUtilsUrl()->prepareUrlForNoSession('sdf?force_sid=111&lang=1'));
        $this->assertEquals('sdf?a&lang=1' . $sShopId, Registry::getUtilsUrl()->prepareUrlForNoSession('sdf?force_sid=111&a&lang=1'));
        $this->assertEquals('sdf?a&amp;lang=1' . $sShopId, Registry::getUtilsUrl()->prepareUrlForNoSession('sdf?force_sid=111&a&amp;lang=1'));
        $this->assertEquals('sdf?a&&amp;lang=3' . $sShopId, Registry::getUtilsUrl()->prepareUrlForNoSession('sdf?force_sid=111&a&'));

        $this->assertEquals('sdf?bonusid=111&amp;lang=3' . $sShopId, Registry::getUtilsUrl()->prepareUrlForNoSession('sdf?bonusid=111'));
        $this->assertEquals('sdf?a=1&bonusid=111&amp;lang=3' . $sShopId, Registry::getUtilsUrl()->prepareUrlForNoSession('sdf?a=1&bonusid=111'));
        $this->assertEquals('sdf?a=1&amp;bonusid=111&amp;lang=3' . $sShopId, Registry::getUtilsUrl()->prepareUrlForNoSession('sdf?a=1&amp;bonusid=111&amp;force_admin_sid=111'));

        $this->setRequestParameter('currency', 2);
        $this->assertEquals('sdf?lang=3&amp;cur=2' . $sShopId, Registry::getUtilsUrl()->prepareUrlForNoSession('sdf'));

        oxTestModules::addFunction('oxUtils', 'seoIsActive', '{return true;}');
        $this->assertEquals('sdf', Registry::getUtilsUrl()->prepareUrlForNoSession('sdf'));
    }

    /*
     * subshop host with SAME domain url
     */
    public function testProcessSeoUrlAdminSubshopWithOnSameDomain()
    {
        $shopId = 2;
        $this->getConfig()->setShopId($shopId);
        $url = $this->getConfig()->getConfigParam("sShopURL") . "index.php?param1=value1";

        $utils = $this->getMock($this->mockClassName, array("isAdmin"));
        $utils->expects($this->any())->method('isAdmin')->will($this->returnValue(true));
        $this->assertEquals($url . "&amp;shp=" . $shopId, $utils->processSeoUrl($url));
    }

    /**
     * subshop host with SEPARATE domain url
     */
    public function testProcessSeoUrlAdminSubshopWithSeparateDomain()
    {
        $shopId = 2;
        $this->getConfig()->setShopId($shopId);
        $this->getConfig()->setConfigParam("sMallShopURL", "http://subshophost.com");

        $url = "http://subshophost.com/index.php?param1=value1";
        $utils = $this->getMock($this->mockClassName, array("isAdmin"));
        $utils->expects($this->any())->method('isAdmin')->will($this->returnValue(true));
        $this->assertEquals($url, $utils->processSeoUrl($url));
    }

    /**
     * not admin. if needed, must be added shop id, session identifier etc.
     */
    public function testProcessSeoUrlNonAdmin()
    {
        // sub shop
        $shopId = 2;
        $this->getConfig()->setShopId($shopId);
        $shopUrl = $this->getConfig()->getConfigParam("sShopURL");

        $utilsMock = $this->getMock($this->mockClassName, array("isAdmin"));
        $utilsMock->expects($this->any())->method('isAdmin')->will($this->returnValue(false));
        $this->assertEquals($shopUrl . "?shp=" . $shopId, $utilsMock->processSeoUrl($shopUrl));

        // subshop with different domain
        $shopId = 2;
        $this->getConfig()->setShopId($shopId);
        $this->getConfig()->setConfigParam("sMallShopURL", "http://subshophost.com");

        $shopUrl = "http://subshophost.com/index.php?param1=value1";
        $utilsMock = $this->getMock($this->mockClassName, array("isAdmin"));
        $utilsMock->expects($this->any())->method('isAdmin')->will($this->returnValue(false));
        $this->assertEquals($shopUrl, $utilsMock->processSeoUrl($shopUrl));
    }

    public function testIsCurrentShopHostWithMallShopURL()
    {
        $this->getConfig()->setConfigParam("sMallShopURL", 'http://shopHost');
        $this->getConfig()->setConfigParam("sShopURL", '');
        $this->getConfig()->setConfigParam("aLanguageURLs", array());

        $oUtils = new UtilsUrl();
        $this->assertSame(true, $oUtils->isCurrentShopHost('http://shopHost'));
    }

    public function testIsCurrentShopHostWithMallSslShopURL()
    {
        $this->getConfig()->setConfigParam("sMallShopURL", 'http://shopHost');
        $this->getConfig()->setConfigParam("sMallSSLShopURL", 'https://shopHost');
        $this->getConfig()->setConfigParam("sShopURL", '');
        $this->getConfig()->setConfigParam("aLanguageURLs", array());

        $oUtils = new UtilsUrl();
        $this->assertSame(true, $oUtils->isCurrentShopHost('https://shopHost'));
    }
}
