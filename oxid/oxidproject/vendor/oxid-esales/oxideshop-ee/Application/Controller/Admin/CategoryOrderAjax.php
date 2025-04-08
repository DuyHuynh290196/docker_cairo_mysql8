<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Controller\Admin;

/**
 * @inheritdoc
 */
class CategoryOrderAjax extends \OxidEsales\EshopProfessional\Application\Controller\Admin\CategoryOrderAjax
{
    /**
     * @inheritdoc
     */
    public function saveNewOrder()
    {
        parent::saveNewOrder();
    }

    /**
     * @inheritdoc
     */
    protected function updateQueryFilterForResetCategoryArticlesOrder()
    {
        $sqlShopFilter = parent::updateQueryFilterForResetCategoryArticlesOrder();

        $shopId = $this->getConfig()->getShopId();
        $sqlShopFilter .= "and oxshopid = {$shopId} ";

        return $sqlShopFilter;
    }

    /**
     * @inheritdoc
     */
    protected function onCategoryChange($categoryId)
    {
        parent::onCategoryChange($categoryId);

        $category = oxNew(\OxidEsales\Eshop\Application\Model\Category::class);
        $category->executeDependencyEvent(array($categoryId));
    }
}
