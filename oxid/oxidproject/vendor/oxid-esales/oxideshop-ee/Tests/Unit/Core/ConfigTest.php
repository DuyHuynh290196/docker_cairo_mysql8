<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Tests\Unit\Core;

use OxidEsales\EshopEnterprise\Core\Config;
use OxidEsales\EshopEnterprise\Core\Serial;
use OxidEsales\Eshop\Core\Registry;
use \oxField;
use \oxDb;

class modFortestGetShopTakingFromRequestNoMall extends Config
{
    public function isMall()
    {
        return false;
    }
}

class ConfigTest extends \oxUnitTestCase
{
    /** @var array */
    private $_aShops;

    protected function setUp(): void
    {
        parent::setUp();
        $this->getConfig()->sTheme = false;

        $this->_iCurr = $this->getSession()->getVariable('currency');

        for ($i = 2; $i < 7; $i++) {
            $this->_aShops[$i] = oxNew(\OxidEsales\Eshop\Core\Model\BaseModel::class);
            $this->_aShops[$i]->init('oxshops');
            $this->_aShops[$i]->setId($i);
            $this->_aShops[$i]->oxshop__oxactive = new \OxidEsales\Eshop\Core\Field(1, \OxidEsales\Eshop\Core\Field::T_RAW);
            $this->_aShops[$i]->oxshop__oxactive_1 = new \OxidEsales\Eshop\Core\Field(1, \OxidEsales\Eshop\Core\Field::T_RAW);
            $this->_aShops[$i]->oxshop__oxactive_2 = new \OxidEsales\Eshop\Core\Field(1, \OxidEsales\Eshop\Core\Field::T_RAW);
            $this->_aShops[$i]->oxshop__oxactive_3 = new \OxidEsales\Eshop\Core\Field(1, \OxidEsales\Eshop\Core\Field::T_RAW);
            $this->_aShops[$i]->save();
        }
    }

    /**
     * Tear down the fixture.
     *
     * @return null
     */
    protected function tearDown(): void
    {
        Registry::getLang()->setBaseLanguage(1);

        // cleaning up
        $sQ = 'delete from oxconfig where oxvarname = "xxx" ';
        \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute($sQ);

        foreach ($this->_aShops as $oShop) {
            $oShop->delete();
        }
        $this->_aShops = array();

        $sDir = $this->getConfig()->getConfigParam('sShopDir') . "/out/2";
        if (is_dir(realpath($sDir))) {
            Registry::getUtilsFile()->deleteDir($sDir);
        }
        $sDir = $this->getConfig()->getConfigParam('sShopDir') . "/out/en/tpl";
        if (is_dir(realpath($sDir))) {
            Registry::getUtilsFile()->deleteDir($sDir);
        }

        $this->cleanUpTable('oxconfig');
        parent::tearDown();
    }

    /**
     * Testing base shop id getter
     */
    public function testGetBaseShopId()
    {
        $oConfig = oxNew(\OxidEsales\Eshop\Core\Config::class);
        $oConfig->init();

        $this->assertEquals('1', $oConfig->getBaseShopId());
    }

    /**
     * Testing mall mode getter
     */
    public function testIsMall()
    {
        $oConfig = oxNew(\OxidEsales\Eshop\Core\Config::class);
        $oConfig->init();
        $this->assertTrue($oConfig->isMall());
    }

    /**
     * Testing shop mall type getter (only for EE)
     */
    public function testIsMultiShop()
    {
        $oConfig = oxNew(\OxidEsales\Eshop\Core\Config::class);
        $oConfig->init();
        $this->assertEquals((bool) $oConfig->getActiveShop()->oxshops__oxismultishop->value, $oConfig->isMultiShop());
    }

