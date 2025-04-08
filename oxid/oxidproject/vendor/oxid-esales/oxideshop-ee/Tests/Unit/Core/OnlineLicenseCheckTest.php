<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Tests\Unit\Core;

use OxidEsales\EshopEnterprise\Core\OnlineLicenseCheck;

class OnlineLicenseCheckTest extends \oxUnitTestCase
{
    public function testShopGoesToGracePeriodWhenShopSerialInvalid()
    {
        $config = $this->getConfig();
        $config->setConfigParam('blShopStopped', false);
        $config->setConfigParam('sShopVar', '');

        $response = oxNew(\OxidEsales\Eshop\Core\OnlineLicenseCheckResponse::class);
        $response->code = '1';
        $response->message = 'NACK';

        $caller = $this->getMock(\OxidEsales\Eshop\Core\OnlineLicenseCheckCaller::class, array('doRequest'), array(), '', false);
        $caller->expects($this->once())->method('doRequest')->will($this->returnValue($response));
        /** @var \OxidEsales\Eshop\Core\OnlineLicenseCheckCaller $caller */

        $licenseCheck = oxNew(\OxidEsales\Eshop\Core\OnlineLicenseCheck::class, $caller);
        $licenseCheck->validateShopSerials();

        $this->assertTrue($this->getConfig()->getConfigParam('blShopStopped'));
        $this->assertEquals('unlc', $this->getConfig()->getConfigParam('sShopVar'));
    }

    public function testShopDoesNotGoToGracePeriodWhenLicensingServerIsDown()
    {
        $config = $this->getConfig();
        $config->setConfigParam('blShopStopped', false);
        $config->setConfigParam('sShopVar', '');

        $exception = oxNew(\OxidEsales\Eshop\Core\Exception\StandardException::class);

        $caller = $this->getMock(\OxidEsales\Eshop\Core\OnlineLicenseCheckCaller::class, array('doRequest'), array(), '', false);
        $caller->expects($this->once())->method('doRequest')->will($this->throwException($exception));
        /** @var \OxidEsales\Eshop\Core\OnlineLicenseCheckCaller $caller */

        $licenseCheck = oxNew(\OxidEsales\Eshop\Core\OnlineLicenseCheck::class, $caller);
        $licenseCheck->validateShopSerials();

        $this->assertFalse($config->getConfigParam('blShopStopped'));
        $this->assertEquals('', $config->getConfigParam('sShopVar'));
    }

    public function testShopDoesNotGoToGracePeriodWhenSerialValid()
    {
        $config = $this->getConfig();
        $config->setConfigParam('blShopStopped', false);
        $config->setConfigParam('sShopVar', '');

        $response = oxNew(\OxidEsales\Eshop\Core\OnlineLicenseCheckResponse::class);
        $response->code = '0';
        $response->message = 'ACK';

        $caller = $this->getMock(\OxidEsales\Eshop\Core\OnlineLicenseCheckCaller::class, array('doRequest'), array(), '', false);
        $caller->expects($this->once())->method('doRequest')->will($this->returnValue($response));
        /** @var \OxidEsales\Eshop\Core\OnlineLicenseCheckCaller $caller */

        $licenseCheck = oxNew(\OxidEsales\Eshop\Core\OnlineLicenseCheck::class, $caller);
        $licenseCheck->validateShopSerials();

        $this->assertFalse($this->getConfig()->getConfigParam('blShopStopped'));
        $this->assertEquals('', $this->getConfig()->getConfigParam('sShopVar'));
    }
}
