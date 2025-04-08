<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Component\Widget;

use OxidEsales\Eshop\Core\Registry;

/**
 * @inheritdoc
 */
class MiniBasket extends \OxidEsales\EshopProfessional\Application\Component\Widget\MiniBasket
{
    /**
     * Returns if view should be cached
     *
     * @return bool
     */
    public function isCacheable()
    {
        $sType = \OxidEsales\Eshop\Core\Registry::getConfig()->getRequestParameter('nocookie');

        if ($sType) {
            return true;
        } else {
            return false;
        }
    }
}
