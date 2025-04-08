<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Controller\Admin;

/**
 * @inheritdoc
 */
class DiscountArticlesAjax extends \OxidEsales\EshopProfessional\Application\Controller\Admin\DiscountArticlesAjax
{
    /**
     * @inheritdoc
     */
    public function removeDiscArt()
    {
        parent::removeDiscArt();

        $discount = oxNew(\OxidEsales\Eshop\Application\Model\Discount::class);
        $discount->setId($this->getConfig()->getRequestParameter('oxid'));
        $discount->executeDependencyEvent();
    }

    /**
     * @inheritdoc
     */
    public function addDiscArt()
    {
        parent::addDiscArt();

        $discount = oxNew(\OxidEsales\Eshop\Application\Model\Discount::class);
        $discount->setId($this->getConfig()->getRequestParameter('synchoxid'));
        $discount->executeDependencyEvent();
    }

    /**
     * @inheritdoc
     */
    protected function addArticleToDiscount($discountListId, $articleId)
    {
        parent::addArticleToDiscount($discountListId, $articleId);

        $oArticle = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
        $oArticle->setId($articleId);
        $oArticle->executeDependencyEvent();
    }
}
