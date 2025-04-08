<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Controller\Admin;

/**
 * @inheritdoc
 */
class ActionsMainAjax extends \OxidEsales\EshopProfessional\Application\Controller\Admin\ActionsMainAjax
{
    /**
     * @inheritdoc
     */
    public function removeArtFromAct()
    {
        parent::removeArtFromAct();

        $oPromotion = oxNew(\OxidEsales\Eshop\Application\Model\Actions::class);
        $oPromotion->executeDependencyEvent();
    }


    /**
     * @inheritdoc
     */
    public function setSorting()
    {
        parent::setSorting();

        $oPromotion = oxNew(\OxidEsales\Eshop\Application\Model\Actions::class);
        $oPromotion->executeDependencyEvent();
    }

    /**
     * @inheritdoc
     */
    public function addArtToAct()
    {
        $articleAdded = parent::addArtToAct();
        if ($articleAdded) {
            $oPromotion = oxNew(\OxidEsales\Eshop\Application\Model\Actions::class);
            $oPromotion->executeDependencyEvent();
        }

        return $articleAdded;
    }
}
