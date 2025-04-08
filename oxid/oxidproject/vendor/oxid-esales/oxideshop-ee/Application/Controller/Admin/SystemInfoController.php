<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Controller\Admin;

/**
 * @inheritdoc
 */
class SystemInfoController extends \OxidEsales\EshopProfessional\Application\Controller\Admin\SystemInfoController
{
    /**
     * @inheritdoc
     */
    protected function isClassVariableVisible($varName)
    {
        $result = parent::isClassVariableVisible($varName);

        // only if visible by parent, check for more conditions
        if ($result) {
            $skipNames = array(
                'oActView',
                'oRRoles',
            );
            $result = !in_array($varName, $skipNames);
        }

        return $result;
    }
}
