<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Core;

/**
 * @inheritdoc
 */
class UtilsCount extends \OxidEsales\EshopProfessional\Core\UtilsCount
{
    /**
     * @inheritdoc
     */
    protected function getCurrentUserSessionGroups()
    {
        $currentUserSessionGroups = parent::getCurrentUserSessionGroups();
        if (!$this->isAdmin() && $rights = $this->getRights()) {
            $currentUserSessionGroups = $rights->getUserGroupIndex();
        }

        return $currentUserSessionGroups;
    }
}
