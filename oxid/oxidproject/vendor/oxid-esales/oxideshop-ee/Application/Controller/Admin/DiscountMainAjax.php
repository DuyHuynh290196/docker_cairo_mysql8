<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Controller\Admin;

/**
 * @inheritdoc
 */
class DiscountMainAjax extends \OxidEsales\EshopProfessional\Application\Controller\Admin\DiscountMainAjax
{
    /**
     * @inheritdoc
     */
    public function removeDiscCountry()
    {
        $discount = oxNew(\OxidEsales\Eshop\Application\Model\Discount::class);
        $discount->setId($this->getConfig()->getRequestParameter('oxid'));
        $discount->executeDependencyEvent();

        parent::removeDiscCountry();
    }

    /**
     * @inheritdoc
     */
    public function addDiscCountry()
    {
        $discount = oxNew(\OxidEsales\Eshop\Application\Model\Discount::class);
        $discount->setId($this->getConfig()->getRequestParameter('synchoxid'));
        $discount->executeDependencyEvent();

        parent::addDiscCountry();
    }
}
