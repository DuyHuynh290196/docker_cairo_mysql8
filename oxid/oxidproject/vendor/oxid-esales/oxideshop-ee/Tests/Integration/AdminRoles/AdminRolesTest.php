<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */
namespace OxidEsales\EshopEnterprise\Tests\Integration\AdminRoles;

/**
 * Class AdminRolesTest
 *
 * @package OxidEsales\EshopEnterprise\Tests\Integration\AdminRoles
 */
class AdminRolesTest extends \OxidEsales\EshopEnterprise\Tests\Integration\AdminRoles\AdminRolesTestCase
{
    /**
     * Test if getViewId call returns the expected field ids.
     *
     * @group adminroles
     */
    public function testGetViewIds()
    {
        $adminControllers = $this->getAdminControllerIdToNavigationGroup();

        $misses = [];
        foreach ($adminControllers as $controllerId => $navigationGroup) {
            $controllerClass = \OxidEsales\Eshop\Core\Registry::getControllerClassNameResolver()->getClassNameById($controllerId);
            $controller = oxNew($controllerClass);

            if ($navigationGroup !== $controller->getViewId()) {
                $misses[$controllerId] = [
                    'navigationgroup' => $navigationGroup,
                    'viewid'          => $controller->getViewId(),
                    'class'           => $controllerClass
                    ];
            }
        }

        $this->assertEquals([], $misses);
    }

    /**
     * Test is test admin gets the read only rights on all admin controllers.
     *
     * @group adminroles
     */
    public function testReadOnlyRights()
    {
        $maps = $this->getNavigationMaps();
        $this->assignFieldsToRole(array_keys($maps['id2cl']), 'oxview', 1);
        $this->assignFieldsToRole(array_keys($maps['id2list']), 'oxview', 1);
        $this->logInTestAdmin();
        $adminControllers = $this->getAdminControllerIdToNavigationGroup();

        $misses = [];
        foreach ($adminControllers as $controllerId => $navigationGroup) {
            $controllerClass = \OxidEsales\Eshop\Core\Registry::getControllerClassNameResolver()->getClassNameById($controllerId);
            $controller = oxNew($controllerClass);

            $rights = $controller->getRights();
            $rights->processView($controller);
            if (false == $this->getReadOnly($controller)) {
                $misses[$controllerId] = $navigationGroup;

            }
        }
        $this->assertEquals([], $misses);
    }

    /**
     * Test genexport navigation class id getter.
     *
     * @group adminroles
     */
    public function testNavigationClassId()
    {
        $controllerIdToViewId = [
            'genexport'      => 'mxgenexp',
            'genexport_do'   => 'mxgenexp',
            'genexport_main' => 'tbclgenexport_main'
        ];

        $controller = oxNew(\OxidEsales\Eshop\Application\Controller\Admin\AdminController::class);
        $navigationTree = $controller->getNavigation();
        $xPath = new \DOMXPath($navigationTree->_getInitialDom());

        foreach ($controllerIdToViewId as $controllerId => $expected) {
            $nodeList = $xPath->query("//*[@cl='{$controllerId}' or @list='{$controllerId}']");
            if ($nodeList->length && ($firstItem = $nodeList->item(0))) {
                $viewId = $firstItem->getAttribute('id');
                $this->assertEquals($expected, $viewId, $controllerId);
            }
        }
    }

    /**
     * Check if test admin can change the company name with full permissions.
     *
     * @group adminroles
     */
    public function testChangeCompanyNameFullRights()
    {
        $shop = oxNew(\OxidEsales\Eshop\Application\Model\Shop::class);
        $shop->load(1);
        $companyBefore = $shop->getFieldData('oxcompany');

        $maps = $this->getNavigationMaps();
        $this->assignFieldsToRole(array_keys($maps['id2cl']), 'oxview', 2);
        $this->assignFieldsToRole(array_keys($maps['id2list']), 'oxview', 2);

        $this->preventRedirects();
        $this->mockCookieCount();
        $this->logInTestAdmin();

        $requestData = [
            'stoken' => \OxidEsales\Eshop\Core\Registry::getSession()->getSessionChallengeToken(),
            'force_admin_sid' => \OxidEsales\Eshop\Core\Registry::getSession()->getId(),
            'cl' => 'shop_main',
            'fnc' => 'save',
            'oxid' => '1',
            'editval' => [
                'oxshops__oxcompany' => 'some shiny new company name'
            ]
        ];
        foreach ($requestData as $key => $value) {
            $this->setRequestParameter($key, $value);
        }

        //When accessing save method via ShopControl, admin rights and roles kick in
        //In readonly rights case, function name is set to empty string in controller object
        //so only render method will be called.
        $shopControl = oxNew(\OxidEsales\Eshop\Core\ShopControl::class);
        $view = $shopControl->_initializeViewObject('shop_main', 'save');
        $shopControl->executeAction($view, $view->getFncName());

        $shop = oxNew(\OxidEsales\Eshop\Application\Model\Shop::class);
        $shop->load(1);
        $companyAfter = $shop->getFieldData('oxcompany');

        $this->assertNotEquals($companyBefore, $companyAfter);
        $this->assertEquals('some shiny new company name', $companyAfter);
    }

