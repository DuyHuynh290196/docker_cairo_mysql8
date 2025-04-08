<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Tests\Unit\Core;

use Exception;
use OxidEsales\Eshop\Core\Theme;
use OxidEsales\EshopCommunity\Internal\Container\ContainerFactory;
use OxidEsales\EshopEnterprise\Core\Cache\DynamicContent\ContentCache;
use oxOutput;
use oxTestModules;

class ShopControlTest extends \oxUnitTestCase
{
    /**
     * @inheritdoc
     */
    protected function setUp(): void
    {
        parent::setUp();

        $theme = oxNew(Theme::class);
        $theme->load('azure');
        $theme->activate();
    }

    /**
     * Testing \OxidEsales\Eshop\Core\ShopControl::start()
     *
     * @return null
     */
    public function testStartAccessRightException_withoutDebugMode_redirectsButNoExceptionShown()
    {
        $lackOfRightException = $this->getMock(\OxidEsales\Eshop\Core\Exception\SystemComponentException::class, ['debugOut']);
        $lackOfRightException->expects($this->atLeastOnce())->method('debugOut');

        $this->setRequestParameter('cl', null);
        $this->setRequestParameter('fnc', "testFnc");
        $this->getSession()->setVariable('actshop', null);

        $oxUtilsView = $this->getMock(\OxidEsales\Eshop\Core\UtilsView::class, array("addErrorToDisplay"));
        $oxUtilsView->expects($this->never())->method("addErrorToDisplay")->with($lackOfRightException);
        oxTestModules::addModuleObject('oxUtilsView', $oxUtilsView);

        $oxUtils = $this->getMock(\OxidEsales\Eshop\Core\Utils::class, array("redirect"));
        $oxUtils->expects($this->atLeastOnce())->method("redirect");
        oxTestModules::addModuleObject('oxUtils', $oxUtils);

        $oConfig = $this->getMock(\OxidEsales\Eshop\Core\Config::class, array("isMall", "getConfigParam", "getShopId", "getShopHomeUrl"));
        $oConfig->expects($this->at(0))->method('isMall')->will($this->returnValue(true));
        $oConfig->expects($this->at(1))->method('getShopId')->will($this->returnValue(999));
        $oConfig->expects($this->at(2))->method('getShopHomeUrl');

        $oControl = $this->getMock(\OxidEsales\Eshop\Core\ShopControl::class, array("getConfig", "_runOnce", "isAdmin", "_process", '_isDebugMode'), array(), '', false);
        $oControl->expects($this->atLeastOnce())->method('getConfig')->will($this->returnValue($oConfig));
        $oControl->expects($this->once())->method('_runOnce');
        $oControl->expects($this->atLeastOnce())->method('isAdmin')->will($this->returnValue(false));
        $oControl->expects($this->once())->method('_process')->with($this->equalTo(\OxidEsales\Eshop\Application\Controller\StartController::class), $this->equalTo("testFnc"))->will($this->throwException($lackOfRightException));
        $oControl->expects($this->any())->method('_isDebugMode')->will($this->returnValue(false));

        $oControl->start();
    }

