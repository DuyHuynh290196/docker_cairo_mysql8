<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Component\Service;

/**
 * Class Element2ShopRelationsService. Acts as a service for shop relations.
 *
 * @internal Do not make a module extension for this class.
 */
class Element2ShopRelationsService
{
    /**
     * DB table used for multiple shops we are going to deal with
     */
    protected $_sMallTable = null;

    /**
     * Original item shopid, (eg oxarticle__oxshopid->value)
     */
    protected $_sItemShopId = null;


    /**
     * @var array Selected subshops
     */
    protected $_aSelectedSubshops = null;

    /**
     * Class name of object to load
     */
    protected $_sObjectClassName = null;

    /**
     * Object id, that was edited
     */
    protected $_sEditObjectId = null;

    /**
     * Assigns record information in multiple shop field
     */
    public function assignToSubShops()
    {
        $selectedSubShops = $this->getSelectedSubShops();
        $item = $this->_getSelectedItem();
        $itemShopIds = $item->getItemAssignedShopIds();
        $allSubShops = $this->getSubShopList($this->_getItemShopId());

        foreach ($allSubShops as $oneSubShop) {
            $subShopId = $oneSubShop->getId();
            //naturally inherited(+), but not select from form input(-)
            if (in_array($subShopId, $itemShopIds) && !in_array($subShopId, $selectedSubShops)) {
                $item->unassignFromShop($subShopId);
            }

            //naturally not inherited(-) and selected (+)
            if (!in_array($subShopId, $itemShopIds) && in_array($subShopId, $selectedSubShops)) {
                $item->assignToShop($subShopId);
            }
        }
    }

    /**
     * Returns subshop tree.
     *
     * @param string $sShopId shop id
     *
     * @return null
     */
    public function getSubShopList($sShopId)
    {
        $activeShop = oxNew(\OxidEsales\Eshop\Application\Model\Shop::class);
        $activeShop->load($sShopId);
        $oShopList = $activeShop->getSubShopList();

        return $oShopList;
    }

    /**
     * Loads selected item using oxBase
     *
     * @return \oxBase
     * @deprecated underscore prefix violates PSR12, will be renamed to "getSelectedItem" in next major
     */
    protected function _getSelectedItem() // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $objectClassName = $this->_getObjectClassName();
        $item = oxNew($objectClassName);
        $item->init($this->_getMallTable());
        $item->load($this->getEditObjectId());

        return $item;
    }

    /**
     * Returns selected subshops
     *
     * @return mixed
     */
    public function getSelectedSubShops()
    {
        return $this->_aSelectedSubshops;
    }

    /**
     * Returns array of selected subshop ids
     *
     * @param array $selectedSubShops Array of shop ids, that were selected in admin.
     */
    public function setSelectedSubShops($selectedSubShops)
    {
        $this->_aSelectedSubshops = $selectedSubShops;
    }

    /**
     * Returns object class name
     *
     * @return string
     * @deprecated underscore prefix violates PSR12, will be renamed to "getObjectClassName" in next major
     */
    protected function _getObjectClassName() // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        return $this->_sObjectClassName;
    }

    /**
     * Object class name setter
     *
     * @param string $objectClassName Object class name
     */
    public function setObjectClassName($objectClassName)
    {
        $this->_sObjectClassName = $objectClassName;
    }

    /**
     * Returns mall table name
     *
     * @return string
     * @deprecated underscore prefix violates PSR12, will be renamed to "getMallTable" in next major
     */
    protected function _getMallTable() // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        return $this->_sMallTable;
    }

    /**
     * Mall table name setter
     *
     * @param string $mallTable Mall table name
     */
    public function setMallTable($mallTable)
    {
        $this->_sMallTable = $mallTable;
    }

    /**
     * Returns active/editable object id
     *
     * @return string
     */
    public function getEditObjectId()
    {
        return $this->_sEditObjectId;
    }

    /**
     * Sets active/editable object id
     *
     * @param string $editObjectId Active/editable object.
     */
    public function setEditObjectId($editObjectId)
    {
        $this->_sEditObjectId = $editObjectId;
    }

    /**
     * Returns item shop id
     *
     * @return string
     * @deprecated underscore prefix violates PSR12, will be renamed to "getItemShopId" in next major
     */
    protected function _getItemShopId() // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        return $this->_sItemShopId;
    }

    /**
     * Item shop id setter
     *
     * @param string $shopId item shop id
     */
    public function setItemShopId($shopId)
    {
        $this->_sItemShopId = $shopId;
    }
}
