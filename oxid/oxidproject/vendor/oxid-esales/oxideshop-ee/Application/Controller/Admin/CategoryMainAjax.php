<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Controller\Admin;

use oxDb;
use OxidEsales\Eshop\Core\Registry;

/**
 * @inheritdoc
 */
class CategoryMainAjax extends \OxidEsales\EshopProfessional\Application\Controller\Admin\CategoryMainAjax
{
    /**
     * @inheritdoc
     */
    public function addArticle()
    {
        parent::addArticle();
        $config = $this->getConfig();
        $categoryID = $config->getRequestEscapedParameter('synchoxid');

        $category = oxNew(\OxidEsales\Eshop\Application\Model\Category::class);
        $category->executeDependencyEvent(array($categoryID));
    }

    /**
     * @inheritdoc
     */
    protected function getRemoveCategoryArticlesQueryFilter($categoryID, $prodIds)
    {
        $db = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();
        $where = parent::getRemoveCategoryArticlesQueryFilter($categoryID, $prodIds);

        $shopID = $this->getConfig()->getShopId();
        $where .= " and oxshopid = " . $db->quote($shopID);

        return $where;
    }

    /**
     * @inheritdoc
     */
    protected function getUpdateOxTimeQueryShopFilter()
    {
        $shopFilterQuery = parent::getUpdateOxTimeQueryShopFilter();

        $shopId = $this->getConfig()->getShopId();
        $shopFilterQuery .= " and oxshopid = {$shopId} ";

        return $shopFilterQuery;
    }

    /**
     * @inheritdoc
     */
    protected function getUpdateOxTimeSqlWhereFilter()
    {
        $shopFilterQuery = parent::getUpdateOxTimeSqlWhereFilter();

        $shopId = $this->getConfig()->getShopId();
        $shopFilterQuery .= "and oxobject2category.oxshopid = {$shopId} ";

        return $shopFilterQuery;
    }

    /**
     * Flush category dependencies when removing articles.
     */
    public function removeArticle()
    {
        parent::removeArticle();

        $categoryID = \OxidEsales\Eshop\Core\Registry::getConfig()->getRequestParameter('oxid');

        $category = oxNew(\OxidEsales\Eshop\Application\Model\Category::class);
        $category->executeDependencyEvent(array($categoryID));
    }

    /**
     * @inheritdoc
     */
    protected function removeCategoryArticles($articles, $categoryID)
    {
        parent::removeCategoryArticles($articles, $categoryID);

        $article = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
        foreach ($articles as $articleId) {
            $article->setId($articleId);
            $article->executeDependencyEvent();
        }
    }
}
