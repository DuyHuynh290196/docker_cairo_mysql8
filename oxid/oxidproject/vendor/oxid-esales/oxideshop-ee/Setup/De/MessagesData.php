<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Setup\De;

/**
 * @inheritdoc
 */
class MessagesData extends \OxidEsales\EshopEnterprise\Setup\Messages
{
    /**
     * @inheritdoc
     */
    public function getMessages()
    {
        return array(
            'MOD_MEMORY_LIMIT' => 'PHP Memory Limit (min. 32MB, 60MB empfohlen)',
            'STEP_0_ERROR_URL' => 'http://www.oxid-esales.com/de/support-services/dokumentation-und-hilfe/oxid-eshop/installation/oxid-eshop-neu-installieren/server-und-systemvoraussetzungen/systemvoraussetzungen-ee.html',
        );
    }
}
