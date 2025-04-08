<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopProfessional\Tests\Unit\Core;

require_once TEST_LIBRARY_HELPERS_PATH . 'oxUtilsHelper.php';

use OxidEsales\Eshop\Core\Registry;
use OxidEsales\EshopProfessional\Core\SystemEventHandler;
use \oxRegistry;
use \oxOnlineLicenseCheck;
use \oxUtilsHelper;
use \oxUtilsDate;

/**
 * @covers SystemEventHandler
 */
class SystemEventHandlerTest extends \oxUnitTestCase
{
    /**
     * @return null|void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->getConfig()->saveShopConfVar('str', 'sOnlineLicenseNextCheckTime', null);
        $this->getConfig()->saveShopConfVar('str', 'sOnlineLicenseCheckTime', null);
        $this->getConfig()->setConfigParam('blSendTechnicalInformationToOxid', true);
    }

    public function testShopInformationSendingWhenSendingIsNotAllowedInEnterpriseEdition()
    {
        $this->_prepareCurrentTime(1400000000);
        $this->getConfig()->setConfigParam('blSendTechnicalInformationToOxid', false);

        $oSystemEventHandler = new SystemEventHandler();

        $oOnlineLicenseCheck = $this->getMock(\OxidEsales\Eshop\Core\OnlineLicenseCheck::class, array(), array(), '', false);
        $oOnlineLicenseCheck->expects($this->once())->method("validateShopSerials");

        /** @var oxOnlineLicenseCheck $oOnlineLicenseCheck */
        $oSystemEventHandler->setOnlineLicenseCheck($oOnlineLicenseCheck);

        $oSystemEventHandler->onShopEnd();
    }

    public function testAppInitUnlicensed()
    {
        oxUtilsHelper::$sRedirectUrl = null;

        oxAddClassModule("oxUtilsHelper", "oxutils");
        $this->setConfigParam('redirected', 1);

        $oSerial = $this->getMock(\OxidEsales\Eshop\Core\Serial::class);
        $oSerial->expects($this->any())->method('validateShop')->will($this->returnValue(false));
        /** @var oxSerial $oSerial */

        $oConfig = $this->getMock(\OxidEsales\Eshop\Core\Config::class);
        $oConfig->expects($this->once())->method('getSerial')->will($this->returnValue($oSerial));

        $oSystemEventHandler = $this->getMock('\OxidEsales\EshopProfessional\Core\SystemEventHandler', array("getConfig", 'sendShopInformation'));
        $oSystemEventHandler->expects($this->any())->method('getConfig')->will($this->returnValue($oConfig));
        $oSystemEventHandler->expects($this->any())->method('sendShopInformation');

        $oUtils = $this->getMock(\OxidEsales\Eshop\Core\Utils::class, array('showOfflinePage'));
        $oUtils->expects($this->once())->method('showOfflinePage');
        Registry::set(\OxidEsales\Eshop\Core\Utils::class, $oUtils);

        /** @var SystemEventHandler $oSystemEventHandler */
        $oSystemEventHandler->onShopStart();
    }

    public function testAppInitLicensed()
    {
        oxUtilsHelper::$sRedirectUrl = null;

        oxAddClassModule("oxUtilsHelper", "oxutils");
        $this->setConfigParam('redirected', 1);

        $oSerial = $this->getMock(\OxidEsales\Eshop\Core\Serial::class);
        $oSerial->expects($this->any())->method('validateShop')->will($this->returnValue(true));
        /** @var oxSerial $oSerial */

        $oConfig = $this->getMock(\OxidEsales\Eshop\Core\Config::class);
        $oConfig->expects($this->once())->method('getSerial')->will($this->returnValue($oSerial));

        $oSystemEventHandler = $this->getMock('\OxidEsales\EshopProfessional\Core\SystemEventHandler', array("getConfig", 'sendShopInformation'));
        $oSystemEventHandler->expects($this->any())->method('getConfig')->will($this->returnValue($oConfig));
        $oSystemEventHandler->expects($this->any())->method('sendShopInformation');
        /** @var SystemEventHandler $oSystemEventHandler */
        $oSystemEventHandler->onShopStart();

        $this->assertNull(oxUtilsHelper::$sRedirectUrl);
    }

    public function testOnAdminLoginSendModuleInformationEvenWhenNotConfigured()
    {
        $this->getConfig()->setConfigParam('blSendTechnicalInformationToOxid', false);

        $systemEventHandler = oxNew(\OxidEsales\Eshop\Core\SystemEventHandler::class);

        $moduleNotifierMock = $this->getMockBuilder(\OxidEsales\Eshop\Core\OnlineModuleVersionNotifier::class)
            ->disableOriginalConstructor()
            ->getMock();
        $moduleNotifierMock->expects($this->once())->method("versionNotify");

        /** @var \OxidEsales\Eshop\Core\OnlineModuleVersionNotifier $moduleNotifier */
        $moduleNotifier = $moduleNotifierMock;
        $systemEventHandler->setOnlineModuleVersionNotifier($moduleNotifier);

        $systemEventHandler->onAdminLogin(1);
    }

    /**
     * @param int $iCurrentTime
     */
    private function _prepareCurrentTime($iCurrentTime) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $utilsDate = $this->getMock(\OxidEsales\Eshop\Core\UtilsDate::class, array('getTime'));
        $utilsDate->expects($this->any())->method('getTime')->will($this->returnValue($iCurrentTime));
        /** @var oxUtilsDate $oUtils */
        \OxidEsales\Eshop\Core\Registry::set(\OxidEsales\Eshop\Core\UtilsDate::class, $utilsDate);
    }
}
