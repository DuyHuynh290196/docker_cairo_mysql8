<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Tests\Unit\Model;

use OxidEsales\Eshop\Application\Model\User;
use OxidEsales\Eshop\Core\Exception\UserException;
use OxidEsales\Eshop\Core\Registry;
use oxTestModules;

class UserTest extends \OxidEsales\TestingLibrary\UnitTestCase
{
    protected $_aUsers = array();

    /**
     * Tear down the fixture.
     *
     * @return null
     */
    protected function tearDown(): void
    {
        foreach ($this->_aUsers as $sUserId => $oUser) {
            /** @var \OxidEsales\Eshop\Application\Model\User $oUser */
            $oUser->delete($sUserId);
        }
        parent::tearDown();
    }

    public function testChangeUserDataWithIncorrectVatId()
    {
        $oUser = $this->getMock(\OxidEsales\Eshop\Application\Model\User::class, array("checkValues", "assign", "save", "_setAutoGroups"));

        $oUser->expects($this->once())->method('checkValues')->with('user', 'password', 'password2', 'inv-address', 'del-address');
        $oUser->expects($this->once())->method('assign')->with($this->equalto('inv-address'));
        $oUser->expects($this->once())->method('save')->will($this->returnValue(false));
        $oUser->expects($this->never())->method('_setAutoGroups');

        $oInputValidator = $this->getMock(\OxidEsales\Eshop\Core\InputValidator::class);
        $oInputValidator->expects($this->once())->method('checkVatId')->with($this->isInstanceOf('\OxidEsales\EshopCommunity\Application\Model\User'), 'inv-address')->will($this->throwException(new \OxidEsales\Eshop\Core\Exception\StandardException()));
        Registry::set(\OxidEsales\Eshop\Core\InputValidator::class, $oInputValidator);

        $oUser->changeUserData('user', 'password', 'password2', 'inv-address', 'del-address');

        $this->assertEquals(0, $oUser->oxuser__oxustidstatus->value);
    }

    /**
     * User creation problems in MALL
     * EE only
     */
    // saving subshop admins details in main shop, users shop id and rights must not be changed
    public function testSubShopAminInMainShop()
    {
        $oUser = $this->createUser();
        $sUserId = $oUser->getId();

        // creating subshop user ..
        $oUser = oxNew(\OxidEsales\Eshop\Core\Model\BaseModel::class);
        $oUser->init('oxuser');
        $oUser->load($sUserId);
        $oUser->oxuser__oxshopid = new \OxidEsales\Eshop\Core\Field(2, \OxidEsales\Eshop\Core\Field::T_RAW);
        $oUser->oxuser__oxrights = new \OxidEsales\Eshop\Core\Field('malladmin', \OxidEsales\Eshop\Core\Field::T_RAW);
        $oUser->save();

        // saving user in base shop
        $oUser = oxNew(\OxidEsales\Eshop\Application\Model\User::class);
        $oUser->load($sUserId);
        $oUser->save();

        // now testing for user rights ..
        $this->assertEquals(2, $oUser->oxuser__oxshopid->value, 'changed user shop id');
        $this->assertEquals('malladmin', $oUser->oxuser__oxrights->value, 'changed user rights');
    }

    /**
     * Testing user object saving if birthday is added.
     */
    public function testSaveWithBirthDay()
    {
        $oUser = oxNew(\OxidEsales\Eshop\Application\Model\User::class);
        $oUser->setId($oUser->getId());
        $oUser->oxuser__oxbirthdate = new \OxidEsales\Eshop\Core\Field(array('day' => '12', 'month' => '12', 'year' => '1212'), \OxidEsales\Eshop\Core\Field::T_RAW);
        $oUser->save();
        $this->assertEquals('1212-12-12', $oUser->oxuser__oxbirthdate->value);
    }

