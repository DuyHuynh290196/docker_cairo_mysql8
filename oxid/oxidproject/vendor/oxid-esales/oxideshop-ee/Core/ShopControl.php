<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Core;

use oxDb;
use oxException;
use OxidEsales\Eshop\Application\Controller\FrontendController;
use OxidEsales\Eshop\Core\Cache\DynamicContent\ContentCache;
use OxidEsales\EshopCommunity\Internal\Framework\Templating\TemplateRendererBridgeInterface;
use OxidEsales\EshopCommunity\Internal\Framework\Templating\TemplateRendererInterface;

/**
 * @inheritdoc
 */
class ShopControl extends \OxidEsales\EshopProfessional\Core\ShopControl
{
    /**
     * Allow setting env_key cookies for shopcontrol, but not for widget controller.
     * @deprecated in v6.0.0 on 2017-08-23; use $isLayout instead.
     * @var bool
     */
    protected $_blAllowEnvKeySetting = true;

    /** @var bool Distinguishes layout from widget. */
    protected $isLayout = true;

    /** @var bool Allow invalidating cache for shopcontrol, but not for widget controller. */
    protected $_blAllowCacheInvalidating = true;

    /** @var bool Is current view loaded from cache */
    protected $viewLoadedFromCache = false;

    /**
     * Sets whether to allow cache invalidation
     *
     * @param bool $allowCacheInvalidating
     */
    public function setAllowCacheInvalidating($allowCacheInvalidating)
    {
        $this->_blAllowCacheInvalidating = $allowCacheInvalidating;
    }

    /**
     * Returns whether cache invalidation is allowed
     *
     * @return bool
     */
    public function getAllowCacheInvalidating()
    {
        return $this->_blAllowCacheInvalidating;
    }

    /**
     * Getter for isLayout flag.
     *
     * @return bool
     */
    public function isLayout()
    {
        return $this->isLayout;
    }

    /**
     * Returns if the controller has errors
     *
     * @param \OxidEsales\Eshop\Core\Controller\BaseController $controller The controller to check for errors
     *
     * @return bool
     */
    public function hasErrors(\OxidEsales\Eshop\Core\Controller\BaseController $controller)
    {
        $errors = $this->_getErrors($controller->getClassName());
        $hasErrors = is_array($errors) && count($errors);

        return $hasErrors;
    }

    /**
     * Forms output and returns it.
     * If possible takes from cache, if not - renders page and stores it to cache.
     *
     * @param FrontendController $view
     *
     * @return string
     */
    protected function formOutput($view)
    {
        $output = $this->getFromCache($view);

        $this->viewLoadedFromCache = $output === false;

        if ($this->viewLoadedFromCache) {
            $output = parent::formOutput($view);
            $this->addToCache($view, $output);
        }

        return $this->processOutput($view, $output);
    }

    /**
     * Checks if current view output is cached. If so - returns it, if not - returns false.
     *
     * @param FrontendController $view
     *
     * @return string|false
     */
    protected function getFromCache($view)
    {
        $output = false;
        if ($view->getIsCallForCache()) {
            $errors = $this->_getErrors($view->getClassName());
            if (is_array($errors) && count($errors)) {
                $view->setIsCallForCache(false);
                $view->initCacheableComponents();
            } else {
                // now we are ready for cache check
                $viewId = $view->getViewId();
                $cacheManager = $this->_getCacheManager();
                $output = $cacheManager->get($viewId);

                // If no cache found - must initiate cacheable components to render template correctly.
                if ($output === false) {
                    $view->initCacheableComponents();
                } else {
                    // rendering non cacheable components ...
                    $view->renderNonCacheableComponents();

                    // setting template variables
                    $viewData = $view->getViewData();
                    $engine = $this->getRenderer()->getTemplateEngine();
                    foreach (array_keys($viewData) as $viewName) {
                        $engine->addGlobal($viewName, $viewData[$viewName]);
                    }
                }
            }
        }

        return $output;
    }

    /**
     * Adds content to cache.
     *
     * @param FrontendController $view
     * @param string         $output
     */
    protected function addToCache($view, $output)
    {
        if ($view->getIsCallForCache()) {
            $this->_getCacheManager()->put($view->getViewId(), $output, $view->getViewResetId());
        }
    }

    /**
     * Changes cached content session information.
     *
     * @param FrontendController $view
     * @param string         $output
     *
     * @return string
     */
    protected function processOutput($view, $output)
    {
        if ($view->getIsCallForCache()) {
            $output = $this->_getCacheManager()->processCache($output);
        }

        return $output;
    }