    /**
     * Test case for \OxidEsales\Eshop\Core\Config::mustAddShopIdToRequest()
     */
    public function testMustAddShopIdToRequest()
    {
        /** @var Config|\PHPUnit\Framework\MockObject\MockObject $oConfig */
        $oConfig = $this->getMock('\OxidEsales\EshopEnterprise\Core\Config', array("getShopId", "getConfigParam", "isAdmin"));
        $oConfig->expects($this->at(0))->method('getShopId')->will($this->returnValue(1));
        $oConfig->expects($this->at(1))->method('getShopId')->will($this->returnValue(2));
        $oConfig->expects($this->at(2))->method('isAdmin')->will($this->returnValue(false));
        $oConfig->expects($this->at(3))->method('getConfigParam')->will($this->returnValue(true));
        $oConfig->expects($this->at(4))->method('getShopId')->will($this->returnValue(2));
        $oConfig->expects($this->at(5))->method('isAdmin')->will($this->returnValue(false));
        $oConfig->expects($this->at(6))->method('getConfigParam')->will($this->returnValue(false));
        $oConfig->expects($this->at(7))->method('getShopId')->will($this->returnValue(2));
        $oConfig->expects($this->at(8))->method('isAdmin')->will($this->returnValue(true));

        $this->assertFalse($oConfig->mustAddShopIdToRequest());
        $this->assertFalse($oConfig->mustAddShopIdToRequest());
        $this->assertTrue($oConfig->mustAddShopIdToRequest());
        $this->assertTrue($oConfig->mustAddShopIdToRequest());
    }


    /**
     * Testing if shop var saver writes correct info into db
     */
    public function testSaveShopConfVar()
    {
        $sName = 'xxx';
        $sVal = '123';

        $oConfig = oxNew(\OxidEsales\Eshop\Core\Config::class);
        $oConfig->init();
        $sShopId = $oConfig->getShopId();
        $oConfig->saveShopConfVar('int', $sName, $sVal, $sShopId);
        $this->assertEquals($sVal, $oConfig->getShopConfVar($sName, $sShopId));
        $this->assertEquals($sVal, $oConfig->getConfigParam($sName));

        $sName = 'xxx2';
        $sVal = '321';
        $oConfig->saveShopConfVar('int', $sName, $sVal, 2);
        $this->assertEquals($sVal, $oConfig->getShopConfVar($sName, 2));
        $this->assertNotEquals($sVal, $oConfig->getConfigParam($sName));
    }

    public function testSetConfVarFromDb()
    {
        /** @var Config|\PHPUnit\Framework\MockObject\MockObject $oConfig */
        $oConfig = $this->getMock('OxidEsales\EshopEnterprise\Core\Config', array("setConfigParam"));
        $oConfig->expects($this->at(0))->method('setConfigParam')
            ->with(
                $this->equalTo("test1"),
                $this->equalTo("t1")
            );
        $oConfig->expects($this->at(1))->method('setConfigParam')
            ->with(
                $this->equalTo("test2"),
                $this->equalTo(array('x'))
            );
        $oConfig->expects($this->at(2))->method('setConfigParam')
            ->with(
                $this->equalTo("test3"),
                $this->equalTo(array('x' => 'y'))
            );
        $oConfig->expects($this->at(3))->method('setConfigParam')
            ->with(
                $this->equalTo("test4"),
                $this->equalTo(true)
            );
        $oConfig->expects($this->at(4))->method('setConfigParam')
            ->with(
                $this->equalTo("test5"),
                $this->equalTo(false)
            );

        $oConfig->_setConfVarFromDb('test1', 'blabla', 't1');
        $oConfig->_setConfVarFromDb('test2', 'arr', serialize(array('x')));
        $oConfig->_setConfVarFromDb('test3', 'aarr', serialize(array('x' => 'y')));
        $oConfig->_setConfVarFromDb('test4', 'bool', 'true');
        $oConfig->_setConfVarFromDb('test5', 'bool', '0');
    }

