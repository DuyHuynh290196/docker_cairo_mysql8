<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Tests\Unit\Core;

use OxidEsales\Eshop\Core\ExpirationEmailBuilder;
use OxidEsales\EshopProfessional\Core\Serial;

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

    public function setup(): void
    {
        parent::setUp();

        $this->_oSerial = new _oxSerial();
        $myConfig = $this->getConfig();

        $this->aConfig = array();
        $this->aConfig['blShopStopped'] = \OxidEsales\Eshop\Core\DatabaseProvider::getDb(\OxidEsales\Eshop\Core\DatabaseProvider::FETCH_MODE_ASSOC)->getRow("select * from oxconfig where oxvarname='blShopStopped'");

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

    public function testIsValidSerial()
    {
        $this->assertFalse($this->_oSerial->isValidSerial('47YUR-RQX6N-DAMBH-LBUU6-RMA9P-QZKDN'));

        $this->assertTrue($this->_oSerial->isValidSerial('U7YUR-RQX6N-DAMBH-LBUU6-RMA9P-QZKDM'));
        $this->assertTrue($this->_oSerial->isValidSerial('VFTJR-CF9KF-BRLTQ-RMUJC-F5AN7-AUKGN'));
    }

    /**
     * Tests Beta key.
     */
    public function testBetaKey()
    {
        $sBetaKey = 'FLNBR-LTGNQ-SKN8E-CK759-M7MAM-B9PKG';

        $oSerial = oxNew(\OxidEsales\Eshop\Core\Serial::class);

        $this->assertFalse($oSerial->isUnlicensedSerial($sBetaKey));
        $this->assertEquals(4, $oSerial->detectVersion($sBetaKey));
    }

    public function testValidateShop_MandateCountIncorrect_ShopUnlicensed()
    {
        $oSerial = $this->_getSerial(false, true);
        $this->getConfig()->setConfigParam('IMS', 0);

        $this->assertFalse($oSerial->validateShop());
        $this->assertEquals('incorrect_mandate_amount', $oSerial->getValidationMessage());
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
