<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Controller\Admin;

/**
 * @inheritdoc
 */
class AttributeOrderAjax extends \OxidEsales\EshopProfessional\Application\Controller\Admin\AttributeOrderAjax
{
    /**
     * @inheritdoc
     */
    public function setSorting()
    {
        parent::setSorting();
        $sSelId = \OxidEsales\Eshop\Core\Registry::getConfig()->getRequestParameter('oxid');
        $oCategory = oxNew(\OxidEsales\Eshop\Application\Model\Category::class);
        $oCategory->executeDependencyEvent(array($sSelId));
    }
}
