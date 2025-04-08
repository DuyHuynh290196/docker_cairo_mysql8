<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */
namespace OxidEsales\EshopEnterprise\Tests\Integration\User;

use OxidEsales\Eshop\Core\Registry;

class LoginInSubShopTest extends UserTestCase
{
    /**
     * @return array
     */
    public function providerSuccessfulLoginInDifferentSubShop()
    {
        return array(
            //Old style md5 password encoding
            // Login not in to the same shop where mall admin was created.
            array(2, 1, 'malladmin', false, true),
            array(2, 1, 'malladmin', true, true),
            // Login in to the same shop where mall admin was created.
            array(1, 1, 'malladmin', false, true),
            // Login in to the same shop where user was created.
            array(1, 1, 'user', false, true),
            // Login not in to the same shop where user was created.
            array(1, 2, 'user', true, true),
            // Login in to the same shop where admin was created.
            array(2, 2, 'oxidadmin', false, true),
            // Login not in to the same shop where admin was created.
            array(1, 2, 'oxidadmin', true, true),

            //New style encoding
            // Login not in to the same shop where mall admin was created.
            array(2, 1, 'malladmin', false, false),
            array(2, 1, 'malladmin', true, false),
            // Login in to the same shop where mall admin was created.
            array(1, 1, 'malladmin', false, false),
            // Login in to the same shop where user was created.
            array(1, 1, 'user', false, false),
            // Login not in to the same shop where user was created.
            array(1, 2, 'user', true, false),
            // Login in to the same shop where admin was created.
            array(2, 2, 'oxidadmin', false, false),
            // Login not in to the same shop where admin was created.
            array(1, 2, 'oxidadmin', true, false),

        );
    }

    /**
     * @param int    $shopIdToLogIn
     * @param int    $shopIdToCreateUser
     * @param string $userRight
     * @param bool   $allowUsersFromOtherShops
     * @param bool   $md5Encoded
     *
     * @dataProvider providerSuccessfulLoginInDifferentSubShop
     */
    public function testSuccessfulLoginInDifferentSubShop($shopIdToLogIn, $shopIdToCreateUser, $userRight, $allowUsersFromOtherShops, $md5Encoded)
    {
        $user = $this->createDefaultUser($userRight, $shopIdToCreateUser, $md5Encoded);
        $this->createSubShop(2);

        $this->setShopId($shopIdToLogIn);
        $this->setConfigParam('blMallUsers', $allowUsersFromOtherShops);
        $this->login();

        $this->assertSame($user->getId(), Registry::getSession()->getVariable('usr'), 'User ID is missing in session.');
    }

    /**
     * @return array
     */
    public function providerNotSuccessfulLoginInDifferentSubShop()
    {
        return array(
            // User tries to login in to other subshop. Old style encoded.
            array(2, 1, 'user', true),
            // Admin tries to login in to other subshop. Old style encoded.
            array(1, 2, 'oxidadmin', true),
            // User tries to login in to other subshop. New encoding.
            array(2, 1, 'user', false),
            // Admin tries to login in to other subshop. New encoding.
            array(1, 2, 'oxidadmin', false),

        );
    }

    /**
     * @param int    $shopIdToLogIn
     * @param int    $shopIdToCreateUser
     * @param string $userRight
     * @param bool   $md5Encoded
     *
     * @dataProvider providerNotSuccessfulLoginInDifferentSubShop
     */
    public function testNotSuccessfulLoginInDifferentSubShop($shopIdToLogIn, $shopIdToCreateUser, $userRight, $md5Encoded)
    {
        $this->createDefaultUser($userRight, $shopIdToCreateUser, $md5Encoded);
        $this->createSubShop(2);

        $this->setShopId($shopIdToLogIn);
        $this->login();

        $this->assertNull(Registry::getSession()->getVariable('usr'), 'User ID should not be set session.');
    }

    /**
     * @return array
     */
    public function providerLoginInMulitshopSameCredentials()
    {
        $userModes = array(
                        array(true, true),
                        array(true, false),
                        array(false, true),
                        array(false, false),
                      );
        return $userModes;
    }

    /**
     * Test case for bugfix #5988.
     *
     * @dataProvider providerLoginInMulitshopSameCredentials
     *
     * @param bool $subshop1UserMode
     * @param bool $subshop2UserMode
     */
    public function testLoginInMulitshopSameCredentials($subshop1UserMode, $subshop2UserMode)
    {
        //subshop 1 MD5 encoded user
        $user1 = $this->createDefaultUser("user", 1, $subshop1UserMode);
        $this->createSubShop(2);

        //subshop newly encoded user
        $this->setShopId(2);
        $user2 = $this->createDefaultUser("user", 2, $subshop2UserMode);

        //checking if users are really different
        $this->assertNotEquals($user1->getId(), $user2->getId());

        //check login to subshop 1
        $this->assertNull(Registry::getSession()->getVariable('usr'), 'User ID should not be set session.');
        $this->setShopId(1);
        //user password and salt is regenerated to the new one after the login
        $this->login();
        $this->assertSame($user1->getId(), Registry::getSession()->getVariable('usr'), 'User ID is missing in session.');

        //check login to subshop 2
        $this->setShopId(2);
        $this->login();
        $this->assertSame($user2->getId(), Registry::getSession()->getVariable('usr'), 'User ID is missing in session.');
    }
}
