<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Controller\Admin;

use OxidEsales\Eshop\Application\Model\Shop;
use oxBase;

/**
 * @inheritdoc
 */
class AdminListController extends \OxidEsales\EshopProfessional\Application\Controller\Admin\AdminListController
{
    /**
     * Unassigns entry from current shop
     */
    public function unassignEntry()
    {
        /** @var \OxidEsales\Eshop\Core\Model\BaseModel $item */
        $item = oxNew($this->_sListClass);
        $item->load($this->getEditObjectId());

        $shop = $this->_getShopObject();
        $shopIds = $shop->getInheritanceGroup($item->getCoreTableName(), $this->_getShopId());

        $shouldUnassign = $item->unassignFromShop($shopIds);

        // #A - we must reset object ID
        if ($shouldUnassign && isset($_POST["oxid"])) {
            $_POST["oxid"] = -1;
        }

        $this->init();
    }

    /**
     * Returns oxShop object
     *
     * @return Shop
     * @deprecated underscore prefix violates PSR12, will be renamed to "getShopObject" in next major
     */
    protected function _getShopObject() // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        return oxNew(\OxidEsales\Eshop\Application\Model\Shop::class);
    }

    /**
     * Returns item shop id
     *
     * @return string
     * @deprecated underscore prefix violates PSR12, will be renamed to "getShopId" in next major
     */
    protected function _getShopId() // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        return $this->getConfig()->getShopId();
    }
}
