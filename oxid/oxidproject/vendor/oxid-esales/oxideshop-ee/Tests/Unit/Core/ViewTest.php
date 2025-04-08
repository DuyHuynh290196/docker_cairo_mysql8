<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Tests\Unit\Core;

use oxConfig;
use OxidEsales\TestingLibrary\UnitTestCase;
use oxView;
use PHPUnit\Framework\MockObject\MockObject;
use stdClass;
use OxidEsales\Eshop\Core\Registry;
use oxField;
use oxUtilsHelper;

require_once TEST_LIBRARY_HELPERS_PATH . 'oxUtilsHelper.php';

class ViewTest extends UnitTestCase
{
    /*
     * Test adding global params to view data
     */
    public function testAddGlobalParams()
    {
        $oView = oxNew(\OxidEsales\Eshop\Core\Controller\BaseController::class);
        $this->assertTrue($oView->isMall());
    }

    /**
     * \OxidEsales\Eshop\Core\Controller\BaseController::executeFunction() test case
     *
     * @return null
     */
    public function testExecuteFunctionEE()
    {
        $oRights = $this->getMock(\OxidEsales\Eshop\Application\Model\Rights::class, array('processView'));
        $oRights->expects($this->once())->method('processView')->will($this->returnValue(new stdClass()));

        $oCmp = $this->getMock(\OxidEsales\Eshop\Application\Component\CategoriesComponent::class, array('xxx', 'getRights'));
        $oCmp->expects($this->once())->method('xxx');
        $oCmp->expects($this->once())->method('getRights')->will($this->returnValue($oRights));

        $oCmp->executeFunction('xxx');
    }

    /**
     * \OxidEsales\Eshop\Core\Controller\BaseController::_executeNewAction() test case
     *
     * @return null
     */
    public function testExecuteNewActionSslIsAdminEE()
    {
        $this->getSession()->setId('SID');

        oxAddClassModule("oxUtilsHelper", "oxUtils");

        /** @var oxConfig|PHPUnit\Framework\MockObject\MockObject $config */
        $config = $this->getMock(\OxidEsales\Eshop\Core\Config::class, array('isSsl', 'getSslShopUrl', 'getShopUrl'));
        $config->expects($this->once())->method('isSsl')->will($this->returnValue(true));
        $config->expects($this->once())->method('getSslShopUrl')->will($this->returnValue('SSLshopurl/'));
        $config->expects($this->never())->method('getShopUrl');
        $config->setConfigParam('sAdminDir', 'admin');
        
        /** @var oxView|PHPUnit\Framework\MockObject\MockObject $view */
        $view = $this->getMock(\OxidEsales\Eshop\Core\Controller\BaseController::class, array('getConfig', 'isAdmin'));
        $view->expects($this->once())->method('getConfig')->will($this->returnValue($config));
        $view->expects($this->once())->method('isAdmin')->will($this->returnValue(true));
        $view->UNITexecuteNewAction("details?fnc=somefnc&anid=someanid");
        $sRedirectUrl = oxUtilsHelper::$sRedirectUrl;

        $this->assertEquals('SSLshopurl/admin/index.php?cl=details&fnc=somefnc&anid=someanid&' . $this->getSession()->sid(), $sRedirectUrl);
    }

    public function testIsCacheable()
    {
        $oView = oxNew(\OxidEsales\Eshop\Core\Controller\BaseController::class);
        $this->assertTrue($oView->isCacheable());
    }

    public function testSetIsCacheable()
    {
        $oView = oxNew(\OxidEsales\Eshop\Core\Controller\BaseController::class);
        $oView->setIsCacheable(false);
        $this->assertFalse($oView->isCacheable());
    }

