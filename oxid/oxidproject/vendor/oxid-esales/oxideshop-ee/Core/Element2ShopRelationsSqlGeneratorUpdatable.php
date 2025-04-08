<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\EshopEnterprise\Core;

/**
 * @internal Do not make a module extension for this class.
 */
class Element2ShopRelationsSqlGeneratorUpdatable extends Element2ShopRelationsSqlGenerator
{
    /**
     * @param string $itemTable
     * @param int $itemId
     * @param int $shopId
     * @return array
     */
    public function getSqlForAddToShop($itemTable, $itemId, $shopId): array
    {
        if ($itemTable !== 'oxobject2category') {
            return parent::getSqlForAddToShop($itemTable, $itemId, $shopId);
        }
        return [
            $this->getReplaceStatementForObject2CategoryTable(),
            [$shopId, $shopId, $itemId]
        ];
    }

    /** @return string */
    private function getReplaceStatementForObject2CategoryTable(): string
    {
        return 'INSERT INTO `oxobject2category`
            (`oxid`, `oxshopid`, `oxobjectid`, `oxcatnid`, `oxpos`, `oxtime`)
            SELECT
                MD5(CONCAT(`oxobjectid`, `oxcatnid`, ?)),
                ?,
                `oxobjectid`,
                `oxcatnid`,
                `oxpos`,
                UNIX_TIMESTAMP()
            FROM `oxobject2category` AS t
            WHERE `oxid` = ?
            ON DUPLICATE KEY UPDATE `oxpos` = t.`oxpos`';
    }
}