    /**
     * Testing serial number setter
     */
    public function testSetSerial()
    {
        $oConfig = oxNew(\OxidEsales\Eshop\Core\Config::class);
        $oConfig->init();
        $oConfig->setSerial('xxx');
        $this->assertEquals('xxx', $oConfig->getConfigParam('sSerialNr'));
    }

    /**
     * Testing active shop getter if it returns same object + if serial is set while loading shop
     */
    public function testGetActiveShop()
    {
        $oConfig = oxNew(\OxidEsales\Eshop\Core\Config::class);
        $oConfig->init();

        // no serial info at the begining
        $this->assertNull($oConfig->getConfigParam('sSerialNr'));

        // comparing serials
        $oShop = $oConfig->getActiveShop();

        $this->assertEquals($oShop->oxshops__oxserial->value, $oConfig->getConfigParam('sSerialNr'));

        // additionally checking caching
        $oShop->xxx = 'yyy';
        $this->assertEquals('yyy', $oConfig->getActiveShop()->xxx);

        // checking if different language forces reload
        $iCurrLang = Registry::getLang()->getBaseLanguage();
        Registry::getLang()->resetBaseLanguage();
        $this->setRequestParameter('lang', $iCurrLang + 1);

        $oShop = $oConfig->getActiveShop();
        $this->assertFalse(isset($oShop->xxx));
    }

    /**
     * Testing Mandate Counter (default installation count is 0)
     */
    public function testGetMandateCount()
    {
        $oConfig = oxNew(\OxidEsales\Eshop\Core\Config::class);
        $oConfig->init();

        $iShopCount = \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->GetOne('select count(*) from oxshops');

        $this->assertEquals($iShopCount, $oConfig->getMandateCount());
    }

    /**
     * When mall is off - all the time shop id will be "1"
     */
    public function testGetShopTakingFromRequestNoMall()
    {
        $this->setRequestParameter('actshop', 5);
        $this->getSession()->setVariable('actshop', 5);

        $oConfig = new modFortestGetShopTakingFromRequestNoMall();
        $oConfig->init();
        $this->assertEquals(1, $oConfig->getShopId());
    }

    public function testGetShopIdTakingFromRequestWithValidId()
    {
        $this->setRequestParameter('actshop', 3);
        $oConfig = oxNew(\OxidEsales\Eshop\Core\Config::class);
        $this->assertEquals(3, $oConfig->getShopId());
    }

    public function testGetShopTakingFromRequestInvalidShopId()
    {
        $this->getSession()->setVariable('actshop', 9);

        /** @var Config|\PHPUnit\Framework\MockObject\MockObject $oConfig */
        $oConfig = $this->getMock('\OxidEsales\EshopEnterprise\Core\Config', array("onShopChange"));
        $oConfig->expects($this->never())->method('onShopChange');
        $this->assertEquals(1, $oConfig->getShopId());
    }

    public function testGetShopIfMallIsOff()
    {
        $this->getSession()->setVariable('actshop', 2);

        $oConfig = $this->getMock('\OxidEsales\EshopEnterprise\Core\Config', array('isMall', "onShopChange"));
        $oConfig->expects($this->any())->method('onShopChange');
        $oConfig->expects($this->any())->method('isMall')->will($this->returnValue(false));
        $this->assertEquals($oConfig->getBaseShopId(), $oConfig->getShopId());
    }

    public function testGetShopFromDB()
    {
        $sQ1 = "insert into oxconfig (oxid, oxshopid, oxvarname, oxvartype, oxvarvalue) values
                                    ('_test1', '2', 'sMallShopURL', 'int', 0x071d6980dc7afb6707bb)";
        \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute($sQ1);

        /** @var Config|\PHPUnit\Framework\MockObject\MockObject $oConfig */
        $oConfig = $this->getMock('\OxidEsales\EshopEnterprise\Core\Config', array('isAdmin', 'isCurrentUrl', '_getShopIdFromLangUrls'));
        $oConfig->expects($this->any())->method('_getShopIdFromLangUrls')->will($this->returnValue(null));
        $oConfig->expects($this->any())->method('isAdmin')->will($this->returnValue(false));
        $oConfig->expects($this->any())->method('isCurrentUrl')->will($this->returnValue(true));

        $this->setRequestParameter('actshop', null);
        $this->getSession()->setVariable('actshop', 0);
        $this->assertEquals(2, $oConfig->getShopId());
    }