    /**
     * Tests \OxidEsales\Eshop\Core\Controller\BaseController::getCacheLifeTime() with all tables used in function
     */
    public function testGetCacheLifeTime_FromAllTables()
    {
        $this->_mockUtilsDateTime(Registry::getUtilsDate()->getTime());

        $sActiveFrom = date('Y-m-d H:i:s', Registry::getUtilsDate()->getTime() + 5);
        $oDiscount = oxNew(\OxidEsales\Eshop\Application\Model\Discount::class);
        $oDiscount->setId('_testDiscount');
        $oDiscount->oxdiscount__oxactivefrom = new \OxidEsales\Eshop\Core\Field($sActiveFrom);
        $oDiscount->save();

        $sActiveFrom = date('Y-m-d H:i:s', Registry::getUtilsDate()->getTime() + 4);
        $sUpdatePriceTime = date('Y-m-d H:i:s', Registry::getUtilsDate()->getTime() + 3);
        $oArticle = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
        $oArticle->setId('_testArticle');
        $oArticle->oxarticles__oxactivefrom = new \OxidEsales\Eshop\Core\Field($sActiveFrom);
        $oArticle->oxarticles__oxupdatepricetime = new \OxidEsales\Eshop\Core\Field($sUpdatePriceTime);
        $oArticle->save();

        $sUpdatePriceTime = date('Y-m-d H:i:s', Registry::getUtilsDate()->getTime() + 6);
        $oField2Shop = oxNew(\OxidEsales\Eshop\Application\Model\Field2Shop::class);
        $oField2Shop->setId('_testDiscount');
        $oField2Shop->oxfield2shop__oxupdatepricetime = new \OxidEsales\Eshop\Core\Field($sUpdatePriceTime);
        $oField2Shop->save();

        $iTime = Registry::getUtilsDate()->getTime() - 5;
        $oUserBasket = oxNew(\OxidEsales\Eshop\Application\Model\UserBasket::class);
        $oUserBasket->setId('_testBasket');
        $oUserBasket->oxuserbaskets__oxuserid = new \OxidEsales\Eshop\Core\Field(1, \OxidEsales\Eshop\Core\Field::T_RAW);
        $oUserBasket->oxuserbaskets__oxtitle = new \OxidEsales\Eshop\Core\Field('not_reservations', \OxidEsales\Eshop\Core\Field::T_RAW);
        $oUserBasket->save();
        $oUserBasket->load('_testBasket');
        $oUserBasket->oxuserbaskets__oxupdate = new \OxidEsales\Eshop\Core\Field($iTime);
        $oUserBasket->save();

        $this->getConfig()->setConfigParam("iLayoutCacheLifeTime", 10);
        $oView = oxNew(\OxidEsales\Eshop\Core\Controller\BaseController::class);
        $iExpectedCacheLifeTime = 3;
        $this->assertEquals($iExpectedCacheLifeTime, $oView->getCacheLifeTime());
        $this->cleanUpTable('oxdiscount');
        $this->cleanUpTable('oxarticles');
        $this->cleanUpTable('oxfield2shop');
        $this->cleanUpTable('oxuserbaskets');
    }

    /**
     * Tests \OxidEsales\Eshop\Core\Controller\BaseController::getCacheLifeTime() with actions.
     */
    public function testGetCacheLifeTime_FromActions_WhenActionEndsBeforeCacheLifeTime()
    {
        $this->_mockUtilsDateTime(Registry::getUtilsDate()->getTime());

        $activeFrom = date('Y-m-d H:i:s', Registry::getUtilsDate()->getTime() + 4);
        $updatePriceTime = date('Y-m-d H:i:s', Registry::getUtilsDate()->getTime() + 3);

        $actions = oxNew(\OxidEsales\Eshop\Application\Model\Actions::class);
        $actions->setId('_testArticle');
        $actions->oxactions__oxactiveto = new \OxidEsales\Eshop\Core\Field($activeFrom);
        $actions->oxactions__oxactiveto = new \OxidEsales\Eshop\Core\Field($updatePriceTime);
        $actions->save();

        $this->getConfig()->setConfigParam("iLayoutCacheLifeTime", 10);
        $oView = oxNew(\OxidEsales\Eshop\Core\Controller\BaseController::class);
        $iExpectedCacheLifeTime = 3;
        $this->assertEquals($iExpectedCacheLifeTime, $oView->getCacheLifeTime());
        $this->cleanUpTable('oxactions');
    }

