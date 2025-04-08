<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopProfessional\Tests\Unit\Application\Controller\Admin;

use oxRegistry;
use oxTestModules;

class ShopLicenseTest extends \OxidEsales\TestingLibrary\UnitTestCase
{
    /**
     * Sets malladmin parameter
     *
     * @return null|void
     */
    public function setUp(): void
    {
        $this->getSession()->setVariable("malladmin", true);

        //licence check mock always return true
        $oLicenceCheckMock = $this->getMock(\OxidEsales\Eshop\Core\OnlineLicenseCheck::class, array("validate"), array(), '', false);
        $oLicenceCheckMock->expects($this->any())->method('validate')->will($this->returnValue(true));
        oxTestModules::addModuleObject("oxonlinelicensecheck", $oLicenceCheckMock);

        parent::setUp();
    }

    /**
     * Shop_License::Init() test case
     */
    public function testInit()
    {
        $this->setRequestParameter("oxid", "testShopId");

        // testing..
        $oView = $this->getMock(\OxidEsales\Eshop\Application\Controller\Admin\ShopLicense::class, array("_authorize"));
        $oView->expects($this->once())->method('_authorize')->will($this->returnValue(true));
        $oView->init();

        $this->assertEquals("testShopId", \OxidEsales\Eshop\Core\Registry::getSession()->getVariable("actshop"));
    }

    /**
     * Shop_License::Save() test case
     */
    public function testSaveSameSerialKey()
    {
        $this->setRequestParameter("editval", array("oxnewserial" => "testSerial"));

        $oConfig = $this->getMock(\OxidEsales\Eshop\Core\Config::class, array("isDemoShop", "getConfigParam", "setConfigParam", "saveShopConfVar", "getBaseShopId"));
        $oConfig->expects($this->once())->method('isDemoShop')->will($this->returnValue(false));
        $oConfig->expects($this->once())->method('getConfigParam')->will($this->returnValue(array("testSerial")));
        $oConfig->expects($this->never())->method('setConfigParam');
        $oConfig->expects($this->never())->method('saveShopConfVar');
        $oConfig->expects($this->never())->method('getBaseShopId');

        $aTasks = array("getConfig", "updateShopSerial", "resetContentCache");

        // testing..
        $oView = $this->getMock(\OxidEsales\Eshop\Application\Controller\Admin\ShopLicense::class, $aTasks, array(), '', false);
        $oView->expects($this->any())->method('getConfig')->will($this->returnValue($oConfig));
        $oView->expects($this->never())->method('updateShopSerial');
        $oView->expects($this->once())->method('resetContentCache');

        $oView->save();
    }

    /**
     * Shop_License::Save() test case
     */
    public function testSaveNotValidSerial()
    {
        $this->setRequestParameter("editval", array("oxnewserial" => "testSerial1"));

        $oConfig = $this->getMock(\OxidEsales\Eshop\Core\Config::class, array("isDemoShop", "getConfigParam", "setConfigParam", "saveShopConfVar", "getBaseShopId"));
        $oConfig->expects($this->once())->method('isDemoShop')->will($this->returnValue(false));
        $oConfig->expects($this->exactly(2))->method('getConfigParam')->will($this->returnValue(array("testSerial2")));
        $oConfig->expects($this->never())->method('setConfigParam');
        $oConfig->expects($this->once())->method('saveShopConfVar')->with($this->equalTo("arr"), $this->equalTo("aSerials"), $this->equalTo(array("testSerial2")), $this->equalTo($this->getConfig()->getBaseShopId()));
        $oConfig->expects($this->once())->method('getBaseShopId')->will($this->returnValue($this->getConfig()->getBaseShopId()));

        $aTasks = array("getConfig", "updateShopSerial", "resetContentCache");

        // testing..
        $oView = $this->getMock(\OxidEsales\Eshop\Application\Controller\Admin\ShopLicense::class, $aTasks, array(), '', false);
        $oView->expects($this->any())->method('getConfig')->will($this->returnValue($oConfig));
        $oView->expects($this->never())->method('updateShopSerial');
        $oView->expects($this->once())->method('resetContentCache');

        $oView->save();
        $this->assertEquals(\OxidEsales\Eshop\Core\Registry::getLang()->translateString('invalid_serial'), $oView->getViewDataElement("error"));
    }

