<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Model;

use OxidEsales\Eshop\Core\Registry;
use oxDb;
use OxidEsales\Eshop\Core\Element2ShopRelations;

/**
 * @inheritdoc
 */
class Shop extends \OxidEsales\EshopProfessional\Application\Model\Shop
{
    /** @var array Special tables which also must be cleaned-up when deleting shop. */
    protected $_aExtTables = array("oxartextends" => "oxarticles");

    /** @var int Maximum shop id value (max sub shops count). */
    protected $_iMaxShopId = 1500;

    /** @var array variable. */
    protected $_aMallInherit = array();

    /**
     * @inheritdoc
     */
    protected function formDatabaseTablesArray()
    {
        $tables = parent::formDatabaseTablesArray();

        $multishopTables = $this->getMultiShopTables();
        $tables = array_unique(array_merge($multishopTables, $tables));

        return $tables;
    }

    /**
     * @inheritdoc
     */
    public function getMultiShopTables()
    {
        if (is_null($this->_aMultiShopTables)) {
            $this->_aMultiShopTables = $this->getConfig()->getConfigParam('aMultiShopTables');
        }

        return parent::getMultiShopTables();
    }

    /**
     * Collects and cleans language set tables.
     *
     * @param string $shopId    Shop id
     * @param string $mainTable Main table name
     * @deprecated underscore prefix violates PSR12, will be renamed to "cleanLangSetTables" in next major
     */
    protected function _cleanLangSetTables($shopId, $mainTable) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $database = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();

