<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Controller\Admin;

/**
 * @inheritdoc
 */
class ActionsArticleAjax extends \OxidEsales\EshopProfessional\Application\Controller\Admin\ActionsArticleAjax
{
    /**
     * @inheritdoc
     */
    public function removeActionArticle()
    {
        parent::removeActionArticle();

        $oPromotion = oxNew(\OxidEsales\Eshop\Application\Model\Actions::class);
        $oPromotion->executeDependencyEvent();
    }

    /**
     * @inheritdoc
     */
    public function setActionArticle()
    {
        parent::setActionArticle();

        $oPromotion = oxNew(\OxidEsales\Eshop\Application\Model\Actions::class);
        $oPromotion->executeDependencyEvent();
    }
}
