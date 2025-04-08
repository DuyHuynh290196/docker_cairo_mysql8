<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Controller\Admin;

/**
 * @inheritdoc
 */
class ArticleSelectionAjax extends \OxidEsales\EshopProfessional\Application\Controller\Admin\ArticleSelectionAjax
{
    /**
     * @inheritdoc
     */
    protected function onArticleSelectionListChange($articleId)
    {
        parent::onArticleSelectionListChange($articleId);

        $article = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
        $article->load($articleId);
        $article->executeDependencyEvent();
    }
}
