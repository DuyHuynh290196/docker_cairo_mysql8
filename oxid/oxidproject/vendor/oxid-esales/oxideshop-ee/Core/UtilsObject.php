<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Core;

use oxDb;

/**
 * @inheritdoc
 */
class UtilsObject extends \OxidEsales\EshopProfessional\Core\UtilsObject
{
    /**
     * Checks if item with oxid from table $tableName is derived from parent shop
     * Returns true if article is derived
     *
     * @deprecated on b-dev (2015-07-27); Use \OxidEsales\Eshop\Core\Model\BaseModel::isDerived();
     *
     * @param string $objectId  oxid
     * @param string $tableName table name
     *
     * @return bool
     */
    public function isDerivedFromParentShop($objectId, $tableName)
    {
        $database = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();
        $isDerived = false;
        $query = "select oxshopid from $tableName where oxid = :oxid";
        $shopId = $database->getOne($query, [
            ':oxid' => $objectId
        ]);
        $currentShopId = $this->getShopIdCalculator()->getShopId();
        if ($currentShopId && $shopId && $shopId != $currentShopId) {
            //now check if that shop is really a parent shop of current shop
            $query = "select oxid from oxshops where oxid = '$currentShopId' and oxparentid = :oxparentid";
            $isDerived = (bool) $database->getOne($query, [
                ':oxparentid' => $shopId
            ]);
        }

        return $isDerived;
    }
}
