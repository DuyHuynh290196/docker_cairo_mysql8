<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Tests\Unit\Core;

use oxConnectionException;
use OxidEsales\EshopEnterprise\Core\LDAP as oxLdap;
use OxidEsales\TestingLibrary\UnitTestCase;

/**
 * Test oxLdap test class
 */
class LDAPTest extends UnitTestCase
{
    protected $_sUserName = "user name";
    protected $_sPassword = "user pass";
    protected $_sHost = "host";
    protected $_sDc = "cd";

    /**
     * Test setup
     */
    protected function setUp(): void
    {
        if (!extension_loaded('ldap')) {
            $this->markTestSkipped('LDAP module do not exist.');
        }

        parent::setUp();
    }

    /**
     * oxLdap::__construct() test case
     */
    public function testConstruction()
    {
        try {
            new \OxidEsales\Eshop\Core\LDAP($this->_sHost, 1);
        } catch (\OxidEsales\EshopCommunity\Core\Exception\ConnectionException $oEx) {
            $this->assertEquals("ERROR_MESSAGE_CONNECTION_NOLDAP", $oEx->getMessage());
        }
    }

    /**
     * oxLdap::login() test case
     */
    public function testLoginWrongLogin()
    {
        $oLdap = new \OxidEsales\Eshop\Core\LDAP($this->_sHost, 389);
        $oLdap->setVerbose(true);

        $this->expectException('oxConnectionException'); $this->expectExceptionMessage( 'ERROR_MESSAGE_CONNECTION_NOLDAPBIND');
        $oLdap->login("testuser", "testpw", "query", "basedn", "filter");
    }

    /**
     * oxLdap::login() test case
     */
    public function testLogin()
    {
        $oLdap = new \OxidEsales\Eshop\Core\LDAP($this->_sHost, 389);
        $oLdap->setVerbose(true);

        $this->expectException('oxConnectionException'); $this->expectExceptionMessage( 'ERROR_MESSAGE_CONNECTION_NOLDAPBIND');
        $oLdap->login("testit@oxid-esales.local", "ldap4lt", "@@USERNAME@@", "ou=MyBusiness,DC={$this->_sDc},DC=local", "(&(|(objectClass=user)(objectClass=contact))(objectCategory=person)(cn=@@USERNAME@@))");
    }

    /**
     * oxLdap::setErrorMsg() & oxLdap::getErrorMsg() test case
     */
    public function testSetErrorMsgGetErrorMsg()
    {
        $sMsg = "testMsg";
        $oLdap = new \OxidEsales\Eshop\Core\LDAP($this->_sHost, 389);
        $oLdap->setErrorMsg($sMsg);
        $this->assertEquals($sMsg, $oLdap->getErrorMsg());
    }

    /**
     * oxLdap::setResult() & oxLdap::mapData() test case
     */
    public function testSetResultMapData()
    {
        $aDataMap = array("givenname" => "OXFNAME",
            "sn" => "OXLNAME",
            "l" => "OXCITY",
            "postalcode" => "OXZIP",
            "telephonenumber" => "OXFON",
            "co" => "OXCOUNTRY",
            "streetaddress" => "OXSTREET",
            "mail" => "OXUSERNAME"
        );

        $oLdap = new \OxidEsales\Eshop\Core\LDAP($this->_sHost, 389);
        $aData[0] = $aDataMap;
        $aData['count'] = 1;
        // Set result if login do not succeed.
        // And it will not succeed as we give wrong information to login function.
        $oLdap->setResult($aData);
        $oLdap->login($this->_sUserName, $this->_sPassword, "@@USERNAME@@", "ou=MyBusiness,DC={$this->_sDc},DC=local", "(&(|(objectClass=user)(objectClass=contact))(objectCategory=person)(cn=@@USERNAME@@))");
        $aResult = $oLdap->mapData($aDataMap);
        $this->assertTrue(isset($aResult["OXFNAME"]));
        $this->assertTrue(isset($aResult["OXLNAME"]));
        $this->assertTrue(isset($aResult["OXFON"]));
        $this->assertTrue(isset($aResult["OXCOUNTRY"]));
        $this->assertTrue(isset($aResult["OXUSERNAME"]));
    }
}
