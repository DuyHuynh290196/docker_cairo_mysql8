<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Model\Contract;

/**
 * Cache backend interface
 * defines basic cache operations
 */
interface CacheBackendInterface
{

    /**
     * sets cache ttl in seconds
     *
     * @param int $timeToLive cache timeout value in seconds
     *
     * @return null
     */
    public function cacheSetTTL($timeToLive);

    /**
     * Returns cache data. If data is not found - returns false
     *
     * @param string $id cache id
     *
     * @return mixed
     */
    public function cacheGet($id);

    /**
     * Stores cache data, returns storing status
     *
     * @param string $id      cache id
     * @param string $content cache data
     *
     * @return mixed
     */
    public function cachePut($id, $content);

    /**
     * Removes cache according to cache key, returns removal status
     *
     * @param string $id cache key
     *
     * @return null
     */
    public function cacheRemoveKey($id);

    /**
     * Removes all cache entries if possible by backend
     *
     * @return null
     */
    public function cacheClear();

    /**
     * check if this backend is available
     *
     * @return bool
     */
    public static function isAvailable();
}
