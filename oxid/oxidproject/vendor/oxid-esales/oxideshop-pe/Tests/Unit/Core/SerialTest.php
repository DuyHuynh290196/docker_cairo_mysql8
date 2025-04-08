<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopProfessional\Tests\Unit\Core;

use OxidEsales\Eshop\Core\ExpirationEmailBuilder;
use OxidEsales\EshopProfessional\Core\GracePeriodResetEmailBuilder;
use OxidEsales\EshopProfessional\Core\Serial;
use \oxRegistry;
use \oxDb;
use \oxField;

class _oxSerial extends Serial
{
    public function charShift($cIn, $sShift)
    {
        return parent::_charShift($cIn, $sShift);
    }

    public function hashName($sName = "")
    {
        return parent::_hashName($sName);
    }

    public function getChecksum($sIn)
    {
        return parent::_getChecksum($sIn);
    }

    public function mangleSerial($sUnmangledSerial = "")
    {
        return parent::_mangleSerial($sUnmangledSerial);
    }

    public function unmangleSerial($sMangledSerial = "")
    {
        return parent::_unmangleSerial($sMangledSerial);
    }

    public function getBlankSerial()
    {
        return parent::_getBlankSerial();
    }

    public function getSSerial()
    {
        return $this->sSerial;
    }

    public function getSName()
    {
        return $this->_sName;
    }

    public function UNITgetClassVar($sVarName)
    {
        return $this->$sVarName;
    }
}

/**
 * License key managing class.
 *
 * @package core
 */
class SerialTest extends \oxUnitTestCase
{
    private $_oSerial = null;

    public function setUp(): void
    {
        parent::setUp();

        $this->_oSerial = new _oxSerial();
        $myConfig = $this->getConfig();

        $this->aConfig = array();
        $this->aConfig['blShopStopped'] = oxDb::getDb(oxDb::FETCH_MODE_ASSOC)->getRow("select * from oxconfig where oxvarname='blShopStopped'");

        $myConfig->saveShopConfVar('bool', 'blShopStopped', 'false', $myConfig->getBaseShopId());
        $myConfig->saveShopConfVar('str', 'sBackTag', '', $myConfig->getBaseShopId());
        $myConfig->saveShopConfVar('bool', 'blExpirationEmailSent', 'false', $myConfig->getBaseShopId());
    }

    /**
     * Tear down the fixture.
     *
     * @return null
     */
    protected function tearDown(): void
    {
        $oDBRestore = self::_getDbRestore();
        $oDBRestore->restoreTable('oxconfig');

        parent::tearDown();
    }

    public function testOxSerial_constructor()
    {
        $sSerial = 'Serial val';
        $oSerial = new _oxSerial($sSerial);
        $this->assertEquals($oSerial->getSSerial(), $sSerial);
    }

    public function testCharShift()
    {
        $c1 = $this->_oSerial->charShift('b', 9);
        $c2 = $this->_oSerial->charShift('i', 46);
        $c3 = $this->_oSerial->charShift('G', 50);
        $c4 = $this->_oSerial->charShift('6', 15);
        $this->assertEquals($c1, 'L');
        $this->assertEquals($c2, 'I');
        $this->assertEquals($c3, '2');
        $this->assertEquals($c4, 'M');

    }

    public function testSetName()
    {
        $this->_oSerial->setName('Test name');
        $this->assertEquals($this->_oSerial->getSName(), 'Test name');
    }

    public function testHashName()
    {
        $this->assertEquals($this->_oSerial->hashName('Test name'), 'TESTNA');
    }

    public function testGetName()
    {
        $this->assertEquals($this->_oSerial->getName('U7YUR-RQX6N-DAMBH-LBUU6-RMA9P-QZKDM'), 'LWC9PY');
    }

    public function testIsValidSerial()
    {
        if ($this->getTestConfig()->getShopEdition() !== 'PE') {
            $this->markTestSkipped('This test is for Professional edition only.');
        }

        $this->assertFalse($this->_oSerial->isValidSerial('47YUR-RQX6N-DAMBH-LBUU6-RMA9P-QZKDN'));

        $this->assertTrue($this->_oSerial->isValidSerial('2EKTC-M77B6-HPJTA-64EKM-LRMR4-GNVLK'));
        $this->assertTrue($this->_oSerial->isValidSerial('5K7UF-29WU7-6LPJ3-ACL2Q-NTZZ4-6GKK6'));
    }