    /**
     * Testing \OxidEsales\Eshop\Core\ShopControl::_process()
     */
    public function testProcess()
    {
        ContainerFactory::resetContainer();
        $controllerClassName = 'content';

        oxTestModules::addFunction('oxCache', 'isViewCacheable', '{ return true; }');
        oxTestModules::addFunction('oxCache', 'get', '{ return false; }');
        oxTestModules::addFunction('oxUtils', 'isSearchEngine', '{ return false; }');
        oxTestModules::addFunction('oxUtils', 'setHeader', '{}');

        $header = $this->getMockBuilder(\OxidEsales\Eshop\Core\Header::class)
            ->setMethods(['sendHeader'])
            ->getMock();
        \OxidEsales\Eshop\Core\Registry::set(\OxidEsales\Eshop\Core\Header::class, $header);

        $sTplPath = $this->getConfig()->getConfigParam('sShopDir') . "/Application/views/";
        $sTplPath .= $this->getConfig()->getConfigParam('sTheme') . "/tpl/page/checkout/basket.tpl";

        $oConfig = $this->getMock(\OxidEsales\Eshop\Core\Config::class, array("getTemplatePath", "getConfigParam", "pageClose"));
        $map = [
            ['blLogging', null, true],
            ['blUseContentCaching', null, true]
        ];
        $oConfig->expects($this->any())->method('getConfigParam')->will($this->returnValueMap($map));
        $oConfig->expects($this->any())->method('getTemplatePath')->will($this->returnValue($sTplPath));

        $aTasks = array("isAdmin", "_log", "_startMonitor", "getConfig", "_stopMonitor", '_getOutputManager', '_executeMaintenanceTasks', 'sendAdditionalHeaders');

        $oRights = $this->getMock(\OxidEsales\Eshop\Application\Model\Rights::class, array("processView"));
        $oRights->expects($this->once())->method('processView');
        $aTasks[] = "getRights";

        $oOut = $this->getMock(\OxidEsales\Eshop\Core\Output::class, array('output', 'flushOutput', 'sendHeaders'));
        $oOut->expects($this->once())->method('output')->with($this->equalTo($controllerClassName));
        $oOut->expects($this->once())->method('flushOutput')->will($this->returnValue(null));
        $oOut->expects($this->once())->method('sendHeaders')->will($this->returnValue(null));

        $oSmarty = $this->getSmartyMock($this->getTemplateName($controllerClassName));

        $oUtilsView = $this->getMock(\OxidEsales\Eshop\Core\UtilsView::class, array('getSmarty'));
        $oUtilsView->expects($this->any())->method('getSmarty')->will($this->returnValue($oSmarty));
        oxTestModules::addModuleObject('oxUtilsView', $oUtilsView);

        $oControl = $this->getMock(\OxidEsales\Eshop\Core\ShopControl::class, $aTasks, array(), '', false);
        $oControl->expects($this->any())->method('getConfig')->will($this->returnValue($oConfig));
        $oControl->expects($this->any())->method('isAdmin')->will($this->returnValue(false));
        $oControl->expects($this->any())->method('_getOutputManager')->will($this->returnValue($oOut));
        $oControl->expects($this->atLeastOnce())->method('_executeMaintenanceTasks');

        $oControl->expects($this->once())->method('getRights')->will($this->returnValue($oRights));

        $oControl->UNITprocess($controllerClassName, null);
    }

    public function testProcessJson()
    {
        ContainerFactory::resetContainer();
        $controllerClassName = 'content';

        oxTestModules::addFunction('oxcache', 'isViewCacheable', '{ return true; }');
        oxTestModules::addFunction('oxcache', 'get', '{ return false; }');
        oxTestModules::addFunction('oxUtils', 'isSearchEngine', '{ return false; }');
        oxTestModules::addFunction('oxUtils', 'setHeader', '{}');

        $header = $this->getMockBuilder(\OxidEsales\Eshop\Core\Header::class)
            ->setMethods(['sendHeader'])
            ->getMock();
        \OxidEsales\Eshop\Core\Registry::set(\OxidEsales\Eshop\Core\Header::class, $header);

        $this->setRequestParameter('renderPartial', 'asd');

        $sTplPath = $this->getConfig()->getConfigParam('sShopDir') . "/Application/views/";
        $sTplPath .= $this->getConfig()->getConfigParam('sTheme') . "/tpl/page/checkout/basket.tpl";

        $oConfig = $this->getMock(\OxidEsales\Eshop\Core\Config::class, array("getTemplatePath", "getConfigParam", "pageClose"));
        $map = [
            ['blLogging', null, true],
            ['blUseContentCaching', null, true]
        ];
        $oConfig->expects($this->any())->method('getConfigParam')->will($this->returnValueMap($map));
        $oConfig->expects($this->any())->method('getTemplatePath')->will($this->returnValue($sTplPath));

        $aTasks = array("isAdmin", "_log", "_startMonitor", "getConfig", "_stopMonitor", '_getOutputManager', '_getErrors', '_executeMaintenanceTasks', 'sendAdditionalHeaders');

        $oRights = $this->getMock(\OxidEsales\Eshop\Application\Model\Rights::class, array("processView"));
        $oRights->expects($this->once())->method('processView');
        $aTasks[] = "getRights";

        $oOut = $this->getMock(\OxidEsales\Eshop\Core\Output::class, array('output', 'flushOutput', 'sendHeaders', 'setOutputFormat'));
        $oOut->expects($this->at(0))->method('setOutputFormat')->with($this->equalTo(\OxidEsales\Eshop\Core\Output::OUTPUT_FORMAT_JSON));
        $oOut->expects($this->at(1))->method('sendHeaders')->will($this->returnValue(null));
        $oOut->expects($this->at(3))->method('output')->with($this->equalTo($controllerClassName), $this->anything());
        $oOut->expects($this->at(4))->method('flushOutput')->will($this->returnValue(null));

        $oSmarty = $this->getSmartyMock($this->getTemplateName($controllerClassName));

        $oUtilsView = $this->getMock(\OxidEsales\Eshop\Core\UtilsView::class, array('getSmarty'));
        $oUtilsView->expects($this->any())->method('getSmarty')->will($this->returnValue($oSmarty));
        oxTestModules::addModuleObject('oxUtilsView', $oUtilsView);

        $oControl = $this->getMock(\OxidEsales\Eshop\Core\ShopControl::class, $aTasks, array(), '', false);
        $oControl->expects($this->any())->method('getConfig')->will($this->returnValue($oConfig));
        $oControl->expects($this->any())->method('isAdmin')->will($this->returnValue(false));
        $oControl->expects($this->any())->method('_getOutputManager')->will($this->returnValue($oOut));
        $oControl->expects($this->any())->method('_getErrors')->will($this->returnValue(array()));
        $oControl->expects($this->atLeastOnce())->method('_executeMaintenanceTasks');
        $oControl->expects($this->once())->method('getRights')->will($this->returnValue($oRights));

        $oControl->UNITprocess($controllerClassName, null);
    }

