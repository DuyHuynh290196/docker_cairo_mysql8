<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Controller\Admin;

use OxidEsales\Eshop\Core\Registry;

/**
 * Admin shop TERMS manager.
 * Collects shop TERMS information, updates it on user submit, etc.
 * Admin Menu: Main Menu -> Core Settings -> Terms.
 */
class ShopCache extends \OxidEsales\Eshop\Application\Controller\Admin\ShopConfiguration
{
    /**
     * Executes parent method parent::render(), creates oxshop object and
     * passes it's data to Smarty engine, returns name of template file
     * "shop_cache.tpl".
     *
     * @return string
     */
    public function render()
    {
        parent::render();

        $cache = oxNew(\OxidEsales\Eshop\Core\Cache\DynamicContent\ContentCache::class);

        $totalValidCacheCount = $cache->getTotalCacheCount();
        $totalExpiredCacheCount = $cache->getTotalCacheCount(true);
        $totalCacheCount = $totalValidCacheCount + $totalExpiredCacheCount;

        // Cache Hit/Miss Ratio.
        $totalValidCacheHitCount = $cache->getTotalCacheHits();
        $totalValidCacheMissCount = $totalValidCacheCount;

        if ($totalValidCacheHitCount + $totalValidCacheMissCount > 0) {
            $totalValidCacheHitRatio = $totalValidCacheHitCount /
                ($totalValidCacheHitCount + $totalValidCacheMissCount);
            $totalValidCacheMissRatio = $totalValidCacheMissCount /
                ($totalValidCacheHitCount + $totalValidCacheMissCount);

            $totalValidCacheHitPercent = $totalValidCacheHitRatio * 100;
            $totalValidCacheMissPercent = $totalValidCacheMissRatio * 100;
        } else {
            $totalValidCacheHitCount = 0;
            $totalValidCacheMissCount = 0;

            $totalValidCacheHitRatio = 0;
            $totalValidCacheMissRatio = 0;
            $totalValidCacheHitPercent = 0;
            $totalValidCacheMissPercent = 0;
        }

        $this->_aViewData["TotalValidCacheHitCount"] = $totalValidCacheHitCount;
        $this->_aViewData["TotalValidCacheMissCount"] = $totalValidCacheMissCount;

        $this->_aViewData["TotalValidCacheHitRatio"] = round($totalValidCacheHitRatio, 2);
        $this->_aViewData["TotalValidCacheMissRatio"] = round($totalValidCacheMissRatio, 2);

        $this->_aViewData["TotalValidCacheHitPercent"] = round($totalValidCacheHitPercent, 2);
        $this->_aViewData["TotalValidCacheMissPercent"] = round($totalValidCacheMissPercent, 2);

        $this->_aViewData["TotalValidCacheCount"] = $totalValidCacheCount;
        $this->_aViewData["TotalExpiredCacheCount"] = $totalExpiredCacheCount;
        $this->_aViewData["TotalCacheCount"] = $totalCacheCount;


        $totalValidCacheSize = $cache->getTotalCacheSize();
        $totalExpiredCacheSize = $cache->getTotalCacheSize(true);

        if (!$totalValidCacheSize) {
            $totalValidCacheSize = 0;
        }
        if (!$totalExpiredCacheSize) {
            $totalExpiredCacheSize = 0;
        }

        $totalCacheSize = $totalValidCacheSize + $totalExpiredCacheSize;

        $this->_aViewData["TotalValidCacheSize"] = $totalValidCacheSize;
        $this->_aViewData["TotalExpiredCacheSize"] = $totalExpiredCacheSize;
        $this->_aViewData["TotalCacheSize"] = $totalCacheSize;
        $this->_aViewData["ActiveCacheLifetime"] = $cache->getCacheLifeTime();

        $backends = array_flip($cache->getAvailableBackends());
        foreach (array_keys($backends) as $key) {
            $backends[$key] = false;
        }
        $backends[$cache->getSelectedBackend()] = true;
        $this->_aViewData["aCacheBackends"] = $backends;

        $cacheBackend = \OxidEsales\Eshop\Core\Registry::get(\OxidEsales\Eshop\Core\Cache\Generic\Cache::class);
        $this->_aViewData["aCacheConnectors"] = $cacheBackend->getAvailableConnectors();

        $this->_aViewData["sShopHomeURL"] = $this->getConfig()->getShopURL();

        return "shop_cache.tpl";
    }

    /**
     * Flush all cache.
     */
    public function flushCache()
    {
        $this->flushDefaultCacheBackend();
        $this->flushContentCache();
    }

    /**
     * Flush content cache.
     */
    public function flushContentCache()
    {
        $this->resetContentCache(true);
    }

    /**
     * Flushes (Invalidates) all default cache backend cache.
     */
    public function flushDefaultCacheBackend()
    {
        $backend = \OxidEsales\Eshop\Core\Registry::get(\OxidEsales\Eshop\Core\Cache\Generic\Cache::class);
        $backend->flush();
    }
}
