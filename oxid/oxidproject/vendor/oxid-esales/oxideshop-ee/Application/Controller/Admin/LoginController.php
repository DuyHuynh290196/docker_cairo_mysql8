<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Controller\Admin;

/**
 * @inheritdoc
 */
class LoginController extends \OxidEsales\EshopProfessional\Application\Controller\Admin\LoginController
{
    /**
     * @inheritdoc
     */
    protected function setShopConfigParameters()
    {
        parent::setShopConfigParameters();
        $config = $this->getConfig();
        $baseShop = oxNew(\OxidEsales\Eshop\Application\Model\Shop::class);
        $baseShop->load($config->getBaseShopId());
        $serial = $baseShop->oxshops__oxserial->value;
        $config->setConfigParam('sSerialNr', $serial);
    }
}
