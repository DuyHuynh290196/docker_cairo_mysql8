<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */
namespace OxidEsales\EshopEnterprise\Tests\Integration\User;

use OxidEsales\EshopEnterprise\Tests\Integration\SubShopTrait;
use OxidEsales\TestingLibrary\UnitTestCase;
use oxUser;

/**
 * Base class for user test cases
 */
abstract class UserTestCase extends UnitTestCase
{
    use SubShopTrait;

    /** @var string Password encoded with old algorithm. */
    protected $_sOldEncodedPassword = '4bb11fbb0c6bf332517a7ec397e49f1c';

    /** @var string Salt generated with old algorithm. */
    protected $_sOldSalt = '3262383936333839303439393466346533653733366533346137326666393632';

    /** @var string Password encoded with new algorithm. */
    protected $_sNewEncodedPassword = 'b016e37ac8ec71449b475e84a941e3c39a27fb8f0710d4b47d6116ad6a6afcaa0c17006a4c01ffc67f3db95772fe001584cb4ce7e5bacd74198c24d1851841d5';

    /** @var string Salt generated with new algorithm. */
    protected $_sNewSalt = '56784f8ffc657fff84915b93e12a626e';

    /** @var string */
    protected $_sDefaultUserName = '_testUserName@oxid-esales.com';

    /** @var string */
    protected $_sDefaultUserPassword = '_testPassword';

    /** @var bool */
    protected $_blSkipCustomTearDown = false;

    /**
     * Restores database tables.
     */
    public function tearDown(): void
    {
        if (!$this->_blSkipCustomTearDown) {
            $dbRestore = $this->_getDbRestore();
            $dbRestore->restoreTable('oxuser');
            $dbRestore->restoreTable('oxshops');
        }
        parent::tearDown();
    }

    /**
     * Creates user with the default credentials for given shop.
     *
     * MD5 encoded password style is used for legacy shops
     *
     * @param string $right           OXRIGHTS column value ('malladmin', 'user' or <shopid>)
     * @param int    $shopId          Shop ID
     * @param bool   $md5EncodedStyle Use MD5 encoded (legacy) password encryption
     *
     * @return oxUser
     */
    protected function createDefaultUser($right, $shopId, $md5EncodedStyle = true)
    {
        if ($md5EncodedStyle) {
            $passVal = $this->_sOldEncodedPassword;
            $saltVal = $this->_sOldSalt;
        } else {
            $passVal = $this->_sNewEncodedPassword;
            $saltVal = $this->_sNewSalt;
        }

        $user = oxNew(\OxidEsales\Eshop\Application\Model\User::class);
        $user->oxuser__oxusername = new \OxidEsales\Eshop\Core\Field('_testUserName@oxid-esales.com', \OxidEsales\Eshop\Core\Field::T_RAW);
        $user->oxuser__oxpassword = new \OxidEsales\Eshop\Core\Field($passVal, \OxidEsales\Eshop\Core\Field::T_RAW);
        $user->oxuser__oxpasssalt = new \OxidEsales\Eshop\Core\Field($saltVal, \OxidEsales\Eshop\Core\Field::T_RAW);
        $user->save();

        $userFromBase = oxNew(\OxidEsales\Eshop\Core\Model\BaseModel::class);
        $userFromBase->init('oxuser');
        $userFromBase->load($user->getId());
        $userFromBase->oxuser__oxshopid = new \OxidEsales\Eshop\Core\Field($shopId, \OxidEsales\Eshop\Core\Field::T_RAW);
        $userFromBase->oxuser__oxrights = new \OxidEsales\Eshop\Core\Field($right, \OxidEsales\Eshop\Core\Field::T_RAW);
        $userFromBase->save();

        return $user;
    }

    /**
     * @param string $userName
     * @param string $userPassword
     */
    protected function login($userName = null, $userPassword = null)
    {
        if (is_null($userName)) {
            $userName = $this->_sDefaultUserName;
        }
        if (is_null($userPassword)) {
            $userPassword = $this->_sDefaultUserPassword;
        }
        $this->setLoginParametersToRequest($userName, $userPassword);
        $cmpUser = oxNew(\OxidEsales\Eshop\Application\Component\UserComponent::class);
        $cmpUser->setSession(\OxidEsales\Eshop\Core\Registry::getSession());
        $cmpUser->login();
    }

    /**
     * @param string $userName
     * @param string $userPassword
     */
    private function setLoginParametersToRequest($userName, $userPassword)
    {
        $this->setRequestParameter('lgn_usr', $userName);
        $this->setRequestParameter('lgn_pwd', $userPassword);
    }
}
