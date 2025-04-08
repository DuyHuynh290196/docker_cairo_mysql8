<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Controller\Admin;

/**
 * @inheritdoc
 */
class ArticleExtendAjax extends \OxidEsales\EshopProfessional\Application\Controller\Admin\ArticleExtendAjax
{
    /**
     * @inheritdoc
     */
    public function onCategoriesRemoval($categoriesToRemove, $oxId)
    {
        $category = oxNew(\OxidEsales\Eshop\Application\Model\Category::class);
        $category->executeDependencyEvent($categoriesToRemove);

        $article = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
        $article->setId($oxId);
        $article->executeDependencyEvent();
    }

    /**
     * @inheritdoc
     */
    protected function updateQueryForRemovingArticleFromCategory($query)
    {
        $shopID = $this->getConfig()->getShopId();
        $query .= " oxshopid = " . \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->quote($shopID) . " and";

        return $query;
    }

    /**
     * @inheritdoc
     */
    protected function onCategoriesAdd($categories)
    {
        $category = oxNew(\OxidEsales\Eshop\Application\Model\Category::class);
        $category->executeDependencyEvent($categories);
    }

    /**
     * @inheritdoc
     */
    protected function formQueryToEmbedForUpdatingTime()
    {
        $shopId = $this->getConfig()->getShopId();
        $sqlShopFilter = "and oxshopid = {$shopId}";

        return $sqlShopFilter;
    }

    /**
     * @inheritdoc
     */
    protected function formQueryToEmbedForSettingCategoryAsDefault()
    {
        $shopId = $this->getConfig()->getShopId();
        $sqlShopFilter = "and oxshopid = {$shopId}";

        return $sqlShopFilter;
    }
}