    /**
     * Tests \OxidEsales\Eshop\Core\Controller\BaseController::getCacheLifeTime() with actions.
     */
    public function testGetCacheLifeTime_FromActions_WhenActionEndsAfterCacheLifeTime()
    {
        $this->_mockUtilsDateTime(Registry::getUtilsDate()->getTime());

        $activeFrom = date('Y-m-d H:i:s', Registry::getUtilsDate()->getTime() + 4);
        $updatePriceTime = date('Y-m-d H:i:s', Registry::getUtilsDate()->getTime() + 30);

        $actions = oxNew(\OxidEsales\Eshop\Application\Model\Actions::class);
        $actions->setId('_testArticle');
        $actions->oxactions__oxactiveto = new \OxidEsales\Eshop\Core\Field($activeFrom);
        $actions->oxactions__oxactiveto = new \OxidEsales\Eshop\Core\Field($updatePriceTime);
        $actions->save();

        $this->getConfig()->setConfigParam("iLayoutCacheLifeTime", 10);
        $oView = oxNew(\OxidEsales\Eshop\Core\Controller\BaseController::class);
        $iExpectedCacheLifeTime = 10;
        $this->assertEquals($iExpectedCacheLifeTime, $oView->getCacheLifeTime());
        $this->cleanUpTable('oxactions');
    }

    /**
     * Tests \OxidEsales\Eshop\Core\Controller\BaseController::getCacheLifeTime() when ir returns default lifetime- 3600
     */
    public function testGetCacheLifeTime_DefaultTime()
    {
        $this->getConfig()->setConfigParam("iLayoutCacheLifeTime", null);
        $oView = oxNew(\OxidEsales\Eshop\Core\Controller\BaseController::class);
        $this->assertEquals(3600, $oView->getCacheLifeTime());
    }

    /**
     * Tests \OxidEsales\Eshop\Core\Controller\BaseController::getCacheLifeTime() when reservation is enabled, but time is taken from discount table
     */
    public function testgetCacheLifeTime_ReservationEnabled_FromDiscount()
    {
        $this->_mockUtilsDateTime(Registry::getUtilsDate()->getTime());

        $iTime = Registry::getUtilsDate()->getTime() - 100;
        $oUserBasket = oxNew(\OxidEsales\Eshop\Application\Model\UserBasket::class);
        $oUserBasket->setId('_testBasket');
        $oUserBasket->oxuserbaskets__oxuserid = new \OxidEsales\Eshop\Core\Field(1, \OxidEsales\Eshop\Core\Field::T_RAW);
        $oUserBasket->oxuserbaskets__oxtitle = new \OxidEsales\Eshop\Core\Field('reservations', \OxidEsales\Eshop\Core\Field::T_RAW);
        $oUserBasket->save();
        $oUserBasket->load('_testBasket');
        $oUserBasket->oxuserbaskets__oxupdate = new \OxidEsales\Eshop\Core\Field($iTime);
        $oUserBasket->save();

        $sActiveFrom = date('Y-m-d H:i:s', Registry::getUtilsDate()->getTime() + 5);
        $oDiscount = oxNew(\OxidEsales\Eshop\Application\Model\Discount::class);
        $oDiscount->setId('_testDiscount');
        $oDiscount->oxdiscount__oxactivefrom = new \OxidEsales\Eshop\Core\Field($sActiveFrom);
        $oDiscount->save();

        $this->getConfig()->setConfigParam('blPsBasketReservationEnabled', true);
        $this->getConfig()->setConfigParam('iPsBasketReservationTimeout', 110);
        $oView = oxNew(\OxidEsales\Eshop\Core\Controller\BaseController::class);
        $iExpectedCacheLifeTime = 5;
        $this->assertEquals($iExpectedCacheLifeTime, $oView->getCacheLifeTime());
        $this->cleanUpTable('oxuserbaskets');
        $this->cleanUpTable('oxdiscount');
    }

