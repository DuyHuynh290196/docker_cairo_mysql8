<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopProfessional\Setup;

use OxidEsales\EshopProfessional\Setup\De\MessagesData as MessagesDataDe;
use OxidEsales\EshopProfessional\Setup\En\MessagesData as MessagesDataEn;

/**
 * @inheritdoc
 */
class Language extends \OxidEsales\EshopCommunity\Setup\Language
{
    /**
     * Method returns messages according language.
     *
     * @return array
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