    /**
     * Assert that test admin with read only rights cannot create new user
     *
     * @group adminroles
     */
    public function testCreateNewUserAllowedForFullRights()
    {
        $query = "SELECT count(*) FROM oxuser WHERE oxusername = '" . self::TEST_USER_NAME . "'";
        $this->assertEquals(0, \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->getOne($query));

        $maps = $this->getNavigationMaps();
        $this->assignFieldsToRole(array_keys($maps['id2cl']), 'oxview', 2);
        $this->assignFieldsToRole(array_keys($maps['id2list']), 'oxview', 2);

        $this->preventRedirects();
        $this->mockCookieCount();
        $this->logInTestAdmin();

        $requestData = [
            'stoken'          => \OxidEsales\Eshop\Core\Registry::getSession()->getSessionChallengeToken(),
            'force_admin_sid' => \OxidEsales\Eshop\Core\Registry::getSession()->getId(),
            'cl'              => 'user_main',
            'fnc'             => 'save',
            'oxid'            => '1',
            'editval'         => [
                'oxuser__oxfname'     => 'UserFirstName',
                'oxuser__oxlname'     => 'UserLastName',
                'oxuser__oxusername'  => self::TEST_USER_NAME,
                'oxuser__oxactive'    => 1,
                'oxuser__oxshopid'    => 1,
                'oxuser__oxcountryid' => 'a7c40f631fc920687.20179984',
                'oxuser__oxboni'      => '600',
                'oxuser__oxrights'    => 'user'
            ]
        ];
        foreach ($requestData as $key => $value) {
            $this->setRequestParameter($key, $value);
        }

        //When accessing save method via ShopControl, admin rights and roles kick in
        //In readonly rights case, function name is set to empty string in controller object
        //so only render method will be called.
        $shopControl = oxNew(\OxidEsales\Eshop\Core\ShopControl::class);
        $view = $shopControl->_initializeViewObject('user_main', 'save');
        $shopControl->executeAction($view, $view->getFncName());

        //user could not be created
        $this->assertEquals(1, \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->getOne($query));
    }

    /**
     * Check if test admin is correctly denied changing the company name.
     *
     * @group adminroles
     */
    public function testChangeCompanyNameReadOnlyRights()
    {
        $shop = oxNew(\OxidEsales\Eshop\Application\Model\Shop::class);
        $shop->load(1);
        $companyBefore = $shop->getFieldData('oxcompany');

        $maps = $this->getNavigationMaps();
        $this->assignFieldsToRole(array_keys($maps['id2cl']), 'oxview', 1);
        $this->assignFieldsToRole(array_keys($maps['id2list']), 'oxview', 1);

        $this->preventRedirects();
        $this->mockCookieCount();
        $this->logInTestAdmin();

        $requestData = [
            'stoken' => \OxidEsales\Eshop\Core\Registry::getSession()->getSessionChallengeToken(),
            'force_admin_sid' => \OxidEsales\Eshop\Core\Registry::getSession()->getId(),
            'cl' => 'shop_main',
            'fnc' => 'save',
            'oxid' => '1',
            'editval' => [
                'oxshops__oxcompany' => 'some shiny new company name'
            ]
        ];
        foreach ($requestData as $key => $value) {
            $this->setRequestParameter($key, $value);
        }

        //When accessing save method via ShopControl, admin rights and roles kick in
        //In readonly rights case, function name is set to empty string in controller object
        //so only render method will be called.
        $shopControl = oxNew(\OxidEsales\Eshop\Core\ShopControl::class);
        $view = $shopControl->_initializeViewObject('shop_main', 'save');
        $shopControl->executeAction($view, $view->getFncName());

        $shop = oxNew(\OxidEsales\Eshop\Application\Model\Shop::class);
        $shop->load(1);
        $companyAfter = $shop->getFieldData('oxcompany');

        $this->assertEquals($companyBefore, $companyAfter);
    }

    /**
     * Assert that test admin with read only rights cannot create new user
     *
     * @group adminroles
     */
    public function testCreateNewUserDeniedForReadOnlyRights()
    {
        $query = "SELECT count(*) FROM oxuser WHERE oxusername = '" . self::TEST_USER_NAME . "'";
        $this->assertEquals(0, \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->getOne($query));

        $maps = $this->getNavigationMaps();
        $this->assignFieldsToRole(array_keys($maps['id2cl']), 'oxview', 1);
        $this->assignFieldsToRole(array_keys($maps['id2list']), 'oxview', 1);

        $this->preventRedirects();
        $this->mockCookieCount();
        $this->logInTestAdmin();

        $requestData = [
            'stoken'          => \OxidEsales\Eshop\Core\Registry::getSession()->getSessionChallengeToken(),
            'force_admin_sid' => \OxidEsales\Eshop\Core\Registry::getSession()->getId(),
            'cl'              => 'user_main',
            'fnc'             => 'save',
            'oxid'            => '1',
            'editval'         => [
                'oxuser__oxfname'     => 'UserFirstName',
                'oxuser__oxlname'     => 'UserLastName',
                'oxuser__oxusername'  => self::TEST_USER_NAME,
                'oxuser__oxactive'    => 1,
                'oxuser__oxshopid'    => 1,
                'oxuser__oxcountryid' => 'a7c40f631fc920687.20179984',
                'oxuser__oxboni'      => '600',
                'oxuser__oxrights'    => 'user'
            ]
        ];
        foreach ($requestData as $key => $value) {
            $this->setRequestParameter($key, $value);
        }

        //When accessing save method via ShopControl, admin rights and roles kick in
        //In readonly rights case, function name is set to empty string in controller object
        //so only render method will be called.
        $shopControl = oxNew(\OxidEsales\Eshop\Core\ShopControl::class);
        $view = $shopControl->_initializeViewObject('user_main', 'save');
        $shopControl->executeAction($view, $view->getFncName());

        //user could not be created
        $this->assertEquals(0, \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->getOne($query));
    }
}
