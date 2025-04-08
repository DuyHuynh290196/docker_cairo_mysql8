<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Tests\Unit\Core;

use OxidEsales\Eshop\Core\Registry;
use \oxTestModules;
use \oxbase;
use \oxField;
use OxidEsales\EshopEnterprise\Core\ViewConfig;

class ViewConfigTest extends \oxUnitTestCase
{
    /**
     * Tear down the fixture.
     *
     * @return null
     */
    protected function tearDown(): void
    {
        $this->getDb()->execute('delete from oxshops where oxid = "999"');
        parent::tearDown();
    }

    public function testGetHomeLinkEeMallModeOffAndFewShopsActive()
    {
        $this->getConfig()->setConfigParam("iMallMode", 0);

        $oShop = new \OxidEsales\Eshop\Core\Model\BaseModel();
        $oShop->init('oxshops');
        $oShop->setId('999');
        $oShop->oxshops__oxactive = new \OxidEsales\Eshop\Core\Field(1);
        $oShop->save();

        $oViewConfig = new ViewConfig();
        $this->assertEquals($this->getConfig()->getShopURL(), $oViewConfig->getHomeLink());
    }

    public function testGetHomeLinkEeMallModeOnAndFewShopsActiveDe()
    {
        $this->getConfig()->setConfigParam("iMallMode", 1);
        oxTestModules::addFunction("oxLang", "getBaseLanguage", "{return 0;}");

        $oShop = new \OxidEsales\Eshop\Core\Model\BaseModel();
        $oShop->init('oxshops');
        $oShop->setId('999');
        $oShop->oxshops__oxactive = new \OxidEsales\Eshop\Core\Field(1);
        $oShop->save();

        $oViewConfig = new ViewConfig();
        $this->assertEquals($this->getConfig()->getShopURL() . "startseite/", $oViewConfig->getHomeLink());

    }

    public function testGetHomeLinkEeMallModeOn()
    {
        oxTestModules::addFunction("oxutilsserver", "getServerVar", "{ \$aArgs = func_get_args(); if ( \$aArgs[0] === 'HTTP_HOST' ) { return '" . $this->getConfig()->getShopUrl() . "'; } elseif ( \$aArgs[0] === 'SCRIPT_NAME' ) { return ''; } else { return \$_SERVER[\$aArgs[0]]; } }");

        $oViewConfig = new ViewConfig();
        $this->assertEquals($this->getConfig()->getShopURL(), $oViewConfig->getHomeLink());
    }

    /**
     * Checks if shop licenze is in staging mode
     */
    public function testIsStagingMode()
    {
        $oConfig = $this->getMock(\OxidEsales\Eshop\Core\Config::class, array("isStagingMode"));
        $oConfig->expects($this->at(0))->method("isStagingMode")->will($this->returnValue(false));
        $oConfig->expects($this->at(1))->method("isStagingMode")->will($this->returnValue(true));

        $oViewConfig = $this->getMock(\OxidEsales\Eshop\Core\ViewConfig::class, array('getConfig'));
        $oViewConfig->expects($this->any())->method('getConfig')->will($this->returnValue($oConfig));

        $this->assertFalse($oViewConfig->isStagingMode());
        $this->assertTrue($oViewConfig->isStagingMode());
    }

