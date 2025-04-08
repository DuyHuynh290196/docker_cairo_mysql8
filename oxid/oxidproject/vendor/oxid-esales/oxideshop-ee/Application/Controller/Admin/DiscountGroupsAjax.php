<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Controller\Admin;

/**
 * @inheritdoc
 */
class DiscountGroupsAjax extends \OxidEsales\EshopProfessional\Application\Controller\Admin\DiscountGroupsAjax
{
    /**
     * @inheritdoc
     */
    public function removeDiscGroup()
    {
        parent::removeDiscGroup();

        $discount = oxNew(\OxidEsales\Eshop\Application\Model\Discount::class);
        $discount->setId($this->getConfig()->getRequestParameter('oxid'));
        $discount->executeDependencyEvent();
    }

    /**
     * @inheritdoc
     */
    public function addDiscGroup()
    {
        parent::addDiscGroup();

        $discountId = $this->getConfig()->getRequestParameter('synchoxid');
        $discount = oxNew(\OxidEsales\Eshop\Application\Model\Discount::class);
        $discount->setId($discountId);
        $discount->executeDependencyEvent();
    }
}
