<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Controller;

use oxCategory;
use OxidEsales\Eshop\Core\Registry;

/**
 * @inheritdoc
 */
class FrontendController extends \OxidEsales\EshopProfessional\Application\Controller\FrontendController
{
    /**
     * Returns whether init() should initialize created components.
     * Skips non cacheable components as they are already initiated by initNonCacheableComponents
     *
     * @return bool
     */
    protected function shouldInitializeComponents()
    {
        return !$this->getIsCallForCache();
    }

    /**
     * @inheritdoc
     */
    protected function generateViewIdBase()
    {
        $config = $this->getConfig();

        $languageId = \OxidEsales\Eshop\Core\Registry::getLang()->getBaseLanguage();
        $currencyId = (int) $this->getConfig()->getShopCurrency();

        $controllerId = $config->getRequestControllerId();
        $controllerId = $controllerId ? $controllerId : 'start';

        $function = $config->getRequestParameter('fnc');
        $function = $function ? $function : '';

        $shopUrl = $config->getConfigParam('sShopURL');
        $shopId = $config->getShopId();
        $userId = \OxidEsales\Eshop\Core\Registry::getSession()->getVariable('usr');

        $viewRights = null;
        $userGroupRightsId = null;

        if ($rights = $this->getRights()) {
            $viewRights = $rights->getViewRights();
            if (!$this->isAdmin()) {
                $userGroupRightsId = $rights->getUserGroupIndex();
            }
        }

        $sortingIndex = $this->getSortingSql($this->getSortIdent());
        $isSeoActive = (int) \OxidEsales\Eshop\Core\Registry::getUtils()->seoIsActive();

        $isSession = (int) ($this->getSession()->getId() != '');

        return "ox|$shopUrl|$shopId|$isSession|$languageId|$currencyId|$controllerId|$function|$userId|$sortingIndex|" .
            md5(serialize($viewRights) . serialize($userGroupRightsId)) . "|" . $isSeoActive;
    }

    /**
     * Initiates all non cacheable components
     */
    public function initNonCacheableComponents()
    {
        // init all components if there are any
        foreach ($this->_getComponentNames() as $componentName => $isNotCacheable) {
            if (!$isNotCacheable) {
                continue;
            }

            $component = oxNew($componentName);
            $component->setParent($this);
            $component->setThisAction($componentName);
            $component->init();
            $component->executeFunction($this->getFncName());
            $this->_oaComponents[$componentName] = $component;
        }
    }

    /**
     * Rendering non cacheable components
     */
    public function renderNonCacheableComponents()
    {
        // rendering only non cacheable components. Data will be used for finalizing template code
        foreach ($this->_getComponentNames() as $componentName => $isNotCacheable) {
            if ($isNotCacheable) {
                $this->_aViewData[$componentName] = $this->_oaComponents[$componentName]->render();
            }
        }
    }

    /**
     *  Initiates all cacheable components
     */
    public function initCacheableComponents()
    {
        // init all cacheable components if there are any
        foreach ($this->_getComponentNames() as $componentName => $isNotCacheable) {
            // initializing cacheable components ...
            if (!$isNotCacheable) {
                $this->_oaComponents[$componentName]->init();
                $this->_oaComponents[$componentName]->executeFunction($this->getFncName());
            }
        }
    }

    /**
     * If current reset ID is not set - forms and returns view ID
     * according to category and user group ....
     *
     * @return string
     */
    public function getViewResetId()
    {
        if ($this->_sViewResetID === null) {
            $category = $this->getActiveCategory();
            $categoryId = ($category && $category instanceof \OxidEsales\EshopCommunity\Application\Model\Category) ? $category->getId() : '-';
            $this->_sViewResetID = "ox|cid={$categoryId}|cl=" . $this->getClassName();
        }

        return $this->_sViewResetID;
    }

    /**
     * checks if this view can be cached - i.e. this view is not varying too much so that caching would
     * be ineffective.
     * Note: this method is called before the init()
     *
     * @return bool
     */
    public function canCache()
    {
        return true;
    }
}