        $languageSetTables = $this->_getLanguageSetTables($mainTable);
        foreach ($languageSetTables as $setTable) {
            // special case for artextends table
            if (stripos($mainTable, "oxartextends") !== false) {
                $mainTable = "oxarticles";
            }

            $database->execute("delete {$setTable}.* from {$setTable}, {$mainTable} where {$mainTable}.oxid = {$setTable}.oxid and {$mainTable}.oxshopid = :oxshopid", [
                ':oxshopid' => $shopId
            ]);
        }
    }

    /**
     * Cleans special language set tables like "oxartextends"
     *
     * @param string $shopId shop id
     * @deprecated underscore prefix violates PSR12, will be renamed to "cleanExtLangSetTables" in next major
     */
    protected function _cleanExtLangSetTables($shopId) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $database = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();

        foreach ($this->_aExtTables as $extendingTables => $shopIdTable) {
            $this->_cleanLangSetTables($shopId, $extendingTables);
            $database->execute("delete {$extendingTables}.* from {$extendingTables}, {$shopIdTable} where {$shopIdTable}.oxid={$extendingTables}.oxid and {$shopIdTable}.oxshopid = :oxshopid", [
                ':oxshopid' => $shopId
            ]);
        }
    }

    /**
     * Sets shop id. Then executes parent method
     * parent::_insert() and returns insertion status.
     *
     * @return bool
     * @deprecated underscore prefix violates PSR12, will be renamed to "insert" in next major
     */
    protected function _insert() // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $shopId = $this->getId();
        if (!isset($shopId) || ((int) $shopId < 1)) {
            $shopId = $this->getNewShopId();
            if ($shopId !== false) {
                $this->setId($shopId);
            } else {
                return false;
            }
        }

        $this->executeDependencyEvent();

        return parent::_insert();
    }

    /**
     * Returns next available shop id
     *
     * @return string
     */
    public function getNewShopId()
    {
        $newShopId = false;
        $existingShopIds = $this->getConfig()->getShopIds();

        //new shop id
        $maxShopId = $this->getMaxShopId();

        for ($i = 1; $i <= $maxShopId; $i++) {
            if (!in_array($i, $existingShopIds)) {
                $newShopId = $i;
                break;
            }
        }

        return $newShopId;
    }

    /**
     * Returns maximum shop id value
     *
     * @return integer
     */
    public function getMaxShopId()
    {
        return $this->_iMaxShopId;
    }

    /**
     * Set maximum shop id value
     *
     * @param int $maxShopId - max value
     */
    public function setMaxShopId($maxShopId)
    {
        $this->_iMaxShopId = ($maxShopId > 2000) ? 2000 : $maxShopId;
    }

    /**
     * Deletes most of the stuff, this will leave some unwanted entries left
     * but we do think this is tolerable.
     *
     * @param array $sOXID object ID (default null)
     *
     * @return bool
     */
    public function delete($sOXID = null)
    {
        if (!$sOXID) {
            $sOXID = $this->getId();
        }
        if (!$sOXID || !$this->canDelete($sOXID)) {
            return false;
        }

        $shopAwareTables = array(
            "oxconfig", "oxcategories", "oxprice2article",
            "oxdelivery", "oxvoucherseries",
            // @deprecated 6.5.3 "News" feature will be removed completely
            "oxnews",
            // END deprecated
            "oxcontents", "oxactions",
            "oxobject2group", "oxpricealarm",
            "oxactions2article", "oxseo", "oxseohistory",
            "oxadminlog", "oxroles", "oxfield2shop", "oxcache"
        );

        $shopAwareTables = array_merge($shopAwareTables, $this->getMultiShopTables());

        $database = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();
        $params = [
            ':oxshopid' => $sOXID
        ];

        //V #M384: after deleting subshop, remove all related data too
        // deleting related data from oxroles
        $database->execute(
            'delete from oxobject2role where oxroleid in (select oxid from oxroles where oxshopid = :oxshopid) ',
            $params
        );
        $database->execute(
            'delete from oxfield2role where oxroleid in (select oxid from oxroles where oxshopid = :oxshopid) ',
            $params
        );
        $database->execute(
            'delete from oxobjectrights where oxobjectid in (select oxid from oxroles where oxshopid = :oxshopid) ',
            $params
        );

        // deleting related data from oxcategories
        $database->execute(
            'delete from oxcategory2attribute where oxobjectid in (select oxid from oxcategories where oxshopid = :oxshopid) ',
            $params
        );
        $database->execute(
            'delete from oxobject2delivery where oxobjectid in (select oxid from oxcategories where oxshopid = :oxshopid) ',
            $params
        );
        $database->execute(
            'delete from oxobject2discount where oxobjectid in (select oxid from oxcategories where oxshopid = :oxshopid) ',
            $params
        );
        // deleting related data from oxvoucherseries
        $database->execute(
            'delete from oxvouchers where oxvoucherserieid in (select oxid from oxvoucherseries where oxshopid = :oxshopid) ',
            $params
        );

        // cleaning up oxartectends etc
        $this->_cleanExtLangSetTables($sOXID);

        // delete most of the stuff, this will leave some unwanted entries left
        // but we do think this is tolerable
        foreach ($shopAwareTables as $shopAwareTable) {
            // cleaning language set tables
            $this->_cleanLangSetTables($sOXID, $shopAwareTable);
            $database->execute('delete from ' . $shopAwareTable . ' where oxshopid = :oxshopid ', $params);
        }

        foreach ($this->getMultiShopTables() as $multishopTable) {
            $query = "drop view if exists oxv_{$multishopTable}_{$sOXID}";
            $database->execute($query);
        }

        $this->executeDependencyEvent($sOXID);

        return parent::delete($sOXID);
    }

    /**
     * Returns a list of subshops where certain type of inheritable elements (oxarticles, oxattributes, ...)
     * are inherited in bulk via config option from the current shop.
     * The items are considered inherited in case it is inherited directly to the subshop, recursive check is performed
     * for subsequent subshops.
     *
     * @return array
     */
    public function getSubShopList()
    {
        $shopId = $this->getId();

        if ($this->isSuperShop()) {
            $shopList = oxNew(\OxidEsales\Eshop\Application\Model\ShopList::class);
            $shopList->loadSuperShopList($shopId);
        } else {
            $shopList = $this->_getSubShopTree($shopId);
        }

        return $shopList;
    }

    /**
     * Returns if current shop has supershop type
     *
     * @return integer
     */
    public function isSuperShop()
    {
        return $this->oxshops__oxissupershop->value;
    }

    /**
     * Returns if current shop has multishop type
     *
     * @return integer
     */
    public function isMultiShop()
    {
        return $this->oxshops__oxismultishop->value;
    }

    /**
     * Returns a list of subshops where certain type of inheritable elements (oxarticles, oxattributes, ...)
     * are inherited in bulk via config option from the current shop.
     * The items are considered inherited in case it is inherited directly to the subshop, recursive check is performed
     * for subsequent subshops.
     *
     * @param int $sShopId Shop id
     *
     * @return array
     * @deprecated underscore prefix violates PSR12, will be renamed to "getSubShopTree" in next major
     */
    protected function _getSubShopTree($sShopId = null) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $shopList = oxNew(\OxidEsales\Eshop\Application\Model\ShopList::class);
        $shopList->loadSubShopList($sShopId);
        foreach ($shopList as $shop) {
            $subShops = $this->_getSubShopTree($shop->getId());
            foreach ($subShops as $subShopKey => $subShop) {
                if (!isset($shopList[$subShopKey])) {
                    $shopList[$subShopKey] = $subShop;
                }
            }
        }

        return $shopList;
    }

    /**
     * Returns a list of subshop ids, including the parent, where certain type of inheritable elements (oxarticles,
     * oxattributes, ...) are inherited in bulk via config option from the current shop.
     * The items are considered inherited in case it is inherited directly to the subshop, recursive check is performed
     * for subsequent subshops.
     *
     * @param string $inheritanceType Inheritable table type
     * @param string $shopId          Shop ID
     *
     * @return array
     */
    public function getInheritanceGroup($inheritanceType, $shopId = null)
    {
        if (is_null($shopId)) {
            $shopId = $this->getId();
        }

        $subShopIds = $this->getMultiShopListForInheritedElement($inheritanceType);
        $subShopIds[] = $shopId;

        $allSubShopIds = $subShopIds;
        foreach ($subShopIds as $sSubShopId) {
            $allSubShopIds = $this->_getInheritedSubshopIds($inheritanceType, $sSubShopId, $allSubShopIds);
        }
        $allSubShopIds = array_unique($allSubShopIds);

        return $allSubShopIds;
    }

    /**
     * Returns a list of subshop ids where certain type of inheritable elements (oxarticles, oxattributes, ...)
     * are inherited in bulk via config option from the current shop combining with provided list of shop ids.
     * The items are considered inherited in case it is inherited directly to the subshop, recursive check is performed
     * for subsequent subshops.
     *
     * @param string $inheritanceType Inheritable table type
     * @param string $shopId          Shop ID
     * @param array  $subShopIds      The list of subshop ids to combine with the result
     *
     * @return array
     */
    private function _getInheritedSubshopIds($inheritanceType, $shopId, $subShopIds) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $subShopIdsByParentId = $this->_getSubshopIdsByParentId($shopId);

        foreach ($subShopIdsByParentId as $subShopId) {
            if ($this->_isTableInherited($inheritanceType, $subShopId)) {
                array_push($subShopIds, $subShopId);
                $subShopIds = $this->_getInheritedSubshopIds($inheritanceType, $subShopId, $subShopIds);
            }
        }

        return $subShopIds;
    }

    /**
     * Gets list of subshop IDs for the given parent shop ID.
     *
     * @param int $shopId Shop ID.
     *
     * @return array
     */
    private function _getSubshopIdsByParentId($shopId) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $database = \OxidEsales\Eshop\Core\DatabaseProvider::getDb(\OxidEsales\Eshop\Core\DatabaseProvider::FETCH_MODE_ASSOC);

        $subShopIds = $database->getCol(
            "SELECT `oxid` FROM `{$this->getCoreTableName()}` WHERE `oxparentid` = :oxparentid",
            [
                ':oxparentid' => $shopId
            ]
        );

        return $subShopIds;
    }

    /**
     * Checks if given table is inherited from parent shop.
     *
     * @param string $mallTableName Table name
     * @param int    $shopId        Shop ID
     *
     * @return bool
     * @deprecated underscore prefix violates PSR12, will be renamed to "isTableInherited" in next major
     */
    protected function _isTableInherited($mallTableName, $shopId = null) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        if (is_null($shopId)) {
            $shopId = $this->getId();
        }

        if ($mallTableName == 'oxcategories' && $this->getMultiShopInheritCategories()) {
            return true;
        }
        $shopRelations = $this->_getShopRelations($shopId);
        $shopRelations->setIsMultiShopType($this->isMultiShop());

        return $shopRelations->isShopElementInherited($mallTableName);
    }

    /**
     * Define whether multishop categories should be inherited.
     *
     * @param bool $shouldMultiShopInheritCategories
     */
    public function setMultiShopInheritCategories($shouldMultiShopInheritCategories)
    {
        $this->_blMultiShopInheritCategories = $shouldMultiShopInheritCategories;
    }

    /**
     * Returns whether multishop category can be inherited or not.
     *
     * @return bool
     */
    public function getMultiShopInheritCategories()
    {
        return $this->_blMultiShopInheritCategories;
    }

    /**
     * $_aMallInherit setter
     * TODO: update variable and comment, as it is not clear what this is for.
     *
     * @param array $mallInherit
     */
    public function setMallInherit($mallInherit)
    {
        $this->_aMallInherit = $mallInherit;
    }

    /**
     * $_aMallInherit getter
     *
     * @return array
     */
    public function getMallInherit()
    {
        return $this->_aMallInherit;
    }

    /**
     * @inheritdoc
     */
    public function generateViews($multishopInheritCategories = false, $mallInherit = null)
    {
        $this->setMultiShopInheritCategories($multishopInheritCategories);
        $this->setMallInherit($mallInherit);

        return parent::generateViews($multishopInheritCategories, $mallInherit);
    }

    /**
     * Generates and returns view WHERE query part.
     *
     * @param string $table Table name.
     *
     * @return string Generated where query.
     * @deprecated underscore prefix violates PSR12, will be renamed to "getViewWhere" in next major
     */
    protected function _getViewWhere($table = null) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $query = ' WHERE 1 ';

        $tableName = 't2s';
        if ('oxobject2category' == $table) {
            $tableName = $table;
        }

        if ($table == 'oxcategories' && $this->getMultiShopInheritCategories()) {
            return $query;
        }

        $shopId = $this->getId();
        if ($shopId) {
            $query = " WHERE {$tableName}.oxshopid = " . $shopId . " ";
        }

        return $query;
    }

    /**
     * Updates object information in DB.
     * @deprecated underscore prefix violates PSR12, will be renamed to "update" in next major
     */
    protected function _update() // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $this->executeDependencyEvent();

        parent::_update();
    }

    /**
     * Execute cache dependencies.
     *
     * @deprecated since 2019-02-22 (6.3.0 component version). All calls of the method will be removed soon.
     *
     * @param string $shopId
     */
    public function executeDependencyEvent($shopId = null)
    {
    }

    /**
     * Returns all possible shop urls.
     *
     * @return array
     */
    public function getUrls()
    {
        $config = $this->getConfig();
        $shopUrls = array();

        $languageUrls = $config->getConfigParam('aLanguageURLs');
        if (is_array($languageUrls) && !empty($languageUrls)) {
            foreach ($languageUrls as $url) {
                if ($url) {
                    $shopUrls[] = \OxidEsales\Eshop\Core\Registry::getUtils()->checkUrlEndingSlash($url);
                }
            }
        }

        $mallShopUrl = $config->getConfigParam('sMallShopURL');
        if ($mallShopUrl) {
            $shopUrls[] = \OxidEsales\Eshop\Core\Registry::getUtils()->checkUrlEndingSlash($mallShopUrl);
        }

        $mallSslShopUrl = $config->getConfigParam('sMallSSLShopURL');
        if ($mallSslShopUrl) {
            $shopUrls[] = \OxidEsales\Eshop\Core\Registry::getUtils()->checkUrlEndingSlash($mallSslShopUrl);
        }

        if (empty($shopUrls)) {
            $shopUrls[] = $config->getConfigParam('sShopURL');
        }

        return $shopUrls;
    }

    /**
     * Updates inheritance information.
     *
     * @param array $elementWhitelist Optional parameter providing the whitelist of tables the changes should be applied to.
     */
    public function updateInheritance($elementWhitelist = null)
    {
        $isMultiShop = $this->isMultiShop();

        $multishopTables = $this->getMultiShopTables();

        foreach ($multishopTables as $table) {
            if (is_array($elementWhitelist) && !in_array($table, $elementWhitelist)) {
                continue;
            }

            $shopIds = $this->getInheritanceGroup($table);

            if ($isMultiShop) {
                $this->_updateMultiShopInheritance($table, $shopIds);
            } else {
                $this->_updateSubShopInheritance($table, $shopIds);
            }
        }
    }

    /**
     * Updates inheritance for multi shops.
     * Copy all elements to this shop.
     *
     * @param string $table   Inherited element table name.
     * @param array  $shopIds Array of inherited shop ids.
     * @deprecated underscore prefix violates PSR12, will be renamed to "updateMultiShopInheritance" in next major
     */
    protected function _updateMultiShopInheritance($table, $shopIds) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $element2ShopRelations = $this->_prepareElement2ShopRelations($table, $shopIds);

        if ($this->_isTableInherited($table)) {
            $element2ShopRelations->inheritAllElements();
        } else {
            $element2ShopRelations->removeAllElements();
        }
    }

    /**
     * Updates inheritance for sub-shops.
     * Copy all parent shop elements to this shop.
     *
     * @param string $table   inherited element table name
     * @param array  $shopIds array of inherited shop ids
     * @deprecated underscore prefix violates PSR12, will be renamed to "updateSubShopInheritance" in next major
     */
    protected function _updateSubShopInheritance($table, $shopIds) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $element2ShopRelations = $this->_prepareElement2ShopRelations($table, $shopIds);

        if ($this->_isTableInherited($table)) {
            $element2ShopRelations->inheritFromShop($this->oxshops__oxparentid->value);
        } else {
            $element2ShopRelations->removeInheritedFromShop($this->oxshops__oxparentid->value);
        }
    }

    /**
     * Prepares and returns oxElement2ShopRelations object.
     * Sets array of inherited shop ids and inherited element table name to an object.
     *
     * @param string $table   inherited element table name
     * @param array  $shopIds array of inherited shop ids
     *
     * @return Element2ShopRelations
     */
    private function _prepareElement2ShopRelations($table, $shopIds) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $element2ShopRelations = $this->_getElement2ShopRelations();
        $element2ShopRelations->setShopIds($shopIds);
        $element2ShopRelations->setItemType($table);

        return $element2ShopRelations;
    }

    /**
     * Gets relations between shops object.
     *
     * @param string $shopId Shop id
     *
     * @return ShopRelations
     * @deprecated underscore prefix violates PSR12, will be renamed to "getShopRelations" in next major
     */
    protected function _getShopRelations($shopId) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $shopRelations = oxNew(\OxidEsales\Eshop\Application\Model\ShopRelations::class, $shopId);

        return $shopRelations;
    }

    /**
     * Gets list of multi shop ids.
     *
     * @param string $inheritanceType Inherited element name
     *
     * @return array
     */
    public function getMultiShopListForInheritedElement($inheritanceType)
    {
        $multiShopIds = $this->getMultiShopList();
        $shopIds = array();
        if (!empty($multiShopIds)) {
            foreach ($multiShopIds as $shopId) {
                $shopRelations = $this->_getShopRelations($shopId);
                $shopRelations->setIsMultiShopType(true);
                if ($shopRelations->isShopElementInherited($inheritanceType)) {
                    $shopIds[] = $shopId;
                }
            }
        }

        return $shopIds;
    }

    /**
     * Gets list of multi shop ids.
     *
     * @return array
     */
    public function getMultiShopList()
    {
        $database = \OxidEsales\Eshop\Core\DatabaseProvider::getDb(\OxidEsales\Eshop\Core\DatabaseProvider::FETCH_MODE_ASSOC);

        $multiShopIds = $database->getCol(
            "SELECT `oxid` FROM `{$this->getCoreTableName()}` WHERE `oxismultishop` = 1"
        );

        return $multiShopIds;
    }

    /**
     * Removes all relations of shop, that will be removed.
     *
     * @param string $table   inherited element table name
     * @param array  $shopIds array of inherited shop ids
     * @deprecated underscore prefix violates PSR12, will be renamed to "removeShopInheritance" in next major
     */
    protected function _removeShopInheritance($table, $shopIds) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $element2ShopRelations = $this->_prepareElement2ShopRelations($table, $shopIds);

        if ($this->_isTableInherited($table)) {
            $element2ShopRelations->inheritAllElements();
        } else {
            $element2ShopRelations->removeAllElements();
        }
    }

    /**
     * Removes relevant mapping data from all inheritable tables
     * when shop will be deleted.
     *
     * @param string $shopId
     * @deprecated underscore prefix violates PSR12, will be renamed to "removeElement2ShopRelations" in next major
     */
    protected function _removeElement2ShopRelations($shopId) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $shopIds = array($shopId);
        $inheritableTables = $this->getMultiShopTables();
        foreach ($inheritableTables as $tableName) {
            if (strtolower($tableName) !== 'oxobject2category') {
                $element2ShopRelations = $this->_prepareElement2ShopRelations($tableName, $shopIds);
                $element2ShopRelations->removeAllElements();
            }
        }
    }

    /**
     * @inheritdoc
     */
    protected function addViewLanguageQuery($queryStart, $table, $languageId, $languageAbbr)
    {
        parent::addViewLanguageQuery($queryStart, $table, $languageId, $languageAbbr);

        if (in_array($table, $this->getMultiShopTables()) && $shopId = $this->getId()) {
            $fields = is_null($languageAbbr) ? $this->_getViewSelectMultilang($table) : $this->_getViewSelect($table, $languageId);
            $join = is_null($languageAbbr) ? $this->_getViewJoinAll($table) : $this->_getViewJoinLang($table, $languageId);

            $mappedTable = 't2s';
            $joinSnippet = " INNER JOIN {$table}2shop as {$mappedTable} ON {$mappedTable}.oxmapobjectid={$table}.oxmapid ";
            if ('oxobject2category' == $table) {
                $joinSnippet = "";
            }
            $join = $joinSnippet . $join;

            $languagePart = is_null($languageAbbr) ? '' : "_{$languageAbbr}";
            $viewTable = "oxv_{$table}_{$shopId}{$languagePart}";
            $sWhere = $this->_getViewWhere($table);

            $query = "{$queryStart} `{$viewTable}` AS SELECT {$fields} FROM {$table}{$join}{$sWhere}";
            $this->addQuery($query);
        }
    }

    /**
     * Checks whether current shop is valid.
     *
     * @return bool
     */
    protected function isShopValid()
    {
        return 'oxstart' == \OxidEsales\Eshop\Core\Registry::getConfig()->getRequestControllerId() || $this->isAdmin() || $this->getConfig()->getSerial()->validateShop();
    }
}
