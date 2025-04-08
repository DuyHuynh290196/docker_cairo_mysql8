<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Controller\Admin;

/**
 * @inheritdoc
 */
class ShopController extends \OxidEsales\EshopProfessional\Application\Controller\Admin\ShopController
{
    /**
     * @inheritdoc
     */
    public function render()
    {
        $template = parent::render();

        $sCurrentAdminShop = $this->getEditObjectId();
        if (!$sCurrentAdminShop) {
            $sCurrentAdminShop = $this->getConfig()->getShopId();
        }
        $this->_aViewData['currentadminshop'] = $sCurrentAdminShop;

        return $template;
    }
}
