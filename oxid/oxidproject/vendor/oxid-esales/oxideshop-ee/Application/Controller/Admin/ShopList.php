<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Controller\Admin;

use OxidEsales\Eshop\Core\Registry;
use oxDb;

/**
 * @inheritdoc
 */
class ShopList extends \OxidEsales\EshopProfessional\Application\Controller\Admin\ShopList
{
    /**
     * Sets SQL query parameters (such as sorting),
     * executes parent method parent::Init().
     */
    public function init()
    {
        parent::Init();

        $database = \OxidEsales\Eshop\Core\DatabaseProvider::getMaster();

        $itemsList = $this->getItemList();
        //setting isparent param
        foreach ($itemsList as $key => $shop) {
            $query = "select oxid from oxshops where oxparentid = :oxparentid";

            $shop->isparent = $database->getOne($query, [':oxparentid' => $key]) ? true : false;
        }
    }

    /**
     * Deletes selected shop files from server.
     *
     * @return null
     */
    public function deleteEntry()
    {
        $config = $this->getConfig();
        $shopId = \OxidEsales\Eshop\Core\Registry::getConfig()->getRequestParameter("delshopid");

        // We force reading from master to prevent issues with slow replications or open transactions (see ESDEV-3804).
        $masterDb = \OxidEsales\Eshop\Core\DatabaseProvider::getMaster();

        //do not delete parent shops
        $query = "select oxid from oxshops where oxparentid = :oxparentid";
        if ($masterDb->getOne($query, [':oxparentid' => $shopId])) {
            return;
        }

        // try to remove directories
        $shopId = strtr($shopId, "\\/", "__");
        $target = $config->getConfigParam('sShopDir') . "/Application/views/" . $shopId;

        // Shom message if deleted shop has custom templates.
        if (is_dir($target)) {
            $excetion = oxNew(\OxidEsales\Eshop\Core\Exception\ExceptionToDisplay::class);
            $excetion->setMessage('CUSTOM_TEMPLATE_EXIST_FOR_DELETED_SHOP');
            \OxidEsales\Eshop\Core\Registry::getUtilsView()->addErrorToDisplay($excetion, false);
        }

        $delete = oxNew(\OxidEsales\Eshop\Application\Model\Shop::class);
        $tables = $config->getConfigParam('aMultiShopTables');
        $delete->setMultiShopTables($tables);

        //disabling deletion for derived items
        if ($delete->isDerived()) {
            return;
        }

        $delete->delete($shopId);

        // Task #1476 Administer attributes
        $this->resetContentCache();
        $this->_blUpdateNav = true;

        $config->setShopId($config->getBaseShopId());
        $this->clearItemList();
        $this->init();
    }

    /**
     * Set to view data if update navigation menu.
     */
    protected function updateNavigation()
    {
        parent::updateNavigation();
        $this->_aViewData['updatenav'] = isset($this->_blUpdateNav) ? $this->_blUpdateNav : ($this->getRights() ? \OxidEsales\Eshop\Core\Registry::getConfig()->getRequestParameter('updatenav') : 0);
    }
}
