<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopProfessional\Core;

use oxRegistry;

/**
 * @inheritdoc
 */
class SystemEventHandler extends \OxidEsales\EshopCommunity\Core\SystemEventHandler
{
    /**
     * @inheritdoc
     */
    protected function isSendingShopDataEnabled()
    {
        return true;
    }

    /**
     * Check if shop valid.
     * Redirect offline if not valid.
     */
    protected function validateOffline()
    {
        if ($this->needValidateShop() && !$this->getConfig()->getSerial()->validateShop()) {
            \OxidEsales\Eshop\Core\Registry::getUtils()->showOfflinePage();
        }
    }

    /**
     * Performance - run these checks only each 5 times statistically.
     *
     * @return bool
     */
    private function needValidateShop()
    {
        $config = $this->getConfig();

        return !$config->isProductiveMode() || !((\OxidEsales\Eshop\Core\Registry::getUtilsDate()->getTime()) % 5) || $config->getConfigParam('blShopStopped');
    }
}
