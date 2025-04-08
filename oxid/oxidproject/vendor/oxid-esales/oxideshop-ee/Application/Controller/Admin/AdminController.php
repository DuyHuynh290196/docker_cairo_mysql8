<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Controller\Admin;

use OxidEsales\Eshop\Core\Registry;

/**
 * @inheritdoc
 */
class AdminController extends \OxidEsales\EshopProfessional\Application\Controller\Admin\AdminController
{
    /**
     * @inheritdoc
     */
    public function __construct()
    {
        parent::__construct();

        $config = $this->getConfig();
        if ($shop = $this->_getEditShop($config->getShopId())) {
            $config->setSerial($shop->oxshops__oxserial->value);
        }
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $config = $this->getConfig();

        $this->_aViewData['allowSharedEdit'] = $config->getConfigParam('blAllowSharedEdit');
        $this->_aViewData['malladmin'] = $config->getConfigParam('blAllowSharedEdit');
    }

    /**
     * @inheritdoc
     */
    public function resetContentCache($forceReset = null)
    {
        parent::resetContentCache($forceReset);

        $needCleanupOnLogout = $this->getConfig()->getConfigParam('blClearCacheOnLogout');

        if (!$needCleanupOnLogout || $forceReset) {
            $this->resetCaches();
        }
    }

    /**
     * @inheritdoc
     * @deprecated underscore prefix violates PSR12, will be renamed to "allowAdminEdit" in next major
     */
    protected function _allowAdminEdit($userId) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        if (!$userId || $userId == -1) {
            return true;
        }

        $user = oxNew(\OxidEsales\Eshop\Application\Model\User::class);
        $user->load($userId);
        $userRights = $user->oxuser__oxrights->value;
        $userShopId = $user->oxuser__oxshopid->value;

        if (!isset(self::$_sAuthUserRights)) {
            $authUser = oxNew(\OxidEsales\Eshop\Application\Model\User::class);
            $authUser->loadAdminUser();
            self::$_sAuthUserRights = $authUser->oxuser__oxrights->value;
        }

        $isMallAdmin = \OxidEsales\Eshop\Core\Registry::getSession()->getVariable('malladmin');

        //allow edit if editable user is not admin, is from the same shop, or we are malladmin
        if ($userRights != 'user' && self::$_sAuthUserRights != $userShopId && !$isMallAdmin) {
            return false;
        }

        //mall admin is not allowed to be edited by shop admin
        if ($userRights == 'malladmin' && !$isMallAdmin) {
            return false;
        }

        return parent::_allowAdminEdit($userId);
    }

    /**
     * Resets cache.
     * @deprecated underscore prefix violates PSR12, will be renamed to "resetContentCacheAfterResetCounter" in next major
     */
    protected function _resetContentCache() // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        parent::_resetContentCache();
        $cache = oxNew(\OxidEsales\Eshop\Core\Cache\DynamicContent\ContentCache::class);
        $cache->reset();
    }

    /**
     * @inheritdoc
     */
    private function resetCaches()
    {
        //reset output cache
        $cache = oxNew(\OxidEsales\Eshop\Core\Cache\DynamicContent\ContentCache::class);
        $cache->reset(false);
    }
}
