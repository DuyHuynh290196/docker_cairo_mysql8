<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Tests\Unit\Model;

use \OxidEsales\TestingLibrary\UnitTestCase;
use \oxField;

class NewsSubscribedTest extends UnitTestCase
{
    private $_oNewsSub = null;

    /**
     * Initialize the fixture.
     *
     * @return null
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->_oNewsSub = oxNew(\OxidEsales\Eshop\Application\Model\NewsSubscribed::class);
        $this->_oNewsSub->setId('_testNewsSubscrId');
        $this->_oNewsSub->oxnewssubscribed__oxuserid = new \OxidEsales\Eshop\Core\Field('_testUserId', \OxidEsales\Eshop\Core\Field::T_RAW);
        $this->_oNewsSub->oxnewssubscribed__oxemail = new \OxidEsales\Eshop\Core\Field('useremail@useremail.nl', \OxidEsales\Eshop\Core\Field::T_RAW);
        $this->_oNewsSub->oxnewssubscribed__oxdboptin = new \OxidEsales\Eshop\Core\Field('1', \OxidEsales\Eshop\Core\Field::T_RAW);
        $this->_oNewsSub->oxnewssubscribed__oxunsubscribed = new \OxidEsales\Eshop\Core\Field('0000-00-00 00:00:00', \OxidEsales\Eshop\Core\Field::T_RAW);
        $this->_oNewsSub->save();
    }

    /**
     * Testing email subscription loader by user email with mall user.
     */
    public function testLoadFromEMailMallUser()
    {
        $oNewsSubscribed = oxNew(\OxidEsales\Eshop\Application\Model\NewsSubscribed::class);
        $oNewsSubscribed->setMallUsers(true);

        $this->assertTrue($oNewsSubscribed->loadFromEmail('useremail@useremail.nl'));
        $this->assertEquals('_testNewsSubscrId', $oNewsSubscribed->oxnewssubscribed__oxid->value);
    }

    /**
     * Testing subscription loading by userid in different shops
     */
    public function testLoadFromUserIdDifferentShops()
    {
        // assert that user is subscribed in first shop
        $sShopId1 = $this->getConfig()->getShopId();
        $oNewsSubscribed = oxNew(\OxidEsales\Eshop\Application\Model\NewsSubscribed::class);
        $this->assertTrue($oNewsSubscribed->loadFromUserId('_testUserId'));
        $this->assertEquals($sShopId1, $oNewsSubscribed->oxnewssubscribed__oxshopid->value);
        $this->assertEquals(1, $oNewsSubscribed->oxnewssubscribed__oxdboptin->value);

        // set second shop
        $sShopId2 = $sShopId1 + 1;
        $this->getConfig()->setShopId($sShopId2);

        // assert that user is not subscribed in second shop
        $oNewsSubscribed = oxNew(\OxidEsales\Eshop\Application\Model\NewsSubscribed::class);
        $this->assertFalse($oNewsSubscribed->loadFromUserId('_testUserId'));

        // subscribe user in second shop
        $oNewsSubscribed->setId('_testNewsSubscrId2');
        $oNewsSubscribed->oxnewssubscribed__oxuserid = new \OxidEsales\Eshop\Core\Field('_testUserId', \OxidEsales\Eshop\Core\Field::T_RAW);
        $oNewsSubscribed->oxnewssubscribed__oxdboptin = new \OxidEsales\Eshop\Core\Field(1, \OxidEsales\Eshop\Core\Field::T_RAW);
        $oNewsSubscribed->save();

        // assert that user is subscribed in second shop
        $oNewsSubscribed = oxNew(\OxidEsales\Eshop\Application\Model\NewsSubscribed::class);
        $this->assertTrue($oNewsSubscribed->loadFromUserId('_testUserId'));
        $this->assertEquals($sShopId2, $oNewsSubscribed->oxnewssubscribed__oxshopid->value);
        $this->assertEquals(1, $oNewsSubscribed->oxnewssubscribed__oxdboptin->value);

        // unsuscribe user in second shop and assert
        $oNewsSubscribed->oxnewssubscribed__oxdboptin->value = new \OxidEsales\Eshop\Core\Field(0, \OxidEsales\Eshop\Core\Field::T_RAW);
        $oNewsSubscribed->save();
        $this->assertTrue($oNewsSubscribed->loadFromUserId('_testUserId'));
        $this->assertEquals(0, $oNewsSubscribed->oxnewssubscribed__oxdboptin->value);

        // set first shop
        $this->getConfig()->setShopId($sShopId1);

        // check if user is still subscribed in first shop
        $oNewsSubscribed = oxNew(\OxidEsales\Eshop\Application\Model\NewsSubscribed::class);
        $this->assertTrue($oNewsSubscribed->loadFromUserId('_testUserId'));
        $this->assertEquals($sShopId1, $oNewsSubscribed->oxnewssubscribed__oxshopid->value);
        $this->assertEquals(1, $oNewsSubscribed->oxnewssubscribed__oxdboptin->value);
    }
}
