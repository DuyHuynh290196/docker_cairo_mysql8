<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Model\Contract;

use OxidEsales\Eshop\Core\Cache\Generic\CacheItem;

/**
 * Cache connector interface.
 */
interface CacheConnectorInterface extends \OxidEsales\EshopProfessional\Application\Model\Contract\CacheConnectorInterface
{

    /**
     * Check if connector is available.
     *
     * @return bool
     */
    public static function isAvailable();

    /**
     * Store single or multiple items.
     *
     * @param array|string    $key   key or array of cache items with keys.
     * @param CacheItem|int $value value or cache TTL (if mKey is array)
     * @param int             $timeToLive   cache TTL
     *
     * @return bool
     */
    public function set($key, $value = null, $timeToLive = null);

    /**
     * Retrieve single or multiple cache items.
     *
     * @param array|string $key key or array of keys (if mKey is array)
     *
     * @return CacheItem|array[string]oxCacheItem
     */
    public function get($key);

    /**
     * Invalidate single or multiple items.
     *
     * @param array|string $key key or array of keys (if mKey is array)
     *
     * @return bool
     */
    public function invalidate($key);

    /**
     * Invalidate all items in the cache.
     *
     * @return null
     */
    public function flush();
}
