<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Core;

use oxUtilsServer;

/**
 * @inheritdoc
 */
class ShopIdCalculator extends \OxidEsales\EshopProfessional\Core\ShopIdCalculator
{
    /**
     * @var int $_iShopId the active shop
     * stored as member variable so it does not has to be fetched from database
     */
    private $_iShopId;

    /**
     * @inheritdoc
     */
    public function getShopId()
    {
        if (isset($this->_iShopId)) {
            return $this->_iShopId;
        }
        $iShopId = false;

        if (!$iShopId && isset($_POST['shp'])) {
            $iShopId = (int) $_POST['shp'];
        }

        if (!$iShopId && isset($_GET['shp'])) {
            $iShopId = (int) $_GET['shp'];
        }

        if (!$iShopId && isset($_POST['actshop'])) {
            $iShopId = (int) $_POST['actshop'];
        }

        if (!$iShopId && isset($_GET['actshop'])) {
            $iShopId = (int) $_GET['actshop'];
        }

        if (!$iShopId) {
            $aShopUrlMap = $this->_getShopUrlMap();
            if (is_array($aShopUrlMap) && count($aShopUrlMap)) {
                // TODO: check posibility to use OxidEsales\Eshop\Core\Registry
                $oUtilsServer = new \OxidEsales\Eshop\Core\UtilsServer();
                foreach ($aShopUrlMap as $sUrl => $iShp) {
                    if ($sUrl && $oUtilsServer->isCurrentUrl($sUrl)) {
                        $iShopId = $iShp;
                    }
                }
            }
        }

        if (!$iShopId) {
            $iShopId = 1;
        }
        $this->_iShopId = $iShopId;
        return $iShopId;
    }
}
