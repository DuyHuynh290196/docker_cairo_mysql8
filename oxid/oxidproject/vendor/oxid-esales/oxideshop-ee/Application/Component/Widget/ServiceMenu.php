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
class ServiceMenu extends \OxidEsales\EshopProfessional\Application\Component\Widget\ServiceMenu
{
    /**
     * Returns if view should be cached
     *
     * @return bool
     */
    public function isCacheable()
    {
        $type = \OxidEsales\Eshop\Core\Registry::getConfig()->getRequestParameter('nocookie');

        if ($type) {
            return true;
        } else {
            return false;
        }
    }
}