    /**
     * Checking if user has enough rights to execute preferred functionality
     * Initializes non cacheable components if view itself is cached.
     *
     * @param FrontendController $view
     */
    protected function onViewCreation($view)
    {
        if (($rights = $this->getRights())) {
            $rights->processView($view);
        }

        if ($this->getConfig()->getConfigParam('blUseContentCaching') && !$this->isAdmin()) {
            // lets look for cached data
            $cacheManager = $this->_getCacheManager();
            if ($cacheManager->isViewCacheable($view->getClassName()) && $view->canCache()) {
                // initializing non cacheable components
                $view->setIsCallForCache(true);
                $view->initNonCacheableComponents();
            }
        }
    }

    /**
     * @inheritdoc
     * @deprecated underscore prefix violates PSR12, will be renamed to "render" in next major
     */
    protected function _render($view) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        if ($view->getIsCallForCache()) {
            $view->addTplParam('_render4cache', '1');
        }

        return parent::_render($view);
    }

    /**
     * return cache manager instance
     *
     * @return ContentCache
     * @deprecated underscore prefix violates PSR12, will be renamed to "getCacheManager" in next major
     */
    protected function _getCacheManager() // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        if (!$this->_oCache) {
            $this->_oCache = oxNew(\OxidEsales\Eshop\Core\Cache\DynamicContent\ContentCache::class);
        }

        return $this->_oCache;
    }

    /**
     * @inheritdoc
     */
    protected function formMonitorMessage($view)
    {
        $debugInfo = oxNew(\OxidEsales\Eshop\Core\DebugInfo::class);
        $viewId = $view->getViewId();

        $message = $debugInfo->formatContentCaching($view->getIsCallForCache(), $this->viewLoadedFromCache, $viewId);

        $message .= parent::formMonitorMessage($view);

        return $message;
    }

    /**
     * Catching other not caught exceptions.
     *
     * @param \OxidEsales\Eshop\Core\Exception\StandardException $exception
     * @deprecated underscore prefix violates PSR12, will be renamed to "handleBaseException" in next major
     */
    protected function _handleBaseException($exception) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        if ($exception instanceof \OxidEsales\Eshop\Core\Exception\AccessRightException) {
            $this->_handleAccessRightsException($exception);
        }

        $exception->debugOut();

        if ($this->_isDebugMode()) {
            \OxidEsales\Eshop\Core\Registry::getUtilsView()->addErrorToDisplay($exception);
            $this->_process('exceptionError', 'displayExceptionError');
        }
    }

    /**
     * R&R handling -> redirect to error msg, also, can call _process again, specifying error handler view class.
     *
     * @param \OxidEsales\Eshop\Core\Exception\StandardException $exception Exception
     * @deprecated underscore prefix violates PSR12, will be renamed to "handleAccessRightsException" in next major
     */
    protected function _handleAccessRightsException($exception) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        \OxidEsales\Eshop\Core\Registry::getUtils()->redirect($this->getConfig()->getShopHomeUrl() . 'cl=content&tpl=err_accessdenied.tpl', true, 302);
    }

    /**
     * @deprecated since v6.0 (2017-02-03). Use ShopControl::getStartControllerKey() instead.
     *
     * Returns which controller should be loaded at shop start.
     * Check whether we have to display mall start screen or not.
     *
     * @return string
     */
    protected function _getFrontendStartController() // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        return $this->getStartControllerKey();
    }

    /**
     * Returns which controller should be loaded at shop start.
     * Check whether we have to display mall start screen or not.
     *
     * @return string
     */
    protected function getFrontendStartControllerKey()
    {
        if ($this->getConfig()->isMall()) {
            $key = $this->getFrontendMallStartControllerKey();
        } else {
            $key = parent::getFrontendStartControllerKey();
        }

        return $key;
    }

    /**
     * @deprecated since v6.0 (2017-02-03). Use ShopControl::getFrontendMallStartControllerKey() instead.
     *
     * Returns start controller class name for frontend mall.
     * If no class specified, we need to change back to base shop
     *
     * @return string
     */
    protected function _getFrontendMallStartController() // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        return $this->getFrontendMallStartControllerKey();
    }

    /**
     * Returns start controller key for frontend mall.
     * If no class specified, we need to change back to base shop
     *
     * @return string
     */
    protected function getFrontendMallStartControllerKey()
    {
        $activeShopsCount = $this->fetchActiveShopCount();

        $mallShopUrl = $this->getConfig()->getConfigParam('sMallShopURL');

        $key = 'start';
        if ($activeShopsCount && $activeShopsCount > 1 && $this->getConfig()->getConfigParam('iMallMode') != 0 && !$mallShopUrl) {
            $key = 'mallstart';
        }

        return $key;
    }

    /**
     * Fetch the number of active shops.
     *
     * @return false|string The number of active shops.
     */
    protected function fetchActiveShopCount()
    {
        return \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->getOne('select count(*) from oxshops where oxactive = 1');
    }

    /**
     * @internal
     *
     * @return TemplateRendererInterface
     */
    private function getRenderer()
    {
        return $this->getContainer()
            ->get(TemplateRendererBridgeInterface::class)
            ->getTemplateRenderer();
    }
}
