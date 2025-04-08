<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Core\Edition;

/**
 * Class EditionSelector
 *
 * @package OxidEsales\EshopEnterprise\Core\Edition
 *
 * @deprecated since v6.0.0-rc.2 (2017-08-24); Use \OxidEsales\Facts\Facts instead.
 */
class EditionSelector extends \OxidEsales\EshopProfessional\Core\Edition\EditionSelector
{
    /**
     * Determine shop edition by existence of edition specific classes.
     *
     * @return string
     */
    protected function getEditionByExistingClasses()
    {
        return static::ENTERPRISE;
    }
}
