<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Controller\Admin;

/**
 * @inheritdoc
 */
class ArticleStock extends \OxidEsales\EshopProfessional\Application\Controller\Admin\ArticleStock
{
    /**
     * @inheritdoc
     */
    protected function onArticleAmountPriceChange($articleId)
    {
        parent::onArticleAmountPriceChange($articleId);

        $amountPriceList = oxNew(\OxidEsales\Eshop\Application\Model\AmountPriceList::class);
        $amountPriceList->executeDependencyEvent($articleId);
    }
}