    public function testDelete()
    {
        $oDb = $this->getDb();

        $oUser = $this->createUser();
        $sUserId = $oUser->getId();

        $o2r = oxNew(\OxidEsales\Eshop\Core\Model\BaseModel::class);
        $o2r->init("oxobject2role");
        $o2r->setId("_testo2r");
        $o2r->oxobject2role__oxobjectid = new \OxidEsales\Eshop\Core\Field($sUserId);
        $o2r->oxobject2role__oxroleid = new \OxidEsales\Eshop\Core\Field($sUserId);
        $o2r->save();

        $oUser = oxNew(\OxidEsales\Eshop\Application\Model\User::class);
        $oUser->load($sUserId);
        $oUser->delete();

        $aWhat['oxobject2role'] = 'oxobjectid';

        // now checking if all related records were deleted
        foreach ($aWhat as $sTable => $sField) {
            $sQ = 'select count(*) from ' . $sTable . ' where ' . $sField . ' = "' . $sUserId . '" ';

            if ($sTable == 'oxremark') {
                $sQ .= " AND oxtype ='o'";
            }

            $iCnt = $oDb->getOne($sQ);
            if ($iCnt > 0) {
                $this->fail($iCnt . ' records were not deleted from "' . $sTable . '" table');
            }
        }
    }

    // Checking order count for random user but emulating non multishop. order count must be 1
    public function testGetOrdersForRandumUserNotMultishop()
    {
        $myConfig = $this->getConfig();

        $oActShop = oxNew(\OxidEsales\Eshop\Application\Model\Shop::class);
        $oActShop->sShopID = $myConfig->getShopId();
        $oActShop->oxshops__oxismultishop = new \OxidEsales\Eshop\Core\Field(false, \OxidEsales\Eshop\Core\Field::T_RAW);

        $this->getConfig()->setConfigParam('oActShop', $oActShop);

        $oUser = $this->createUser();
        $oOrders = $oUser->getOrders();

        // checking order count
        $this->assertEquals(1, count($oOrders));
    }

    public function testCheckValuesWithConnectionException()
    {
        $oInputValidator = $this->getMock(\OxidEsales\Eshop\Core\InputValidator::class);
        $oInputValidator->expects($this->once())->method("checkVatId")->will($this->throwException(new \OxidEsales\Eshop\Core\Exception\ConnectionException()));
        Registry::set(\OxidEsales\Eshop\Core\InputValidator::class, $oInputValidator);

        $oUser = oxNew(\OxidEsales\Eshop\Application\Model\User::class);
        $oUser->checkValues("X", "X", "X", array(), array());
    }

    /**
     * Testing customer information update function
     */
    // 1. all data "is fine" (emulated), just checking if all necessary methods were called
    public function testChangeUserDataAllDataIsFine()
    {
        $oUser = $this->getMock(\OxidEsales\Eshop\Application\Model\User::class, array("checkValues", "assign", "save", "_setAutoGroups"));
        $oUser->expects($this->once())->method('checkValues');
        $oUser->expects($this->once())->method('assign');
        $oUser->expects($this->once())->method('save')->will($this->returnValue(true));
        $oUser->expects($this->once())->method('_setAutoGroups');

        $oInputValidator = $this->getMock(\OxidEsales\Eshop\Core\InputValidator::class);
        $oInputValidator->expects($this->once())->method('checkVatId')->will($this->returnValue(null));
        Registry::set(\OxidEsales\Eshop\Core\InputValidator::class, $oInputValidator);

        $oUser->changeUserData(null, null, null, null, null, null, null, null, null, null);

        $this->assertEquals(1, $oUser->oxuser__oxustidstatus->value);
    }

    /**
     * \OxidEsales\Eshop\Application\Model\User::login() and \OxidEsales\Eshop\Application\Model\User::logout() test
     */
    public function testNotSuccessfulDbLoginTriggersLdapLoginAndThrowsException()
    {
        $this->expectException(\OxidEsales\Eshop\Core\Exception\UserException::class);
        $this->expectExceptionMessage('ERROR_MESSAGE_USER_NOVALIDLOGIN');

        $this->getConfig()->setConfigParam('blUseLDAP', 1);
        $this->getConfig()->setConfigParam('blMallUsers', 1);

        $userMock = $this
            ->getMockBuilder(\OxidEsales\Eshop\Application\Model\User::class)
            ->setMethods(['_ldapLogin'])
            ->getMock();

        $userName = oxADMIN_LOGIN;
        $password = 'wrong_password';
        $userMock
            ->expects($this->once())
            ->method('_ldapLogin')
            ->with(
                $this->equalTo($userName),
                $this->equalTo('' . $password . ''),
                $this->equalTo($this->getConfig()->getShopId()),
                $this->equalTo('')
            );

        $userMock->login($userName, $password);
    }

    public function testCanRead()
    {
        $oUser = oxNew(\OxidEsales\Eshop\Application\Model\User::class);
        $this->assertTrue($oUser->canRead());
    }

