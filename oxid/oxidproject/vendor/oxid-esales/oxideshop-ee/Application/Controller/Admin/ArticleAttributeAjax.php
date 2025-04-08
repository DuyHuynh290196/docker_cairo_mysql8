<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Controller\Admin;

/**
 * @inheritdoc
 */
class ArticleAttributeAjax extends \OxidEsales\EshopProfessional\Application\Controller\Admin\ArticleAttributeAjax
{
    /**
     * @inheritdoc
     */
    protected function onArticleAttributeRelationChange($articleId)
    {
        parent::onArticleAttributeRelationChange($articleId);

        $article = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
        $article->setId($articleId);
        $article->executeDependencyEvent();
    }

    /**
     * @inheritdoc
     */
    protected function onAttributeValueChange($article)
    {
        parent::onArticleAttributeRelationChange($article);

        $article->executeDependencyEvent();
    }
}