    /**
     * Tests \OxidEsales\Eshop\Core\Controller\BaseController::getCacheLifeTime() when reservation is enabled and time is taken from userbaskets table
     */
    public function testGetCacheLifeTime_ReservationEnabled_FromUserbaskets()
    {
        $this->_mockUtilsDateTime(Registry::getUtilsDate()->getTime());

        $baseTime = 109;
        $iExpectedCacheLifeTime = 1;
        $differentThenExpectedCacheLifeTime = 5;

        $iTime = Registry::getUtilsDate()->getTime() - $baseTime;
        $oUserBasket = oxNew(\OxidEsales\Eshop\Application\Model\UserBasket::class);
        $oUserBasket->setId('_testBasket');
        $oUserBasket->oxuserbaskets__oxuserid = new \OxidEsales\Eshop\Core\Field(1, \OxidEsales\Eshop\Core\Field::T_RAW);
        $oUserBasket->oxuserbaskets__oxtitle = new \OxidEsales\Eshop\Core\Field('reservations', \OxidEsales\Eshop\Core\Field::T_RAW);
        $oUserBasket->save();
        $oUserBasket->load('_testBasket');
        $oUserBasket->oxuserbaskets__oxupdate = new \OxidEsales\Eshop\Core\Field($iTime);
        $oUserBasket->save();

        $sActiveFrom = date('Y-m-d H:i:s', Registry::getUtilsDate()->getTime() + $differentThenExpectedCacheLifeTime);
        $oDiscount = oxNew(\OxidEsales\Eshop\Application\Model\Discount::class);
        $oDiscount->setId('_testDiscount');
        $oDiscount->oxdiscount__oxactivefrom = new \OxidEsales\Eshop\Core\Field($sActiveFrom);
        $oDiscount->save();

        $oView = oxNew(\OxidEsales\Eshop\Core\Controller\BaseController::class);
        $this->assertEquals($differentThenExpectedCacheLifeTime, $oView->getCacheLifeTime());

        $this->getConfig()->setConfigParam('blPsBasketReservationEnabled', true);
        $this->getConfig()->setConfigParam('iPsBasketReservationTimeout', $baseTime + $iExpectedCacheLifeTime);

        $this->assertEquals($iExpectedCacheLifeTime, $oView->getCacheLifeTime());
        $this->cleanUpTable('oxuserbaskets');
        $this->cleanUpTable('oxdiscount');
    }

    /**
     * Mocks time and uses it in \OxidEsales\Eshop\Core\Controller\BaseController::getCacheLifeTime()
     *
     * @param $sTime
     */
    protected function _mockUtilsDateTime($sTime) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $oUtilsDate = $this->getMock(\OxidEsales\Eshop\Core\UtilsDate::class, array('getTime'));
        $oUtilsDate->expects($this->any())->method('getTime')->will($this->returnValue($sTime));
        Registry::set(\OxidEsales\Eshop\Core\UtilsDate::class, $oUtilsDate);
    }

    public function testSetGetAllowCacheInvalidating()
    {
        $oView = oxNew(\OxidEsales\Eshop\Core\Controller\BaseController::class);
        // default is now false
        $this->assertEquals(true, $oView->getAllowCacheInvalidating());

        $oView->setAllowCacheInvalidating(false);
        $this->assertEquals(false, $oView->getAllowCacheInvalidating());

        $oView->setAllowCacheInvalidating(true);
        $this->assertEquals(true, $oView->getAllowCacheInvalidating());
    }
}
