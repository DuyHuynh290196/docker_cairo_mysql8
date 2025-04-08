<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Controller\Admin;

/**
 * @inheritdoc
 */
class NavigationTree extends \OxidEsales\EshopProfessional\Application\Controller\Admin\NavigationTree
{
    /**
     * @inheritdoc
     * @deprecated underscore prefix violates PSR12, will be renamed to "getMenuFiles" in next major
     */
    protected function _getMenuFiles() // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $filesToLoad = parent::_getMenuFiles();

        $dynamicPagesMenuPath = null;
        $config = $this->getConfig();
        $isLoadedDynamicContents = $config->getConfigParam('blSendTechnicalInformationToOxid');
        $sShopCountry = $config->getConfigParam('sShopCountry');
        if (!$isLoadedDynamicContents && $sShopCountry) {
            $dynamicPagesMenuLanguage = $this->_getDynMenuLang();
            $dynamicPagesMenuPath = $this->_getDynMenuUrl($dynamicPagesMenuLanguage, $isLoadedDynamicContents);
        }
        if (!is_null($dynamicPagesMenuPath)) {
            $filesToLoad[] = $dynamicPagesMenuPath;
        }

        return $filesToLoad;
    }

    /**
     * Process cache contents and return the result
     * deprecated, as cache files are cleared from session data, which is only added
     * after loading the cache by _sessionizeLocalUrls().
     *
     * @param string $cacheContents Initial cached string.
     *
     * @see self::_sessionizeLocalUrls()
     *
     * @return string
     * @deprecated underscore prefix violates PSR12, will be renamed to "processCachedFile" in next major
     */
    protected function _processCachedFile($cacheContents) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $cacheContents = parent::_processCachedFile($cacheContents);
        return getStr()->preg_replace("/shp\=\d+/", "shp=" . $this->getConfig()->getShopId(), $cacheContents);
    }

    /**
     * Process navigation tree for rights and roles.
     */
    protected function onGettingDomXml()
    {
        parent::onGettingDomXml();
        if (($rights = $this->getRights())) {
            $rights->processNaviTree($this->_oDom);
        }
    }
}
