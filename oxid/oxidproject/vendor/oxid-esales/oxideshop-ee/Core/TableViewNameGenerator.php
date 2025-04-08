<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Core;

/**
 * @inheritdoc
 */
class TableViewNameGenerator extends \OxidEsales\EshopProfessional\Core\TableViewNameGenerator
{
    /**
     * if user does not want to use views he could specify that in config.inc.php
     * this could be used for example for performance reasons when there exists only one shop
     */
    protected function getViewSuffix($table, $languageId, $shopId, $isMultiLang)
    {
        $config = $this->getConfig();

        $viewSuffix = '';
        if ($shopId != -1 && $config->isMall() && in_array($table, $config->getConfigParam('aMultiShopTables'))) {
            $viewSuffix .= "_{$shopId}";
        }

        return $viewSuffix . parent::getViewSuffix($table, $languageId, $shopId, $isMultiLang);
    }
}
