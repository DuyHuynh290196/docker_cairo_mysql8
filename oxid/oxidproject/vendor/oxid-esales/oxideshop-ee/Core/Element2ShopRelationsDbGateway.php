<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Core;

use oxDb;
use OxidEsales\Eshop\Core\Database\Adapter\DatabaseInterface;

/**
 * This class handles data in database for multi-shop item relation with shops.
 *
 * @internal Do not make a module extension for this class.
 */
class Element2ShopRelationsDbGateway
{
    /**
     * Database class object.
     *
     * @var DatabaseInterface
     */
    private $_oDb = null;

    /**
     * SQL generator class object.
     *
     * @var Element2ShopRelationsSqlGenerator
     */
    private $_oSqlGenerator = null;

    /**
     * SQL query list with parameters.
     *
     * @var array
     */
    private $_aSqls = array();

    /**
     * Adds SQL query to list.
     *
     * @param array $queries SQL query wit parameters Array($queryQuery, $parameters).
     * @deprecated underscore prefix violates PSR12, will be renamed to "addSql" in next major
     */
    protected function _addSql($queries) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $this->_aSqls[] = $queries;
    }

    /**
     * Gets SQL query list.
     *
     * @return array
     * @deprecated underscore prefix violates PSR12, will be renamed to "getSqlList" in next major
     */
    protected function _getSqlList() // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        return $this->_aSqls;
    }

    /**
     * Clears SQL query list.
     * @deprecated underscore prefix violates PSR12, will be renamed to "clearSqlList" in next major
     */
    protected function _clearSqlList() // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $this->_aSqls = array();
    }

    /**
     * Sets database class object.
     *
     * @param DatabaseInterface $database Database gateway.
     */
    public function setDbGateway($database)
    {
        $this->_oDb = $database;
    }

    /**
     * Gets database class object.
     *
     * @return DatabaseInterface
     */
    public function getDbGateway()
    {
        if (is_null($this->_oDb)) {
            $this->setDbGateway(\OxidEsales\Eshop\Core\DatabaseProvider::getDb());
        }

        return $this->_oDb;
    }

    /**
     * Sets SQL generator class object.
     *
     * @param Element2ShopRelationsSqlGenerator $queryGenerator SQL generator class object.
     */
    public function setSqlGenerator($queryGenerator)
    {
        $this->_oSqlGenerator = $queryGenerator;
    }

    /**
     * Gets SQL generator class object.
     *
     * @return Element2ShopRelationsSqlGenerator
     */
    public function getSqlGenerator()
    {
        if (is_null($this->_oSqlGenerator)) {
            $this->setSqlGenerator(oxNew(\OxidEsales\Eshop\Core\Element2ShopRelationsSqlGenerator::class));
        }

        return $this->_oSqlGenerator;
    }

    /**
     * Adds item to shop.
     *
     * @param int    $itemId   Item ID.
     * @param string $itemType Item type.
     * @param int    $shopId   Shop ID.
     */
    public function addToShop($itemId, $itemType, $shopId)
    {
        $this->_addSql($this->getSqlGenerator()->getSqlForAddToShop($itemType, $itemId, $shopId));
    }

    /**
     * Removes item from shop.
     *
     * @param int    $itemId   Item ID.
     * @param string $itemType Item type.
     * @param int    $shopId   Shop ID.
     */
    public function removeFromShop($itemId, $itemType, $shopId)
    {
        $this->_addSql($this->getSqlGenerator()->getSqlForRemoveFromShop($itemType, $itemId, $shopId));
    }

    /**
     * Removes all items from shop
     *
     * @param int    $itemId   Item map ID
     * @param string $itemType Item type
     */
    public function removeFromAllShops($itemId, $itemType)
    {
        $this->_addSql($this->getSqlGenerator()->getSqlForRemoveFromAllShops($itemType, $itemId));
    }

    /**
     * Inherits items by type to sub shop from parent shop.
     *
     * @param int    $parentShopId Parent shop ID
     * @param int    $subShopId    Sub shop ID
     * @param string $itemType     Item type
     */
    public function inheritFromShop($parentShopId, $subShopId, $itemType)
    {
        $this->_addSql($this->getSqlGenerator()->getSqlForInheritFromShop($itemType, $parentShopId, $subShopId));
    }

    /**
     * Removes items by type from sub shop that were inherited from parent shop.
     *
     * @param int    $parentShopId Parent shop ID
     * @param int    $subShopId    Sub shop ID
     * @param string $itemType     Item type
     */
    public function removeInheritedFromShop($parentShopId, $subShopId, $itemType)
    {
        $this->_addSql(
            $this->getSqlGenerator()->getSqlForRemoveInheritedFromShop($itemType, $parentShopId, $subShopId)
        );
    }

    /**
     * Copies inheritance information from one item to another.
     *
     * @param int    $sourceItemId      Item to copy inheritance from
     * @param int    $destinationItemId Item to copy inheritance for
     * @param string $itemType          Item type
     */
    public function copyInheritance($sourceItemId, $destinationItemId, $itemType)
    {
        $this->_addSql(
            $this->getSqlGenerator()->getSqlForCopyInheritance($itemType, $sourceItemId, $destinationItemId)
        );
    }

    /**
     * Checks if item is in one of the set shops.
     *
     * @param int    $itemId     Item map ID
     * @param string $itemType   Item type
     * @param array  $subShopIds Sub shop IDs
     *
     * @return bool
     */
    public function isInShop($itemId, $itemType, $subShopIds)
    {
        list($query, $parameters) = $this->getSqlGenerator()->getSqlForIsInShop($itemType, $itemId, $subShopIds);

        $result = $this->getDbGateway()->getOne($query, $parameters);

        if ($result) {
            $isInShop = true;
        } else {
            $isInShop = false;
        }

        return $isInShop;
    }

    /**
     * Inherit all items by type to shop.
     *
     * @param int    $shopId   Shop ID.
     * @param string $itemType Item type.
     */
    public function inheritAllElements($shopId, $itemType)
    {
        $this->_addSql($this->getSqlGenerator()->getSqlForInheritAllElements($shopId, $itemType));
    }

    /**
     * Remove all items by type from shop.
     *
     * @param int    $shopId   Shop ID.
     * @param string $itemType Item type.
     */
    public function removeAllElements($shopId, $itemType)
    {
        $this->_addSql($this->getSqlGenerator()->getSqlForRemoveAllElements($shopId, $itemType));
    }

    /**
     * Returns array of shop IDs where this item added to.
     *
     * @param int    $itemId   Item ID.
     * @param string $itemType Item type.
     *
     * @return array
     */
    public function getShopIds($itemId, $itemType)
    {
        list($query, $parameters) = $this->getSqlGenerator()->getSqlForGetShopIds($itemType, $itemId);

        $shopIds = $this->getDbGateway()->getCol($query, $parameters);

        return $shopIds;
    }

    /**
     * Executes all SQL queries from the list.
     */
    public function flush()
    {
        foreach ($this->_getSqlList() as $queries) {
            list($query, $parameters) = $queries;
            $this->getDbGateway()->execute($query, $parameters);
        }

        $this->_clearSqlList();
    }
}
