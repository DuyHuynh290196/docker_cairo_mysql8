<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */
namespace OxidEsales\EshopEnterprise\Tests\Integration\User;

use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Core\Field;

/**
 * Class Integration_User_AdminLoginSubShopsTest
 */
class AdminLoginSubShopsTest extends UserTestCase
{
    /**
     * Initialize the fixture.
     * Admin login must have at least one cookie, otherwise login fails with no cookie exception.
     */
    public function setup(): void
    {
        parent::setUp();
        $this->setAdminMode(true);
        $_COOKIE['someAdminCookie'] = 'somevalue';
    }

    /**
     * Disables admin mode.
     */
    public function tearDown(): void
    {
        $this->setAdminMode(false);
        parent::tearDown();
    }

    /**
     * @return array
     */
    public function providerSuccessfulSubShopAdminLogin()
    {
        return array(
            // First sub shop admin logs to shop.
            array('oxidadmin', 1),
            // Second sub shop admin logs to shop.
            array('oxidadmin', 2),
            // First sub shop mall admin logs to shop.
            array('malladmin', 1),
            // Second sub shop mall admin logs to shop.
            array('malladmin', 2),
        );
    }

    /**
     * @param string $rights
     * @param int    $subShopId
     *
     * @dataProvider providerSuccessfulSubShopAdminLogin
     */
    public function testSuccessfulSubShopAdminLogin($rights, $subShopId)
    {
        $this->createSubShop(2);
        $this->setLoginParametersToRequest();
        $user = $this->createDefaultUser($rights, $subShopId);
        $userId = $user->getId();

        $login = oxNew(\OxidEsales\Eshop\Application\Controller\Admin\LoginController::class);
        $this->assertSame("admin_start", $login->checklogin());

        $user = oxNew(\OxidEsales\Eshop\Application\Model\User::class);
        $user->load($userId);
        $this->assertEquals($subShopId, $user->getFieldData('oxshopid'), "User shop ID changed");
        $this->assertSame($userId, Registry::getSession()->getVariable('auth'), 'User ID is missing in session.');
        $this->assertNotSame($this->_sOldEncodedPassword, $user->getFieldData('oxpassword'), 'Old and new passwords must not match.');
        $this->assertNotSame($this->_sOldSalt, $user->getFieldData('oxpasssalt'), 'Old and new salt must not match.');

    }

    /**
     * @return array
     */
    public function providerFailureSubShopAdminLogin()
    {
        return array(
            // User from first sub shop tries to login as admin.
            array('user', 1),
            // User from second sub shop tries to login as admin.
            array('user', 2),
        );
    }

    /**
     * @param string $rights
     * @param int    $subShopId
     *
     * @dataProvider providerFailureSubShopAdminLogin
     */
    public function testFailureSubShopAdminLogin($rights, $subShopId)
    {
        $this->createSubShop(2);
        $this->setLoginParametersToRequest();
        $this->createDefaultUser($rights, $subShopId);

        $login = oxNew(\OxidEsales\Eshop\Application\Controller\Admin\LoginController::class);
        $this->assertEmpty($login->checklogin(), 'Get this result: ' . $login->checklogin());
    }
    
    /**
     * Adds login data to request.
     *
     * @param string $userName
     * @param string $userPassword
     */
    protected function setLoginParametersToRequest($userName = null, $userPassword = null)
    {
        $userName = !is_null($userName) ? $userName : $this->_sDefaultUserName;
        $userPassword = !is_null($userPassword) ? $userPassword : $this->_sDefaultUserPassword;

        $this->setRequestParameter('user', $userName);
        $this->setRequestParameter('pwd', $userPassword);
    }
}
