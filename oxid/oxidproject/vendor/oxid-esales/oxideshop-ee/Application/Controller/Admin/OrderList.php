<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Controller\Admin;

/**
 * @inheritdoc
 */
class OrderList extends \OxidEsales\EshopProfessional\Application\Controller\Admin\OrderList
{
    /**
     * @inheritdoc
     * @deprecated underscore prefix violates PSR12, will be renamed to "prepareWhereQuery" in next major
     */
    protected function _prepareWhereQuery($queries, $queryForAppending) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $query = parent::_prepareWhereQuery($queries, $queryForAppending);
        $query .= " and oxorder.oxshopid = '" . $this->getConfig()->getShopId() . "'";

        return $query;
    }
}