    public function testHasModuleNondemo()
    {
        //non demo version
        $oSerial = $this->getProxyClass("oxSerial");
        $this->assertTrue((bool) $oSerial->UNIThasModule(60, 'ZJ67U-UE985-87K2P-KX35L-UZS5Z-N7DTJ'));
        $this->assertTrue((bool) $oSerial->UNIThasModule(68, 'ZJ67U-UE985-87K2P-KX35L-UZS5Z-N7DTJ'));
        $this->assertTrue((bool) $oSerial->UNIThasModule(83, 'ZJ67U-UE985-87K2P-KX35L-UZS5Z-N7DTJ'));
        $this->assertFalse((bool) $oSerial->UNIThasModule(5, 'ZJ67U-UE985-87K2P-KX35L-UZS5Z-N7DTJ'));
        $this->assertFalse((bool) $oSerial->UNIThasModule(16, 'ZJ67U-UE985-87K2P-KX35L-UZS5Z-N7DTJ'));
        $this->assertTrue((bool) $oSerial->UNIThasModule(84, 'LTUPF-RAQNU-LKQLN-QVN2A-V3PL8-63T8H'));
        $this->assertFalse((bool) $oSerial->UNIThasModule(84, 'CNSJZ-HJK78-Z786G-KNDNT-ZZ46G-KK6GK'));
    }

    public function testHasModuleDemo()
    {
        //non demo version
        $oSerial = $this->getProxyClass("oxSerial");
        //it has NO "demoshop" (60) key inside
        $this->assertFalse((bool) $oSerial->UNIThasModule(60, 'TRKZT-5FZP6-7W4K9-2SLP4-7NWM3-AN7AU'));
        $this->assertTrue((bool) $oSerial->UNIThasModule(68, 'TRKZT-5FZP6-7W4K9-2SLP4-7NWM3-AN7AU'));
        $this->assertTrue((bool) $oSerial->UNIThasModule(83, 'TRKZT-5FZP6-7W4K9-2SLP4-7NWM3-AN7AU'));
        $this->assertFalse((bool) $oSerial->UNIThasModule(41, 'TRKZT-5FZP6-7W4K9-2SLP4-7NWM3-AN7AU'));
        $this->assertFalse((bool) $oSerial->UNIThasModule(44, 'TRKZT-5FZP6-7W4K9-2SLP4-7NWM3-AN7AU'));
        $this->assertTrue((bool) $oSerial->UNIThasModule(85, 'GHPFY-8D3M6-FDNTZ-Z7DW9-5NQSK-PB7AP'));
        $this->assertFalse((bool) $oSerial->UNIThasModule(85, 'LLVAM-HZMBV-KN86Q-R5LTG-NQSKN-86GKK'));
    }

    public function testAddModule()
    {
        //add demoshop module to serial
        $this->assertEquals($this->_oSerial->addModule(60, 'TRKZT-5FZP6-7W4K9-2SLP4-7NWM3-AN7AU'), 'Y5NCN-RVFUP-67W4T-PAEZ4-2Z26G-KK6GK');
    }

    public function testGetChecksum()
    {
        $this->assertEquals($this->_oSerial->getCheckSum('My test value'), '7T');
        $this->assertEquals($this->_oSerial->getCheckSum('value I'), 'SA');
        $this->assertEquals($this->_oSerial->getCheckSum('Value 0'), 'M8');
    }

    public function testUnmangleSerial()
    {
        $this->assertEquals($this->_oSerial->unmangleSerial('WZ9ZV-U8Q99-Q5MRC-SJPM8-8AU9Y-7BA83'), 'AAABAAJFYBAAAJAAJAAAAA');
    }

    public function testMangleSerial()
    {
        $this->assertEquals($this->_oSerial->mangleSerial('ACSAAAWNYBJABAAEAAAAAA'), 'VZ39P-9GCZL-HABTR-K9QB9-U2RG8-789JC');
    }

    public function testAddSerial()
    {
        $this->assertEquals($this->_oSerial->getSName(), '');
    }

    public function testGetBlankSerial()
    {
        $this->assertEquals($this->_oSerial->getBlankSerial(), 'JJ9PT-AYV6H-PJTJ9-KGNVL-KK6GK-K6GKK');
    }

