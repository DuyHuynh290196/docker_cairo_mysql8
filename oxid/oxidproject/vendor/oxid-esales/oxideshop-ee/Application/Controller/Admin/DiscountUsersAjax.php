<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Controller\Admin;

/**
 * @inheritdoc
 */
class DiscountUsersAjax extends \OxidEsales\EshopProfessional\Application\Controller\Admin\DiscountUsersAjax
{
    /**
     * @inheritdoc
     */
    public function removeDiscUser()
    {
        $discount = oxNew(\OxidEsales\Eshop\Application\Model\Discount::class);
        $discount->setId($this->getConfig()->getRequestParameter('oxid'));
        $discount->executeDependencyEvent();

        parent::removeDiscUser();
    }

    /**
     * @inheritdoc
     */
    public function addDiscUser()
    {
        $discount = oxNew(\OxidEsales\Eshop\Application\Model\Discount::class);
        $discount->setId($this->getConfig()->getRequestParameter('synchoxid'));
        $discount->executeDependencyEvent();

        parent::addDiscUser();
    }
}