    // if this function begins to return false, probably shop does not run at all :)
    public function testCanReadField()
    {
        $oUser = oxNew(\OxidEsales\Eshop\Application\Model\User::class);
        $this->assertTrue($oUser->canReadField('xxx'));
    }

    /**
     * \OxidEsales\Eshop\Application\Model\User::_ldapLogin() test case.
     */
    public function testLdapLoginDataMapFails()
    {
        $aLdapParams = array('HOST'      => 'HOST',
            'PORT'      => 'PORT',
            'USERQUERY' => 'USERQUERY',
            'BASEDN'    => 'BASEDN',
            'FILTER'    => 'FILTER',
            'DATAMAP'   => 'DATAMAP');

        $oConfig = $this->getConfig();
        $oConfig->setConfigParam("aLDAPParams", $aLdapParams);


        $oLdap = $this->getMock(\OxidEsales\Eshop\Core\LDAP::class, array("login", "mapData"), array(), '', false);
        $oLdap->expects($this->once())->method('login')->with(
            $this->equalTo("testUser"),
            $this->equalTo("testPassword"),
            $this->equalTo("USERQUERY"),
            $this->equalTo("BASEDN"),
            $this->equalTo("FILTER")
        );
        $oLdap->expects($this->once())->method('mapData')->with($this->equalTo('DATAMAP'));
        oxTestModules::addModuleObject("oxLDAP", $oLdap);

        try {
            $oUser = $this->getMock(\OxidEsales\Eshop\Application\Model\User::class, array("setId", "setPassword", "save", "load"));
            $oUser->expects($this->never())->method('setId');
            $oUser->expects($this->never())->method('setPassword');
            $oUser->expects($this->never())->method('save');
            $oUser->expects($this->never())->method('load');
            $oUser->UNITldapLogin("testUser", "testPassword", "testshopid", "");
        } catch (\OxidEsales\EshopCommunity\Core\Exception\UserException $oExcp) {
            return;
        }
        $this->fail("Error while runing testLdapLogin");
    }

    /**
     * \OxidEsales\Eshop\Application\Model\User::_ldapLogin() test case.
     */
    public function testLdapLogin()
    {
        $aReturn["OXUSERNAME"] = $this->getDb()->getOne("select oxusername from oxuser where oxid = 'oxdefaultadmin'");
        $aLdapParams = array('HOST'      => 'HOST',
            'PORT'      => 'PORT',
            'USERQUERY' => 'USERQUERY',
            'BASEDN'    => 'BASEDN',
            'FILTER'    => 'FILTER',
            'DATAMAP'   => 'DATAMAP');

        $oConfig = $this->getConfig();
        $oConfig->setConfigParam("aLDAPParams", $aLdapParams);


        $oLdap = $this->getMock(\OxidEsales\Eshop\Core\LDAP::class, array("login", "mapData"), array(), '', false);
        $oLdap->expects($this->once())->method('login')->with(
            $this->equalTo("testUser"),
            $this->equalTo("testPassword"),
            $this->equalTo("USERQUERY"),
            $this->equalTo("BASEDN"),
            $this->equalTo("FILTER")
        );
        $oLdap->expects($this->once())->method('mapData')->with($this->equalTo('DATAMAP'))->will($this->returnValue($aReturn));
        oxTestModules::addModuleObject("oxLDAP", $oLdap);

        $oUser = $this->getMock(\OxidEsales\Eshop\Application\Model\User::class, array("setId", "setPassword", "save", "load"));
        $oUser->expects($this->never())->method('setId');
        $oUser->expects($this->never())->method('setPassword');
        $oUser->expects($this->never())->method('save');
        $oUser->expects($this->once())->method('load');
        $oUser->UNITldapLogin("testUser", "testPassword", "testshopid", "");
    }