    public function testGetShopFromDBSSL()
    {
        $sQ1 = "insert into oxconfig (oxid, oxshopid, oxvarname, oxvartype, oxvarvalue) values
                                    ('_test1', '2', 'sMallSSLShopURL', 'int', 0x071d6980dc7afb6707bb)";
        \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute($sQ1);

        /** @var Config|\PHPUnit\Framework\MockObject\MockObject $oConfig */
        $oConfig = $this->getMock('\OxidEsales\EshopEnterprise\Core\Config', array('isAdmin', 'isCurrentUrl', '_getShopIdFromLangUrls'));
        $oConfig->expects($this->any())->method('_getShopIdFromLangUrls')->will($this->returnValue(null));
        $oConfig->expects($this->any())->method('isAdmin')->will($this->returnValue(false));
        $oConfig->expects($this->any())->method('isCurrentUrl')->will($this->returnValue(true));

        $this->setRequestParameter('actshop', null);
        $this->getSession()->setVariable('actshop', 0);
        $this->assertEquals(2, $oConfig->getShopId());
    }

    public function testGetShopFromLangUrls()
    {
        /** @var Config|\PHPUnit\Framework\MockObject\MockObject $oConfig */
        $oConfig = $this->getMock('\OxidEsales\EshopEnterprise\Core\Config', array('isCurrentUrl'));
        $oConfig->expects($this->any())->method('isCurrentUrl')->will($this->evalFunction(array($this, 'getShopFromLangUrls_isCurrentUrl14')));

        $sQ1 = "replace into oxconfig (oxid, oxshopid, oxvarname, oxvartype, oxvarvalue) values
                                    ('_test11', '14', 'aLanguageURLs', 'arr', ENCODE( " . \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->quote(serialize(array('asd', 'dsa'))) . ", '" . $oConfig->getConfigParam('sConfigKey') . "') )";
        $sQ2 = "replace into oxconfig (oxid, oxshopid, oxvarname, oxvartype, oxvarvalue) values
                                    ('_test21', '15', 'aLanguageURLs', 'arr', ENCODE( " . \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->quote(serialize(array('asda', 'dsad'))) . ", '" . $oConfig->getConfigParam('sConfigKey') . "') )";

        \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute($sQ1);
        \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute($sQ2);
        $this->assertEquals(14, $oConfig->UNITgetShopIdFromLangUrls());


        /** @var Config|\PHPUnit\Framework\MockObject\MockObject $oConfig */
        $oConfig1 = $this->getMock('\OxidEsales\EshopEnterprise\Core\Config', array('isCurrentUrl'));
        $oConfig1->expects($this->any())->method('isCurrentUrl')->will($this->evalFunction(array($this, 'getShopFromLangUrls_isCurrentUrl15')));


        $this->assertEquals(15, $oConfig1->UNITgetShopIdFromLangUrls());
    }

    public function testGetShopAfterSetShop()
    {
        $oConfig = oxNew(\OxidEsales\Eshop\Core\Config::class);
        $oConfig->setShopId(3);
        $this->assertEquals(3, $oConfig->getShopId());
        $this->assertEquals(3, $this->getSession()->getVariable('actshop'));
    }

