<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Controller\Admin;

/**
 * @inheritdoc
 */
class ActionsOrderAjax extends \OxidEsales\EshopProfessional\Application\Controller\Admin\ActionsOrderAjax
{
    /**
     * @inheritdoc
     */
    public function setSorting()
    {
        parent::setSorting();

        $oPromotion = oxNew(\OxidEsales\Eshop\Application\Model\Actions::class);
        $oPromotion->executeDependencyEvent();
    }
}
