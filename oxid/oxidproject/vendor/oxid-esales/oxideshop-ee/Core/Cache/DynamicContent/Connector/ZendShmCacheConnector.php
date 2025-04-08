<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Core\Cache\DynamicContent\Connector;

/**
 * Zend Server Data Cache shared memory storage cache backend
 */
class ZendShmCacheConnector implements \OxidEsales\Eshop\Application\Model\Contract\CacheBackendInterface
{
    /** @var int Time to live. */
    protected $_iTtl = 0;

    /**
     * sets cache ttl in seconds
     *
     * @param int $iTimeToLive cache timeout value in seconds
     *
     * @return null
     */
    public function cacheSetTTL($iTimeToLive)
    {
        $this->_iTtl = $iTimeToLive;
    }

    /**
     * Returns cache data. If data is not found - returns false
     *
     * @param string $sId cache id
     *
     * @return mixed
     */
    public function cacheGet($sId)
    {
        return zend_shm_cache_fetch($sId);
    }

    /**
     * Stores cache data, returns storing status
     *
     * @param string $sId      cache id
     * @param string $sContent cache data
     *
     * @return mixed
     */
    public function cachePut($sId, $sContent)
    {
        return zend_shm_cache_store($sId, $sContent, $this->_iTtl);
    }

    /**
     * Removes cache according to cache key, returns removal status
     *
     * @param string $sId cache key
     *
     * @return null
     */
    public function cacheRemoveKey($sId)
    {
        return zend_shm_cache_delete($sId);
    }

    /**
     * Removes all cache entries if possible by backend
     */
    public function cacheClear()
    {
        zend_shm_cache_clear();
    }

    /**
     * check if this backend is available
     *
     * @return bool
     */
    public static function isAvailable()
    {
        return function_exists('zend_shm_cache_fetch');
    }
}
