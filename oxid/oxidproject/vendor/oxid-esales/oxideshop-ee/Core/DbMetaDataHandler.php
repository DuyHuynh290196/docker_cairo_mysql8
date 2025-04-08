<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Core;

/**
 * Class for handling database related operations
 */
class DbMetaDataHandler extends \OxidEsales\EshopProfessional\Core\DbMetaDataHandler
{
    /**
     * Mark view tables as invalid.
     *
     * @param string $tableName
     *
     * @return bool
     */
    protected function validateTableName($tableName)
    {
        $result = parent::validateTableName($tableName);
        if ($result) {
            $result = strpos($tableName, "oxv_") !== 0;
        }

        return $result;
    }
}
