<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopProfessional\Core;

/**
 * @inheritdoc
 */
class ViewConfig extends \OxidEsales\EshopCommunity\Core\ViewConfig
{
    /**
     * Checks if the shop is in staging mode.
     *
     * @return bool
     */
    public function hasDemoKey()
    {
        return $this->getConfig()->hasDemoKey();
    }
}
