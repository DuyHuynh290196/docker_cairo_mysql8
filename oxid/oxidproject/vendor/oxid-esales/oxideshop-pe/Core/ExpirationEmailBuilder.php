<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopProfessional\Core;

use OxidEsales\Eshop\Core\EmailBuilder;
use OxidEsales\Eshop\Core\Registry;

/**
 * Expiration email builder class to send mail about shop offline
 */
class ExpirationEmailBuilder extends EmailBuilder
{
    /**
     * Constant defines left days till grace period ends.
     */
    const LEFT_DAYS_TO_SEND_LAST_EMAIL = 1;

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
     * Returns email content dependent on days left till grace period ends.
     *
     * @return string
     */
    protected function getBody()
    {
        $lang = Registry::getLang();

        if ($this->buildParam <= self::LEFT_DAYS_TO_SEND_LAST_EMAIL) {
            $body = $lang->translateString('SHOP_LICENSE_ERROR_GRACE_WILL_EXPIRE', null, true);
        } else {
            $body = $lang->translateString('SHOP_LICENSE_ERROR_shop_unlicensed', null, true);
        }

        $body .= $this->getEmailOriginMessage();

        return $body;
    }
}
