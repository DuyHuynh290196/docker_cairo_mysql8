<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Tests\Unit\Core;

use oxDb;
use oxEmail;
use oxField;
use OxidEsales\TestingLibrary\UnitTestCase;
use OxidEsales\Eshop\Core\Registry;
use oxShop;
use PHPUnit\Framework\MockObject\MockObject;

class EmailWithAzureTemplateTest extends UnitTestCase
{
    /**
     * Initialize the fixture.
     */
    protected function setUp(): void
    {
        parent::setUp();

        // reload smarty
        Registry::getUtilsView()->getSmarty(true);
    }

    /**
     * Tear down the fixture.
     */
    protected function tearDown(): void
    {
        // reload smarty
        Registry::getUtilsView()->getSmarty(true);

        $oActShop = $this->getConfig()->getActiveShop();
        $oActShop->setLanguage(0);
        Registry::getLang()->setBaseLanguage(0);

        parent::tearDown();
    }

    /**
     * Test sending forgot password to not existing user
     */
    public function testSendForgotPwdEmailToUserInAnotherShopMallUsers()
    {
        $this->getConfig()->setConfigParam('sTheme', 'azure');
        $this->getConfig()->setConfigParam('blMallUsers', 1);
        \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute(
            "INSERT INTO  `oxuser` (
                    `OXID`, `OXACTIVE`, `OXRIGHTS`, `OXSHOPID`, `OXUSERNAME`,
                    `OXPASSWORD`, `OXPASSSALT`, `OXCUSTNR`, `OXUSTID`, `OXUSTIDSTATUS`,
                    `OXCOMPANY`, `OXFNAME`, `OXLNAME`, `OXSTREET`, `OXSTREETNR`, `OXADDINFO`,
                    `OXCITY`, `OXCOUNTRYID`, `OXZIP`, `OXFON`, `OXFAX`, `OXSAL`,
                    `OXBONI`, `OXCREATE`, `OXREGISTER`, `OXPRIVFON`, `OXMOBFON`, `OXBIRTHDATE`,
                    `OXURL`, `OXLDAPKEY`, `OXWRONGLOGINS`, `OXUPDATEKEY`, `OXUPDATEEXP`
                )
                VALUES (
                    '_test_another', '1',  'user', '5', 'test@testforsubs.com',
                    'zzz', '', '0', '', '0',
                     '', 'testUserFName', 'testUserLName', '', '', '',
                     '', '', '', '', '', '',
                     '0', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00',
                     '', '', '0', '', '0'
                );
            "
        );

        /** @var \OxidEsales\Eshop\Core\Email|PHPUnit\Framework\MockObject\MockObject $email */
        $email = $this->getMock(\OxidEsales\Eshop\Core\Email::class, array("_sendMail", "_getShop", "_getUseInlineImages"));
        $email->expects($this->once())->method('_sendMail')->will($this->returnValue(true));
        $email->expects($this->any())->method('_getShop')->will($this->returnValue($this->getShop()));
        $email->expects($this->any())->method('_getUseInlineImages')->will($this->returnValue(true));

        $result = $email->sendForgotPwdEmail('test@testforsubs.com');
        $this->assertTrue($result, 'Forgot password email was not sent');

        // check mail fields
        $fields['sRecipient'] = 'test@testforsubs.com';
        $fields['sRecipientName'] = 'testUserFName testUserLName';
        $fields['sSubject'] = 'testUserFogotPwdSubject';
        $fields['sFrom'] = 'orderemail@orderemail.nl';
        $fields['sFromName'] = 'testShopName';
        $fields['sReplyTo'] = 'orderemail@orderemail.nl';
        $fields['sReplyToName'] = 'testShopName';

