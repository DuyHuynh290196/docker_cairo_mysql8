<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Model;

/**
 * @inheritdoc
 */
class BasketItem extends \OxidEsales\EshopProfessional\Application\Model\BasketItem
{
    /**
     * @inheritdoc
     */
    protected function applyPackageOnAmount($article, $amount)
    {
        $amount = parent::applyPackageOnAmount($article, $amount);
        $amount = $article->checkForVpe($amount);

        return $amount;
    }
}