    /**
     * Shop_License::Save() test case
     */
    public function testSaveNonStackableSerial()
    {
        $this->setRequestParameter("editval", array("oxnewserial" => "testSerial1"));
        oxTestModules::addFunction('oxserial', 'isValidSerial', '{ return true; }');
        oxTestModules::addFunction('oxserial', 'isStackable', '{ return false; }');
        oxTestModules::addFunction('oxserial', 'detectVersion', '{ return 2; }');

        $oConfig = $this->getMock(\OxidEsales\Eshop\Core\Config::class, array("isDemoShop", "getConfigParam", "setConfigParam", "saveShopConfVar", "getBaseShopId"));
        $oConfig->expects($this->at(0))->method('isDemoShop')->will($this->returnValue(false));
        $oConfig->expects($this->at(1))->method('getConfigParam')->will($this->returnValue(array("testSerial2")));
        $oConfig->expects($this->at(2))->method('getConfigParam')->will($this->returnValue("testSerial2"));
        $oConfig->expects($this->at(3))->method('setConfigParam')->with($this->equalTo("aSerials"), $this->equalTo(array("testSerial2")));
        $oConfig->expects($this->at(4))->method('getConfigParam')->will($this->returnValue(array("testSerial2")));
        $oConfig->expects($this->at(5))->method('getBaseShopId')->will($this->returnValue($this->getConfig()->getBaseShopId()));
        $oConfig->expects($this->at(6))->method('saveShopConfVar')->with($this->equalTo("arr"), $this->equalTo("aSerials"), $this->equalTo(array("testSerial2")), $this->equalTo($this->getConfig()->getBaseShopId()));


        $aTasks = array("getConfig", "updateShopSerial", "resetContentCache");

        // testing..
        $oView = $this->getMock(\OxidEsales\Eshop\Application\Controller\Admin\ShopLicense::class, $aTasks, array(), '', false);
        $oView->expects($this->any())->method('getConfig')->will($this->returnValue($oConfig));
        $oView->expects($this->once())->method('updateShopSerial');

        $oView->expects($this->once())->method('resetContentCache');

        $oView->save();
        $this->assertEquals(\OxidEsales\Eshop\Core\Registry::getLang()->translateString('nonstackable_serial_detected'), $oView->getViewDataElement("error"));
    }

    /**
     * Shop_License::Save() test case
     */
    public function testSaveStackableSerial()
    {
        $this->setRequestParameter("editval", array("oxnewserial" => "testSerial1"));
        oxTestModules::addFunction('oxserial', 'isValidSerial', '{ return true; }');
        oxTestModules::addFunction('oxserial', 'isStackable', '{ return true; }');
        oxTestModules::addFunction('oxserial', 'detectVersion', '{ return 1; }');

        $oConfig = $this->getMock(\OxidEsales\Eshop\Core\Config::class, array("isDemoShop", "getConfigParam", "setConfigParam", "saveShopConfVar", "getBaseShopId"));
        $oConfig->expects($this->at(0))->method('isDemoShop')->will($this->returnValue(false));
        $oConfig->expects($this->at(1))->method('getConfigParam')->will($this->returnValue(array("testSerial2")));
        $oConfig->expects($this->at(2))->method('getConfigParam')->will($this->returnValue("testSerial2"));
        $oConfig->expects($this->at(3))->method('setConfigParam')->with($this->equalTo("aSerials"), $this->equalTo(array("testSerial2", "testSerial1")));
        $oConfig->expects($this->at(4))->method('getConfigParam')->will($this->returnValue(array("testSerial2")));
        $oConfig->expects($this->at(5))->method('getBaseShopId')->will($this->returnValue($this->getConfig()->getBaseShopId()));
        $oConfig->expects($this->at(6))->method('saveShopConfVar')->with($this->equalTo("arr"), $this->equalTo("aSerials"), $this->equalTo(array("testSerial2")), $this->equalTo($this->getConfig()->getBaseShopId()));


        $aTasks = array("getConfig", "updateShopSerial", "resetContentCache");

        // testing..
        $oView = $this->getMock(\OxidEsales\Eshop\Application\Controller\Admin\ShopLicense::class, $aTasks, array(), '', false);
        $oView->expects($this->any())->method('getConfig')->will($this->returnValue($oConfig));
        $oView->expects($this->once())->method('updateShopSerial');
        $oView->expects($this->once())->method('resetContentCache');

        $oView->save();

        $this->assertEquals(\OxidEsales\Eshop\Core\Registry::getLang()->translateString('serial_added'), $oView->getViewDataElement("message"));
        $this->assertEquals("", $oView->getViewDataElement("error"));
    }

    /**
     * Shop_License::Save() test case
     */
    public function testSaveDetectVersion()
    {
        $this->setRequestParameter("editval", array("oxnewserial" => "testSerial1"));
        oxTestModules::addFunction('oxserial', 'isValidSerial', '{ return true; }');
        oxTestModules::addFunction('oxserial', 'isStackable', '{ return true; }');
        oxTestModules::addFunction('oxserial', 'detectVersion', '{ if ( $aA[0] == "testSerial2" ) return 1; }');

        $oConfig = $this->getMock(\OxidEsales\Eshop\Core\Config::class, array("isDemoShop", "getConfigParam", "setConfigParam", "saveShopConfVar", "getBaseShopId"));
        $oConfig->expects($this->at(0))->method('isDemoShop')->will($this->returnValue(false));
        $oConfig->expects($this->at(1))->method('getConfigParam')->will($this->returnValue(array("testSerial2")));
        $oConfig->expects($this->at(2))->method('getConfigParam')->will($this->returnValue("testSerial2"));
        $oConfig->expects($this->at(3))->method('setConfigParam')->with($this->equalTo("aSerials"), $this->equalTo(array("testSerial1")));
        $oConfig->expects($this->at(4))->method('getConfigParam')->will($this->returnValue(array("testSerial2")));
        $oConfig->expects($this->at(5))->method('getBaseShopId')->will($this->returnValue($this->getConfig()->getBaseShopId()));
        $oConfig->expects($this->at(6))->method('saveShopConfVar')->with($this->equalTo("arr"), $this->equalTo("aSerials"), $this->equalTo(array("testSerial2")), $this->equalTo($this->getConfig()->getBaseShopId()));


        $aTasks = array("getConfig", "updateShopSerial", "resetContentCache");

        // testing..
        $oView = $this->getMock(\OxidEsales\Eshop\Application\Controller\Admin\ShopLicense::class, $aTasks, array(), '', false);
        $oView->expects($this->any())->method('getConfig')->will($this->returnValue($oConfig));
        $oView->expects($this->once())->method('updateShopSerial');

        $oView->expects($this->once())->method('resetContentCache');

        $oView->save();

        $this->assertEquals(\OxidEsales\Eshop\Core\Registry::getLang()->translateString('serial_updated'), $oView->getViewDataElement("message"));
        $this->assertEquals("", $oView->getViewDataElement("error"));
    }

