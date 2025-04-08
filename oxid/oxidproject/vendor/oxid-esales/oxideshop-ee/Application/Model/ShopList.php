<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Model;

use oxDb;

/**
 * @inheritdoc
 */
class ShopList extends \OxidEsales\EshopProfessional\Application\Model\ShopList
{
    /**
     * Loads the list of subshops for given parent.
     * Multishop should be removed from mall tabs.
     *
     * @param int $shopId Parent id
     */
    public function loadSubShopList($shopId)
    {
        if (!$shopId) {
            $shopId = $this->getConfig()->getShopId();
        }

        $database = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();
        $query = "select * from oxshops where oxismultishop = 0 and oxparentid = :oxparentid";

        $this->selectString($query, [
            ':oxparentid' => $shopId
        ]);
    }

    /**
     * Loads the list of subshops for given parent.
     * Multishop should be removed from mall tabs.
     *
     * @param int $shopId Parent id
     */
    public function loadSuperShopList($shopId)
    {
        if (!$shopId) {
            $shopId = $this->getConfig()->getShopId();
        }

        $database = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();
        $query = "select * from oxshops where oxismultishop = 0 and  oxid <> :notoxid ";

        $this->selectString($query, [
            ':notoxid' => $shopId
        ]);
    }
}
