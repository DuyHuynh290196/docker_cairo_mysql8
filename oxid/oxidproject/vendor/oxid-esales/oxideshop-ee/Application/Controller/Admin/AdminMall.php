<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Controller\Admin;

use oxElement2ShopRelationsService;
use oxShop;
use oxShopList;
use oxBase;
use OxidEsales\Eshop\Core\Registry;

/**
 * Admin mall assignment manager
 * Admin Menu: Shop Settings -> Payment Methods.
 */
class AdminMall extends \OxidEsales\Eshop\Application\Controller\Admin\AdminDetailsController
{
    /**
     * This template files
     **/
    protected $_blThisTemplate = "admin_mall.tpl";

    /**
     * Set $_blAllowSubshopAssign to true if you want to allow the record to be assignment to subshops
     *
     * @var bool
     */
    protected $_blAllowSubshopAssign = true;

    /**
     * DB table used for multiple shops we are going to deal with
     */
    protected $_sMallTable = null;

    /**
     * Class name of object to load
     */
    protected $_sObjectClassName = null;

    /**
     * Original item shopid, (eg oxarticle__oxshopid->value)
     */
    protected $_sItemShopId = null;

    /**
     * @var array Selected subshops
     */
    protected $_aSelectedSubshops = null;

    /**
     * @var object Selected loaded item
     */
    protected $_oItem = null;

    /**
     * Executes parent method parent::render() and returns template file
     * name "admin_payment.tpl".
     *
     * @return string
     */
    public function render()
    {
        parent::render();

        $className = $this->findCurrentClassName();
        $this->_aViewData["class"] = $className;

        //loading shops
        if ($this->getConfig()->isMall() && $this->_getMallTable()) {
            $this->_aViewData["shoplist"] = $this->getMarkedShopList();
        }

        $this->_aViewData['allowAssign'] = $this->_blAllowSubshopAssign;

        return $this->_blThisTemplate;
    }

    /**
     * Assigns record information in multiple shop field
     *
     * @return null
     */
    public function assignToSubShops()
    {
        if (!$this->_blAllowSubshopAssign) {
            return;
        }

        $this->resetContentCache();

        if (($sMallTable = $this->_getMallTable())) {
            $oElement2ShopRelationsService = oxNew(\OxidEsales\Eshop\Application\Component\Service\Element2ShopRelationsService::class);
            $oElement2ShopRelationsService->setMallTable($sMallTable);
            $oElement2ShopRelationsService->setObjectClassName($this->_getObjectClassName());
            $oElement2ShopRelationsService->setEditObjectId($this->getEditObjectId());
            $oElement2ShopRelationsService->setItemShopId($this->_getShopId());
            $oElement2ShopRelationsService->setSelectedSubShops($this->getSelectedSubShops());
            $oElement2ShopRelationsService->assignToSubshops();
        }
    }

    /**
     * Returns subshop tree with marked selected shops.
     *
     * @param string $sShopID shop id
     *
     * @return oxShopList
     */
    public function getSubShopList($sShopID = null)
    {
        if (!$sShopID) {
            $sShopID = $this->_getShopId();
        }
        $oActShop = oxNew(\OxidEsales\Eshop\Application\Model\Shop::class);
        $oActShop->load($sShopID);
        $oShopList = $oActShop->getSubShopList();

        return $oShopList;
    }

    /**
     * Returns subshop tree with marked selected shops.
     *
     * @return null
     */
    public function getMarkedShopList()
    {
        $oShopList = $this->getSubShopList();
        $aItemShopIds = $this->_getItemAssignedShopIds();

        //marking included shops
        foreach ($oShopList as $key => $oShop) {
            $iShopId = $oShop->getId();

            //should we check the checkbox?
            $oShopList[$key]->selected = false;

            //marking items included in shop
            if (in_array($iShopId, $aItemShopIds)) {
                $oShopList[$key]->selected = true;
            }
        }

        return $oShopList;
    }

    /**
     * Returns selected subshops
     *
     * @return mixed
     */
    public function getSelectedSubShops()
    {
        if (is_null($this->_aSelectedSubshops)) {
            if (!$this->_aSelectedSubshops = $this->getConfig()->getRequestParameter("allartshops")) {
                $this->_aSelectedSubshops = array();
            }
        }

        return $this->_aSelectedSubshops;
    }

    /**
     * Returns IDs of shops where this element exists.
     *
     * @return array
     * @deprecated underscore prefix violates PSR12, will be renamed to "getItemAssignedShopIds" in next major
     */
    protected function _getItemAssignedShopIds() // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $sObjectClassName = $this->_getObjectClassName();
        /** @var \OxidEsales\Eshop\Core\Model\BaseModel $oItem */
        $oItem = oxNew($sObjectClassName);
        $oItem->load($this->getEditObjectId());
        $aItemShopIds = $oItem->getItemAssignedShopIds();

        return $aItemShopIds;
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
     * @param string $sMallTable Mall table name
     * @deprecated underscore prefix violates PSR12, will be renamed to "setMallTable" in next major
     */
    protected function _setMallTable($sMallTable) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $this->_sMallTable = $sMallTable;
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
     * @param string $sObjectClassName Object class name
     */
    public function setObjectClassName($sObjectClassName)
    {
        $this->_sObjectClassName = $sObjectClassName;
    }

    /**
     * Returns item shop id
     *
     * @return string
     * @deprecated underscore prefix violates PSR12, will be renamed to "getShopId" in next major
     */
    protected function _getShopId() // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $oConfig = $this->getConfig();

        return $oConfig->getShopId();
    }

    /**
     * Searches for class name in class map and if it does not find, returns name of an object.
     *
     * @return string
     */
    private function findCurrentClassName()
    {
        $className = get_class($this);
        $utilsObject = Registry::getUtilsObject();
        $classAliasName = $utilsObject->getClassAliasName($className);

        // if not found try to search in the Unified Namespace
        if (is_null($classAliasName)) {
            $tmp = explode('\\', $className);
            array_shift($tmp);
            array_shift($tmp);

            $unifiedClassName = 'OxidEsales\Eshop\\' . implode('\\', $tmp);
            $classAliasName = $utilsObject->getClassAliasName($unifiedClassName);
        }
        if (!is_null($classAliasName)) {
            $className = $classAliasName;
        }

        return $className;
    }
}
