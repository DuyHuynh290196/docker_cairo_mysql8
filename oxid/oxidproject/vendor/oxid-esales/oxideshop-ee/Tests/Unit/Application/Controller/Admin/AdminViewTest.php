<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */
namespace OxidEsales\EshopEnterprise\Tests\Unit\Application\Controllers\Admin;

use oxField;
use oxTestModules;

/**
 * Tests for AdminView class
 */
class AdminViewTest extends \OxidEsales\TestingLibrary\UnitTestCase
{
    /**
     * Test allow admin edit ee user is default admin but according to session not mall admin.
     *
     * @return null
     */
    public function testAllowAdminEditEEUserIsDefaultAdminButAccordingToSessionNotMallAdmin()
    {
        $this->getSession()->setVariable('malladmin', false);
        $adminView = oxNew(\OxidEsales\Eshop\Application\Controller\Admin\AdminController::class);
        $this->assertFalse($adminView->UNITallowAdminEdit('oxdefaultadmin'));
    }

    /**
     * Test allow admin edit ee.
     *
     * @return null
     */
    public function testAllowAdminEditEEAdmin()
    {
        $this->getSession()->setVariable('auth', 'oxdefaultadmin');
        $this->getSession()->setVariable('malladmin', true);

        $adminView = oxNew(\OxidEsales\Eshop\Application\Controller\Admin\AdminController::class);
        $this->assertTrue($adminView->UNITallowAdminEdit('oxdefaultadmin'));
    }

    /**
     * Test allow admin edit ee with some user.
     *
     * @return null
     */
    public function testAllowAdminEditEESomeUser()
    {
        $user = oxNew(\OxidEsales\Eshop\Application\Model\User::class);
        $user->setId("_testUser");
        $user->oxuser__oxactive = new \OxidEsales\Eshop\Core\Field("1");
        $user->oxuser__oxusername = new \OxidEsales\Eshop\Core\Field("adminname");
        $user->oxuser__oxrights = new \OxidEsales\Eshop\Core\Field('user');
        $user->save();

        $this->getSession()->setVariable('auth', 'oxdefaultadmin');

        $adminView = oxNew(\OxidEsales\Eshop\Application\Controller\Admin\AdminController::class);
        $this->assertTrue($adminView->UNITallowAdminEdit('_testUser'));
    }

    /**
     * Test init.
     *
     * @return null
     */
    public function testInit()
    {
        $adminView = $this->getMock(\OxidEsales\Eshop\Application\Controller\Admin\AdminController::class, array('_authorize'));
        $adminView->expects($this->any())->method('_authorize')->will($this->returnValue(true));
        $adminView->init();

        $config = $this->getConfig();

        $this->assertEquals($config->getConfigParam('blAllowSharedEdit'), $adminView->getViewDataElement('allowSharedEdit'));
        $this->assertEquals($config->getConfigParam('blAllowSharedEdit'), $adminView->getViewDataElement('malladmin'));
    }

    /**
     * Test reset cached content .
     *
     * @return null
     */
    public function testResetContentCached()
    {
        oxTestModules::addFunction('oxCache', 'reset', '{ $_GET["testReset"] = "resetDoneAdditional"; }');

        $this->getConfig()->setConfigParam("blClearCacheOnLogout", null);

        $adminView = oxNew(\OxidEsales\Eshop\Application\Controller\Admin\AdminController::class);
        $adminView->resetContentCache();

        $this->assertEquals('resetDoneAdditional', $_GET["testReset"]);
    }

    /**
     * Checking reset when reset on logout is enabled and passing param
     *
     * @return null
     */
    public function testResetContentCachedWhenResetOnLogoutEnabled()
    {
        oxTestModules::addFunction('oxCache', 'reset', '{ $_GET["testReset"] = "resetDoneAdditional"; }');

        $this->getConfig()->setConfigParam("blClearCacheOnLogout", 1);

        $adminView = oxNew(\OxidEsales\Eshop\Application\Controller\Admin\AdminController::class);
        $adminView->resetContentCache();

        $this->assertEquals(null, $_GET["testReset"]);
    }

    /**
     * Checking reset when reset on logout is enabled and passing param
     * to force reset.
     *
     * @return null
     */
    public function testResetContentCachedWhenResetOnLogoutEnabledAndForceResetIsOn()
    {
        oxTestModules::addFunction('oxCache', 'reset', '{ $_GET["testReset"] = "resetDone"; }');

        $this->getConfig()->setConfigParam("blClearCacheOnLogout", 1);

        $adminView = oxNew(\OxidEsales\Eshop\Application\Controller\Admin\AdminController::class);
        $adminView->resetContentCache(true);

        $this->assertEquals('resetDone', $_GET["testReset"]);
    }
}
