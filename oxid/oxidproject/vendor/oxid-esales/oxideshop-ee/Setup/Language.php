<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Setup;

use OxidEsales\EshopEnterprise\Setup\De\MessagesData as MessagesDataDe;
use OxidEsales\EshopEnterprise\Setup\En\MessagesData as MessagesDataEn;

/**
 * @inheritdoc
 */
class Language extends \OxidEsales\EshopProfessional\Setup\Language
{
    /**
     * @inheritdoc
     */
    protected function getAdditionalMessages()
    {
        $messagesData = new MessagesDataEn();
        if ($this->getLanguage() === 'de') {
            $messagesData = new MessagesDataDe();
        }

        return $messagesData->getMessages();
    }
}
