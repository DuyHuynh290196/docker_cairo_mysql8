<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Core\Cache\Generic\Connector;

use OxidEsales\Eshop\Core\Cache\Generic\CacheItem;
use oxException;

/**
 * oxZendShmCacheConnector class - Zend Server shared memory cache
 *
 */
class ZendShmCacheConnector implements \OxidEsales\Eshop\Application\Model\Contract\CacheConnectorInterface
{
    /**
     * Zend shared memory cache connector.
     *
     * @throws oxException
     */
    public function __construct()
    {
        if (!self::isAvailable()) {
            throw oxNew(\OxidEsales\Eshop\Core\Exception\StandardException::class, EXCEPTION_NOZENDSHMCACHE);
        }
    }

    /**
     * Check if connector is available.
     *
     * @return bool
     */
    public static function isAvailable()
    {
        return function_exists('zend_shm_cache_fetch');
    }


    /**
     * Store single or multiple items.
     *
     * @param array|string    $mKey   key or array of cache items with keys.
     * @param CacheItem|int $mValue value or cache TTL (if mKey is array )
     * @param int             $iTTL   cache TTL
     *
     * @return null
     */
    public function set($mKey, $mValue = null, $iTTL = 0)
    {
        $blArray = is_array($mKey);
        if (!$blArray) {
            $mKey = array($mKey => $mValue);
        } elseif (is_int($mValue)) {
            $iTTL = $mValue;
        }

        foreach ($mKey as $sKey => $mValue) {
            zend_shm_cache_store($sKey, $mValue, $iTTL);
        }
    }

    /**
     * Retrieve single or multiple cache items.
     *
     * @param array|string $mKey key or array of keys (if mKey is array)
     *
     * @return CacheItem|array[string]oxCacheItem
     */
    public function get($mKey)
    {
        $blArray = is_array($mKey);
        if (!$blArray) {
            $mKey = array($mKey);
        }

        $mValue = array();
        foreach ($mKey as $sKey) {
            $mData = zend_shm_cache_fetch($sKey);
            if ($mData !== false) {
                $mValue[$sKey] = $mData;
            }
        }

        if (!$blArray) {
            if (count($mValue)) {
                $mValue = reset($mValue);
            } else {
                $mValue = null;
            }
        }

        return $mValue;
    }

    /**
     * Invalidate single or multiple items.
     *
     * @param array|string $mKey key or array of keys (if mKey is array)
     *
     * @return null
     */
    public function invalidate($mKey)
    {
        $blArray = is_array($mKey);
        if (!$blArray) {
            $mKey = array($mKey);
        }

        foreach ($mKey as $sKey) {
            zend_shm_cache_delete($sKey);
        }
    }

    /**
     * Invalidate all items in the cache.
     *
     * @return null
     */
    public function flush()
    {
        zend_shm_cache_clear();
    }
}
