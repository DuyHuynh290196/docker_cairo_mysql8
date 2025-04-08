<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Tests\Unit\Application\Component;

use OxidEsales\TestingLibrary\UnitTestCase;
use \oxTestModules;
use \oxField;
use OxidEsales\Eshop\Core\Registry;

class UserComponentTest extends UnitTestCase
{
    /**
     * Test view logout().
     *
     * @return null
     */
    public function testLogoutForLoginFeature()
    {
        oxTestModules::addFunction("oxUser", "logout", "{ return true;}");

        $aMockFnc = array('_afterLogout', '_getLogoutLink', 'setRights', 'getParent');

        $oParent = $this->getMock(\OxidEsales\Eshop\Application\Controller\FrontendController::class, array("isEnabledPrivateSales"));
        $oParent->expects($this->atLeastOnce())->method('isEnabledPrivateSales')->will($this->returnValue(true));

        $oUserView = $this->getMock(\OxidEsales\Eshop\Application\Component\UserComponent::class, $aMockFnc);
        $oUserView->expects($this->once())->method('_afterLogout');
        $oUserView->expects($this->any())->method('_getLogoutLink')->will($this->returnValue("testurl"));
        $oUserView->expects($this->any())->method('getParent')->will($this->returnValue($oParent));

        $oUserView->expects($this->once())->method('setRights');

        $this->assertEquals('account', $oUserView->logout());
    }

    /**
     * Test _afterlogin().
     *
     * @return null
     */
    public function testAfterLogin()
    {
        $this->setRequestParameter('blPerfNoBasketSaving', true);
        $oBasket = $this->getMock(\OxidEsales\Eshop\Application\Model\Basket::class, array('onUpdate'));
        $oBasket->expects($this->once())->method('onUpdate');

        $oSession = $this->getMock(\OxidEsales\Eshop\Core\Session::class, [
            'getBasket', 'regenerateSessionId', 'isSessionStarted'
        ]);
        $oSession->expects($this->atLeastOnce())->method('getBasket')->will($this->returnValue($oBasket));
        $oSession->method('isSessionStarted')->willReturn(true);
        $oSession->expects($this->once())->method('regenerateSessionId');

        $oUser = $this->getMock(\OxidEsales\Eshop\Application\Component\UserComponent::class, array('inGroup'));
        $oUser->expects($this->once())->method('inGroup')->will($this->returnValue(false));

        $aMockFnc = ['getSession', 'setRights'];

        $oUserView = $this->getMock(\OxidEsales\Eshop\Application\Component\UserComponent::class, $aMockFnc);
        $oUserView->expects($this->atLeastOnce())->method('getSession')->will($this->returnValue($oSession));

        $oUserView->expects($this->once())->method('setRights');

        $this->assertEquals('payment', $oUserView->UNITafterLogin($oUser));
    }

    /**
     * Test logout.
     *
     * @return null
     */
    public function testLogout()
    {
        oxTestModules::addFunction("oxUtils", "redirect", "{ return true;}");
        oxTestModules::addFunction("oxUser", "logout", "{ return true;}");
        $this->setRequestParameter('redirect', true);
        $blParam = $this->getConfig()->getConfigParam('sSSLShopURL');
        $this->getConfig()->setConfigParam('sSSLShopURL', true);

        $oParent = $this->getMock(\OxidEsales\Eshop\Application\Controller\FrontendController::class, array("isEnabledPrivateSales"));
        $oParent->expects($this->atLeastOnce())->method('isEnabledPrivateSales')->will($this->returnValue(false));

        $aMockFnc = array('_afterLogout', '_getLogoutLink', 'setRights', 'getParent');

        $oUserView = $this->getMock(\OxidEsales\Eshop\Application\Component\UserComponent::class, $aMockFnc);
        $oUserView->expects($this->once())->method('_afterLogout');
        $oUserView->expects($this->once())->method('_getLogoutLink')->will($this->returnValue("testurl"));
        $oUserView->expects($this->atLeastOnce())->method('getParent')->will($this->returnValue($oParent));
        $oUserView->expects($this->once())->method('setRights');

        $oUserView->logout();
        $this->assertEquals(3, $oUserView->getLoginStatus());
        $this->getConfig()->setConfigParam('sSSLShopURL', $blParam);
    }

    /**
     * Test _changeUser_noRedirect()().
     *
     * @return null
     */
    public function testChangeUserNoRedirect()
    {
        $this->setRequestParameter('order_remark', 'TestRemark');
        $this->setRequestParameter('blnewssubscribed', null);
        $aRawVal = array('oxuser__oxfname'     => 'fname',
            'oxuser__oxlname'     => 'lname',
            'oxuser__oxstreetnr'  => 'nr',
            'oxuser__oxstreet'    => 'street',
            'oxuser__oxzip'       => 'zip',
            'oxuser__oxcity'      => 'city',
            'oxuser__oxcountryid' => 'a7c40f631fc920687.20179984');
        $this->setRequestParameter('invadr', $aRawVal);

        $oUser = $this->getMock(\OxidEsales\Eshop\Application\Model\User::class, array('changeUserData', 'getNewsSubscription', 'setNewsSubscription'));
        $oUser->expects($this->once())->method('changeUserData')->with(
            $this->equalTo('test@oxid-esales.com'),
            $this->equalTo(crc32('Test@oxid-esales.com')),
            $this->equalTo(crc32('Test@oxid-esales.com')),
            $this->equalTo($aRawVal),
            null
        );
        $oUser->expects($this->atLeastOnce())->method('getNewsSubscription')->will($this->returnValue(oxNew(\OxidEsales\Eshop\Application\Model\NewsSubscribed::class)));
        $oUser->expects($this->once())->method('setNewsSubscription')->will($this->returnValue(1));
        $oUser->oxuser__oxusername = new \OxidEsales\Eshop\Core\Field('test@oxid-esales.com');
        $oUser->oxuser__oxpassword = new \OxidEsales\Eshop\Core\Field(crc32('Test@oxid-esales.com'));
        $oBasket = $this->getMock(\OxidEsales\Eshop\Application\Model\Basket::class, array('onUpdate'));
        $oBasket->expects($this->once())->method('onUpdate');
        $oSession = $this->getMock(\OxidEsales\Eshop\Core\Session::class, array('getBasket', 'checkSessionChallenge'));
        $oSession->expects($this->atLeastOnce())->method('getBasket')->will($this->returnValue($oBasket));
        $oSession->expects($this->atLeastOnce())->method('checkSessionChallenge')->will($this->returnValue(true));
        Registry::set(\OxidEsales\Eshop\Core\Session::class, $oSession);

        $aMockFnc = array('getSession', 'getUser', '_getDelAddressData', 'setRights');
        $oUserView = $this->getMock($this->getProxyClassName("oxcmp_user"), $aMockFnc);
        $oUserView->expects($this->atLeastOnce())->method('_getDelAddressData')->will($this->returnValue(null));
        $oUserView->expects($this->any())->method('getSession')->will($this->returnValue($oSession));
        $oUserView->expects($this->atLeastOnce())->method('getUser')->will($this->returnValue($oUser));

        $oUserView->expects($this->once())->method('setRights');

        $this->assertTrue($oUserView->UNITchangeUser_noRedirect());
        $this->assertEquals('TestRemark', Registry::getSession()->getVariable('ordrem'));
        $this->assertEquals(1, $oUserView->getNonPublicVar('_blNewsSubscriptionStatus'));
    }
}
