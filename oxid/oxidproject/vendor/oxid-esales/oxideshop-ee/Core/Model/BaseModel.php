<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Core\Model;

use oxField;
use OxidEsales\Eshop\Application\Model\Shop;
use oxDb;

/**
 * @inheritdoc
 */
class BaseModel extends \OxidEsales\EshopProfessional\Core\Model\BaseModel
{
    /**
     * Set $blForceCoreTableUsage to true to use only core table exclusively.
     * This option is useful when you want to load object data exclusively from core table and not the view (means not depending on shop)
     *
     * @var bool
     */
    protected $_blForceCoreTableUsage = false;

    /**
     * When $_blDisableShopCheck is set to false then table oxshopid field value is checked when loading the object
     * Objects originating from other shops are not loaded.
     * Set $_blDisableShopCheck to true to load objects from any shop.
     *
     * @var string
     */
    protected $_blDisableShopCheck = true;

    /**
     * Shop relations object.
     *
     * @var Element2ShopRelations
     */
    protected $_oElement2ShopRelations = null;

    /**
     * Force usage of master DB
     *
     * @var bool
     */
    protected $_blUseMaster = false;

    /**
     * @inheritdoc
     */
    public function assign($dbRecord)
    {
        if (!is_array($dbRecord)) {
            return null;
        }

        if (!$this->canRead()) {
            return false;
        }

        parent::assign($dbRecord);

        $shopIdFieldName = $this->_getFieldLongName('oxshopid');
        if (isset($this->$shopIdFieldName, $this->$shopIdFieldName->value)) {
            $this->setShopId($this->$shopIdFieldName->value);
        }
    }

    /**
     * @inheritdoc
     */
    public function setShopId($shopId)
    {
        $shopId = (int) $shopId;
        if (!$shopId) {
            $shopId = 1;
        }

        parent::setShopId($shopId);
    }

    /**
     * Sets $this->_blForceCoreTableUsage property.
     * Set it to true if you want to disable db view usage and select object records from core table (from all shops).
     *
     * @param bool $forceCoreTableUsage New value
     */
    public function setForceCoreTableUsage($forceCoreTableUsage)
    {
        $this->_blForceCoreTableUsage = $forceCoreTableUsage;
        // reset view table
        $this->_sViewTable = false;
    }

    /**
     * Gets $this->_blForceCoreTableUsage property.
     *
     * @return bool
     */
    public function getForceCoreTableUsage()
    {
        return $this->_blForceCoreTableUsage;
    }

    /**
     * Set $this->_blDisableShopCheck class variable.
     *
     * @param bool $disableShopCheck New value
     */
    public function setDisableShopCheck($disableShopCheck)
    {
        $this->_blDisableShopCheck = $disableShopCheck;
    }

    /**
     * Gets $this->_blDisableShopCheck property.
     *
     * @return bool
     */
    public function getDisableShopCheck()
    {
        return $this->_blDisableShopCheck;
    }

    /**
     * @inheritdoc
     */
    public function isDerived()
    {
        if ($this->_blIsDerived === null) {
            $currentShopId = $this->getShopId();
            if (isset($currentShopId)) {
                if ($currentShopId != $this->getConfig()->getShopId()) {
                    $this->_blIsDerived = true;
                } else {
                    $this->_blIsDerived = false;
                }
            }
        }

        return parent::isDerived();
    }

    /**
     * @inheritdoc
     */
    public function load($oxid)
    {
        $useCoreTables = $this->getForceCoreTableUsage();
        $this->_forceCoreTableUsageForSharedBasket();

        $originalLoadResult = parent::load($oxid);

        $this->setForceCoreTableUsage($useCoreTables);

        return $originalLoadResult;
    }

