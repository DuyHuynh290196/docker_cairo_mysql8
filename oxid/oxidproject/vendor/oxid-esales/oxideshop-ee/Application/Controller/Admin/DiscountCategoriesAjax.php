<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Controller\Admin;

/**
 * @inheritdoc
 */
class DiscountCategoriesAjax extends \OxidEsales\EshopProfessional\Application\Controller\Admin\DiscountCategoriesAjax
{
    /**
     * @inheritdoc
     */
    public function removeDiscCat()
    {
        parent::removeDiscCat();

        $discount = oxNew(\OxidEsales\Eshop\Application\Model\Discount::class);
        $discount->setId($this->getConfig()->getRequestParameter('oxid'));
        $discount->executeDependencyEvent();
    }

    /**
     * @inheritdoc
     */
    public function addDiscCat()
    {
        parent::addDiscCat();

        $discountId = $this->getConfig()->getRequestParameter('synchoxid');
        $discount = oxNew(\OxidEsales\Eshop\Application\Model\Discount::class);
        $discount->setId($discountId);
        $discount->executeDependencyEvent();

        $categoryIds = $this->_getActionIds('oxcategories.oxid');
        $category = oxNew(\OxidEsales\Eshop\Application\Model\Category::class);
        $category->executeDependencyEvent($categoryIds);
    }
}
