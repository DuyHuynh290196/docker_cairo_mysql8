<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Controller\Admin;

/**
 * @inheritdoc
 */
class ActionsGroupsAjax extends \OxidEsales\EshopProfessional\Application\Controller\Admin\ActionsGroupsAjax
{
    /**
     * @inheritdoc
     */
    public function removePromotionGroup()
    {
        parent::removePromotionGroup();

        $oPromotion = oxNew(\OxidEsales\Eshop\Application\Model\Actions::class);
        $oPromotion->executeDependencyEvent();
    }

    /**
     * @inheritdoc
     */
    public function addPromotionGroup()
    {
        $promotionAdded = parent::addPromotionGroup();
        if ($promotionAdded) {
            $oPromotion = oxNew(\OxidEsales\Eshop\Application\Model\Actions::class);
            $oPromotion->executeDependencyEvent();
        }

        return $promotionAdded;
    }
}