    public function testGetFullEdition()
    {
        $sFEdition = $this->getConfig()->getFullEdition();

        $this->assertEquals("Enterprise Edition", $sFEdition);

        /** @var Config|\PHPUnit\Framework\MockObject\MockObject $oConfig */
        $oConfig = $this->getMock('\OxidEsales\EshopEnterprise\Core\Config', array('getEdition'));
        $oConfig->expects($this->any())->method('getEdition')->will($this->returnValue("Test Edition"));
        $this->assertEquals("Test Edition", $oConfig->getFullEdition());
    }

    public function testGetDirForSubShop()
    {
        $vfsStreamWrapper = $this->getVfsStreamWrapper();
        $vfsStreamWrapper->createFile('out/test4/2/de/test1/text.txt', '');
        $sTestDir = $vfsStreamWrapper->getRootPath();

        /** @var Config|\PHPUnit\Framework\MockObject\MockObject $oConfig */
        $oConfig = $this->getMock('\OxidEsales\EshopEnterprise\Core\Config', array('getOutDir', 'getShopId'));
        $oConfig->expects($this->any())->method('getOutDir')->will($this->returnValue($sTestDir . 'out/'));
        $oConfig->expects($this->any())->method('getShopId')->will($this->returnValue(2));
        $oConfig->init();

        $sOutDir = $sTestDir . $this->_getOutPath($oConfig, 'test4', false);

        $sDir = $oConfig->getDir('text.txt', 'test1', false, 0, null, 'test4');
        $this->assertEquals($sOutDir . '2/de/test1/text.txt', $sDir);
    }

    public function testGetDirForSubShopFromParent()
    {
        $vfsStreamWrapper = $this->getVfsStreamWrapper();
        $vfsStreamWrapper->createFile('out/test4/2/de/test1/text.txt', '');
        $sTestDir = $vfsStreamWrapper->getRootPath();

        /** @var Config|\PHPUnit\Framework\MockObject\MockObject $oConfig */
        $oConfig = $this->getMock('\OxidEsales\EshopEnterprise\Core\Config', array('getOutDir', 'getParentShopId'));
        $oConfig->expects($this->any())->method('getOutDir')->will($this->returnValue($sTestDir . 'out/'));
        $oConfig->expects($this->any())->method('getParentShopId')->will($this->returnValue(2));
        $oConfig->init();

        $sOutDir = $sTestDir . $this->_getOutPath($oConfig, 'test4', false);

        $sDir = $oConfig->getDir('text.txt', 'test1', false, 0, 3, 'test4');
        $this->assertEquals($sOutDir . '2/de/test1/text.txt', $sDir);
    }

    public function testGetDirForSubShopFromBaseTheme()
    {
        $vfsStreamWrapper = $this->getVfsStreamWrapper();
        $vfsStreamWrapper->createFile('out/test4/de/test2a/text.txt', '');
        $sTestDir = $vfsStreamWrapper->getRootPath();

        /** @var Config|\PHPUnit\Framework\MockObject\MockObject $oConfig */
        $oConfig = $this->getMock('\OxidEsales\EshopEnterprise\Core\Config', array('getOutDir', 'getParentShopId'));
        $oConfig->expects($this->any())->method('getOutDir')->will($this->returnValue($sTestDir . 'out/'));
        $oConfig->expects($this->any())->method('getParentShopId')->will($this->returnValue(0));
        $oConfig->init();

        $sOutDir = $sTestDir . $this->_getOutPath($oConfig, 'test4', false);

        $sDir = $oConfig->getDir('text.txt', 'test2a', false, 0, 3, 'test4');
        $this->assertEquals($sOutDir . 'de/test2a/text.txt', $sDir);
    }

    public function testGetParentShopId()
    {
        $oConfig = oxNew(\OxidEsales\Eshop\Core\Config::class);
        $this->_aShops[3]->oxshops__oxparentid = new \OxidEsales\Eshop\Core\Field(2);
        $this->_aShops[3]->save();
        $this->assertEquals(2, $oConfig->getParentShopId(3));
        //cached
        $this->_aShops[3]->oxshops__oxparentid = new \OxidEsales\Eshop\Core\Field(1);
        $this->_aShops[3]->save();
        $this->assertEquals(2, $oConfig->getParentShopId(3));
    }