    public function testProcessJsonWithErrors()
    {
        ContainerFactory::resetContainer();
        $controllerClassName = 'content';

        oxTestModules::addFunction('oxcache', 'isViewCacheable', '{ return true; }');
        oxTestModules::addFunction('oxcache', 'get', '{ return false; }');
        oxTestModules::addFunction('oxUtils', 'isSearchEngine', '{ return false; }');
        oxTestModules::addFunction('oxUtils', 'setHeader', '{}');

        $header = $this->getMockBuilder(\OxidEsales\Eshop\Core\Header::class)
            ->setMethods(['sendHeader'])
            ->getMock();
        \OxidEsales\Eshop\Core\Registry::set(\OxidEsales\Eshop\Core\Header::class, $header);

        $this->setRequestParameter('renderPartial', 'asd');

        $sTplPath = $this->getConfig()->getConfigParam('sShopDir') . "/Application/views/";
        $sTplPath .= $this->getConfig()->getConfigParam('sTheme') . "/tpl/page/checkout/basket.tpl";

        $oConfig = $this->getMock(\OxidEsales\Eshop\Core\Config::class, array("getTemplatePath", "getConfigParam", "pageClose"));
        $map = [
            ['blLogging', null, true],
            ['blUseContentCaching', null, true]
        ];
        $oConfig->expects($this->any())->method('getConfigParam')->will($this->returnValueMap($map));
        $oConfig->expects($this->any())->method('getTemplatePath')->will($this->returnValue($sTplPath));

        $aTasks = array("isAdmin", "_log", "_startMonitor", "getConfig", "_stopMonitor", '_getOutputManager', '_getErrors', '_executeMaintenanceTasks', 'sendAdditionalHeaders');
        $oRights = $this->getMock(\OxidEsales\Eshop\Application\Model\Rights::class, array("processView"));
        $oRights->expects($this->once())->method('processView');
        $aTasks[] = "getRights";

        $oOut = $this->getMock(\OxidEsales\Eshop\Core\Output::class, array('output', 'flushOutput', 'sendHeaders', 'setOutputFormat'));
        $oOut->expects($this->at(0))->method('setOutputFormat')->with($this->equalTo(\OxidEsales\Eshop\Core\Output::OUTPUT_FORMAT_JSON));
        $oOut->expects($this->at(1))->method('output')->with(
            $this->equalTo('errors'),
            $this->equalTo(
                array(
                    'other' => array('test1', 'test3'),
                    'default' => array('test2', 'test4'),
                )
            )
        );
        $oOut->expects($this->at(2))->method('sendHeaders')->will($this->returnValue(null));
        $oOut->expects($this->at(3))->method('output')->with($this->equalTo($controllerClassName), $this->anything());
        $oOut->expects($this->at(4))->method('flushOutput')->will($this->returnValue(null));

        $oSmarty = $this->getSmartyMock($this->getTemplateName($controllerClassName));

        $oUtilsView = $this->getMock(\OxidEsales\Eshop\Core\UtilsView::class, array('getSmarty'));
        $oUtilsView->expects($this->any())->method('getSmarty')->will($this->returnValue($oSmarty));
        oxTestModules::addModuleObject('oxUtilsView', $oUtilsView);

        $oControl = $this->getMock(\OxidEsales\Eshop\Core\ShopControl::class, $aTasks, array(), '', false);
        $oControl->expects($this->any())->method('getConfig')->will($this->returnValue($oConfig));
        $oControl->expects($this->any())->method('isAdmin')->will($this->returnValue(false));
        $oControl->expects($this->any())->method('_getOutputManager')->will($this->returnValue($oOut));
        $oControl->expects($this->atLeastOnce())->method('_executeMaintenanceTasks');
        $aErrors = array();
        $oDE = oxNew(\OxidEsales\Eshop\Core\DisplayError::class);
        $oDE->setMessage('test1');
        $aErrors['other'][] = serialize($oDE);
        $oDE->setMessage('test2');
        $aErrors['default'][] = serialize($oDE);
        $oDE->setMessage('test3');
        $aErrors['other'][] = serialize($oDE);
        $oDE->setMessage('test4');
        $aErrors['default'][] = serialize($oDE);

        $oControl->expects($this->any())->method('_getErrors')->will($this->returnValue($aErrors));
        $oControl->expects($this->once())->method('getRights')->will($this->returnValue($oRights));

        $oControl->UNITprocess($controllerClassName, null);
    }

