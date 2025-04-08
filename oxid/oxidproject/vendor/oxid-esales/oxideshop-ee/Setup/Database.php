<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Setup;

/**
 * @inheritdoc
 */
class Database extends \OxidEsales\EshopProfessional\Setup\Database
{
    /**
     * @inheritdoc
     *
     * @deprecated 6.5.4 dynpages will be removed on the next major
     */
    protected function setIfDynamicPagesShouldBeUsed($session)
    {
        $session->setSessionParam('use_dynamic_pages', 'true');
    }
}
