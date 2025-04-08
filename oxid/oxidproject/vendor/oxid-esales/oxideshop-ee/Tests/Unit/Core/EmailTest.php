<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Tests\Unit\Core;

use \oxDb;
use \oxField;
use \oxTestModules;
use OxidEsales\Eshop\Core\Registry;

class EmailTest extends \oxUnitTestCase
{
    /**
     * Initialize the fixture.
     *
     * @return null
     */
    protected function setUp(): void
    {
        parent::setUp();

        // set shop params for testing
        $this->_oShop = oxNew(\OxidEsales\Eshop\Application\Model\Shop::class);
        $this->_oShop->load($this->getConfig()->getShopId());
        $this->_oShop->oxshops__oxorderemail = new \OxidEsales\Eshop\Core\Field('orderemail@orderemail.nl', \OxidEsales\Eshop\Core\Field::T_RAW);
        $this->_oShop->oxshops__oxordersubject = new \OxidEsales\Eshop\Core\Field('testOrderSubject', \OxidEsales\Eshop\Core\Field::T_RAW);
        $this->_oShop->oxshops__oxsendednowsubject = new \OxidEsales\Eshop\Core\Field('testSendedNowSubject', \OxidEsales\Eshop\Core\Field::T_RAW);
        $this->_oShop->oxshops__oxname = new \OxidEsales\Eshop\Core\Field('testShopName', \OxidEsales\Eshop\Core\Field::T_RAW);
        $this->_oShop->oxshops__oxowneremail = new \OxidEsales\Eshop\Core\Field('shopOwner@shopOwnerEmail.nl', \OxidEsales\Eshop\Core\Field::T_RAW);
        $this->_oShop->oxshops__oxinfoemail = new \OxidEsales\Eshop\Core\Field('shopInfoEmail@shopOwnerEmail.nl', \OxidEsales\Eshop\Core\Field::T_RAW);
        //$this->_oShop->oxshops__oxsmtp = new \OxidEsales\Eshop\Core\Field('localhost', \OxidEsales\Eshop\Core\Field::T_RAW);
        $this->_oShop->oxshops__oxsmtp = new \OxidEsales\Eshop\Core\Field('127.0.0.1', \OxidEsales\Eshop\Core\Field::T_RAW);
        $this->_oShop->oxshops__oxsmtpuser = new \OxidEsales\Eshop\Core\Field('testSmtpUser', \OxidEsales\Eshop\Core\Field::T_RAW);
        $this->_oShop->oxshops__oxsmtppwd = new \OxidEsales\Eshop\Core\Field('testSmtpPassword', \OxidEsales\Eshop\Core\Field::T_RAW);
        $this->_oShop->oxshops__oxregistersubject = new \OxidEsales\Eshop\Core\Field('testUserRegistrationSubject', \OxidEsales\Eshop\Core\Field::T_RAW);
        $this->_oShop->oxshops__oxforgotpwdsubject = new \OxidEsales\Eshop\Core\Field('testUserFogotPwdSubject', \OxidEsales\Eshop\Core\Field::T_RAW);
    }

    /**
     * Tear down the fixture.
     *
     * @return null
     */
    protected function tearDown(): void
    {
        $oActShop = $this->getConfig()->getActiveShop();
        $oActShop->setLanguage(0);
        Registry::getLang()->setBaseLanguage(0);

        parent::tearDown();
    }

    /**
     * Test sending forgot password to not existing user
     */
    public function testSendForgotPwdEmailToUserInAnotherShopNoMallUsers()
    {
        $this->getConfig()->setConfigParam('blMallUsers', 0);

        \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute(
            "INSERT INTO  `oxuser` (
                                            `OXID` , `OXACTIVE` , `OXRIGHTS` , `OXSHOPID` , `OXUSERNAME` ,
                                            `OXPASSWORD` , `OXPASSSALT` , `OXCUSTNR` , `OXUSTID` , `OXUSTIDSTATUS` ,
                                            `OXCOMPANY` , `OXFNAME` , `OXLNAME` , `OXSTREET` , `OXSTREETNR` ,`OXADDINFO` ,
                                            `OXCITY` ,`OXCOUNTRYID` ,`OXZIP` , `OXFON` , `OXFAX` , `OXSAL` ,
                                            `OXBONI` , `OXCREATE` , `OXREGISTER` , `OXPRIVFON` , `OXMOBFON` , `OXBIRTHDATE` ,
                                            `OXURL`, `OXLDAPKEY` , `OXWRONGLOGINS` , `OXUPDATEKEY` ,
                                            `OXUPDATEEXP`
                                            )
                                            VALUES (
                                            '_test_another',  '1',  'user',  '5',  'test@testforsubs.com',  'zzz',  '',  '0',  '',  '0',  '',  '',  '',  '',  '',  '',  '',  '',  '',  '',  '',  '',  '0',  '0000-00-00 00:00:00',  '0000-00-00 00:00:00',  '',  '',  '0000-00-00', '',  '',  '0',  '',  '0'
                                            );
                                            "
        );

        $oEmail = $this->getMock(\OxidEsales\Eshop\Core\Email::class, array("_sendMail", "_getShop"));
        $oEmail->expects($this->never())->method('_sendMail');
        $oEmail->expects($this->any())->method('_getShop')->will($this->returnValue($this->_oShop));

        $blRet = $oEmail->sendForgotPwdEmail('test@testforsubs.com');
        $this->assertFalse($blRet, 'Mail was sent to user from another shop');
    }
}
