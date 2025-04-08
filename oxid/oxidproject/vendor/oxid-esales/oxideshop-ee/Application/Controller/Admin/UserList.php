<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Controller\Admin;

/**
 * @inheritdoc
 */
class UserList extends \OxidEsales\EshopProfessional\Application\Controller\Admin\UserList
{
    /**
     * @inheritdoc
     * @deprecated will be renamed to "prepareWhereQuery" in next major
     */
    public function _prepareWhereQuery($conditionList, $queryString) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $generatedQuery = parent::_prepareWhereQuery($conditionList, $queryString);

        // malladmin stuff
        $user = oxNew(\OxidEsales\Eshop\Application\Model\User::class);
        $user->loadAdminUser();
        if ($user->oxuser__oxrights->value != "malladmin") {
            $generatedQuery .= " and oxuser.oxrights != 'malladmin'";
        }

        if (!$this->getConfig()->getConfigParam('blMallUsers')) {
            $generatedQuery .= " and oxshopid = '" . $this->getConfig()->getShopId() . "' ";
        }

        return $generatedQuery;
    }

    /**
     * Builds and returns SQL query string for user list loading
     *
     * @param mixed $oListObject list main object
     *
     * @return string
     * @deprecated underscore prefix violates PSR12, will be renamed to "buildSelectString" in next major
     */
    protected function _buildSelectString($oListObject = null) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $oBase = oxNew(\OxidEsales\Eshop\Core\Model\BaseModel::class);
        $oBase->init($oListObject->getCoreTableName());

        if ($this->getConfig()->getConfigParam('blMallUsers')) {
            $oBase->setDisableShopCheck(true);
        }

        return $oBase->buildSelectString(null);
    }
}