    public function testGetDemoSerial()
    {
        $this->assertEquals($this->_oSerial->getDemoserial(), 'PXK48-B2BBZ-2P6L8-WPY4K-6GKK6-GKK6G');
    }

    public function testIsDemoSerial()
    {
        $this->assertFalse($this->_oSerial->isDemoserial('B5FER-2T33M-R4GN5-TA83R-3S582-JQBQM'));
        $this->assertTrue($this->_oSerial->isDemoserial('83FKZ-RHCLK-NDNT9-9AEZ4-Z6FDN-TZZ46'));
    }

    public function testIsUnlicensedSerial()
    {
        $this->assertTrue($this->_oSerial->isUnlicensedSerial('83FKZ-RHCLK-NDNT9'));
        $this->assertFalse($this->_oSerial->isUnlicensedSerial('83FKZ-RHCLK-NDNT9-9AEZ4-Z6FDN-TZZ46'));
    }

    public function testDetectVersion()
    {
        $this->assertEquals($this->_oSerial->detectVersion('83FKZ-RHCLK-NDNT9'), 0);
        $this->assertEquals($this->_oSerial->detectVersion('83FKZ-RHCLK-NDNT9-9AEZ4-Z6FDN-TZZ46'), 1);
        $this->assertEquals($this->_oSerial->detectVersion('M526Q-8EE2B-AP8ZF-VATYA-7TZZ4-6GKK6'), 2);
        $this->assertEquals($this->_oSerial->detectVersion('U3AJE-F46NU-FRHJY-5Q967-W4KZ6-FDNTZ'), 3);
    }

    public function testGetMaxDays()
    {
        $this->assertEquals($this->_oSerial->getMaxDays('83FKZ-RHCLK-NDNT9-9AEZ4-Z6FDN-TZZ46'), 0);
        $this->assertEquals($this->_oSerial->getMaxDays('55ZD7-FQ5P7-955RB-KMLRM-R4GNV-LKK6G'), 30);
        $this->assertTrue($this->_oSerial->getMaxDays('BAV5U-G359P-67W4F-NJ699-C3N9L-Q4N86') > 200000000);
    }

    public function testGetMaxArticles()
    {
        $this->assertEquals($this->_oSerial->getMaxArticles('DTUSW-3Y6PL-T379U-46JNU-LKQLN-QSKN8'), 4000);
        $this->assertEquals($this->_oSerial->getMaxArticles('ZA96Q-HCA84-K6GKK-46MH4-DM882-JQBQM'), 0);
        $this->assertTrue($this->_oSerial->getMaxArticles('BAV5U-G359P-67W4F-NJ699-C3N9L-Q4N86') > 200000000);
    }

    public function testGetMaxShops()
    {
        $this->assertEquals($this->_oSerial->getMaxShops('DTUSW-3Y6PL-T379U-46JNU-LKQLN-QSKN8'), 2);
        $this->assertEquals($this->_oSerial->getMaxShops('Q8TP5-VX97N-DNTZ9-87QMD-LNQSK-N86GK'), 0);
        $this->assertEquals($this->_oSerial->getMaxShops('G98DK-UQMBE-382JQ-BT6E6-JPHPJ-TJ9KG'), 15);
        $this->assertEquals($this->_oSerial->getMaxShops('QMJJN-T5RBP-SLH82-JTX7R-79LQ4-N86GK'), 63);
        $this->assertEquals($this->_oSerial->getMaxShops('H8Z23-764ZZ-92MNC-E3LXZ-P67W4-KZ6FD'), 101);
        $this->assertEquals($this->_oSerial->getMaxShops('Z2SUS-YN3DB-BFW4K-Z5MUJ-CF5AN-7AUKG'), 126);
        $this->assertTrue($this->_oSerial->getMaxShops('UUPVX-FH2K4-Y4K6Q-9RATV-N4GNV-LKK6G') > 2000);
    }

    public function testIsStackable()
    {
        $this->assertTrue($this->_oSerial->isStackable('NHHYR-NN297-6LPJ3-SBGSS-ATYA7-TZZ46'));
        $this->assertFalse($this->_oSerial->isStackable('3FSG6-LRRAQ-NULKY-59LKQ-LNQSK-N86GK'));
    }

