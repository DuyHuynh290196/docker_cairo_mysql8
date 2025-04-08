<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Controller\Admin;

/**
 * Defines table and class name.
 */
class PriceAlarmList extends \OxidEsales\EshopProfessional\Application\Controller\Admin\PriceAlarmList
{
    /**
     * @inheritdoc
     */
    public function buildWhere()
    {
        $this->_aWhere = parent::buildWhere();

        $priceAlarmViewName = getViewName("oxpricealarm");
        $this->_aWhere[$priceAlarmViewName . '.oxshopid'] = $this->getConfig()->getShopId();

        return $this->_aWhere;
    }
}
