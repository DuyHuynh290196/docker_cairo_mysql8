<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopProfessional\Core;

/**
 * @inheritdoc
 */
class OnlineLicenseCheck extends \OxidEsales\EshopCommunity\Core\OnlineLicenseCheck
{
    /**
     * Starts grace period.
     * Sets to config options.
     */
    protected function startGracePeriod()
    {
        $config = \OxidEsales\Eshop\Core\Registry::getConfig();
        $config->saveShopConfVar('bool', 'blShopStopped', 'true', $config->getBaseShopId());
        $config->saveShopConfVar('str', 'sShopVar', 'unlc', $config->getBaseShopId());
    }
}
