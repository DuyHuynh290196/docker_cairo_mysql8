<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopProfessional\Tests\Unit\Core;

use OxidEsales\EshopEnterprise\Core\Config;
use \oxTestModules;
use \oxDb;
use \oxRegistry;

class ConfigTest extends \oxUnitTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->getConfig()->sTheme = false;

        $this->_iCurr = $this->getSession()->getVariable('currency');
    }

    /**
     * Tear down the fixture.
     *
     * @return null
     */
    protected function tearDown(): void
    {
        \OxidEsales\Eshop\Core\Registry::getLang()->setBaseLanguage(1);

        // cleaning up
        $sQ = 'delete from oxconfig where oxvarname = "xxx" ';
        oxDb::getDb()->execute($sQ);

        $sDir = $this->getConfig()->getConfigParam('sShopDir') . "/out/2";
        if (is_dir(realpath($sDir))) {
            \OxidEsales\Eshop\Core\Registry::getUtilsFile()->deleteDir($sDir);
        }
        $sDir = $this->getConfig()->getConfigParam('sShopDir') . "/out/en/tpl";
        if (is_dir(realpath($sDir))) {
            \OxidEsales\Eshop\Core\Registry::getUtilsFile()->deleteDir($sDir);
        }

        $this->cleanUpTable('oxconfig');
        parent::tearDown();
    }

    /**
     * Testing version detector
     */
    public function testDetectVersion()
    {
        $oSerial = $this->getMock(\OxidEsales\Eshop\Core\Serial::class, array('detectVersion'));
        $oSerial->expects($this->once())->method('detectVersion')->will($this->returnValue(1));
        $oSerial->sSerial = 'test';
        oxTestModules::addVariable('oxConfig', '_oSerial', 'public');
        $oConfig = oxNew(\OxidEsales\Eshop\Core\Config::class);
        $oConfig->_oSerial = $oSerial;

        $this->assertEquals(1, $oConfig->detectVersion());
    }

    /**
     * Testing if serial getter really returns same object
     */
    public function testGetSerialIsSameObject()
    {
        $oConfig = oxNew(\OxidEsales\Eshop\Core\Config::class);
        $oConfig->init();
        $oSerial = $oConfig->getSerial();

        // writing something to verify
        $oSerial->xxx = 'yyy';

        $this->assertEquals('yyy', $oConfig->getSerial()->xxx);
    }

    /**
     * Test if reload parameter really reloads.
     */
    public function testGetSerialForcingToReload()
    {
        $oConfig = oxNew(\OxidEsales\Eshop\Core\Config::class);
        $oConfig->init();
        $oSerial = $oConfig->getSerial();

        // writing something to verify
        $oSerial->xxx = 'yyy';

        $this->assertEquals('yyy', $oConfig->getSerial()->xxx);
        $this->assertFalse(isset($oConfig->getSerial(true)->xxx));
    }

    /**
     * Test if reload parameter really loads from DB.
     */
    public function testGetSerialForcingToLoadSerialFromDb()
    {
        $oConfig = oxNew(\OxidEsales\Eshop\Core\Config::class);
        $oConfig->init();
        $oSerial = $oConfig->getSerial();
        $oSerial->sSerial = null;
        $sSerial = oxDb::getDb()->getOne('select oxserial from oxshops where oxid = "' . $oConfig->getShopId() . '"');

        $this->assertEquals($sSerial, $oConfig->getSerial()->sSerial);
    }
    /**
     * Checks if shop license has demo mode
     */
    public function testHasDemoKey()
    {
        // all modules off
        $oSerial = $this->getMock(\OxidEsales\Eshop\Core\Serial::class, array("isFlagEnabled"));
        $oSerial->expects($this->once())->method('isFlagEnabled')->will($this->returnValue(true));

        /** @var Config|\PHPUnit\Framework\MockObject\MockObject $oConfig */
        $oConfig = $this->getMock('\OxidEsales\EshopProfessional\Core\Config', array("getSerial"));
        $oConfig->expects($this->once())->method('getSerial')->will($this->returnValue($oSerial));

        $this->assertTrue($oConfig->hasDemoKey());
    }
}