    /**
     * 'demoshop' module should not be included in installation serial keys
     */
    public function testDemoSerialHasNoDemoShop()
    {
        //non demo version
        $oSerial = $this->getProxyClass("oxSerial");

        $this->assertFalse((bool) $oSerial->UNIThasModule(60, 'TRKZT-5FZP6-7W4K9-2SLP4-7NWM3-AN7AU'));
        $this->assertTrue((bool) $oSerial->UNIThasModule(60, 'Y5NCN-RVFUP-67W4T-PAEZ4-2Z26G-KK6GK'));

        //most important check - default EE and PE installation keys should not have demoshop
        $this->assertFalse((bool) $oSerial->UNIThasModule(60, 'TRKZT-5FZP6-7W4K9-2SLP4-7NWM3-AN7AU'));
        $this->assertFalse((bool) $oSerial->UNIThasModule(60, '3Q3EQ-U4562-Y9JTE-2N6LP-JTJ9K-GNVLK'));
    }

    /**
     * Tests Beta key.
     */
    public function testBetaKey()
    {
        if ($this->getTestConfig()->getShopEdition() !== 'PE') {
            $this->markTestSkipped('This test is for Professional edition only.');
        }

        $sBetaKey = 'FLNBR-LTGNQ-SKN8E-CK759-M7MAM-B9PKG';

        $oSerial = oxNew(\OxidEsales\Eshop\Core\Serial::class);

        $this->assertTrue($oSerial->isUnlicensedSerial($sBetaKey));
        $this->assertEquals(1, $oSerial->detectVersion($sBetaKey));
    }

    public function testValidateShop_SerialExpired_ShopUnlicensed()
    {
        $this->setConfigParam('sTagList', time() / 2);
        $this->setConfigParam('IMD', 0);
        $oSerial = $this->_getSerial(false, true);

        $this->assertFalse($oSerial->validateShop());
        $this->assertEquals('serial_expired', $oSerial->getValidationMessage());
    }

    public function testValidateShop_BetaSerialShopVersionNotBeta_ShopUnlicensed()
    {
        $oSerial = $this->_getSerial(false, true, true, false);

        $this->assertFalse($oSerial->validateShop());
        $this->assertEquals('shop_unlicensed', $oSerial->getValidationMessage());
    }

    public function testValidateShop_BetaShopNotValid_ShopUnlicensed()
    {
        $oSerial = $this->_getSerial(true, true, true, false);
        $this->assertFalse($oSerial->validateShop());
        $this->assertEquals('shop_unlicensed', $oSerial->getValidationMessage());
    }

    public function testValidateShop_SerialUnlicensed_ShopUnlicensed()
    {
        $oSerial = $this->_getSerial(true, true);

        $this->assertFalse($oSerial->validateShop());
        $this->assertEquals('shop_unlicensed', $oSerial->getValidationMessage());
    }

    public function testValidateShop_SerialNotCorrect_CheckEachCallAdd()
    {
        $oConfig = $this->getConfig();
        $oConfig->saveShopConfVar('bool', 'blShopStopped', 'true');

        $oSerial = $this->_getSerial(true, true);
        $oSerial->validateShop();
        $this->assertEquals($oConfig->getShopConfVar('blShopStopped'), true);
    }

    public function testValidateShop_SerialCorrect_CheckEachCallRemove()
    {
        $oConfig = $this->getConfig();
        $oConfig->saveShopConfVar('bool', 'blShopStopped', 'true');

        $oSerial = $this->_getSerial(false, true);
        $oSerial->validateShop();
        $this->assertFalse($oConfig->getShopConfVar('blShopStopped'));
    }

    public function testValidateShop_SerialUnlicensedShopInGracePeriod_shopValid()
    {
        $oSerial = $this->_getSerial(true, false);
        $this->assertTrue($oSerial->validateShop());
    }

    public function testValidateShop_SerialValidShopInGracePeriod_shopValid()
    {
        $oSerial = $this->_getSerial(false, false);
        $this->assertTrue($oSerial->validateShop());
    }

    public function providerIsGracePeriodStarted()
    {
        return array(
            array('', false),
            array(time(), true),
        );
    }

