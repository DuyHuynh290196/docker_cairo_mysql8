<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Controller\Admin;

use oxDb;

/**
 * @inheritdoc
 */
class PriceAlarmMain extends \OxidEsales\EshopProfessional\Application\Controller\Admin\PriceAlarmMain
{
    /**
     * @inheritdoc
     */
    protected function getActivePriceAlarmsCount()
    {
        $config = $this->getConfig();

        // #889C - Netto prices in Admin
        $priceIndex = "";
        if ($config->getConfigParam('blEnterNetPrice')) {
            $priceIndex = " * " . (1 + $config->getConfigParam('dDefaultVAT') / 100);
        }

        $articleViewName = getViewName('oxarticles');
        $shopId = $config->getShopID();

        $query = "
            SELECT COUNT(*)
            FROM oxpricealarm, $articleViewName
            WHERE $articleViewName.oxid = oxpricealarm.oxartid
            AND $articleViewName.oxprice$priceIndex <= oxpricealarm.oxprice
            AND oxpricealarm.oxsended = '000-00-00 00:00:00'
            AND oxpricealarm.oxshopid = :oxshopid";

        return (int) \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->getOne($query, [
            ':oxshopid' => $shopId
        ]);
    }
}
