<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Core;

use oxException;
use oxBase;

/**
 * This class handles multi-shop item relations with shops.
 *
 * For example: article is available in one or few shops or sub shops.
 *
 * @internal Do not make a module extension for this class.
 */
class Element2ShopRelations
{
    /**
     * Database gateway.
     *
     * @var Element2ShopRelationsDbGateway
     */
    private $_oDbGateway = null;

    /**
     * List of shop IDs
     *
     * @var array
     */
    private $_aShopIds = array();

    /**
     * Item type (table name to add to shop)
     *
     * @var string
     */
    private $_sItemType = null;

    /**
     * Flag whether to stack commands to add/remove item to shop.
     *
     * @var bool
     */
    private $_blAutoExecute = true;

    /**
     * Automatically executes stacked commands to add/remove item to shop.
     * @deprecated underscore prefix violates PSR12, will be renamed to "autoExecute" in next major
     */
    protected function _autoExecute() // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        if ($this->_blAutoExecute) {
            $this->execute();
        }
    }

    /**
     * Constructor class.
     *
     * @param string $sItemType     Type (table name) of item to add to shop
     * @param bool   $blAutoExecute Whether to automatically execute stacked commands to add/remove item to shop.
     */
    public function __construct($sItemType, $blAutoExecute = true)
    {
        $this->setItemType($sItemType);
        $this->_blAutoExecute = $blAutoExecute;
    }

    /**
     * Sets database gateway.
     *
     * @param Element2ShopRelationsDbGateway $oDbGateway Database gateway.
     */
    public function setDbGateway($oDbGateway)
    {
        $this->_oDbGateway = $oDbGateway;
    }

    /**
     * Gets database gateway.
     *
     * @return Element2ShopRelationsDbGateway
     */
    public function getDbGateway()
    {
        if (is_null($this->_oDbGateway)) {
            $this->setDbGateway(oxNew(\OxidEsales\Eshop\Core\Element2ShopRelationsDbGateway::class));
        }

        return $this->_oDbGateway;
    }

    /**
     * Sets shop ID or list of shop IDs.
     *
     * @param int|array $aShopIds Shop ID or list of shop IDs.
     */
    public function setShopIds($aShopIds)
    {
        if (!is_array($aShopIds)) {
            $aShopIds = array($aShopIds);
        }

        $this->_aShopIds = $aShopIds;
    }

    /**
     * Gets shop ID or list of shop IDs.
     *
     * @return array
     */
    public function getShopIds()
    {
        return $this->_aShopIds;
    }

    /**
     * Sets Item type - table name of element
     *
     * @param string $sItemType Type (table name) of item to add to shop
     */
    public function setItemType($sItemType)
    {
        $this->_sItemType = $sItemType;
    }

    /**
     * Gets Item type - table name of element
     *
     * @return string
     */
    public function getItemType()
    {
        return $this->_sItemType;
    }

    /**
     * Adds an object of the element to shop or list of shops.
     *
     * @param \OxidEsales\Eshop\Core\Model\BaseModel $oElementObject Object of the element added to shop
     */
    public function addObjectToShop($oElementObject)
    {
        $this->addToShop($oElementObject->getId());
    }

    /**
     * Adds item to shop or list of shops.
     *
     * @param int $iItemId Item ID to be added to shop.
     */
    public function addToShop($iItemId)
    {
        $sItemType = $this->getItemType();
        foreach ($this->getShopIds() as $iShopId) {
            $this->getDbGateway()->addToShop($iItemId, $sItemType, $iShopId);
        }

        $this->_autoExecute();
    }

    /**
     * Gives an object of item and removes it from shop or list of shops.
     *
     * @param \OxidEsales\Eshop\Core\Model\BaseModel $oElementObject Object of the element added to shop
     */
    public function removeObjectFromShop($oElementObject)
    {
        $this->removeFromShop($oElementObject->getId());
    }

    /**
     * Removes item from shop or list of shops.
     *
     * @param int $iItemId Item ID to be removed.
     */
    public function removeFromShop($iItemId)
    {
        $sItemType = $this->getItemType();
        foreach ($this->getShopIds() as $iShopId) {
            $this->getDbGateway()->removeFromShop($iItemId, $sItemType, $iShopId);
        }

        $this->_autoExecute();
    }

    /**
     * Inherits items by type to sub shop(-s) from parent shop.
     *
     * @param int $iParentShopId Parent shop ID
     */
    public function inheritFromShop($iParentShopId)
    {
        $sItemType = $this->getItemType();
        foreach ($this->getShopIds() as $iSubShopId) {
            $this->getDbGateway()->inheritFromShop($iParentShopId, $iSubShopId, $sItemType);
        }

        $this->_autoExecute();
    }

    /**
     * Removes items by type from sub shop(-s) that were inherited from parent shop.
     *
     * @param int $iParentShopId Parent shop ID
     */
    public function removeInheritedFromShop($iParentShopId)
    {
        $sItemType = $this->getItemType();
        foreach ($this->getShopIds() as $iSubShopId) {
            $this->getDbGateway()->removeInheritedFromShop($iParentShopId, $iSubShopId, $sItemType);
        }

        $this->_autoExecute();
    }

    /**
     * Removes item from all shops. It will remove all relations of a single object from database table.
     * Usage example: removeFromAllShops('abc', 'oxarticles');
     * Will remove all relations data for given object.
     *
     * Shop id is not required for the oxElement2ShopRelations object in this case.
     * Use only with tables that can be inherited.
     *
     * @param int $iItemId Item ID to be removed.
     */
    public function removeFromAllShops($iItemId)
    {
        $this->getDbGateway()->removeFromAllShops($iItemId, $this->getItemType());

        $this->_autoExecute();
    }

    /**
     * Copies inheritance information from one object to another for specified type.
     *
     * @param int $iSourceItemId      Source item id to copy inheritance from.
     * @param int $iDestinationItemId Destination item id to copy inheritance for.
     */
    public function copyInheritance($iSourceItemId, $iDestinationItemId)
    {
        $this->getDbGateway()->copyInheritance($iSourceItemId, $iDestinationItemId, $this->getItemType());

        $this->_autoExecute();
    }

    /**
     * Returns array of shop IDs where this item added to.
     *
     * @param int $iItemId Item ID.
     *
     * @return array
     */
    public function getItemAssignedShopIds($iItemId)
    {
        return $this->getDbGateway()->getShopIds($iItemId, $this->getItemType());
    }

    /**
     * Checks if item is in one of the set shops.
     *
     * @param int $iItemId Item ID to check.
     *
     * @throws oxException
     *
     * @return bool
     */
    public function isInShop($iItemId)
    {
        if (count($this->getShopIds()) <= 0) {
            throw new \OxidEsales\Eshop\Core\Exception\StandardException("There are no shop ids set for relation check");
        }

        return $this->getDbGateway()->isInShop($iItemId, $this->getItemType(), $this->getShopIds());
    }

    /**
     * Assign all elements from shop
     */
    public function inheritAllElements()
    {
        $sItemType = $this->getItemType();
        foreach ($this->getShopIds() as $iSubShopId) {
            $this->getDbGateway()->inheritAllElements($iSubShopId, $sItemType);
        }

        $this->_autoExecute();
    }

    /**
     * Remove all elements to shop
     */
    public function removeAllElements()
    {
        $sItemType = $this->getItemType();
        foreach ($this->getShopIds() as $iSubShopId) {
            $this->getDbGateway()->removeAllElements($iSubShopId, $sItemType);
        }

        $this->_autoExecute();
    }

    /**
     * Executes stacked commands to add/remove item to shop.
     */
    public function execute()
    {
        $this->getDbGateway()->flush();
    }
}