    /**
     * @dataProvider providerIsGracePeriodStarted
     */
    public function testIsGracePeriodStarted($sBackTag, $blPeriodStarted)
    {
        $this->setConfigParam('sBackTag', $sBackTag);
        $oSerial = $this->getMock(\OxidEsales\Eshop\Core\Serial::class, array('getConfig'));
        $oSerial->expects($this->any())->method('getConfig')->will($this->returnValue($this->getConfig()));

        $this->assertEquals($blPeriodStarted, $oSerial->isGracePeriodStarted());
    }

    public function providerIsGracePeriodExpired()
    {
        return array(
            array('', false),
            array(time(), false),
            array(time() / (7 * 24 * 60 * 60) + 1, true),
            array(time() / 2, true),
        );
    }

    /**
     * @dataProvider providerIsGracePeriodExpired
     */
    public function testIsGracePeriodExpired($sBackTag, $blPeriodExpired)
    {
        $this->setConfigParam('sBackTag', $sBackTag);
        $oSerial = $this->getMock(\OxidEsales\Eshop\Core\Serial::class, array('getConfig'));
        $oSerial->expects($this->any())->method('getConfig')->will($this->returnValue($this->getConfig()));

        $this->assertEquals($blPeriodExpired, $oSerial->isGracePeriodExpired());
    }

    public function testValidateShop_ShopNotValid_ValidationCorrect()
    {
        /** @var oxSerial $oSerial */
        $oSerial = $this->getMock(\OxidEsales\Eshop\Core\Serial::class, array('isShopValid', 'getExpirationEmailBuilder'));
        $oSerial->expects($this->any())->method('isShopValid')->will($this->returnValue(false));
        $oSerial->expects($this->any())->method('getExpirationEmailBuilder')->will($this->returnValue($this->_getMockedExpirationEmailBuilder()));

        $this->assertTrue($oSerial->validateShop());
        $this->assertTrue($oSerial->isGracePeriodStarted(), 'Grace period did not start.');
    }

    /**
     * Testing if send function of oxEmail was called once when calling validateShop method twice.
     */
    public function testValidateShop_ShopIsNotValidAndGracePeriodNotStarted_EmailWasSentOnce()
    {
        /** @var oxSerial $oSerial */
        $oSerial = $this->getMock(\OxidEsales\Eshop\Core\Serial::class, array('isShopValid', 'getExpirationEmailBuilder'));
        $oSerial->expects($this->any())->method('isShopValid')->will($this->returnValue(false));
        $oSerial->expects($this->any())->method('getExpirationEmailBuilder')->will($this->returnValue($this->_getMockedExpirationEmailBuilder('once')));

        $oSerial->validateShop();
        $oSerial->validateShop();
    }

    public function testGetGracePeriodResetEmailBuilder()
    {
        $serial = new \OxidEsales\Eshop\Core\Serial();
        $this->assertInstanceOf(
            GracePeriodResetEmailBuilder::class,
            $serial->getGracePeriodResetEmailBuilder()
        );
    }

    /**
     * Testing if send function of oxEmail was called once when calling validateShop method twice.
     */
    public function testValidateShop_ShopGracePeriodShouldReset_GracePeriodResetEmailIsSentOnceOnGraceReset()
    {
        // Mark grace period as active
        $this->setConfigParam('sBackTag', 1);

        // expect email will be sent once
        $emailMock = $this->getMockBuilder(\OxidEsales\Eshop\Core\Email::class)
            ->disableOriginalConstructor() // Constructor of class Email has side effects on this tests
            ->setMethods(['send'])
            ->getMock();
        $emailMock->expects($this->once())->method('send');

        $emailBuilderStub = $this->getMockBuilder(\OxidEsales\Eshop\Core\GracePeriodResetEmailBuilder::class)
            ->getMock();
        $emailBuilderStub->method('build')->willReturn($emailMock);

        $serialStub = $this->getMockBuilder(\OxidEsales\Eshop\Core\Serial::class)
            ->setMethods(['isShopValid','getGracePeriodResetEmailBuilder'])
            ->getMock();

        $serialStub->method('isShopValid')->willReturn(true);
        $serialStub->method('getGracePeriodResetEmailBuilder')->willReturn($emailBuilderStub);

        /** @var \OxidEsales\Eshop\Core\Serial $serialStub */
        $serialStub->validateShop();
        $serialStub->validateShop();
    }

