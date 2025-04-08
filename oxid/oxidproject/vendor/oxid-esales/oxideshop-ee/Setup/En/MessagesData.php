<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Setup\En;

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
            'MOD_MEMORY_LIMIT' => 'PHP Memory limit (min. 32MB, 60MB recommended)',
            'STEP_0_ERROR_URL' => 'http://www.oxid-esales.com/en/products/enterprise-edition/system-requirements',
        );
    }
}
