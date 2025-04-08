<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopProfessional\Application\Controller;

/**
 * Encapsulates methods for application initialization.
 */
class OxidStartController extends \OxidEsales\EshopCommunity\Application\Controller\OxidStartController
{
    /**
     * Creates and starts session object, sets default currency.
     */
    public function pageStart()
    {
        $config = $this->getConfig();

        $shopId = $config->getBaseShopId();
        $config->setConfigParam('IMS', $config->getShopConfVar('IMS', $shopId));
        $config->setConfigParam('IMD', $config->getShopConfVar('IMD', $shopId));
        $config->setConfigParam('IMA', $config->getShopConfVar('IMA', $shopId));
        $config->setConfigParam('aSerials', $config->getShopConfVar('aSerials', $shopId));
        $config->setConfigParam('sBackTag', $config->getShopConfVar('sBackTag', $shopId));
        $config->setConfigParam('sTagList', $config->getShopConfVar('sTagList', $shopId));

        parent::pageStart();
    }
}