    /**
     * Given cases shows that when grace period is started and starts 6-th day email is sent once.
     * Also there are cases that if there is 5th day, than email is not sent at all.
     *
     * @return array
     */
    public function providerValidateShop_ShopIsNotValidAndGracePeriodWillExpireInADay_EmailWasSentOnce()
    {
        /** @var int $iGracePeriodStartTime Time in seconds from now 6 days. */
        $iGracePeriodStartTime = \OxidEsales\Eshop\Core\Registry::getUtilsDate()->getTime() - 6 * 24 * 60 * 60;
        $iLastGracePeriodDay = $iGracePeriodStartTime - 60 * 60 * 12;
        $iGracePeriodJustEnded = $iGracePeriodStartTime - 24 * 60 * 60;
        $iGracePeriodEnded = $iGracePeriodStartTime - (24 * 60 * 60 + 60);
        $iGracePeriodNotStarted = $iGracePeriodStartTime + 60 * 60;

        return array(
            // Grace period will start soon.
            array($iGracePeriodNotStarted, 'never'),
            // Last grace period day just started, so need to send an email.
            array($iGracePeriodStartTime, 'once'),
            // Last grace period day, so need to send an email.
            array($iLastGracePeriodDay, 'once'),
            // Last grace period day just ended, no need to send an email.
            array($iGracePeriodJustEnded, 'never'),
            // Last grace period day ended, no need to send an email.
            array($iGracePeriodEnded, 'never'),
        );
    }

    /**
     *
     *
     * @param $iGracePeriodStartTime
     * @param $sSendExpectsToBeCalled
     *
     * @dataProvider providerValidateShop_ShopIsNotValidAndGracePeriodWillExpireInADay_EmailWasSentOnce
     */
    public function testValidateShop_ShopIsNotValidAndGracePeriodWillExpireInADay($iGracePeriodStartTime, $sSendExpectsToBeCalled)
    {
        /** @var oxSerial $oSerial */
        $oSerial = $this->getMock(\OxidEsales\Eshop\Core\Serial::class, array('isShopValid', 'getExpirationEmailBuilder', 'isGracePeriodExpired'));
        $oSerial->expects($this->any())->method('isShopValid')->will($this->returnValue(false));
        $oSerial->expects($this->any())->method('getExpirationEmailBuilder')->will($this->returnValue($this->_getMockedExpirationEmailBuilder($sSendExpectsToBeCalled)));
        $oSerial->expects($this->any())->method('isGracePeriodExpired')->will($this->returnValue(false));

        $this->getConfig()->setConfigParam('sBackTag', $iGracePeriodStartTime);

        $oSerial->validateShop();
        $oSerial->validateShop();
    }

    private function _getSerial($isUnlicensed = false, $blGracePeriodExpired = false, $blBetaSerial = false) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $oSerial = $this->getMock(\OxidEsales\Eshop\Core\Serial::class, array('isUnlicensedSerial', 'isGracePeriodExpired', 'isGracePeriodStarted', 'isBetaSerial', 'getEmail'));
        $oSerial->expects($this->any())->method('isUnlicensedSerial')->will($this->returnValue($isUnlicensed));
        $oSerial->expects($this->any())->method('isGracePeriodStarted')->will($this->returnValue(true));
        $oSerial->expects($this->any())->method('isGracePeriodExpired')->will($this->returnValue($blGracePeriodExpired));
        $oSerial->expects($this->any())->method('isBetaSerial')->will($this->returnValue($blBetaSerial));
        $oSerial->expects($this->any())->method('getEmail')->will($this->returnValue($this->_getMockedExpirationEmailBuilder()));

        return $oSerial;
    }

    /**
     * Function mocks oxEmail and oxExpirationEmailBuilder.
     *
     * @param string $sSendExpectsToBeCalled defines how much time should be called send function.
     *
     * @return ExpirationEmailBuilder
     */
    private function _getMockedExpirationEmailBuilder($sSendExpectsToBeCalled = 'any') // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $oEmail = $this->getMock(\OxidEsales\Eshop\Core\Email::class, array('send'));
        $oEmail->expects($this->$sSendExpectsToBeCalled())->method('send');

        $oEmailBuilder = $this->getMock(ExpirationEmailBuilder::class, array('build'));
        $oEmailBuilder->expects($this->any())->method('build')->will($this->returnValue($oEmail));

        return $oEmailBuilder;
    }
}
