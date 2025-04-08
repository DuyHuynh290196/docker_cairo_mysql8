<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Core;

/**
 * This class generates SQL for multi-shop item relation with shops.
 *
 * @internal Do not make a module extension for this class.
 */
class Element2ShopRelationsSqlGenerator
{
    /**
     * Gets mapping table for item table.
     *
     * @param string $itemTable Item table.
     *
     * @return string
     */
    private function _getMappingTable($itemTable) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        return $itemTable . '2shop';
    }

    /**
     * Provides sub query SQL for getting map IDs from item table.
     *
     * @param string $itemTable    Item table to select from.
     * @param string $isForElement If select for one special item.
     *
     * @return string
     */
    private function _getSqlSnippetGetMapIds($itemTable, $isForElement = true) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $query = "SELECT `oxmapid`"
            . " FROM `{$itemTable}`";
        if ($isForElement) {
            $query .= " WHERE `oxid` = ?";
        }

        return $query;
    }

    /**
     * Provides SQL to join with item table.
     *
     * @param string $itemTable Item table.
     *
     * @return string
     */
    private function _getSqlSnippetJoinItemTable($itemTable) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $mappingTable = $this->_getMappingTable($itemTable);

        $query = "LEFT JOIN `{$itemTable}`"
            . " ON `{$itemTable}`.`oxmapid` = `{$mappingTable}`.`oxmapobjectid`";

        return $query;
    }

    /**
     * Gets SQL for adding item to shop.
     *
     * @param string $itemTable Item table.
     * @param int    $itemId    Item ID.
     * @param int    $shopId    Shop ID.
     *
     * @return array
     */
    public function getSqlForAddToShop($itemTable, $itemId, $shopId)
    {
        if ($itemTable == 'oxobject2category') {
            $query = $this->_getSqlForAddToShopForObject2Category($itemTable, $itemId, $shopId);
        } else {
            $query = $this->_getSqlForAddToShop($itemTable, $itemId, $shopId);
        }

        return $query;
    }

    /**
     * Gets SQL for adding item to shop for common item tables.
     *
     * @param string $itemTable Item table.
     * @param int    $itemId    Item ID.
     * @param int    $shopId    Shop ID.
     *
     * @return array
     */
    private function _getSqlForAddToShop($itemTable, $itemId, $shopId) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $mappingTable = $this->_getMappingTable($itemTable);
        $queryToGetMapId = $this->_getSqlSnippetGetMapIds($itemTable);

        $query = "INSERT IGNORE INTO `{$mappingTable}` (`oxshopid`, `oxmapobjectid`)"
            . " VALUES (?, ({$queryToGetMapId}))";

        return array($query, array($shopId, $itemId));
    }

    /**
     * Gets SQL for adding item to shop for oxobject2category table.
     *
     * @param string $itemTable Item table.
     * @param int    $itemId    Item ID.
     * @param int    $shopId    Shop ID.
     *
     * @return array
     */
    private function _getSqlForAddToShopForObject2Category($itemTable, $itemId, $shopId) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $queryToGetNewItemId = "MD5(CONCAT(`oxobjectid`, `oxcatnid`, ?))";

        $query = "INSERT IGNORE INTO `{$itemTable}` (`oxid`, `oxshopid`, `oxobjectid`, `oxcatnid`, `oxpos`, `oxtime`)"
            . " SELECT {$queryToGetNewItemId}, ?, `oxobjectid`, `oxcatnid`, `oxpos`, UNIX_TIMESTAMP()"
            . " FROM `{$itemTable}`"
            . " WHERE `oxid` = ?";

        return array($query, array($shopId, $shopId, $itemId));
    }

    /**
     * Gets SQL for removing item from shop.
     *
     * @param string $itemTable Item table.
     * @param int    $itemId    Item ID.
     * @param int    $shopId    Shop ID.
     *
     * @return array
     */
    public function getSqlForRemoveFromShop($itemTable, $itemId, $shopId)
    {
        if ($itemTable == 'oxobject2category') {
            $queries = $this->_getSqlForRemoveFromShopForObject2Category($itemTable, $itemId, $shopId);
        } else {
            $queries = $this->_getSqlForRemoveFromShop($itemTable, $itemId, $shopId);
        }

        return $queries;
    }

    /**
     * Gets SQL for removing item from shop for common item tables.
     *
     * @param string $itemTable Item table.
     * @param int    $itemId    Item ID.
     * @param int    $shopId    Shop ID.
     *
     * @return array
     */
    private function _getSqlForRemoveFromShop($itemTable, $itemId, $shopId) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $mappingTable = $this->_getMappingTable($itemTable);
        $queryJoinItemTable = $this->_getSqlSnippetJoinItemTable($itemTable);

        $query = "DELETE `{$mappingTable}` FROM `{$mappingTable}` {$queryJoinItemTable}"
            . " WHERE `{$mappingTable}`.`oxshopid` = :oxshopid"
            . " AND `{$itemTable}`.`oxid` = :oxid";

        return array($query, [
            ':oxshopid' => $shopId,
            ':oxid' => $itemId
        ]);
    }

    /**
     * Gets SQL for removing item from shop for oxobject2category table.
     *
     * @param string $itemTable Item table.
     * @param int    $itemId    Item ID.
     * @param int    $shopId    Shop ID.
     *
     * @return array
     */
    private function _getSqlForRemoveFromShopForObject2Category($itemTable, $itemId, $shopId) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $query = "DELETE FROM `{$itemTable}`"
            . " WHERE `oxshopid` = :oxshopid"
            . " AND `oxid` = :oxid";

        return array($query, [
            ':oxshopid' => $shopId,
            ':oxid' => $itemId
        ]);
    }

    /**
     * Gets SQL for removing all items by type from shop.
     *
     * @param string $itemTable Item table.
     * @param int    $itemId    Item ID.
     *
     * @return array
     */
    public function getSqlForRemoveFromAllShops($itemTable, $itemId)
    {
        $mappingTable = $this->_getMappingTable($itemTable);
        $queryJoinItemTable = $this->_getSqlSnippetJoinItemTable($itemTable);

        $query = "DELETE `{$mappingTable}` FROM `{$mappingTable}` {$queryJoinItemTable}"
            . " WHERE `{$itemTable}`.`oxid` = :oxid";

        return array($query, [
            ':oxid' => $itemId
        ]);
    }

    /**
     * Gets SQL for inheriting items by type to sub shop from parent shop.
     *
     * @param string $itemTable    Item table.
     * @param int    $parentShopId Parent shop ID.
     * @param int    $subShopId    Sub shop ID.
     *
     * @return array
     */
    public function getSqlForInheritFromShop($itemTable, $parentShopId, $subShopId)
    {
        if ($itemTable == 'oxobject2category') {
            $queries = $this->_getSqlForInheritFromShopForObject2Category($itemTable, $parentShopId, $subShopId);
        } else {
            $queries = $this->_getSqlForInheritFromShop($itemTable, $parentShopId, $subShopId);
        }

        return $queries;
    }

    /**
     * Gets SQL for inheriting items by type to sub shop from parent shop for common item tables.
     *
     * @param string $itemTable    Item table.
     * @param int    $parentShopId Parent shop ID.
     * @param int    $subShopId    Sub shop ID.
     *
     * @return array
     */
    private function _getSqlForInheritFromShop($itemTable, $parentShopId, $subShopId) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $mappingTable = $this->_getMappingTable($itemTable);

        $query = "INSERT IGNORE INTO `{$mappingTable}` (`oxshopid`, `oxmapobjectid`)"
            . " SELECT ?, `oxmapobjectid`"
            . " FROM `{$mappingTable}`"
            . " WHERE `oxshopid` = ?";

        return array($query, array($subShopId, $parentShopId));
    }

    /**
     * Gets SQL for inheriting items by type to sub shop from parent shop for oxobject2category table.
     *
     * @param string $itemTable    Item table.
     * @param int    $parentShopId Parent shop ID.
     * @param int    $subShopId    Sub shop ID.
     *
     * @return array
     */
    private function _getSqlForInheritFromShopForObject2Category($itemTable, $parentShopId, $subShopId) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $queryToGetNewItemId = "MD5(CONCAT(`oxobjectid`, `oxcatnid`, ?))";

        $query = "INSERT IGNORE INTO `{$itemTable}` (`oxid`, `oxshopid`, `oxobjectid`, `oxcatnid`, `oxpos`, `oxtime`)"
            . " SELECT {$queryToGetNewItemId}, ?, `oxobjectid`, `oxcatnid`, `oxpos`, UNIX_TIMESTAMP()"
            . " FROM `{$itemTable}`"
            . " WHERE `oxshopid` = ?";

        return array($query, array($subShopId, $subShopId, $parentShopId));
    }

    /**
     * Provides SQL to join oxobject2category table with itself.
     *
     * @param string $itemTable             Item table.
     * @param string $coreTableAbbreviation Core table abbreviation.
     * @param string $joinAbbreviations     Joined table abbreviation.
     *
     * @return string
     */
    private function _getSqlSnippetJoinItemTableForObject2Category( // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
        $itemTable,
        $coreTableAbbreviation = 't1',
        $joinAbbreviations = 't2'
    ) {
        $query = "LEFT JOIN `{$itemTable}` {$joinAbbreviations}"
            . " ON `{$coreTableAbbreviation}`.`oxobjectid` = `{$joinAbbreviations}`.`oxobjectid`"
            . " AND `{$coreTableAbbreviation}`.`oxcatnid` = `{$joinAbbreviations}`.`oxcatnid`";

        return $query;
    }

    /**
     * Gets SQL for removing items by type from sub shop that were inherited from parent shop for common item tables.
     *
     * @param string $itemTable    Item table.
     * @param int    $parentShopId Parent shop ID.
     * @param int    $subShopId    Sub shop ID.
     *
     * @return array
     */
    private function _getSqlForRemoveInheritedFromShop($itemTable, $parentShopId, $subShopId) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $mappingTable = $this->_getMappingTable($itemTable);

        $query = "DELETE s FROM `{$mappingTable}` AS s"
            . " LEFT JOIN `{$mappingTable}` AS p ON (s.`oxmapobjectid` = p.`oxmapobjectid`)"
            . " WHERE s.`oxshopid` = :subShopId"
            . " AND p.`oxshopid` = :parentShopId";

        return array($query, [
            ':subShopId' => $subShopId,
            ':parentShopId' => $parentShopId
        ]);
    }

    /**
     * Gets SQL for removing items by type from sub shop that were inherited from parent shop for oxobject2category
     * table.
     *
     * @param string $itemTable    Item table.
     * @param int    $parentShopId Parent shop ID.
     * @param int    $subShopId    Sub shop ID.
     *
     * @return array
     */
    private function _getSqlForRemoveInheritedFromShopForObject2Category($itemTable, $parentShopId, $subShopId) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $queryMapItemId = "BINARY MD5(CONCAT(p.`oxobjectid`, p.`oxcatnid`, p.`oxshopid`))";

        $query = "DELETE s FROM `{$itemTable}` AS s"
            . " LEFT JOIN `{$itemTable}` AS p ON (BINARY s.`oxid` = {$queryMapItemId})"
            . " WHERE s.`oxshopid` = :subShopId"
            . " AND p.`oxshopid` = :parentShopId";

        return array($query, [
            ':subShopId' => $subShopId,
            ':parentShopId' => $parentShopId
        ]);
    }

    /**
     * Provides SQL to get shop ids where object exists in oxobject2category table.
     *
     * @param string $itemTable Item table.
     * @param int    $itemId    Item ID.
     *
     * @return array
     * @deprecated underscore prefix violates PSR12, will be renamed to "getSqlForGetShopIdsForObject2Category" in next major
     */
    protected function _getSqlForGetShopIdsForObject2Category($itemTable, $itemId) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $coreTableAbbreviation = 'o1';
        $joinAbbreviations = 'o2';
        $queryJoinItemTable = $this->_getSqlSnippetJoinItemTableForObject2Category(
            $itemTable,
            $coreTableAbbreviation,
            $joinAbbreviations
        );

        $query = "SELECT `{$coreTableAbbreviation}`.`oxshopid`"
            . " FROM `{$itemTable}` {$coreTableAbbreviation} {$queryJoinItemTable}"
            . " WHERE `{$coreTableAbbreviation}`.`oxid` = ?";

        return array($query, array($itemId));
    }

    /**
     * Gets SQL for getting shop IDs for item with mapping tables.
     *
     * @param string $itemTable Item type.
     * @param int    $itemId    Item ID.
     *
     * @return array
     */
    private function _getSqlForGetShopIdsForMappedTables($itemTable, $itemId) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $mappingTable = $this->_getMappingTable($itemTable);
        $queryJoinItemTable = $this->_getSqlSnippetJoinItemTable($itemTable);

        $query = "SELECT `{$mappingTable}`.`oxshopid`"
            . " FROM `{$mappingTable}` {$queryJoinItemTable}"
            . " WHERE `{$itemTable}`.`oxid` = ?";

        return array($query, array($itemId));
    }

    /**
     * Gets SQL for copying inheritance information from one item to another.
     *
     * @param string $itemTable         Item table.
     * @param int    $sourceItemId      Item to copy inheritance from.
     * @param int    $destinationItemId Item to copy inheritance for.
     *
     * @return array
     */
    public function getSqlForCopyInheritance($itemTable, $sourceItemId, $destinationItemId)
    {
        $mappingTable = $this->_getMappingTable($itemTable);
        $queryToGetMapId = $this->_getSqlSnippetGetMapIds($itemTable);

        $query = "INSERT IGNORE INTO `{$mappingTable}` (`oxshopid`, `oxmapobjectid`)"
            . " SELECT `oxshopid`, ({$queryToGetMapId})"
            . " FROM `{$mappingTable}`"
            . " WHERE `oxmapobjectid` = ({$queryToGetMapId})";

        return array($query, array($destinationItemId, $sourceItemId));
    }

    /**
     * Gets SQL for checking if item is in one of the shops.
     *
     * @param string $itemTable  Item table.
     * @param int    $itemId     Item map ID.
     * @param array  $subShopIds Sub shop IDs.
     *
     * @return array
     */
    public function getSqlForIsInShop($itemTable, $itemId, $subShopIds)
    {
        $parameters = array($itemId);
        $parameters = array_merge($parameters, $subShopIds);

        $shopIdsPlaceholders = array_fill(0, count($subShopIds), '?');
        $shopIdsPlaceholders = implode(', ', $shopIdsPlaceholders);

        $mappingTable = $this->_getMappingTable($itemTable);
        $queryJoinItemTable = $this->_getSqlSnippetJoinItemTable($itemTable);

        $query = "SELECT COUNT(*)"
            . " FROM `{$mappingTable}` {$queryJoinItemTable}"
            . " WHERE `{$itemTable}`.`oxid` = ?"
            . " AND `{$mappingTable}`.`oxshopid` IN ({$shopIdsPlaceholders})";

        return array($query, $parameters);
    }

    /**
     * Gets SQL for removing items by type from sub shop that were inherited from parent shop.
     *
     * @param string $itemTable    Item table.
     * @param int    $parentShopId Parent shop ID.
     * @param int    $subShopId    Sub shop ID.
     *
     * @return array
     */
    public function getSqlForRemoveInheritedFromShop($itemTable, $parentShopId, $subShopId)
    {
        if ($itemTable == 'oxobject2category') {
            $queries = $this
                ->_getSqlForRemoveInheritedFromShopForObject2Category($itemTable, $parentShopId, $subShopId);
        } else {
            $queries = $this->_getSqlForRemoveInheritedFromShop($itemTable, $parentShopId, $subShopId);
        }

        return $queries;
    }

    /**
     * Gets SQL for getting shop IDs for item.
     *
     * @param string $itemTable Item type.
     * @param int    $itemId    Item ID.
     *
     * @return array
     */
    public function getSqlForGetShopIds($itemTable, $itemId)
    {
        if ($itemTable == 'oxobject2category') {
            $queries = $this->_getSqlForGetShopIdsForObject2Category($itemTable, $itemId);
        } else {
            $queries = $this->_getSqlForGetShopIdsForMappedTables($itemTable, $itemId);
        }

        return $queries;
    }

    /**
     * Gets SQL for inheriting all items by type to shop.
     *
     * @param int    $shopId    Sub shop ID.
     * @param string $itemTable Item table.
     *
     * @return array
     */
    public function getSqlForInheritAllElements($shopId, $itemTable)
    {
        if ($itemTable == 'oxobject2category') {
            $queries = $this->_getSqlForInheritAllElementsToShopForObject2Category($shopId, $itemTable);
        } else {
            $queries = $this->_getSqlForInheritAllElementsToShop($shopId, $itemTable);
        }

        return $queries;
    }

    /**
     * Gets SQL for inheriting all items by type to shop.
     *
     * @param int    $shopId    Sub shop ID.
     * @param string $itemTable Item table.
     *
     * @return array
     */
    private function _getSqlForInheritAllElementsToShop($shopId, $itemTable) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $mappingTable = $this->_getMappingTable($itemTable);

        $query = "INSERT IGNORE INTO `{$mappingTable}` (`oxshopid`, `oxmapobjectid`)"
            . " SELECT ?, `oxmapid` FROM `{$itemTable}`";

        return array($query, array($shopId));
    }

    /**
     * Gets SQL for inheriting all oxobject2category items by type to shop.
     *
     * @param int    $shopId    Sub shop ID.
     * @param string $itemTable Item table.
     *
     * @return array
     */
    private function _getSqlForInheritAllElementsToShopForObject2Category($shopId, $itemTable) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $queryToGetNewItemId = "MD5(CONCAT(`oxobjectid`, `oxcatnid`, ?))";

        $query = "INSERT IGNORE INTO `{$itemTable}` (`oxid`, `oxshopid`, `oxobjectid`, `oxcatnid`, `oxpos`, `oxtime`)"
            . " SELECT {$queryToGetNewItemId}, ?, `oxobjectid`, `oxcatnid`, `oxpos`, UNIX_TIMESTAMP()"
            . " FROM `{$itemTable}`";

        return array($query, array($shopId, $shopId));
    }

    /**
     * Gets SQL for removing all items by type from shop.
     *
     * @param int    $shopId    Sub shop ID.
     * @param string $itemTable Item table.
     *
     * @return array
     */
    public function getSqlForRemoveAllElements($shopId, $itemTable)
    {
        $queries = $this->_getSqlForRemoveAllElementsFromShop($shopId, $itemTable);

        return $queries;
    }

    /**
     * Gets SQL for removing all items by type from shop.
     *
     * @param int    $shopId    Sub shop ID.
     * @param string $itemTable Item table.
     *
     * @return array
     */
    private function _getSqlForRemoveAllElementsFromShop($shopId, $itemTable) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $mappingTable = $this->_getMappingTable($itemTable);

        $query = "DELETE FROM `{$mappingTable}` "
            . " WHERE `oxshopid` = :oxshopid";

        return array($query, [
            ':oxshopid' => $shopId
        ]);
    }
}
