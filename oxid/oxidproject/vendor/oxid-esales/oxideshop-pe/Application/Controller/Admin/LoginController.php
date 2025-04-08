<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopProfessional\Application\Controller\Admin;

use OxidEsales\Eshop\Core\Serial;

/**
 * @inheritdoc
 */
class LoginController extends \OxidEsales\EshopCommunity\Application\Controller\Admin\LoginController
{
    /**
     * Returns shop validation error message.
     *
     * @return string
     */
    public function getShopValidationMessage()
    {
        $error = '';
        $serial = $this->getConfig()->getSerial();
        if ($serial->isGracePeriodStarted()) {
            $serial->validateShop();
            if (!$serial->isShopValid()) {
                $error = $serial->getValidationMessage();
            }
        }

        return $error;
    }

    /**
     * Returns whether shop grace period expired.
     *
     * @return bool
     */
    public function isGracePeriodExpired()
    {
        $serial = $this->getConfig()->getSerial();

        return $serial->isGracePeriodExpired();
    }
}
