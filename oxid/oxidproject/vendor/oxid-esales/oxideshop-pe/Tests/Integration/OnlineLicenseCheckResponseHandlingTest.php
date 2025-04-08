<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopProfessional\Tests\Integration;

use \oxRegistry;
use \oxTestModules;

class OnlineLicenseCheckResponseHandlingTest extends \oxUnitTestCase
{
    public function testRequestHandlingWithNegativeResponse()
    {
        $config = \OxidEsales\Eshop\Core\Registry::getConfig();
        $config->setConfigParam('blShopStopped', false);
        $config->setConfigParam('sShopVar', '');

        $xml = '<?xml version="1.0" encoding="utf-8"?>'."\n";
        $xml .= '<olc>';
        $xml .=   '<code>1</code>';
        $xml .=   '<message>NACK</message>';
        $xml .= '</olc>'."\n";

        $curl = $this->getMock(\OxidEsales\Eshop\Core\Curl::class, array('execute'));
        $curl->expects($this->any())->method('execute')->will($this->returnValue($xml));
        /** @var oxCurl $curl */

        $emailBuilder = oxNew(\OxidEsales\Eshop\Core\OnlineServerEmailBuilder::class);
        $simpleXml = oxNew(\OxidEsales\Eshop\Core\SimpleXml::class);
        $licenseCaller = oxNew(\OxidEsales\Eshop\Core\OnlineLicenseCheckCaller::class, $curl, $emailBuilder, $simpleXml);

        $userCounter = oxNew(\OxidEsales\Eshop\Core\UserCounter::class);
        $licenseCheck = oxNew(\OxidEsales\Eshop\Core\OnlineLicenseCheck::class, $licenseCaller, $userCounter);

        $licenseCheck->validateShopSerials();

        $this->assertTrue($config->getConfigParam('blShopStopped'));
        $this->assertEquals('unlc', $config->getConfigParam('sShopVar'));
    }
}
