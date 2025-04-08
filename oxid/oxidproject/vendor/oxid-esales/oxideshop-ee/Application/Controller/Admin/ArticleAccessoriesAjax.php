<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Controller\Admin;

use OxidEsales\Eshop\Core\Registry;

/**
 * @inheritdoc
 */
class ArticleAccessoriesAjax extends \OxidEsales\EshopProfessional\Application\Controller\Admin\ArticleAccessoriesAjax
{
    /**
     * @inheritdoc
     */
    public function removeArticleAcc()
    {
        parent::removeArticleAcc();

        $article = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
        $article->setId(\OxidEsales\Eshop\Core\Registry::getConfig()->getRequestParameter('oxid'));
        $article->executeDependencyEvent();
    }

    /**
     * @inheritdoc
     */
    protected function onArticleAccessoryRelationChange($article)
    {
        parent::onArticleAccessoryRelationChange($article);

        $article->executeDependencyEvent();
    }
}
