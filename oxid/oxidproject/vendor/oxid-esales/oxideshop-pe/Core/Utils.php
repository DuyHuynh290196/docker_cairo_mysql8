<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopProfessional\Core;

/**
 * @inheritdoc
 */
class Utils extends \OxidEsales\EshopCommunity\Core\Utils
{
    /**
     * @inheritdoc
     */
    public function getEditionCacheFilePrefix()
    {
        return 'pe';
    }
}
