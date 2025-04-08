<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Core;

/**
 * @inheritdoc
 */
class Output extends \OxidEsales\EshopProfessional\Core\Output
{
    /**
     * Forms Shop mode name.
     *
     * @return string
     */
    protected function getShopMode()
    {
        $sShopMode = parent::getShopMode();

        $blStagingMode = $this->getConfig()->isStagingMode();
        if ($blStagingMode) {
            $sShopMode .= " (Staging Mode)";
        }

        return $sShopMode;
    }
}
