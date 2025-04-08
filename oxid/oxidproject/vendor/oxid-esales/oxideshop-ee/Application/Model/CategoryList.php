<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Model;

use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Core\Cache\Generic\Cache;
use OxidEsales\Eshop\Core\Cache\Generic\CacheItem;
use OxidEsales\Eshop\Core\Cache\DynamicContent\ContentCache;

/**
 * @inheritdoc
 */
class CategoryList extends \OxidEsales\EshopProfessional\Application\Model\CategoryList
{
    /**
     * @inheritdoc
     */
    protected function getActivityFieldsSql($tableName)
    {
        return ",not ($tableName.oxactive " . $this->_getSqlRightsSnippet($tableName) . ") as oxppremove";
    }

    /**
     * Execute cache dependencies
     */
    public function executeDependencyEvent()
    {
        $cache = $this->_getCacheBackend();
        if ($cache->isActive()) {
            $cache->invalidate($this->getCacheKeys());
        }
    }

    /**
     * @inheritdoc
     */
    public function load()
    {
        if ($this->_getCacheBackend()->canLoadDataFromCacheBackend()) {
            $data = $this->_loadFromCache();
            $this->assignArray($data);
        } else {
            parent::load();
        }
    }

    /**
     * Returns SQL select string with checks if items is accessible by R&R config
     *
     * @param string $table (optional) table name
     *
     * @return string
     * @deprecated underscore prefix violates PSR12, will be renamed to "getSqlRightsSnippet" in next major
     */
    protected function _getSqlRightsSnippet($table = null) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        if (!$table) {
            $table = $this->getBaseObject()->getViewName();
        }
        $query = '';

        // R&R: user access
        if (!$this->isAdmin() && ($rights = $this->getRights())) {
            $query .= " and ( ( ";
            $query .= "( select oxobjectrights.oxobjectid from oxobjectrights where oxobjectrights.oxobjectid = $table.oxid and oxobjectrights.oxaction = 1 limit 1 ) is null ";
            $groupIndex = $rights->getUserGroupIndex();
            if (is_array($groupIndex) && count($groupIndex)) {
                $select = "";
                $count = 0;
                foreach ($groupIndex as $offset => $bitMap) {
                    if ($count) {
                        $select .= " | ";
                    }
                    $select .= " ( oxobjectrights.oxgroupidx & $bitMap and oxobjectrights.oxoffset = $offset ) ";
                    $count++;
                }

                $query .= ") or (";
                $query .= "( select oxobjectrights.oxobjectid from oxobjectrights where oxobjectrights.oxobjectid = $table.oxid and oxobjectrights.oxaction = 1 and $select limit 1 ) is not null ";
            }

            $query .= " ) ) ";
        }

        return $query;
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
     * @return array
     * @deprecated underscore prefix violates PSR12, will be renamed to "loadFromCache" in next major
     */
    protected function _loadFromCache() // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $cacheBackend = $this->_getCacheBackend();
        $cacheKey = $this->getCacheKey();
        $cacheItem = $cacheBackend->get($cacheKey);

        if ($cacheItem) {
            $data = $cacheItem->getData();
        } else {
            $data = $this->_loadFromDb();
            $cacheItem = oxNew(CacheItem::class);
            $cacheItem->setData($data);
            $cacheBackend->set($cacheKey, $cacheItem);
        }

        return $data;
    }

    /**
     * Generate cache key for category tree for current shop and language
     *
     * @return string
     */
    public function getCacheKey()
    {
        return 'oxCategoryTree_' . $this->getConfig()->getShopId() . '_' . Registry::getLang()->getLanguageAbbr();
    }

    /**
     * Generate cache key
     *
     * @param array $languages lang id array
     * @param array $shops     shop ids array
     *
     * @return array
     */
    public function getCacheKeys($languages = null, $shops = null)
    {
        $keys = array();
        $languages = $languages ? $languages : Registry::getLang()->getLanguageIds();
        $shops = $shops ? $shops : $this->getConfig()->getShopIds();

        foreach ($shops as $sShopId) {
            foreach ($languages as $sLanguageId) {
                $keys[] = 'oxCategoryTree_' . $sShopId . '_' . $sLanguageId;
            }
        }

        return $keys;
    }

    /**
     * @inheritdoc
     */
    protected function onUpdateCategoryTree()
    {
        $cache = oxNew(ContentCache::class);
        $cache->reset();
    }

    /**
     * @inheritdoc
     */
    protected function getInitialUpdateCategoryTreeCondition($verbose = false)
    {
        $condition = parent::getInitialUpdateCategoryTreeCondition();

        $this->_aUpdateInfo[] = "*** <b>SHOP : {$this->_sShopID}</b><br><br>";
        if ($verbose) {
            echo current($this->_aUpdateInfo);
        }
        $condition .= " and oxshopid = '$this->_sShopID'";

        return $condition;
    }
}