    /**
     * Shop_License::Save() test case
     */
    public function testSaveNewNonStackableSerial()
    {
        $this->setRequestParameter("editval", array("oxnewserial" => "testSerial1"));
        oxTestModules::addFunction('oxserial', 'isValidSerial', '{ return true; }');
        oxTestModules::addFunction('oxserial', 'isStackable', '{ return false; }');
        oxTestModules::addFunction('oxserial', 'detectVersion', '{ return 2; }');

        $aConfMap = array(
            array("editval", array()),
            array("aSerials", array("testSerial2")),
            array("sSerialNr", "testSerial2")
        );

        $oConfig = $this->getMock(\OxidEsales\Eshop\Core\Config::class, array("isDemoShop", "getConfigParam", "setConfigParam", "saveShopConfVar", "getBaseShopId"));

        $oConfig->expects($this->at(0))->method('isDemoShop')->will($this->returnValue(false));
        $oConfig->expects($this->at(1))->method('getConfigParam')->will($this->returnValue(array()));
        $oConfig->expects($this->at(2))->method('getConfigParam')->will($this->returnValue("testSerial2"));
        $oConfig->expects($this->at(3))->method('setConfigParam')->with($this->equalTo("aSerials"), $this->equalTo(array("testSerial1")));
        $oConfig->expects($this->at(4))->method('getConfigParam')->will($this->returnValue(array("testSerial2")));
        $oConfig->expects($this->at(5))->method('getBaseShopId')->will($this->returnValue($this->getConfig()->getBaseShopId()));
        $oConfig->expects($this->at(6))->method('saveShopConfVar')->with($this->equalTo("arr"), $this->equalTo("aSerials"), $this->equalTo(array("testSerial2")), $this->equalTo($this->getConfig()->getBaseShopId()));

        $aTasks = array("getConfig", "updateShopSerial", "resetContentCache");

        // testing..
        $oView = $this->getMock(\OxidEsales\Eshop\Application\Controller\Admin\ShopLicense::class, $aTasks, array(), '', false);
        $oView->expects($this->any())->method('getConfig')->will($this->returnValue($oConfig));
        $oView->expects($this->once())->method('updateShopSerial');

        $oView->expects($this->once())->method('resetContentCache');

        $oView->save();

        $this->assertNull($oView->getViewDataElement("message"));
        $this->assertNull($oView->getViewDataElement("error"));
    }

    /**
     * Shop_License::DeleteSerial() test case
     */
    public function testDeleteSerial()
    {
        $this->setRequestParameter("serial", "serial3");

        $oConfig = $this->getMock(\OxidEsales\Eshop\Core\Config::class, array("isDemoShop", "getConfigParam", "saveShopConfVar", "getBaseShopId"));
        $oConfig->expects($this->any())->method('isDemoShop')->will($this->returnValue(false));
        $oConfig->expects($this->once())->method('getConfigParam')->with($this->equalTo("aSerials"))->will($this->returnValue(array("serial1", "serial2", "serial3")));
        $oConfig->expects($this->once())->method('saveShopConfVar')->with($this->equalTo("arr"), $this->equalTo("aSerials"), $this->equalTo(array("serial1", "serial2")), $this->equalTo("testShopId"));
        $oConfig->expects($this->once())->method('getBaseShopId')->will($this->returnValue("testShopId"));

        // testing..
        $oView = $this->getMock(\OxidEsales\Eshop\Application\Controller\Admin\ShopLicense::class, array("getConfig", "updateShopSerial"), array(), '', false);
        $oView->expects($this->any())->method('getConfig')->will($this->returnValue($oConfig));
        $oView->expects($this->once())->method('updateShopSerial');
        $oView->deleteSerial();
    }

    public function testGetOnlineLicenseCheck()
    {
        $oSystemEventHandler = oxNew('Shop_License');
        $this->assertInstanceOf('oxOnlineLicenseCheck', $oSystemEventHandler->getOnlineLicenseCheck());
    }
}