    public function testOnShopChangeWhenAdmin()
    {
        /** @var Config|\PHPUnit\Framework\MockObject\MockObject $oConfig */
        $oConfig = $this->getMock('\OxidEsales\EshopEnterprise\Core\Config', array('isAdmin', 'getSession'), array(), '', false);
        $oConfig->expects($this->once())->method('isAdmin')->will($this->returnValue(true));
        $oConfig->expects($this->never())->method('getSession');

        $oConfig->onShopChange();
    }

    public function testOnShopChangeNoMallUsers()
    {
        $oSession = $this->getMock(\OxidEsales\Eshop\Core\Session::class, array('initNewSession'));
        $oSession->expects($this->once())->method('initNewSession');

        /** @var Config|\PHPUnit\Framework\MockObject\MockObject $oConfig */
        $oConfig = $this->getMock('\OxidEsales\EshopEnterprise\Core\Config', array('isAdmin', 'getSession'), array(), '', false);
        $oConfig->expects($this->any())->method('isAdmin')->will($this->returnValue(false));
        $oConfig->expects($this->any())->method('getSession')->will($this->returnValue($oSession));
        $oConfig->expects($this->any())->method('getSession')->will($this->returnValue($oSession));
        $oConfig->setConfigParam('blMallUsers', false);
        $oConfig->onShopChange();
    }

    public function testOnShopChangeIfMallUsers()
    {
        $oBasket = $this->getMock(\OxidEsales\Eshop\Application\Model\Basket::class, array('onUpdate'));
        $oBasket->expects($this->once())->method('onUpdate');

        $oSession = $this->getMock(\OxidEsales\Eshop\Core\Session::class, array('initNewSession', 'getBasket'));
        $oSession->expects($this->never())->method('initNewSession');
        $oSession->expects($this->once())->method('getBasket')->will($this->returnValue($oBasket));

        /** @var Config|\PHPUnit\Framework\MockObject\MockObject $oConfig */
        $oConfig = $this->getMock('\OxidEsales\EshopEnterprise\Core\Config', array('isAdmin', 'getSession'), array(), '', false);
        $oConfig->expects($this->any())->method('isAdmin')->will($this->returnValue(false));
        $oConfig->expects($this->any())->method('getSession')->will($this->returnValue($oSession));
        $oConfig->setConfigParam('blMallUsers', true);

        $oConfig->onShopChange();
    }

    public function testGetShopUrlRetursnCorrect()
    {
        $oBasket = $this->getMock(\OxidEsales\Eshop\Application\Model\Basket::class, array('onUpdate'));
        $oBasket->expects($this->once())->method('onUpdate');
        $oSession = $this->getMock(\OxidEsales\Eshop\Core\Session::class, array('initNewSession', 'getBasket'));
        $oSession->expects($this->never())->method('initNewSession');
        $oSession->expects($this->once())->method('getBasket')->will($this->returnValue($oBasket));
        $oConfig = oxNew(\OxidEsales\Eshop\Core\Config::class);
        $oConfig->setSession($oSession);
        $oConfig->setConfigParam('blMallUsers', true);
        $oConfig->onShopChange();
    }

    /**
     * Checks if shop licenze is in staging mode
     */
    public function testIsStagingMode_modeIsOff()
    {
        // all modules off
        $oSerial = new Serial("CNSJZ-HJK78-Z786G-KNDNT-ZZ46G-KK6GK");

        /** @var Config|\PHPUnit\Framework\MockObject\MockObject $oConfig */
        $oConfig = $this->getMock('\OxidEsales\EshopEnterprise\Core\Config', array("getSerial"));
        $oConfig->expects($this->once())->method('getSerial')->will($this->returnValue($oSerial));

        $this->assertFalse($oConfig->isStagingMode());
    }

