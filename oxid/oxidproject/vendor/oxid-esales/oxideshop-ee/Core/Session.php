<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Core;

/**
 * @inheritdoc
 */
class Session extends \OxidEsales\EshopProfessional\Core\Session
{
    /**
     * Return Shop IR parameter for Url.
     *
     * @return string
     */
    protected function getShopUrlId()
    {
        $myConfig = $this->getConfig();
        $shopUrlId = '&amp;shp=' . $myConfig->getShopId();

        return $shopUrlId;
    }
}
