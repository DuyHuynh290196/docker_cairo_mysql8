<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Controller\Admin;

/**
 * @inheritdoc
 */
class ArticleExtend extends \OxidEsales\EshopProfessional\Application\Controller\Admin\ArticleExtend
{
    /**
     * @inheritdoc
     */
    protected function updateArticle($article)
    {
        $article = parent::updateArticle($article);
        //set access field properties to prevent derived articles for editing
        if ($article->isDerived()) {
            $this->_aViewData["readonly"] = true;
        }

        return $article;
    }
}
