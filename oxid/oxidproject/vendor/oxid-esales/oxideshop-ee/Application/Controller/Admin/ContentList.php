<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Controller\Admin;

/**
 * Include parent class.
 **/
class ContentList extends \OxidEsales\EshopProfessional\Application\Controller\Admin\ContentList
{
    /**
     * @inheritdoc
     * @deprecated underscore prefix violates PSR12, will be renamed to "prepareWhereQuery" in next major
     */
    protected function _prepareWhereQuery($aWhere, $sqlFull) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $sql = parent::_prepareWhereQuery($aWhere, $sqlFull);
        $viewName = getviewName("oxcontents");

        //load only for active shop
        $sql .= " and {$viewName}.oxshopid = '" . $this->getConfig()->getShopId() . "'";

        return $sql;
    }
}