    /**
     * \OxidEsales\Eshop\Application\Model\User::_ldapLogin() test case.
     */
    public function testLdapLoginCreatignNewUser()
    {
        $aReturn["OXUSERNAME"] = "testUser";
        $aLdapParams = array('HOST'      => 'HOST',
            'PORT'      => 'PORT',
            'USERQUERY' => 'USERQUERY',
            'BASEDN'    => 'BASEDN',
            'FILTER'    => 'FILTER',
            'DATAMAP'   => 'DATAMAP');

        $oConfig = $this->getConfig();
        $oConfig->setConfigParam("aLDAPParams", $aLdapParams);


        $oLdap = $this->getMock(\OxidEsales\Eshop\Core\LDAP::class, array("login", "mapData"), array(), '', false);
        $oLdap->expects($this->once())->method('login')->with(
            $this->equalTo("testUser"),
            $this->equalTo("testPassword"),
            $this->equalTo("USERQUERY"),
            $this->equalTo("BASEDN"),
            $this->equalTo("FILTER")
        );
        $oLdap->expects($this->once())->method('mapData')->with($this->equalTo('DATAMAP'))->will($this->returnValue($aReturn));
        oxTestModules::addModuleObject("oxLDAP", $oLdap);

        $oUser = $this->getMock(\OxidEsales\Eshop\Application\Model\User::class, array("setId", "setPassword", "save", "load"));
        $oUser->expects($this->once())->method('setId');
        $oUser->expects($this->once())->method('setPassword');
        $oUser->expects($this->once())->method('save');
        $oUser->expects($this->never())->method('load');
        $oUser->UNITldapLogin("testUser", "testPassword", "testshopid", "");
    }

    /**
     * Testing if shopselect is got by ldap.
     */
    public function testLoginLdapGotShopSelectAdmin()
    {
        $this->getConfig()->setConfigParam("blUseLDAP", true);
        $this->getConfig()->getConfigParam("blMallUsers", false);

        $oUser = $this->getMock(\OxidEsales\Eshop\Application\Model\User::class, array("getId"));
        $oUser->expects($this->any())->method('getId')->will($this->returnValue(null));
        $sShopSelect = " and ( oxrights != 'user' ) ";
        $this->assertEquals(
            $sShopSelect,
            $oUser->_getShopSelect($this->getConfig(), $this->getConfig()->getShopId(), true)
        );
    }

    public function testLoginInStagingModeThrowsExceptionWhenUsingInvalidCredentials()
    {
        $this->expectException(UserException::class);
        $configMock = $this->getMockBuilder(\OxidEsales\Eshop\Core\Config::class)->setMethods(array("isStagingMode"))->getMock();
        $configMock
            ->expects($this->atLeastOnce())
            ->method('isStagingMode')
            ->willReturn(true);
        Registry::set(\OxidEsales\Eshop\Core\Config::class, $configMock);
        $this->setAdminMode(true);

        $user = oxNew(User::class);
        $user->login("INVALID_USERNAME", "INVALID_PASSWORD");
    }

    public function testLoginInStagingModeLoadsAdminWhenUsingValidCredentials()
    {
        $configMock = $this->getMockBuilder(\OxidEsales\Eshop\Core\Config::class)->setMethods(array("isStagingMode"))->getMock();
        $configMock
            ->expects($this->atLeastOnce())
            ->method('isStagingMode')
            ->willReturn(true);
        Registry::set(\OxidEsales\Eshop\Core\Config::class, $configMock);
        $this->setAdminMode(true);

        /** Modify password of admin user to make 'normal' login fail */
        $user = oxNew(User::class);
        $user->load('oxdefaultadmin');
        $user->setPassword('MODIFIED_PASSWORD');
        $user->save();

        $user = oxNew(User::class);
        /** The values for user name and password are hardcoded in the staging mode business logic */
        $user->login('admin', 'admin');
        $userIdFromLogin = $user->getId();

        $this->assertSame('oxdefaultadmin', $userIdFromLogin);
    }

