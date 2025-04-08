<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Core;

/**
 * @inheritdoc
 */
class AdminLogSqlDecorator extends \OxidEsales\EshopProfessional\Core\AdminLogSqlDecorator
{
    /**
     * @inheritdoc
     */
    public function prepareSqlForLogging($originalSql)
    {
        $userId = $this->getUserId();
        $shopConfig = \OxidEsales\Eshop\Core\Registry::getConfig();

        $shopId = $shopConfig->getShopId();
        $sessionId = \OxidEsales\Eshop\Core\Registry::getSession()->getId();
        $className = $shopConfig->getActiveView()->getClassName();
        $classMethod = $shopConfig->getActiveView()->getFncName();

        $preparedSql = "insert into {$this->table} (oxuserid, oxshopid, oxsessid, oxclass, oxfnc, oxsql)
          values ('{$userId}', '{$shopId}', '{$sessionId}', '{$className}', '{$classMethod}', " . $this->quote($originalSql) . ")";

        return $preparedSql;
    }
}
