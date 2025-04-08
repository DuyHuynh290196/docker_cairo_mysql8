<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Model;

use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Core\Cache\Generic\Cache;
use OxidEsales\Eshop\Core\Cache\Generic\CacheItem;
use OxidEsales\Eshop\Application\Model\CategoryList;

/**
 * @inheritdoc
 */
class ContentList extends \OxidEsales\EshopProfessional\Application\Model\ContentList
{
    /**
     * @inheritdoc
     * @deprecated underscore prefix violates PSR12, will be renamed to "load" in next major
     */
    protected function _load($type) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        if ($this->_getCacheBackend()->canLoadDataFromCacheBackend()) {
            $itemData = $this->_loadFromCache($type);
            $this->assignArray($itemData);
        } else {
            parent::_load($type);
        }
    }

    /**
     * Returns oxCacheBackend from Registry
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
     * @param integer $type - type of content
     *
     * @return array
     * @deprecated underscore prefix violates PSR12, will be renamed to "loadFromCache" in next major
     */
    protected function _loadFromCache($type) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $cache = $this->_getCacheBackend();
        $key = $this->getCacheKey($type);
        $cacheItem = $cache->get($key);

        if ($cacheItem) {
            $itemData = $cacheItem->getData();
        } else {
            $itemData = $this->_loadFromDb($type);
            $cacheItem = oxNew(CacheItem::class);
            $cacheItem->setData($itemData);
            $cache->set($key, $cacheItem);
        }

        return $itemData;
    }

    /**
     * Generate cache key for category tree for current shop and language
     *
     * @param integer $type - type of content
     *
     * @return string
     */
    public function getCacheKey($type)
    {
        return 'oxContentList_' . $type . '_' .
            $this->getConfig()->getShopId() . '_' . Registry::getLang()->getLanguageAbbr();
    }

    /**
     * Generate cache key
     *
     * @param integer $type      type of content
     * @param array   $languages lang id array
     * @param array   $shopIds     shop ids array
     *
     * @return array
     */
    public function getCacheKeys($type, $languages = null, $shopIds = null)
    {
        $keys = array();
        $languages = $languages ? $languages : Registry::getLang()->getLanguageIds();
        $shopIds = $shopIds ? $shopIds : $this->getConfig()->getShopIds();

        foreach ($shopIds as $oneShopId) {
            foreach ($languages as $oneLanguageId) {
                $keys[] = 'oxContentList_' . $type . '_' . $oneShopId . '_' . $oneLanguageId;
            }
        }

        return $keys;
    }

    /**
     * Execute cache dependencies
     *
     * @param integer $type - type of content
     */
    public function executeDependencyEvent($type)
    {
        $cache = $this->_getCacheBackend();
        if ($cache->isActive()) {
            $cache->invalidate($this->getCacheKeys($type));
        }

        if ($type == self::TYPE_CATEGORY_MENU) {
            $this->_updateCategoryTreeDependencies();
        }
    }

    /**
     * Execute cache dependencies
     * @deprecated will be renamed to "updateCategoryTreeDependencies" in next major
     */
    public function _updateCategoryTreeDependencies() // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $cache = $this->_getCacheBackend();
        if ($cache->isActive()) {
            //category tree cache dependency
            $categoryList = oxNew(CategoryList::class);
            $categoryList->executeDependencyEvent();
        }
    }
}
