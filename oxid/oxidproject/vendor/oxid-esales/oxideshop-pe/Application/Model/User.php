<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopProfessional\Application\Model;

use OxidEsales\Eshop\Core\Config;

/**
 * @inheritdoc
 */
class User extends \OxidEsales\EshopCommunity\Application\Model\User
{
    /**
     * @inheritdoc
     * @deprecated underscore prefix violates PSR12, will be renamed to "isDemoShop" in next major
     */
    protected function _isDemoShop() // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $isDemoMode = false;
        $config = $this->getConfig();
        if ($config->isDemoShop() || $config->hasDemoKey()) {
            $isDemoMode = true;
        }

        return $isDemoMode;
    }
}
