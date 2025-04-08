<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Model;

/**
 * @inheritdoc
 */
class ShopViewValidator extends \OxidEsales\EshopProfessional\Application\Model\ShopViewValidator
{
    /**
     * @inheritdoc
     */
    protected function getShopTables()
    {
        $shopTables = parent::getShopTables();
        $shopTables = array_unique(array_merge($this->getMultiShopTables(), $shopTables));

        return $shopTables;
    }

    /**
     * @inheritdoc
     */
    protected function prepareShopTableViewNames($tableName)
    {
        parent::prepareShopTableViewNames($tableName);

        if (in_array($tableName, $this->getMultiShopTables())) {
            $this->_aValidShopViews[] = 'oxv_' . $tableName . '_' . $this->getShopId();
        }

        if (in_array($tableName, $this->getMultiShopTables()) && in_array($tableName, $this->getMultiLangTables())) {
            foreach ($this->getLanguages() as $oneLanguage) {
                $this->_aValidShopViews[] = 'oxv_' . $tableName . '_' . $this->getShopId() . '_' . $oneLanguage;
            }
        }
    }
}
