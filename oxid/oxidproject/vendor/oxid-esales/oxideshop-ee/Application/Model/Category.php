<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Model;

use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Core\Cache\Generic\Cache;
use OxidEsales\Eshop\Core\Cache\Generic\CacheItem;
use OxidEsales\Eshop\Core\DatabaseProvider;
use OxidEsales\Eshop\Core\Category2ShopRelations;
use OxidEsales\EshopCommunity\Core\Database\Adapter\ResultSetInterface;
use OxidEsales\EshopCommunity\Core\Field;
use OxidEsales\Eshop\Application\Model\CategoryList;

/**
 * @inheritdoc
 */
class Category extends \OxidEsales\EshopProfessional\Application\Model\Category
{
    /**
     * @inheritdoc
     */
    public function load($oxid)
    {
        if ($this->_getCacheBackend()->canLoadDataFromCacheBackend()) {
            $categoryData = $this->_loadFromCache($oxid);
        } else {
            return parent::load($oxid);
        }

        if ($categoryData) {
            $this->assign($categoryData);
            return true;
        }

        return false;
    }

    /**
     * returns Cache from Registry
     *
     * @return Cache
     * @deprecated underscore prefix violates PSR12, will be renamed to "getCacheBackend" in next major
     */
    protected function _getCacheBackend() // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        return Registry::get(Cache::class);
    }

    /**
     * Load data from cache
     *
     * @param string $oxid id
     *
     * @return array
     * @deprecated underscore prefix violates PSR12, will be renamed to "loadFromCache" in next major
     */
    protected function _loadFromCache($oxid) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $cacheBackend = $this->_getCacheBackend();
        $cacheKey = $this->getCacheKey($oxid);
        $cacheItem = $cacheBackend->get($cacheKey);

        if ($cacheItem) {
            $data = $cacheItem->getData();
        } else {
            $data = $this->_loadFromDb($oxid);

            if (!empty($data)) {
                $cacheItem = oxNew(CacheItem::class);
                $cacheItem->setData($data);
                $cacheBackend->set($cacheKey, $cacheItem);
            }
        }

        return $data;
    }

    /**
     * Generate cache key
     *
     * @param string $oxid id
     *
     * @return string
     */
    public function getCacheKey($oxid = null)
    {
        if (!$oxid) {
            $oxid = $this->getId();
        }

        return 'oxCategory_' . $oxid . '_' . $this->getConfig()->getShopId() . '_' .
            Registry::getLang()->getLanguageAbbr($this->getLanguage());
    }


    /**
     * Generate cache keys for dependent cached data
     *
     * @param array $categoryIds category ids array
     * @param array $languages   lang ids array
     * @param array $shops       shop ids array
     *
     * @return array
     */
    public function getCacheKeys($categoryIds, $languages = null, $shops = null)
    {
        $keys = array();
        $languages = $languages ? $languages : Registry::getLang()->getLanguageIds();
        $shops = $shops ? $shops : $this->getConfig()->getShopIds();

        foreach ($categoryIds as $categoryId) {
            foreach ($shops as $shopId) {
                foreach ($languages as $languageId) {
                    $keys[] = 'oxCategory_' . $categoryId . '_' . $shopId . '_' . $languageId;
                }
            }
        }

        return $keys;
    }


    /**
     * Execute cache dependencies
     *
     * @param array $categoryIds    array of category ids
     * @param bool  $flushArticles do articles need to be flushed
     */
    public function executeDependencyEvent($categoryIds = null, $flushArticles = true)
    {
        if (is_null($categoryIds)) {
            $categoryIds[] = $this->getId();
        }

        $cacheBackend = $this->_getCacheBackend();
        if ($cacheBackend->isActive()) {
            //invalidate cache
            $cacheBackend->invalidate($this->getCacheKeys($categoryIds));
        }

        $this->_updateDependencies();
    }

    /**
     * Execute cache dependencies
     * @deprecated will be renamed to "updateDependencies" in next major
     */
    public function _updateDependencies() // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        if ($this->_getCacheBackend()->isActive()) {
            //category tree cache dependency
            $categoryList = oxNew(CategoryList::class);
            $categoryList->executeDependencyEvent();
        }
    }

    /**
     * @inheritdoc
     */
    public function delete($oxid = null)
    {
        if (!$this->getId()) {
            $this->load($oxid);
        }

        if (!$this->canDelete($oxid)) {
            return false;
        }

        $parentResult = parent::delete($oxid);

        $this->executeDependencyEvent();

        return $parentResult;
    }

    /**
     * @inheritdoc
     */
    public function getAdditionalSqlFilter($forceCoreTable = null)
    {
        $parentConditions = parent::getAdditionalSqlFilter($forceCoreTable);
        $result = $parentConditions . $this->_getSqlRightsSnippet($forceCoreTable);

        return $result;
    }

    /**
     * Checks if user rights allows to view/open current category
     *
     * @param string $categoryId category id (optional)
     *
     * @return bool
     */
    public function canView($categoryId = null)
    {
        if ($this->isAdmin() || !$this->getRights()) {
            return true;
        }

        if (!$this->canDo($categoryId, 1)) {
            return false;
        }

        // additionally checking for parent categories
        $database = DatabaseProvider::getDb(DatabaseProvider::FETCH_MODE_ASSOC);
        if ($categoryId) {
            $rs = $this->fetchCategoryTreeInformation($categoryId);
            if ($rs != false && $rs->count() > 0) {
                $left = (int) $rs->fields['oxleft'];
                $right = (int) $rs->fields['oxright'];
                $rootId = $rs->fields['oxrootid'];
            } else {
                return false;
            }
        } else {
            $left = (int) $this->oxcategories__oxleft->value;
            $right = (int) $this->oxcategories__oxright->value;
            $rootId = $this->oxcategories__oxrootid->value;
        }
        $query = "select oxid from oxcategories where oxleft < :oxleft and oxright > :oxright and oxrootid = :oxrootid ";
        $rs = $database->selectLimit($query, 1000, 0, [
            ':oxleft' => $left,
            ':oxright' => $right,
            ':oxrootid' => $rootId
        ]);
        if ($rs != false && $rs->count() > 0) {
            while (!$rs->EOF) {
                if (!$this->canDo($rs->fields['oxid'], 1)) {
                    return false;
                }
                $rs->fetchRow();
            }
        }

        return true;
    }

    /**
     * Fetch the category tree information.
     *
     * @param string $categoryId The id of the category for which we want the tree information.
     *
     * @return ResultSetInterface An associative array with the left, right and rootId of the given category.
     */
    protected function fetchCategoryTreeInformation($categoryId)
    {
        $database = DatabaseProvider::getDb(DatabaseProvider::FETCH_MODE_ASSOC);

        $query = "select oxleft, oxright, oxrootid from oxcategories where oxid = :oxid";

        return $database->selectLimit($query, 1, 0, [
            ':oxid' => $categoryId
        ]);
    }

    /**
     * @inheritdoc
     */
    public function assignViewableRecord($sSelect)
    {
        $categoryList = oxNew(CategoryList::class);
        $categoryList->selectString($sSelect);
        foreach ($categoryList as $category) {
            if ($category->canView()) {
                //$this = $category;
                $record = array();
                //assigning data to this
                foreach ($this->_aFieldNames as $fieldName => $value) {
                    $fieldLongName = $this->_getFieldLongName($fieldName);
                    $record[$fieldName] = $category->$fieldLongName;
                    if ($record[$fieldName] instanceof Field) {
                        $record[$fieldName] = $record[$fieldName]->getRawValue();
                    }
                    $this->assign($record);
                }

                return true;
            }
        }

        return false;
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
        $this->executeDependencyEvent();

        return parent::_insert();
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

        $parentResult = parent::_update();

        if ($parentResult !== false) {
            $this->executeDependencyEvent();
        }

        return $parentResult;
    }

    /**
     * Gets shop relations object.
     *
     * @return Category2ShopRelations
     * @deprecated underscore prefix violates PSR12, will be renamed to "getElement2ShopRelations" in next major
     */
    protected function _getElement2ShopRelations() // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        if (is_null($this->_oElement2ShopRelations)) {
            $this->_oElement2ShopRelations = oxNew(Category2ShopRelations::class, $this->getCoreTableName());
        }

        return $this->_oElement2ShopRelations;
    }

    /**
     * Returns a list of subshop ids, including the parent, where oxcategory elements are inherited in bulk via config
     * option from the current shop.
     * If it is subcategory then it returns a list of subshop ids where parent category is assigned to.
     *
     * @return array
     * @deprecated underscore prefix violates PSR12, will be renamed to "getInheritanceGroup" in next major
     */
    protected function _getInheritanceGroup() // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $parentCategory = $this->getParentCategory();

        if (is_null($parentCategory)) {
            // this is parent category
            $shopIds = parent::_getInheritanceGroup();
        } else {
            // this is subcategory
            $element2ShopRelations = $this->_getElement2ShopRelations();
            $parentCategoryId = $parentCategory->getId();

            $shopIds = $element2ShopRelations->getItemAssignedShopIds($parentCategoryId);
        }

        return $shopIds;
    }

    /**
     * Gets category object IDs from oxobject2category.
     *
     * @param string $categoryId Category ID.
     *
     * @return array
     */
    public function getCategoryObjectIds($categoryId)
    {
        $query = "SELECT `oxid` FROM `oxobject2category` WHERE `oxcatnid` = :oxcatnid";
        return DatabaseProvider::getDb()->getCol($query, [
            ':oxcatnid' => $categoryId
        ]);
    }
}