        $this->checkMailFields($fields, $email);
        $this->checkMailBody('testSendForgotPwdEmail', $email->getBody());
    }

    /**
     * @param array   $fields
     * @param \OxidEsales\Eshop\Core\Email $email
     */
    protected function checkMailFields($fields, $email)
    {
        if ($fields['sRecipient']) {
            $recipient = $email->getRecipient();
            $this->assertEquals($fields['sRecipient'], $recipient[0][0], 'Incorrect mail recipient');
        }

        if ($fields['sRecipientName']) {
            $recipient = $email->getRecipient();
            $this->assertEquals($fields['sRecipientName'], $recipient[0][1], 'Incorrect mail recipient name');
        }

        if ($fields['sSubject']) {
            $this->assertEquals($fields['sSubject'], $email->getSubject(), 'Incorrect mail subject');
        }

        if ($fields['sFrom']) {
            $from = $email->getFrom();
            $this->assertEquals($fields['sFrom'], $from, 'Incorrect mail from address');
        }

        if ($fields['sFromName']) {
            $fromName = $email->getFromName();
            $this->assertEquals($fields['sFromName'], $fromName, 'Incorrect mail from name');
        }

        if ($fields['sReplyTo']) {
            $replyTo = $email->getReplyTo();
            $this->assertEquals($fields['sReplyTo'], $replyTo[0][0], 'Incorrect mail reply to address');
        }

        if ($fields['sReplyToName']) {
            $replyTo = $email->getReplyTo();
            $this->assertEquals($fields['sReplyToName'], $replyTo[0][1], 'Incorrect mail reply to name');
        }

        if ($fields['sBody']) {
            $this->assertEquals($fields['sBody'], $email->getBody(), 'Incorrect mail body');
        }
    }

    /**
     * @param string $function
     * @param string $body
     * @param bool   $writeToTestFile
     */
    protected function checkMailBody($function, $body, $writeToTestFile = false)
    {
        // uncomment line to generate template for checking mail body
        // file_put_contents(__DIR__ ."/../testData/email_templates/azure/$function.html", $body);

        $path = __DIR__ .'/../TestData/email_templates/azure/' . $function . '_utf8.html';
        if (!($expectedBody = file_get_contents($path))) {
            $this->fail("Template '$path' was not found!");
        }

        //remove <img src="cid:1192193298470f6d12383b8" ... from body, because it is everytime different
        $expectedBody = preg_replace("/cid:[0-9a-zA-Z]+\"/", "cid:\"", $expectedBody);

        //replacing test shop id to good one
        $expectedBody = preg_replace("/shp\=testShopId/", "shp=1", $expectedBody);

        $body = preg_replace("/cid:[0-9a-zA-Z]+\"/", "cid:\"", $body);

        // A. very special case for user password reminder
        if ($function == 'testSendForgotPwdEmail') {
            $expectedBody = preg_replace("/uid=[0-9a-zA-Z]+\&amp;/", "", $expectedBody);
            $body = preg_replace("/uid=[0-9a-zA-Z]+\&amp;/", "", $body);
        }

        $expectedBody = preg_replace("/\s+/", " ", $expectedBody);
        $body = preg_replace("/\s+/", " ", $body);

        $expectedBody = str_replace("> <", "><", $expectedBody);
        $body = str_replace("> <", "><", $body);

        $expectedShopUrl = "http://eshop/";
        $shopUrl = $this->getConfig()->getConfigParam('sShopURL');

        //remove shop url base path from links
        $body = str_replace($shopUrl, $expectedShopUrl, $body);

        if ($writeToTestFile) {
            file_put_contents(__DIR__ .'/../testData/email_templates/azure/' . $function . '_test_expecting.html', $expectedBody);
            file_put_contents(__DIR__ .'/../testData/email_templates/azure/' . $function . '_test_result.html', $body);
        }

        $this->assertEquals(strtolower(trim($expectedBody)), strtolower(trim($body)), "Incorect mail body");
    }

    /**
     * @return oxShop
     */
    protected function getShop()
    {
        $shop = oxNew(\OxidEsales\Eshop\Application\Model\Shop::class);
        $shop->load($this->getConfig()->getShopId());
        $shop->oxshops__oxorderemail = new \OxidEsales\Eshop\Core\Field('orderemail@orderemail.nl', \OxidEsales\Eshop\Core\Field::T_RAW);
        $shop->oxshops__oxordersubject = new \OxidEsales\Eshop\Core\Field('testOrderSubject', \OxidEsales\Eshop\Core\Field::T_RAW);
        $shop->oxshops__oxsendednowsubject = new \OxidEsales\Eshop\Core\Field('testSendedNowSubject', \OxidEsales\Eshop\Core\Field::T_RAW);
        $shop->oxshops__oxname = new \OxidEsales\Eshop\Core\Field('testShopName', \OxidEsales\Eshop\Core\Field::T_RAW);
        $shop->oxshops__oxowneremail = new \OxidEsales\Eshop\Core\Field('shopOwner@shopOwnerEmail.nl', \OxidEsales\Eshop\Core\Field::T_RAW);
        $shop->oxshops__oxinfoemail = new \OxidEsales\Eshop\Core\Field('shopInfoEmail@shopOwnerEmail.nl', \OxidEsales\Eshop\Core\Field::T_RAW);
        $shop->oxshops__oxsmtp = new \OxidEsales\Eshop\Core\Field('127.0.0.1', \OxidEsales\Eshop\Core\Field::T_RAW);
        $shop->oxshops__oxsmtpuser = new \OxidEsales\Eshop\Core\Field('testSmtpUser', \OxidEsales\Eshop\Core\Field::T_RAW);
        $shop->oxshops__oxsmtppwd = new \OxidEsales\Eshop\Core\Field('testSmtpPassword', \OxidEsales\Eshop\Core\Field::T_RAW);
        $shop->oxshops__oxregistersubject = new \OxidEsales\Eshop\Core\Field('testUserRegistrationSubject', \OxidEsales\Eshop\Core\Field::T_RAW);
        $shop->oxshops__oxforgotpwdsubject = new \OxidEsales\Eshop\Core\Field('testUserFogotPwdSubject', \OxidEsales\Eshop\Core\Field::T_RAW);

        return $shop;
    }
}
