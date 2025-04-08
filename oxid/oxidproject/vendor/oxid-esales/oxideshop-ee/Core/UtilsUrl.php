<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Core;

use oxUtilsUrl;

/**
 * @inheritdoc
 */
class UtilsUrl extends \OxidEsales\EshopProfessional\Core\UtilsUrl
{
    /**
     * @inheritdoc
     */
    public function getBaseAddUrlParams()
    {
        $additionalParams = parent::getBaseAddUrlParams();
        $shopConfig = $this->getConfig();

        // in case shop has no separate URL and is not base shop - adding shop id to url
        if ($shopConfig->mustAddShopIdToRequest()) {
            $additionalParams['shp'] = $shopConfig->getShopId();
        }

        return $additionalParams;
    }

    /**
     * @inheritdoc
     */
    public function prepareUrlForNoSession($url)
    {
        $url = parent::prepareUrlForNoSession($url);

        if (!\OxidEsales\Eshop\Core\Registry::getUtils()->seoIsActive()) {
            $shopConfig = $this->getConfig();
            if ($shopConfig->isMall()) {
                $url = $this->addShopParameterToUrl($url);
            }
        }

        return $url;
    }

    /**
     * @inheritdoc
     */
    public function prepareCanonicalUrl($url)
    {
        $shopConfig = $this->getConfig();
        $url = parent::prepareCanonicalUrl($url);

        // attaching shop id if current mall shop has no special domain
        if ((bool) $shopConfig->mustAddShopIdToRequest()) {
            $url = $this->addShopParameterToUrl($url);
        }

        return $url;
    }

    /**
     * @inheritdoc
     */
    public function processSeoUrl($url)
    {
        $url = parent::processSeoUrl($url);

        // in admin only add shop id and only if needed
        if ($this->isAdmin() && (!$this->isCurrentShopHost($url) || $this->getConfig()->mustAddShopIdToRequest())) {
            $url = $this->addShopParameterToUrl($url);
        }

        return $url;
    }

    /**
     * @inheritdoc
     * @deprecated underscore prefix violates PSR12, will be renamed to "addMallHosts" in next major
     */
    protected function _addMallHosts(&$aHosts) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        parent::_addMallHosts($aHosts);

        $shopConfig = $this->getConfig();
        $this->_addHost($shopConfig->getConfigParam("sMallShopURL"), $aHosts);
        $this->_addHost($shopConfig->getConfigParam("sMallSSLShopURL"), $aHosts);
    }

    /**
     * Append shop parameter with current shop id to given url
     *
     * @param string $url
     * @return string
     */
    private function addShopParameterToUrl($url)
    {
        $stringManipulator = getStr();
        if (!$stringManipulator->preg_match('/[&?](amp;)?shp=[0-9]+/i', $url)) {
            $url = $this->appendParamSeparator($url);
            $url .= 'shp=' . $this->getConfig()->getShopId();
        }

        return $url;
    }
}
