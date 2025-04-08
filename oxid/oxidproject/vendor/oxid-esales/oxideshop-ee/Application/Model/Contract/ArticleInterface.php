<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Model\Contract;

/**
 * @inheritdoc
 */
interface ArticleInterface extends \OxidEsales\EshopProfessional\Application\Model\Contract\ArticleInterface
{
    /**
     * Checks for VPE info which applies changes on pased amount
     *
     * @param double $amount Amount
     *
     * @return double
     */
    public function checkForVpe($amount);
}