    /**
     * Testing \OxidEsales\Eshop\Core\ShopControl::_process()
     */
    public function testProcessCanCacheIsChecked()
    {
        oxTestModules::addFunction('oxcache', 'isViewCacheable', '{ return true; }');
        oxTestModules::addFunction('content', 'canCache', '{ return true; }');
        oxTestModules::addFunction('content', 'setIsCallForCache', '{ throw new Exception("setIsCallForCache"); }');
        oxTestModules::addFunction('content', 'init', '{ throw new Exception("init"); }');

        oxTestModules::addFunction('oxUtils', 'isSearchEngine', '{ return false; }');
        oxTestModules::addFunction('oxUtils', 'setHeader', '{}');

        $sTplPath = $this->getConfig()->getConfigParam('sShopDir') . "/Application/views/";
        $sTplPath .= $this->getConfig()->getConfigParam('sTheme') . "/tpl/help.tpl";

        $oConfig = $this->getMock(\OxidEsales\Eshop\Core\Config::class, array("getTemplatePath", "getConfigParam", "pageClose"));
        $map = [
            ['blLogging', null, true],
            ['blUseContentCaching', null, true]
        ];
        $oConfig->expects($this->any())->method('getConfigParam')->will($this->returnValueMap($map));
        $oConfig->expects($this->any())->method('getTemplatePath')->will($this->returnValue($sTplPath));

        $aTasks = array("isAdmin", "_log", "_startMonitor", "getConfig", "_stopMonitor", '_executeMaintenanceTasks');
        $oRights = $this->getMock(\OxidEsales\Eshop\Application\Model\Rights::class, array("processView"));
        $oRights->expects($this->any())->method('processView');
        $aTasks[] = "getRights";

        $oControl = $this->getMock(\OxidEsales\Eshop\Core\ShopControl::class, $aTasks, array(), '', false);
        $oControl->expects($this->any())->method('getConfig')->will($this->returnValue($oConfig));
        $oControl->expects($this->any())->method('isAdmin')->will($this->returnValue(false));
        $oControl->expects($this->any())->method('getRights')->will($this->returnValue($oRights));
        $oControl->expects($this->atLeastOnce())->method('_executeMaintenanceTasks');

        $this->expectException('Exception'); $this->expectExceptionMessage( 'setIsCallForCache');
        $oControl->UNITprocess("content", null);
    }

