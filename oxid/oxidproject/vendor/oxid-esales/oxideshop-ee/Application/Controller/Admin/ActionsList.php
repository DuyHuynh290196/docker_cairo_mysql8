<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Controller\Admin;

/**
 * @inheritdoc
 */
class ActionsList extends \OxidEsales\EshopProfessional\Application\Controller\Admin\ActionsList
{
    /**
     * @inheritdoc
     * @deprecated underscore prefix violates PSR12, will be renamed to "prepareWhereQuery" in next major
     */
    protected function _prepareWhereQuery($aWhere, $sqlFull) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $sQ = parent::_prepareWhereQuery($aWhere, $sqlFull);

        $sTable = getViewName("oxactions");
        $iShopId = $this->getConfig()->getShopId();
        $sQ .= " and ( {$sTable}.oxtype = 0 or ( {$sTable}.oxtype != 0 and {$sTable}.oxshopid = '{$iShopId}') ) ";

        return $sQ;
    }
}