    /**
     * Checks if shop licenze is in staging mode
     */
    public function testIsStagingMode_modeIsOn()
    {
        // all modules off, staging on
        $oSerial = new Serial("LTUPF-RAQNU-LKQLN-QVN2A-V3PL8-63T8H");

        /** @var Config|\PHPUnit\Framework\MockObject\MockObject $oConfig */
        $oConfig = $this->getMock('\OxidEsales\EshopEnterprise\Core\Config', array("getSerial"));
        $oConfig->expects($this->once())->method('getSerial')->will($this->returnValue($oSerial));

        $this->assertTrue($oConfig->isStagingMode());
    }

    /**
     * A test case for bug: #0004549: Session restarts on every click after opening a different subshop in a different browser tab
     */
    public function testInitOnShopChange()
    {
        /** @var Config|\PHPUnit\Framework\MockObject\MockObject $oConfig */
        $oConfig = $this->getMock('\OxidEsales\EshopEnterprise\Core\Config', array("onShopChange", "_setShopId", "getShopId"));
        $oConfig->expects($this->once())->method("onShopChange");
        $oConfig->expects($this->any())->method("getShopId")->will($this->returnValue(1));
        //$oConfig->expects( $this->exactly( 2) )->method("setShopId");

        $oConfig->setShopId(2);
        $this->getSession()->setVariable("actshop", 5);
        $oConfig->init();

        $this->assertEquals(2, Registry::getSession()->getVariable("actshop"));
    }

    /**
     * A test case for bug: #0004549: Session restarts on every click after opening a different subshop in a different browser tab
     */
    public function testSetShopId()
    {
        $oConfig = oxNew(\OxidEsales\Eshop\Core\Config::class);

        $oConfig->setShopId(55);

        $this->assertEquals(55, $this->getSession()->getVariable('actshop'));
        $this->assertEquals(55, $oConfig->getShopId());
    }

    /**
     * Data provider for testSetShopId_WrongIdGiven
     */
    public function providerSetShopId_WrongIdGiven()
    {
        return array(array(null), array(0), array('oxidshop'), array(-1));
    }

    /**
     * A test case for bug: #0004549: Session restarts on every click after opening a different subshop in a different browser tab
     *
     * @dataProvider providerSetShopId_WrongIdGiven
     */
    public function testSetShopId_WrongIdGiven($sShopId)
    {
        $oConfig = oxNew(\OxidEsales\Eshop\Core\Config::class);

        $oConfig->setShopId(55);

        $oConfig->setShopId($sShopId);

        $this->assertEquals(55, $this->getSession()->getVariable('actshop'));
        $this->assertEquals(55, $oConfig->getShopId());
    }

    /**
     * Used by test with PhpUnit::evalFunction.
     *
     * @param $url
     * @return bool
     */
    public function getShopFromLangUrls_isCurrentUrl14($url)
    {
        if (!in_array($url, array('asd', 'dsa', 'asda', 'dsad'))) {
            $this->fail("unknown url given");
        }

        return $url == 'asd';
    }

    /**
     * Used by test with PhpUnit::evalFunction.
     *
     * @param $url
     * @return bool
     */
    public function getShopFromLangUrls_isCurrentUrl15($url)
    {
        if (!in_array($url, array('asd', 'dsa', 'asda', 'dsad'))) {
            $this->fail("unknown url given");
        }

        return $url == 'dsad';
    }

    private function _getOutPath($oConfig, $sTheme = null, $blAbsolute = true) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $sShop = $blAbsolute ? $oConfig->getConfigParam('sShopDir') : "";

        if (is_null($sTheme)) {
            $sTheme = $oConfig->getConfigParam('sTheme');
        }

        if ($sTheme) {
            $sTheme .= "/";
        }

        return $sShop . 'out/' . $sTheme;
    }
}
