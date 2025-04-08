<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Core\GenericImport\ImportObject;

use Exception;
use oxBase;

/**
 * Import object for Users.
 */
class User extends \OxidEsales\EshopProfessional\Core\GenericImport\ImportObject\User
{
    /**
     * Basic access check for writing data, checks for same shopid, should be overridden if field oxshopid does not
     * exist.
     *
     * @param \OxidEsales\Eshop\Core\Model\BaseModel $shopObject Loaded shop object
     * @param array  $data       Fields to be written, null for default
     *
     * @throws Exception on now access
     */
    public function checkWriteAccess($shopObject, $data = null)
    {
        $config = \OxidEsales\Eshop\Core\Registry::getConfig();

        if (!$config->getConfigParam('blMallUsers')) {
            parent::checkWriteAccess($shopObject, $data);
        }
    }
}
