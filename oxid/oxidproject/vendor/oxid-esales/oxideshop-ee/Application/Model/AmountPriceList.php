<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Model;

use OxidEsales\Eshop\Core\Cache\Generic\Cache;
use OxidEsales\Eshop\Core\Cache\Generic\CacheItem;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Application\Model\Article;

/**
 * @inheritdoc
 */
class AmountPriceList extends \OxidEsales\EshopProfessional\Application\Model\AmountPriceList
{
    /**
     * Load category list data
     *
     * @param Article $article Article
     */
    public function load($article)
    {
        $this->setArticle($article);

        $data = ($this->_getCacheBackend()->canLoadDataFromCacheBackend()) ? $this->_loadFromCache() : $this->_loadFromDb();

        $this->assignArray($data);
    }

    /**
     * Generate cache key for category tree for current shop and language
     *
     * @return string
     */
    public function getCacheKey()
    {
        return 'oxArticle_' . $this->getArticle()->getId() . '_oxAmountPrice_' . $this->getConfig()->getShopId();
    }

    /**
     * Generate cache keys for dependent cached data.
     *
     * @param string $articleId article id
     * @param array  $shopIds   shop ids array
     *
     * @return array
     */
    public function getCacheKeys($articleId, $shopIds = null)
    {
        $cacheKeys = array();
        $shopIds = $shopIds ? $shopIds : $this->getConfig()->getShopIds();

        foreach ($shopIds as $shopId) {
            $cacheKeys[] = 'oxArticle_' . $articleId . '_oxAmountPrice_' . $shopId;
        }

        return $cacheKeys;
    }


    /**
     * Execute cache dependencies
     *
     * @param string $articleId article id
     *
     * @return null
     */
    public function executeDependencyEvent($articleId)
    {
        $genericCache = $this->_getCacheBackend();

        if ($genericCache->isActive()) {
            $genericCache->invalidate($this->getCacheKeys($articleId));
        }
    }

    /**
     * Load data from cache
     *
     * @return array
     * @deprecated underscore prefix violates PSR12, will be renamed to "loadFromCache" in next major
     */
    protected function _loadFromCache() // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $genericCache = $this->_getCacheBackend();
        $cacheKey = $this->getCacheKey();
        $cacheItem = $genericCache->get($cacheKey);

        if ($cacheItem) {
            $data = $cacheItem->getData();
        } else {
            $data = $this->_loadFromDb();
            $cacheItem = oxNew(CacheItem::class);
            $cacheItem->setData($data);
            $genericCache->set($cacheKey, $cacheItem);
        }

        return $data;
    }

    /**
     * returns Generic\Cache from Registry
     *
     * @return Cache
     * @deprecated underscore prefix violates PSR12, will be renamed to "getCacheBackend" in next major
     */
    protected function _getCacheBackend() // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        return Registry::get(Cache::class);
    }
}
