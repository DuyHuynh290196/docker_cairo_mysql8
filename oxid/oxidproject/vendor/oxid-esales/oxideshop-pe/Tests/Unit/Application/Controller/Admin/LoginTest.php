<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopProfessional\Tests\Unit\Application\Controller\Admin;


use OxidEsales\Eshop\Core\Registry;

class LoginTest extends \OxidEsales\TestingLibrary\UnitTestCase
{
    public function testGetShopValidationMessage_NoNoticeWhenGraceNotStartedAndSerialValid()
    {
        $oSerial = $this->getMock(\OxidEsales\Eshop\Core\Serial::class, array('isGracePeriodStarted', 'isShopValid'));
        $oSerial->expects($this->any())->method('isGracePeriodStarted')->will($this->returnValue(false));
        $oSerial->expects($this->any())->method('isShopValid')->will($this->returnValue(true));

        $oConfig = $this->getMock(\OxidEsales\Eshop\Core\Config::class, array('getSerial'));
        $oConfig->expects($this->any())->method('getSerial')->will($this->returnValue($oSerial));

        $oView = oxNew('Login');
        $oView->setConfig($oConfig);

        $this->assertEquals('', $oView->getShopValidationMessage());
    }

    public function testGetShopValidationMessage_NoNoticeWhenGraceStartedAndSerialValid()
    {
        $shopStub = $this->getMockBuilder(\OxidEsales\Eshop\Application\Model\Shop::class)
            ->disableOriginalConstructor()
            ->getMock();

        $serialStub = $this->getMockBuilder(\OxidEsales\Eshop\Core\Serial::class)->getMock();
        $serialStub->method('isGracePeriodStarted')->will($this->returnValue(true));
        $serialStub->method('isShopValid')->will($this->returnValue(true));

        $configStub = $this->getMockBuilder(\OxidEsales\Eshop\Core\Config::class)->getMock();

        $configStub->method('getSerial')->willReturn($serialStub);
        $configStub->method('getActiveShop')->willReturn($shopStub);
        $configStub->method('getConfigParam')
            ->with('aLanguageParams')
            ->willReturn(
                [
                    'de' =>
                        [
                            'baseId' => 0,
                            'active' => '1',
                            'sort'   => '1',
                        ],
                    'en' =>
                        [
                            'baseId' => 1,
                            'active' => '1',
                            'sort'   => '2',
                        ]
                ]
            );

        $oView = oxNew('Login');
        $oView->setConfig($configStub);

        Registry::set(\OxidEsales\Eshop\Core\Config::class, $configStub);

        $this->assertEquals('', $oView->getShopValidationMessage());
    }

    public function testGetShopValidationMessage_NoticeWhenGraceStartedSerialInvalid()
    {
        $oSerial = $this->getMock(\OxidEsales\Eshop\Core\Serial::class, array('isGracePeriodStarted', 'isGracePeriodExpired', 'isShopValid', 'getValidationMessage'));
        $oSerial->expects($this->any())->method('isGracePeriodStarted')->will($this->returnValue(true));
        $oSerial->expects($this->any())->method('isGracePeriodExpired')->will($this->returnValue(false));
        $oSerial->expects($this->any())->method('isShopValid')->will($this->returnValue(false));
        $oSerial->expects($this->any())->method('getValidationMessage')->will($this->returnValue('validation_message'));

        $oConfig = $this->getMock(\OxidEsales\Eshop\Core\Config::class, array('getSerial'));
        $oConfig->expects($this->any())->method('getSerial')->will($this->returnValue($oSerial));
        $this->getConfig()->setConfigParam('blShopStopped', false);

        $oView = oxNew('Login');
        $oView->setConfig($oConfig);

        $this->assertEquals('validation_message', $oView->getShopValidationMessage());
    }

    public function testIsGracePeriodExpired_NotExpired()
    {
        $oSerial = $this->getMock(\OxidEsales\Eshop\Core\Serial::class, array('isGracePeriodExpired'));
        $oSerial->expects($this->any())->method('isGracePeriodExpired')->will($this->returnValue(true));

        $oConfig = $this->getMock(\OxidEsales\Eshop\Core\Config::class, array('getSerial'));
        $oConfig->expects($this->any())->method('getSerial')->will($this->returnValue($oSerial));

        $oView = oxNew('Login');
        $oView->setConfig($oConfig);

        $this->assertEquals(true, $oView->isGracePeriodExpired());
    }

    public function testIsGracePeriodExpired_Expired()
    {
        $oSerial = $this->getMock(\OxidEsales\Eshop\Core\Serial::class, array('isGracePeriodExpired'));
        $oSerial->expects($this->any())->method('isGracePeriodExpired')->will($this->returnValue(false));

        $oConfig = $this->getMock(\OxidEsales\Eshop\Core\Config::class, array('getSerial'));
        $oConfig->expects($this->any())->method('getSerial')->will($this->returnValue($oSerial));

        $oView = oxNew('Login');
        $oView->setConfig($oConfig);

        $this->assertEquals(false, $oView->isGracePeriodExpired());
    }

    /**
     * When serial is correct, do nothing
     */
    public function testGetShopValidationMessage_GraceResetWhenGraceStartedAndShopValid()
    {
        $oConfig = $this->getMock(\OxidEsales\Eshop\Core\Config::class, array('getSerial'));
        $oConfig->init();
        $oConfig->setConfigParam('sBackTag', time());

        $oSerial = $this->getMock(\OxidEsales\Eshop\Core\Serial::class, array('isShopValid', 'getConfig'));
        $oSerial->expects($this->any())->method('isShopValid')->will($this->returnValue(true));
        $oSerial->expects($this->any())->method('getConfig')->will($this->returnValue($oConfig));

        $oConfig->expects($this->any())->method('getSerial')->will($this->returnValue($oSerial));

        $oView = oxNew('Login');
        $oView->setConfig($oConfig);

        $oView->getShopValidationMessage();

        $this->assertEquals('', $oConfig->getConfigParam('sBackTag'));
    }

    /**
     * When serial is correct, do nothing
     */
    public function testGetShopValidationMessage_GraceNotChangedWhenGraceStartedAndShopNotValid()
    {
        $sTime = time();

        $oConfig = $this->getMock(\OxidEsales\Eshop\Core\Config::class, array('getSerial'));
        $oConfig->init();
        $oConfig->setConfigParam('sBackTag', $sTime);

        $oSerial = $this->getMock(\OxidEsales\Eshop\Core\Serial::class, array('isShopValid', 'getConfig'));
        $oSerial->expects($this->any())->method('isShopValid')->will($this->returnValue(false));
        $oSerial->expects($this->any())->method('getConfig')->will($this->returnValue($oConfig));

        $oConfig->expects($this->any())->method('getSerial')->will($this->returnValue($oSerial));

        $oView = oxNew('Login');
        $oView->setConfig($oConfig);

        $oView->getShopValidationMessage();

        $this->assertEquals($sTime, $oConfig->getConfigParam('sBackTag'));
    }
}
