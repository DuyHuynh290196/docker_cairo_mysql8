<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopProfessional\Core;

use OxidEsales\Eshop\Core\EmailBuilder;
use OxidEsales\Eshop\Core\Registry;

/**
 * Grace period reset email builder
 */
class GracePeriodResetEmailBuilder extends EmailBuilder
{
    /**
     * @inheritdoc
     */
    protected function getSubject()
    {
        return Registry::getLang()->translateString(
            'SHOP_LICENSE_ERROR_INFORMATION',
            null,
            true
        );
    }

    /**
     * @inheritdoc
     */
    protected function getBody()
    {
        $lang = Registry::getLang();

        $body = $lang->translateString(
            'SHOP_LICENSE_ERROR_GRACE_RESET',
            null,
            true
        );

        $body .= $this->getEmailOriginMessage();

        return $body;
    }
}
