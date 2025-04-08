<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Controller\Admin;

/**
 * @inheritdoc
 */
class VendorMainAjax extends \OxidEsales\EshopProfessional\Application\Controller\Admin\VendorMainAjax
{
    /**
     * @inheritdoc
     */
    protected function onVendorActionArticleUpdateConditions($articleIds)
    {
        $parentResult = parent::onVendorActionArticleUpdateConditions($articleIds);
        $oConfig = $this->getConfig();

        $result = $parentResult . " and oxshopid='" . $oConfig->getShopId() . "' ";

        return $result;
    }

    /**
     * @inheritdoc
     */
    protected function onVendorAction($vendorOxid)
    {
        parent::onVendorAction($vendorOxid);

        $vendor = oxNew(\OxidEsales\Eshop\Application\Model\Vendor::class);
        $vendor->load($vendorOxid);
        $vendor->executeDependencyEvent();
    }
}