    /**
     * Testing \OxidEsales\Eshop\Core\ShopControl::_process()
     */
    public function testProcessDoNotCacheOnError()
    {
        oxTestModules::addFunction('oxcache', 'isViewCacheable', '{ return true; }');
        oxTestModules::addFunction('content', 'canCache', '{ return true; }');
        oxTestModules::addFunction('content', 'getIsCallForCache', '{ return true; }');
        // Method setIsCallForCache() must be called with false.
        oxTestModules::addFunction('content', 'setIsCallForCache($blCache)', '{ if(!$blCache) { throw new Exception("setIsCallForCache"); } }');
        oxTestModules::addFunction('content', 'initCacheableComponents', '{ throw new Exception("initCacheableComponents"); }');
        oxTestModules::addFunction('content', 'render', '{ throw new Exception("render"); }');
        oxTestModules::addFunction('content', 'init', '{ $this->getSession()->setVariable("Errors", array("default"=>"test")); }');

        oxTestModules::addFunction('oxUtils', 'isSearchEngine', '{ return false; }');
        oxTestModules::addFunction('oxUtils', 'setHeader', '{}');

        $sTplPath = $this->getConfig()->getConfigParam('sShopDir') . "/Application/views/";
        $sTplPath .= $this->getConfig()->getConfigParam('sTheme') . "/tpl/help.tpl";

        $oConfig = $this->getMock(\OxidEsales\Eshop\Core\Config::class, array("getTemplatePath", "getConfigParam", "pageClose"));
        $map = [
            ['blLogging', null, true],
            ['blUseContentCaching', null, true]
        ];
        $oConfig->expects($this->any())->method('getConfigParam')->will($this->returnValueMap($map));
        $oConfig->expects($this->any())->method('getTemplatePath')->will($this->returnValue($sTplPath));

        $aTasks = array("isAdmin", "_log", "_startMonitor", "getConfig", "_stopMonitor", '_executeMaintenanceTasks');
        $oRights = $this->getMock(\OxidEsales\Eshop\Application\Model\Rights::class, array("processView"));
        $oRights->expects($this->any())->method('processView');
        $aTasks[] = "getRights";

        $oControl = $this->getMock(\OxidEsales\Eshop\Core\ShopControl::class, $aTasks, array(), '', false);
        $oControl->expects($this->any())->method('getConfig')->will($this->returnValue($oConfig));
        $oControl->expects($this->any())->method('isAdmin')->will($this->returnValue(false));
        $oControl->expects($this->any())->method('getRights')->will($this->returnValue($oRights));
        $oControl->expects($this->atLeastOnce())->method('_executeMaintenanceTasks');

        try {
            $oControl->UNITprocess("content", null);
            $this->fail("");
        } catch (Exception $e) {
            // cachable
            $this->assertEquals('setIsCallForCache', $e->getMessage(), $e->getTraceAsString());
        }
    }

    public function testGetCacheManager()
    {
        $oControl = oxNew(\OxidEsales\Eshop\Core\ShopControl::class);
        $oOut = $oControl->UNITgetCacheManager();
        $this->assertTrue($oOut instanceof ContentCache);
        $oOut1 = $oControl->UNITgetCacheManager();
        $this->assertSame($oOut, $oOut1);
    }

    public function testSetGetAllowCacheInvalidating()
    {
        $oControl = oxNew(\OxidEsales\Eshop\Core\ShopControl::class);

        $this->assertEquals(true, $oControl->getAllowCacheInvalidating());

        $oControl->setAllowCacheInvalidating(false);
        $this->assertEquals(false, $oControl->getAllowCacheInvalidating());

        $oControl->setAllowCacheInvalidating(true);
        $this->assertEquals(true, $oControl->getAllowCacheInvalidating());
    }

    /**
     * Testing \OxidEsales\Eshop\Core\ShopControl::start()
     */
    public function testStart()
    {
        $this->setRequestParameter('cl', null);
        $this->setRequestParameter('fnc', "testFnc");

        $map = array(
            array('sMallShopURL', null, false),
            array('iMallMode', null, 1),
        );
        $config = $this->getMock(\OxidEsales\Eshop\Core\Config::class, array("isMall", "getConfigParam"));
        $config->expects($this->any())->method('isMall')->will($this->returnValue(true));
        $config->expects($this->any())->method('getConfigParam')->will($this->returnValueMap($map));

        $control = $this->getMock(\OxidEsales\Eshop\Core\ShopControl::class, array("getConfig", "_runOnce", "isAdmin", "_process", 'fetchActiveShopCount'), array(), '', false);
        $control->expects($this->any())->method('getConfig')->will($this->returnValue($config));
        $control->expects($this->any())->method('isAdmin')->will($this->returnValue(false));
        $control->expects($this->any())->method('fetchActiveShopCount')->will($this->returnValue(2));
        $control->expects($this->once())->method('_process')->with($this->equalTo(\OxidEsales\Eshop\Application\Controller\MallStartController::class), $this->equalTo("testFnc"));

        $control->start();
    }

    /**
     * Check that fetch method returns expected template name.
     * Could be useful as an integrational test to test that template from controller is set to Smarty
     *
     * @param $expectedTemplate
     *
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    private function getSmartyMock($expectedTemplate)
    {
        $oSmarty = $this->getMock("Smarty", array('fetch'));
        $oSmarty
            ->expects($this->once())
            ->method('fetch')
            ->with($this->equalTo($expectedTemplate))
            ->will($this->returnValue(''));

        return $oSmarty;
    }

    /**
     * Get name of active template for controller.
     * Run render() method as it might change the name.
     *
     * @param $controllerClassName
     *
     * @return string
     */
    private function getTemplateName($controllerClassName)
    {
        $control = oxNew($controllerClassName);
        $control->render();

        return $control->getTemplateName();
    }
}
