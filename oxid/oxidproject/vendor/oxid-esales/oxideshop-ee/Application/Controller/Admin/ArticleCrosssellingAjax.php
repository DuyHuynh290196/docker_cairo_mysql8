<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Controller\Admin;

/**
 * @inheritdoc
 */
class ArticleCrosssellingAjax extends \OxidEsales\EshopProfessional\Application\Controller\Admin\ArticleCrosssellingAjax
{
    /**
     * @inheritdoc
     */
    public function removeArticleCross()
    {
        parent::removeArticleCross();
        $article = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
        $article->setId(\OxidEsales\Eshop\Core\Registry::getConfig()->getRequestParameter('oxid'));
        $article->executeDependencyEvent();
    }

    /**
     * @inheritdoc
     */
    protected function onArticleAddingToCrossSelling($article)
    {
        $article->executeDependencyEvent();
    }
}
