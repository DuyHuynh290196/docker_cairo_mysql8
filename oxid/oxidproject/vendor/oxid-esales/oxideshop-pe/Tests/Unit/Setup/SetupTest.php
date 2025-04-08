<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopProfessional\Tests\Unit\Setup;

require_once getShopBasePath() . '/Setup/functions.php';
use OxidEsales\EshopCommunity\Setup\Core;
use OxidEsales\EshopProfessional\Setup\Setup;
use Exception;

class SetupTest extends \OxidEsales\TestingLibrary\UnitTestCase
{
    /**
     * Testing Setup::getDefaultSerial()
     */
    public function testGetDefaultSerial()
    {
        if ($this->getTestConfig()->getShopEdition() !== 'PE') {
            $this->markTestSkipped('This test is for Professional editions only.');
        }
        $setup = $this->getSetup();
        $this->assertEquals('3Q3EQ-U4562-Y9JTE-2N6LP-JTJ9K-GNVLK', $setup->getDefaultSerial());
    }

    /**
     * Testing Setup::getEdition()
     */
    public function testGetEdition()
    {
        if ($this->getTestConfig()->getShopEdition() !== 'PE') {
            $this->markTestSkipped('This test is for Professional editions only.');
        }
        $setup = $this->getSetup();
        $this->assertEquals(1, $setup->getEdition());
    }

    /**
     * Testing Setup::setSerial()
     */
    public function testSetSerialEmptySerial()
    {
        try {
            $oSetup = $this->getSetup();
            $oSetup->setSerial("");
        } catch (Exception $oExcp) {
            return;
        }
        $this->fail("Empty serial should throw an exception");

    }

    /**
     * Testing Setup::setSerial()
     */
    public function testSetSerialInvalidSerial()
    {
        try {
            $setup = $this->getSetup();
            $setup->setSerial("testSerial");
        } catch (Exception $exception) {
            return;
        }
        $this->fail("Empty serial should throw an exception");
    }

    /**
     * Testing Setup::setSerial()
     */
    public function testSetSerial()
    {
        $language = $this->getMock("Language", array("getText"));
        $language->expects($this->once())->method("getText")->with($this->equalTo('STEP_5_1_SERIAL_ADDED'));

        $database = $this->getMock("DatabaseStub", array("writeSerial"));
        $database->expects($this->once())->method("writeSerial");

        /** @var Setup $setup */
        $setup = $this->getMock(get_class($this->getSetup()), array("getInstance", "setNextStep", "getStep"));
        $setup->expects($this->at(0))->method("getInstance")->with($this->equalTo('Language'))->will($this->returnValue($language));
        $setup->expects($this->at(1))->method("getInstance")->with($this->equalTo('Database'))->will($this->returnValue($database));
        $setup->setSerial($setup->getDefaultSerial());
    }

    /**
     * @return Setup
     */
    protected function getsetUp()
    {
        $core = new Core();
        return $core->getInstance('Setup');
    }
}