    /**
     * Sets forcing of core table usage for creating table view name when shared basket is enabled.
     */
    private function _forceCoreTableUsageForSharedBasket() // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        if ($this->getConfig()->getConfigParam('blMallSharedBasket')) {
            $this->setForceCoreTableUsage(true);
        }
    }

    /**
     * @inheritdoc
     */
    public function buildSelectString($whereCondition = array())
    {
        $selectString = parent::buildSelectString($whereCondition);

        // add active shop
        if ($this->getShopId() && $this->getDisableShopCheck() === false) {
            $fieldLongName = $this->_getFieldLongName('oxshopid');
            if (isset($this->$fieldLongName)) {
                $fieldName = $this->getViewName() . '.oxshopid';
                if (!isset($whereCondition[$fieldName])) {
                    $selectString .= " and $fieldName = '" . $this->getShopId() . "'";
                }
            }
        }

        return $selectString;
    }

    /**
     * @inheritdoc
     */
    public function delete($oxid = null)
    {
        $oxid = $oxid ? : $this->getId();
        if (!$this->canDelete($oxid)) {
            return false;
        }

        return parent::delete($oxid);
    }

    /**
     * @inheritdoc
     * @deprecated underscore prefix violates PSR12, will be renamed to "removeElement2ShopRelations" in next major
     */
    protected function _removeElement2ShopRelations($oxid) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $activeShop = $this->getConfig()->getActiveShop();
        $tableName = $this->getCoreTableName();
        $tableName = strtolower($tableName);
        if (in_array($tableName, $activeShop->getMultiShopTables()) && $tableName !== 'oxobject2category') {
            $element2ShopRelations = $this->_getElement2ShopRelations();
            $element2ShopRelations->setShopIds($activeShop->getShopId());
            $element2ShopRelations->removeFromAllShops($oxid);
        }

        parent::_removeElement2ShopRelations($oxid);
    }

    /**
     * Unassigns entry from current (or particular) shop for multi shop objects
     *
     * @param string|array $shopIds Shop ID or list of shop IDs.
     *
     * @return bool
     */
    public function unassignFromShop($shopIds)
    {
        $oxid = $this->getId();

        if (!$oxid || !$shopIds) {
            return false;
        }

        $this->onChange(ACTION_UPDATE, $oxid);

        if (!is_array($shopIds)) {
            $shopIds = array($shopIds);
        }

        $this->_removeElementFromShop($shopIds);

        return true;
    }

    /**
     * Assigns entry from current (or particular) shop for multi shop objects
     *
     * @param string $shopId Shop ID(default - current active shop)
     *
     * @return bool
     */
    public function assignToShop($shopId = null)
    {
        $oxid = $this->getId();

        if (!$oxid) {
            return false;
        }

        $this->onChange(ACTION_UPDATE, $oxid);

        if (!$shopId) {
            $shopId = $this->getConfig()->getShopId();
        }

        if (!$shopId) {
            return false;
        }

        $this->_addSelectedItemToShop($shopId);

        return true;
    }

    /**
     * Remove selected item from shop.
     *
     * @param array $shopIds List of shop IDs.
     * @deprecated underscore prefix violates PSR12, will be renamed to "removeElementFromShop" in next major
     */
    protected function _removeElementFromShop($shopIds) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $element2ShopRelations = $this->_getElement2ShopRelations();
        $element2ShopRelations->setShopIds($shopIds);
        $element2ShopRelations->removeObjectFromShop($this);
    }

    /**
     * Add selected item to shop
     *
     * @param string $shopID Shop ID
     * @deprecated underscore prefix violates PSR12, will be renamed to "addSelectedItemToShop" in next major
     */
    protected function _addSelectedItemToShop($shopID) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $element2ShopRelations = $this->_getElement2ShopRelations();
        $element2ShopRelations->setShopIds(array($shopID));
        $element2ShopRelations->addObjectToShop($this);
    }

    /**
     * Returns IDs of shops where this element exists
     *
     * @return array
     */
    public function getItemAssignedShopIds()
    {
        return $this->_getElement2ShopRelations()->getItemAssignedShopIds($this->getId());
    }

    /**
     * Gets shop relations object.
     *
     * @return Element2ShopRelations
     * @deprecated underscore prefix violates PSR12, will be renamed to "getElement2ShopRelations" in next major
     */
    protected function _getElement2ShopRelations() // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        if (is_null($this->_oElement2ShopRelations)) {
            $this->_oElement2ShopRelations = oxNew(\OxidEsales\Eshop\Core\Element2ShopRelations::class, $this->getCoreTableName());
        }

        return $this->_oElement2ShopRelations;
    }

    /**
     * @inheritdoc
     */
    public function onChange($actionId = null, $oxid = null)
    {
        // cache control ..
        $this->_resetCache($oxid);

        parent::onChange($actionId, $oxid);
    }

    /**
     * Checks if object update is allowed
     *
     * @return bool
     */
    public function canUpdate()
    {
        $canUpdate = true;
        if ($this->isAdmin() && ($rights = $this->getRights())) {
            $canUpdate = $rights->hasRights(RIGHT_EDIT, $this);
        }

        return $canUpdate;
    }

    /**
     * Checks if object field can be read/viewed by user
     *
     * @param string $field name of field to check
     *
     * @return bool
     */
    public function canUpdateField($field)
    {
        $canUpdate = true;
        if ($this->isAdmin() && ($rights = $this->getRights())) {
            $canUpdate = $rights->hasRights(RIGHT_EDIT, $this, $field);
        }

        return $canUpdate;
    }

    /**
     * Checks if object can be read
     *
     * @return bool
     */
    public function canRead()
    {
        $canUpdate = true;
        if ($this->isAdmin() && ($rights = $this->getRights())) {
            $canUpdate = $rights->hasRights(RIGHT_VIEW, $this);
        }

        return $canUpdate;
    }

    /**
     * Checks if object field can be read/viewed by user
     *
     * @param string $fieldName name of field to check
     *
     * @return bool
     */
    public function canReadField($fieldName)
    {
        $canRead = true;
        if ($this->isAdmin() && ($rights = $this->getRights())) {
            $canRead = $rights->hasRights(RIGHT_VIEW, $this, $fieldName);
        }

        return $canRead;
    }

    /**
     * Checks if object insert is allowed
     *
     * @return bool
     */
    public function canInsert()
    {
        $canInsert = true;
        if ($this->isAdmin() && ($rights = $this->getRights())) {
            $canInsert = $rights->hasRights(RIGHT_INSERT, $this);
        }

        return $canInsert;
    }

    /**
     * Checks if deletion of object is allowed
     *
     * @param string $oxid deletable object id (optional if object is loaded)
     *
     * @return bool
     */
    public function canDelete($oxid = null)
    {
        //default is true as non restrictive mode is preferred by default
        $canDelete = true;
        if ($this->isAdmin() && ($rights = $this->getRights())) {
            $canDelete = $rights->hasRights(RIGHT_DELETE, $this);
        }

        return $canDelete;
    }

    /**
     * Checks if current right is allowed. Returns bool value.
     *
     * @param string $objectId Object ID
     * @param int    $action   Action ID
     *
     * @return bool
     */
    public function canDo($objectId = null, $action = 1)
    {
        $rights = $this->getRights();
        if ($this->isAdmin() || !$rights) {
            return true;
        }

        if (!$objectId) {
            $objectId = $this->getId();
        }

        if (!$objectId) {
            return false;
        }

        // R&R: user access
        return $rights->hasObjectRights("'$objectId'", $action);
    }

    /**
     * In admin mode resets full content cache
     *
     * @param string $oxid review id
     * @deprecated underscore prefix violates PSR12, will be renamed to "resetCache" in next major
     */
    protected function _resetCache($oxid = null) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        // resetting all in admin
        if ($this->isAdmin()) {
            if (!$this->getConfig()->getConfigParam('blClearCacheOnLogout')) {
                $cache = oxNew(\OxidEsales\Eshop\Core\Cache\DynamicContent\ContentCache::class);
                $cache->reset();
            }
        }
    }

    /**
     * @inheritdoc
     * @deprecated underscore prefix violates PSR12, will be renamed to "getObjectViewName" in next major
     */
    protected function _getObjectViewName($table, $shopId = null) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        if ($this->_blForceCoreTableUsage) {
            $shopId = -1;
        }

        return parent::_getObjectViewName($table, $shopId);
    }

    /**
     * Returns SQL select string with checks if items is accessible by R&R config
     *
     * @param bool $forceCoreTable forces core table usage (optional)
     *
     * @return string
     *
     * @deprecated underscore prefix violates PSR12, will be renamed to "getSqlRightsSnippet" in next major
     */
    protected function _getSqlRightsSnippet($forceCoreTable = null) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $tableName = $this->getViewName($forceCoreTable);
        $query = '';

        // R&R: user access
        if (!$this->isAdmin() && ($oRights = $this->getRights())) {
            $query .= " and ( ( ";
            $query .= "( select oxobjectrights.oxobjectid from oxobjectrights where oxobjectrights.oxobjectid = $tableName.oxid and oxobjectrights.oxaction = 1 limit 1 ) is null ";

            $groupIndex = $oRights->getUserGroupIndex();
            if (is_array($groupIndex) && count($groupIndex)) {
                $groupSelect = "";
                $iCnt = 0;
                foreach ($groupIndex as $iOffset => $iBitMap) {
                    if ($iCnt) {
                        $groupSelect .= " | ";
                    }
                    $groupSelect .= " ( oxobjectrights.oxgroupidx & $iBitMap and oxobjectrights.oxoffset = $iOffset ) ";
                    $iCnt++;
                }

                $query .= ") or (";
                $query .= "( select oxobjectrights.oxobjectid from oxobjectrights where oxobjectrights.oxobjectid = $tableName.oxid and oxobjectrights.oxaction = 1 and $groupSelect limit 1 ) is not null ";
            }

            $query .= " ) ) ";
        }

        return $query;
    }

    /**
     * @inheritdoc
     * @deprecated underscore prefix violates PSR12, will be renamed to "setFieldData" in next major
     */
    protected function _setFieldData($fieldName, $value, $dataType = \OxidEsales\Eshop\Core\Field::T_TEXT) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        if (!$this->canReadField($fieldName)) {
            return false;
        }

        return parent::_setFieldData($fieldName, $value, $dataType);
    }

    /**
     * @inheritdoc
     * @deprecated underscore prefix violates PSR12, will be renamed to "update" in next major
     */
    protected function _update() // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        if (!$this->canUpdate()) {
            return false;
        }

        return parent::_update();
    }

    /**
     * @inheritdoc
     * @deprecated underscore prefix violates PSR12, will be renamed to "insert" in next major
     */
    protected function _insert() // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        if (!$this->canInsert()) {
            return false;
        }

        $parentResult = parent::_insert();

        $this->_addElement2ShopRelations();

        return $parentResult;
    }

    /**
     * TODO: make this class private
     * Adds object to related map if it's a multishop inheritable table
     * @deprecated underscore prefix violates PSR12, will be renamed to "addElement2ShopRelations" in next major
     */
    protected function _addElement2ShopRelations() // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $shop = $this->getConfig()->getActiveShop();

        // Set record 2 subshop mapping relations for this shop and inherited subshops
        $tableName = $this->getCoreTableName();
        if (in_array($tableName, $shop->getMultiShopTables())) {
            $shopIds = $this->_getInheritanceGroup();

            $element2ShopRelations = $this->_getElement2ShopRelations();
            $element2ShopRelations->setShopIds($shopIds);
            $element2ShopRelations->addObjectToShop($this);
        }
    }

    /**
     * Returns a list of subshop ids, including the parent, where certain type of inheritable elements (oxarticles,
     * oxattributes, ...) are inherited in bulk via config option from the current shop.
     * The items are considered inherited in case it is inherited directly to the subshop, recursive check is performed
     * for subsequent subshops.
     *
     * @return array
     * @deprecated underscore prefix violates PSR12, will be renamed to "getInheritanceGroup" in next major
     */
    protected function _getInheritanceGroup() // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        /** @var \OxidEsales\Eshop\Application\Controller\Admin\ShopController $shop */
        $shop = $this->getConfig()->getActiveShop();
        $coreTable = $this->getCoreTableName();

        $shopIds = $shop->getInheritanceGroup($coreTable);

        return $shopIds;
    }

    /**
     * @inheritdoc
     * @deprecated underscore prefix violates PSR12, will be renamed to "addSkippedSaveFieldsForMapping" in next major
     */
    protected function _addSkippedSaveFieldsForMapping() // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $this->_aSkipSaveFields[] = 'oxmapid';

        parent::_addSkippedSaveFieldsForMapping();
    }

    /**
     * @inheritdoc
     * @deprecated underscore prefix violates PSR12, will be renamed to "disableLazyLoadingForCaching" in next major
     */
    protected function _disableLazyLoadingForCaching() // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $cache = \OxidEsales\Eshop\Core\Registry::get(\OxidEsales\Eshop\Core\Cache\Generic\Cache::class);
        if ($cache->isActive()) {
            $this->_blUseLazyLoading = false;
        }

        parent::_disableLazyLoadingForCaching();
    }

    /**
     * Set usage of master DB
     *
     * @param bool $useMaster - true if master is ON
     */
    public function setUseMaster($useMaster = true)
    {
        $this->_blUseMaster = $useMaster;
    }

    /**
     * return true if master db usage is on
     *
     * @return bool
     */
    public function getUseMaster()
    {
        return $this->_blUseMaster;
    }

    /**
     * @inheritdoc
     * @deprecated method will be removed from the public API in v7.0 use QueryBuilderFactoryInterface
     * @see \OxidEsales\EshopCommunity\Internal\Framework\Database\QueryBuilderFactoryInterface
     */
    protected function getRecordByQuery($query)
    {
        $database = \OxidEsales\Eshop\Core\DatabaseProvider::getDb(\OxidEsales\Eshop\Core\DatabaseProvider::FETCH_MODE_ASSOC);
        if ($this->getUseMaster()) {
            $database = \OxidEsales\Eshop\Core\DatabaseProvider::getMaster(\OxidEsales\Eshop\Core\DatabaseProvider::FETCH_MODE_ASSOC);
        }

        $record = $database->select($query, false);

        return $record;
    }

    /**
     * @inheritdoc
     */
    protected function checkFieldCanBeUpdated($fieldName)
    {
        $result = parent::checkFieldCanBeUpdated($fieldName);
        if ($result && !$this->canUpdateField($fieldName)) {
            $result = false;
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    protected function getGetterViewName()
    {
        $useCoreTable = $this->getForceCoreTableUsage();
        $this->setForceCoreTableUsage(true);

        $viewName = parent::getGetterViewName();

        $this->setForceCoreTableUsage($useCoreTable);

        return $viewName;
    }

    /**
     * @inheritdoc
     */
    protected function checkIfCoreTableNeeded($forceCoreTableUsage)
    {
        if ($forceCoreTableUsage === null) {
            $result = $this->_blForceCoreTableUsage;
        } else {
            $result = parent::checkIfCoreTableNeeded($forceCoreTableUsage);
        }

        return $result;
    }
}
