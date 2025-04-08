<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Tests\Integration\AdminRoles;

use OxidEsales\Eshop\Application\Model\User;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\TestingLibrary\UnitTestCase;

class AdminRolesTestCase extends UnitTestCase
{
    const ADMIN_USER_ID = '_testroleadmin';
    const TEST_USER_NAME = '_testuser@myoxideshop.com';
    const TEST_ROLE_ID = '_test_admin_role';

    protected function setUp(): void
    {
        parent::setUp();

        $base = new \OxidEsales\Eshop\Core\Base();
        $base->setAdminMode(true);

        //clear tmp dir
        $cache = new \OxidEsales\TestingLibrary\Services\Library\Cache();
        $cache->clearTemporaryDirectory();

        //Prepare for test
        $this->activateTheme('flow');
        Registry::getConfig()->setConfigParam('sTheme', 'flow');

        $this->createAdminUser();
        $this->createAdminRole();
        $this->assignAdminUserToRole();
        $this->removeSessionUser();

        //Force reset of property, otherwise tests will influence each other
        $obj         = new \OxidEsales\Eshop\Core\Controller\BaseController();
        $refObject   = new \ReflectionObject($obj);
        $refProperty = $refObject->getProperty('_blExecuted');
        $refProperty->setAccessible(true);
        $refProperty->setValue(null, false);
    }

    /**
     * Tear down.
     */
    protected function tearDown(): void
    {
        $this->cleanUpTable('oxuser', 'oxid');
        $this->cleanUpTable('oxuser', 'oxusername');
        $this->cleanUpTable('oxroles', 'oxid');
        $this->cleanUpTable('oxobject2role', 'oxobjectid');
        $this->cleanUpTable('oxfield2role', 'oxroleid');

        parent::tearDown();
    }

    /**
     * Test helper
     *
     * @return array
     */
    protected function getAdminControllerIdToNavigationGroup()
    {
        $map = $this->getControllerMap();
        $controllerId2NavigationGroup = [];

        foreach ($map as $controllerId => $controllerClass) {
            if (!$this->doesBelongToShopNamespace($controllerId)) {
                continue;
            }
            $navigationGroup = $this->getAdminNavigationGroup($controllerId);
            if (!is_null($navigationGroup)) {
                $controllerId2NavigationGroup[$controllerId] = $navigationGroup;
            }
        }

        return $controllerId2NavigationGroup;
    }

    /**
     * Test helper.
     * Return array with controller id to class name map.
     *
     * @return array
     */
    protected function getControllerMap()
    {
        $shopControllerMapProvider = oxNew(\OxidEsales\Eshop\Core\Routing\ShopControllerMapProvider::class);
        $moduleControllerMapProvider = oxNew(\OxidEsales\Eshop\Core\Routing\ModuleControllerMapProvider::class);
        $controllerMap = array_merge($shopControllerMapProvider->getControllerMap(), $moduleControllerMapProvider->getControllerMap());

        return $controllerMap;
    }

    /**
     * Test helper for parsing navigation tree.
     *
     * @return array
     */
    protected function getNavigationMaps()
    {
        $controller = oxNew(\OxidEsales\Eshop\Application\Controller\Admin\AdminController::class);
        $navigationTree = $controller->getNavigation();
        $xPath = new \DOMXPath($navigationTree->_getInitialDom());

        $return = [
            'id2cl'   => [],
            'id2list' => []
        ];

        $nodeList = $xPath->query("//*[@id]");
        if ($nodeList->length) {
            for ($i = 0; $i < $nodeList->length; $i++) {
                $item = $nodeList->item($i);
                $return['id2cl'][$item->getAttribute('id')] = $item->getAttribute('cl');
                $return['id2list'][$item->getAttribute('id')] = $item->getAttribute('list');
            }
        }

        return $return;
    }

    /**
     * Test helper.
     *
     * @param string $controllerId
     *
     * @return null|string
     */
    protected function getAdminNavigationGroup($controllerId)
    {
        $maps = $this->getNavigationMaps();
        $cl2id2  = array_flip($maps['id2cl']);
        $list2id = array_flip($maps['id2list']);

        $return = isset($cl2id2[$controllerId]) ? $cl2id2[$controllerId] : null;
        if (is_null($return)) {
            $return = isset($list2id[$controllerId]) ? $list2id[$controllerId] : null;
        }
        return $return;
    }

    /**
     * Create admin user that will get limited rights.
     */
    protected function createAdminUser()
    {
        $user = oxNew(User::class);
        $user->setId(self::ADMIN_USER_ID);
        $user->assign(
            ['oxfname'     => 'FirstName',
             'oxlname'     => 'LastName',
             'oxusername'  => 'testadmin@myoxideshop.com',
             'oxactive'    => 1,
             'oxshopid'    => 1,
             'oxcountryid' => 'a7c40f631fc920687.20179984',
             'oxboni'      => '600'
            ]
        );
        $user->setPassword('testadmin');
        $user->save();

        //Otherwise we need to be admin to save this user.
        $query = "UPDATE oxuser SET oxrights = 'malladmin' WHERE oxid = '" . self::ADMIN_USER_ID . "'";
        \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute($query);
    }