    /**
     * Creates user.
     *
     * @param string $sUserName
     * @param int    $iActive
     * @param string $sRights either user or malladmin
     * @param int    $sShopId User shop ID
     *
     * @return \OxidEsales\Eshop\Application\Model\User
     */
    protected function createUser($sUserName = null, $iActive = 1, $sRights = 'user', $sShopId = null)
    {
        $oUtils = Registry::getUtils();
        $oDb = $this->getDb();

        $iLastNr = count($this->_aUsers) + 1;

        if (is_null($sShopId)) {
            $sShopId = $this->getConfig()->getShopId();
        }

        $oUser = oxNew(\OxidEsales\Eshop\Application\Model\User::class);
        $oUser->oxuser__oxshopid = new \OxidEsales\Eshop\Core\Field($sShopId, \OxidEsales\Eshop\Core\Field::T_RAW);
        $oUser->oxuser__oxactive = new \OxidEsales\Eshop\Core\Field($iActive, \OxidEsales\Eshop\Core\Field::T_RAW);
        $oUser->oxuser__oxrights = new \OxidEsales\Eshop\Core\Field($sRights, \OxidEsales\Eshop\Core\Field::T_RAW);

        // setting name
        $sUserName = $sUserName ? $sUserName : 'test' . $iLastNr . '@oxid-esales.com';
        $oUser->oxuser__oxusername = new \OxidEsales\Eshop\Core\Field($sUserName, \OxidEsales\Eshop\Core\Field::T_RAW);
        $oUser->oxuser__oxpassword = new \OxidEsales\Eshop\Core\Field(crc32($sUserName), \OxidEsales\Eshop\Core\Field::T_RAW);
        $oUser->oxuser__oxcountryid = new \OxidEsales\Eshop\Core\Field("testCountry", \OxidEsales\Eshop\Core\Field::T_RAW);
        $oUser->save();

        $sUserId = $oUser->getId();
        $sId = Registry::getUtilsObject()->generateUID();

        // loading user groups
        $sGroupId = $oDb->getOne('select oxid from oxgroups order by rand() ');
        $sQ = 'insert into oxobject2group (oxid,oxshopid,oxobjectid,oxgroupsid) values ( "' . $sUserId . '", "' . $sShopId . '", "' . $sUserId . '", "' . $sGroupId . '" )';
        $oDb->Execute($sQ);

        $sQ = 'insert into oxorder ( oxid, oxshopid, oxuserid, oxorderdate ) values ( "' . $sId . '", "' . $sShopId . '", "' . $sUserId . '", "' . date('Y-m-d  H:i:s', time() + 3600) . '" ) ';
        $oDb->Execute($sQ);

        // adding article to order
        $sArticleID = $oDb->getOne('select oxid from oxarticles order by rand() ');
        $sQ = 'insert into oxorderarticles ( oxid, oxorderid, oxamount, oxartid, oxartnum ) values ( "' . $sId . '", "' . $sId . '", 1, "' . $sArticleID . '", "' . $sArticleID . '" ) ';
        $oDb->Execute($sQ);

        // adding article to basket
        $sQ = 'insert into oxuserbaskets ( oxid, oxuserid, oxtitle ) values ( "' . $sUserId . '", "' . $sUserId . '", "oxtest" ) ';
        $oDb->Execute($sQ);

        $sArticleID = $oDb->getOne('select oxid from oxarticles order by rand() ');
        $sQ = 'insert into oxuserbasketitems ( oxid, oxbasketid, oxartid, oxamount ) values ( "' . $sUserId . '", "' . $sUserId . '", "' . $sArticleID . '", "1" ) ';
        $oDb->Execute($sQ);

        // creating test address
        $sCountryId = $oDb->getOne('select oxid from oxcountry where oxactive = "1"');
        $sQ = 'insert into oxaddress ( oxid, oxuserid, oxaddressuserid, oxcountryid ) values ( "test_user' . $iLastNr . '", "' . $sUserId . '", "' . $sUserId . '", "' . $sCountryId . '" ) ';
        $oDb->Execute($sQ);

        // creating test executed user payment
        $aDynValue = $this->_aDynPaymentFields;
        $oPayment = oxNew(\OxidEsales\Eshop\Application\Model\Payment::class);
        $oPayment->load('oxiddebitnote');
        $oPayment->setDynValues($oUtils->assignValuesFromText($oPayment->oxpayments__oxvaldesc->value, true, true, true));

        $aDynValues = $oPayment->getDynValues();
        foreach ($aDynValues as $key => $oVal) {
            $oVal = new \OxidEsales\Eshop\Core\Field($aDynValue[$oVal->name], \OxidEsales\Eshop\Core\Field::T_RAW);
            $oPayment->setDynValue($key, $oVal);
            $aDynVal[$oVal->name] = $oVal->value;
        }

        $sDynValues = '';
        if (isset($aDynVal)) {
            $sDynValues = $oUtils->assignValuesToText($aDynVal);
        }

        $sQ = 'insert into oxuserpayments ( oxid, oxuserid, oxpaymentsid, oxvalue ) values ( "' . $sId . '", "' . $sUserId . '", "oxiddebitnote", "' . $sDynValues . '" ) ';
        $oDb->Execute($sQ);

        $this->_aUsers[$sUserId] = $oUser;

        return $oUser;
    }
}