    /**
     * getHiddenSid method test case where session is null.
     */
    public function testGetHiddenSidFromSessionNull()
    {
        $sShopId = "tetsShopId";
        $sSid = "newSid";
        $sLang = "testLang";
        $sSidNew = $sSid . '
' . $sLang . '
<input type="hidden" name="shp" value="' . $sShopId . '" />';

        $oSession = $this->getMock(\OxidEsales\Eshop\Core\Session::class, array("hiddenSid"));
        $oSession->expects($this->once())->method("hiddenSid")->will($this->returnValue($sSid));

        $oLang = $this->getMock(\OxidEsales\Eshop\Core\Language::class, array("getFormLang"));
        $oLang->expects($this->once())->method("getFormLang")->will($this->returnValue($sLang));
        Registry::set(\OxidEsales\Eshop\Core\Language::class, $oLang);

        $oConf = $this->getMock(\OxidEsales\Eshop\Core\Config::class, array("mustAddShopIdToRequest", "getShopId"));
        $oConf->expects($this->once())->method("mustAddShopIdToRequest")->will($this->returnValue(true));
        $oConf->expects($this->once())->method("getShopId")->will($this->returnValue($sShopId));

        $oViewConf = $this->getMock(\OxidEsales\Eshop\Core\ViewConfig::class, array("getViewConfigParam", "getSession", "getConfig", "setViewConfigParam"));
        $oViewConf->expects($this->once())->method("getViewConfigParam")->with($this->equalTo("hiddensid"))->will($this->returnValue(null));
        $oViewConf->expects($this->once())->method("getSession")->will($this->returnValue($oSession));
        $oViewConf->expects($this->once())->method("getConfig")->will($this->returnValue($oConf));
        $oViewConf->expects($this->once())->method("setViewConfigParam")->with($this->equalTo("hiddensid"), $this->equalTo($sSidNew));

        $this->assertEquals($sSidNew, $oViewConf->getHiddenSid());
    }

    /**
     * getHiddenSid method test case where session is null and no language.
     */
    public function testGetHiddenSidFromSessionNullNotLang()
    {
        $sShopId = "tetsShopId";
        $sSid = "newSid";
        $sSidNew = $sSid . '
<input type="hidden" name="shp" value="' . $sShopId . '" />';

        $oSession = $this->getMock(\OxidEsales\Eshop\Core\Session::class, array("hiddenSid"));
        $oSession->expects($this->once())->method("hiddenSid")->will($this->returnValue($sSid));

        $oLang = $this->getMock(\OxidEsales\Eshop\Core\Language::class, array("getFormLang"));
        $oLang->expects($this->once())->method("getFormLang")->will($this->returnValue(false));
        Registry::set(\OxidEsales\Eshop\Core\Language::class, $oLang);

        $oConf = $this->getMock(\OxidEsales\Eshop\Core\Config::class, array("mustAddShopIdToRequest", "getShopId"));
        $oConf->expects($this->once())->method("mustAddShopIdToRequest")->will($this->returnValue(true));
        $oConf->expects($this->once())->method("getShopId")->will($this->returnValue($sShopId));

        $oViewConf = $this->getMock(\OxidEsales\Eshop\Core\ViewConfig::class, array("getViewConfigParam", "getSession", "getConfig", "setViewConfigParam"));
        $oViewConf->expects($this->once())->method("getViewConfigParam")->with($this->equalTo("hiddensid"))->will($this->returnValue(null));
        $oViewConf->expects($this->once())->method("getSession")->will($this->returnValue($oSession));
        $oViewConf->expects($this->once())->method("getConfig")->will($this->returnValue($oConf));
        $oViewConf->expects($this->once())->method("setViewConfigParam")->with($this->equalTo("hiddensid"), $this->equalTo($sSidNew));

        $this->assertEquals($sSidNew, $oViewConf->getHiddenSid());
    }

    /**
     * getSerial method test.
     */
    public function testGetSerial()
    {
        $sTest = "testSerial";

        $oViewConf = new ViewConfig();
        $oViewConf->setViewConfigParam('license', $sTest);

        $this->assertEquals($sTest, $oViewConf->getSerial());
    }

    /**
     * getSerial method test case if license param is null.
     */
    public function testGetSerialWhenNull()
    {
        $sTest = "testSerial";

        $oObj = new \stdClass();
        $oObj->sSerial = $sTest;

        $oConfig = $this->getMock(\OxidEsales\Eshop\Core\Config::class, array("getSerial"));
        $oConfig->expects($this->once())->method("getSerial")->will($this->returnValue($oObj));

        $oViewConf = $this->getMock(\OxidEsales\Eshop\Core\ViewConfig::class, array("getViewConfigParam", "getConfig", "setViewConfigParam"));
        $oViewConf->expects($this->once())->method("getViewConfigParam")->with($this->equalTo("license"))->will($this->returnValue(null));
        $oViewConf->expects($this->once())->method("getConfig")->will($this->returnValue($oConfig));
        $oViewConf->expects($this->once())->method("setViewConfigParam")->with($this->equalTo("license"), $this->equalTo($sTest));

        $this->assertEquals($sTest, $oViewConf->getSerial());
    }
}
