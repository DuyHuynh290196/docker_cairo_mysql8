<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Tests\Unit\Model;

use oxField;

class UserListTest extends \oxUnitTestCase
{
    /**
     * Initialize the fixture.
     *
     * @return null
     */
    public function setup(): void
    {
        parent::setUp();
        $oUser = oxNew(\OxidEsales\Eshop\Application\Model\User::class);
        $oUser->setId('user1');
        $oUser->oxuser__oxactive = new \OxidEsales\Eshop\Core\Field(1, \OxidEsales\Eshop\Core\Field::T_RAW);
        $oUser->oxuser__oxshopid = new \OxidEsales\Eshop\Core\Field($this->getConfig()->getBaseShopId(), \OxidEsales\Eshop\Core\Field::T_RAW);
        $oUser->oxuser__oxusername = new \OxidEsales\Eshop\Core\Field('user1', \OxidEsales\Eshop\Core\Field::T_RAW);
        $oUser->save();


        $oUser = oxNew(\OxidEsales\Eshop\Application\Model\User::class);
        $oUser->setId('user2');
        $oUser->oxuser__oxactive = new \OxidEsales\Eshop\Core\Field(1, \OxidEsales\Eshop\Core\Field::T_RAW);
        $oUser->oxuser__oxshopid = new \OxidEsales\Eshop\Core\Field(2, \OxidEsales\Eshop\Core\Field::T_RAW);
        $oUser->oxuser__oxusername = new \OxidEsales\Eshop\Core\Field('user2', \OxidEsales\Eshop\Core\Field::T_RAW);
        $oUser->save();

        $oBasket = oxNew(\OxidEsales\Eshop\Application\Model\UserBasket::class);
        $oBasket->setId("testUserBasket");
        $oBasket->oxuserbaskets__oxuserid = new \OxidEsales\Eshop\Core\Field('user2', \OxidEsales\Eshop\Core\Field::T_RAW);
        $oBasket->oxuserbaskets__oxtitle = new \OxidEsales\Eshop\Core\Field('wishlist', \OxidEsales\Eshop\Core\Field::T_RAW);
        $oBasket->oxuserbaskets__oxpublic = new \OxidEsales\Eshop\Core\Field(1, \OxidEsales\Eshop\Core\Field::T_RAW);
        $oBasket->save();

        $oBasketItem = oxNew(\OxidEsales\Eshop\Application\Model\UserBasketItem::class);
        $oBasketItem->setId("testUserBasketItem");
        $oBasketItem->oxuserbasketitems__oxbasketid = new \OxidEsales\Eshop\Core\Field('testUserBasket', \OxidEsales\Eshop\Core\Field::T_RAW);
        $oBasketItem->oxuserbasketitems__oxamount = new \OxidEsales\Eshop\Core\Field(1, \OxidEsales\Eshop\Core\Field::T_RAW);
        $oBasketItem->oxuserbasketitems__oxartid = new \OxidEsales\Eshop\Core\Field('test', \OxidEsales\Eshop\Core\Field::T_RAW);
        $oBasketItem->save();

        $oBasket = oxNew(\OxidEsales\Eshop\Application\Model\UserBasket::class);
        $oBasket->setId("testUserBasket2");
        $oBasket->oxuserbaskets__oxuserid = new \OxidEsales\Eshop\Core\Field('user1', \OxidEsales\Eshop\Core\Field::T_RAW);
        $oBasket->oxuserbaskets__oxtitle = new \OxidEsales\Eshop\Core\Field('wishlist', \OxidEsales\Eshop\Core\Field::T_RAW);
        $oBasket->oxuserbaskets__oxpublic = new \OxidEsales\Eshop\Core\Field(1, \OxidEsales\Eshop\Core\Field::T_RAW);
        $oBasket->save();

        $oBasketItem = oxNew(\OxidEsales\Eshop\Application\Model\UserBasketItem::class);
        $oBasketItem->setId("testUserBasketItem2");
        $oBasketItem->oxuserbasketitems__oxbasketid = new \OxidEsales\Eshop\Core\Field('testUserBasket2', \OxidEsales\Eshop\Core\Field::T_RAW);
        $oBasketItem->oxuserbasketitems__oxamount = new \OxidEsales\Eshop\Core\Field(1, \OxidEsales\Eshop\Core\Field::T_RAW);
        $oBasketItem->oxuserbasketitems__oxartid = new \OxidEsales\Eshop\Core\Field('test', \OxidEsales\Eshop\Core\Field::T_RAW);
        $oBasketItem->save();
    }

    /**
     * Tear down the fixture.
     *
     * @return null
     */
    public function tearDown(): void
    {
        $oUser = oxNew(\OxidEsales\Eshop\Application\Model\User::class);
        $oUser->delete('user1');
        $oUser->delete('user2');
        $oUserBasket = oxNew(\OxidEsales\Eshop\Application\Model\UserBasket::class);
        $oUserBasket->delete("testUserBasket");
        $oUserBasket->delete("testUserBasket2");
        $oUserBasket = oxNew(\OxidEsales\Eshop\Application\Model\UserBasketItem::class);
        $oUserBasket->delete("testUserBasketItem");
        $oUserBasket->delete("testUserBasketItem2");

        parent::tearDown();
    }

    /**
     * Checking if object is loaded and if type is valid
     */
    public function testUserListLoadingEnabledShopCheck()
    {
        $iUserCount = '7';

        $oUser = oxNew(\OxidEsales\Eshop\Application\Model\User::class);
        $oUser->setDisableShopCheck(false);
        $oUserList = oxNew(\OxidEsales\Eshop\Application\Model\UserList::class);
        $oUserList->selectString($oUser->buildSelectString());

        $this->assertEquals($iUserCount, $oUserList->count());
    }

    public function testUserListLoadingDisabledShopcheck()
    {
        $iUserCount = '8';

        $oUser = oxNew(\OxidEsales\Eshop\Application\Model\User::class);
        $oUser->setDisableShopCheck(true);
        $oUserList = oxNew(\OxidEsales\Eshop\Application\Model\UserList::class);
        $oUserList->selectString($oUser->buildSelectString());

        $this->assertEquals($iUserCount, $oUserList->count());
    }
}