    /**
     * Create admin role
     *
     * @return string
     */
    protected function createAdminRole()
    {
        $role = oxNew(\OxidEsales\Eshop\Application\Model\Role::class);
        $role->setId(self::TEST_ROLE_ID);
        $role->assign(
            [
                'oxtitle'  => '_test_admin_role',
                'oxactive' => 1,
                'oxarea'   => 0 //admin
            ]
        );

        $role->save();
    }

    /**
     * Tets helper to assign
     */
    protected function assignAdminUserToRole()
    {
        $oxobject2role = oxNew(\OxidEsales\Eshop\Core\Model\BaseModel::class);
        $oxobject2role->init('oxobject2role');
        $oxobject2role->assign(
            [
                'oxobjectid' => self::ADMIN_USER_ID,
                'oxroleid'   => self::TEST_ROLE_ID,
                'oxtype'     => 'oxuser'
            ]
        );
        $oxobject2role->save();
    }

    /**
     * @param array  $fields     Field ids
     * @param string $type       Field type
     * @param int    $permission Role permission: 0 - Deny, 1 - Read, 2 - Full
     */
    protected function assignFieldsToRole($fields, $type = 'oxview', $permission = 2)
    {
        $roleId = self::TEST_ROLE_ID;
        $query = "REPLACE INTO oxfield2role (oxfieldid, oxtype, oxroleid, oxidx) VALUES ";
        foreach ($fields as $fieldId) {
            $query .= "('{$fieldId}', '{$type}', '{$roleId}', '{$permission}'),";
        }
        $query = rtrim($query, ',');
        \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute($query);
    }

    /**
     * Test helper.
     * Ensure we have test admin in session and as active user
     */
    protected function logInTestAdmin()
    {
        $someSessionId = time();
        Registry::getSession()->setVariable('usr', self::ADMIN_USER_ID);
        Registry::getSession()->setVariable('auth', self::ADMIN_USER_ID);
        Registry::getSession()->setId($someSessionId);

        $user = oxNew(User::class);
        $user->load(self::ADMIN_USER_ID);
        $user->loadActiveUser();
        $user->setUser($user);
    }

    /**
     * Test helper.
     * Ensure we have no active user in session
     */
    protected function removeSessionUser()
    {
        Registry::getSession()->setVariable('usr', null);
        Registry::getSession()->setVariable('auth', null);

        $user = oxNew(User::class);
        $user->setUser(null);
    }

    /**
     * Test helper.
     *
     * @param \OxidEsales\Eshop\Application\Controller\BaseController $controller
     *
     * @return bool
     */
    protected function getReadOnly($controller)
    {
        $result = false;
        $viewData = $controller->getViewData();
        if (isset($viewData['readonly'])) {
            $result = $viewData['readonly'];
        }
        return $result;
    }

    /**
     * Keep shop from redirecting
     */
    protected function preventRedirects()
    {
        $utils = $this->getMockBuilder(\OxidEsales\Eshop\Core\Utils::class)
            ->setMethods(['redirect'])
            ->getMock();
        $utils->expects($this->any())->method('redirect');
        \OxidEsales\Eshop\Core\UtilsObject::setClassInstance(\OxidEsales\Eshop\Core\Utils::class, $utils);
        Registry::set(\OxidEsales\Eshop\Core\Utils::class, $utils);
    }

    /**
     * Trick shop into assuming we got cookies
     */
    protected function mockCookieCount()
    {
        $utils = $this->getMockBuilder(\OxidEsales\Eshop\Core\UtilsServer::class)
            ->setMethods(['getOxCookie'])
            ->getMock();

        $map = [
            [null,['chocolate']],
            ['oxidadminhistory', 'peanuts']
        ];
        $utils->expects($this->any())->method('getOxCookie')->will($this->returnValueMap($map));

        \OxidEsales\Eshop\Core\UtilsObject::setClassInstance(\OxidEsales\Eshop\Core\UtilsServer::class, $utils);
        Registry::set(\OxidEsales\Eshop\Core\UtilsServer::class, $utils);
    }

    /**
     * Clear tmp directory
     */
    protected function clearTmpDir()
    {
        $cache = new \OxidEsales\TestingLibrary\Services\Library\Cache();
        $cache->clearTemporaryDirectory();
    }

    /**
     * Test helper, we do not want ot test module controllers here.
     *
     * @param string $controllerId
     *
     * @return bool
     */
    protected function doesBelongToShopNamespace($controllerId)
    {
        $resolvedClass = Registry::getControllerClassNameResolver()->getClassNameById($controllerId);
        return \OxidEsales\Eshop\Core\NamespaceInformationProvider::classBelongsToShopUnifiedNamespace($resolvedClass);
    }
}
