<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Model;

use OxidEsales\Eshop\Application\Model\Contract\ArticleInterface;

/**
 * @inheritdoc
 */
class OrderArticle extends \OxidEsales\EshopProfessional\Application\Model\OrderArticle implements ArticleInterface
{
    /**
     * Checks for VPE info which applies changes on pased amount
     *
     * @param double $amount Amount
     *
     * @return double
     */
    public function checkForVpe($amount)
    {
        return $amount;
    }

    /**
     * Order status (ERP) getter
     *
     * @return array
     */
    public function getStatus()
    {
        if ($this->_aStatuses != null) {
            return $this->_aStatuses;
        }

        if ($this->oxorderarticles__oxerpstatus->value) {
            $this->_aStatuses = unserialize($this->oxorderarticles__oxerpstatus->value);
        }

        return $this->_aStatuses;
    }
}
