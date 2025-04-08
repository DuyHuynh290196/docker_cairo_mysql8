<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Core\Cache\DynamicContent\Connector;

/**
 * Zend Server Data Cache disk storage cache backend
 */
class DefaultCacheConnector extends \OxidEsales\Eshop\Core\Base implements \OxidEsales\Eshop\Application\Model\Contract\CacheBackendInterface
{
    /**
     * Returns cache data. If data is not found - returns false
     *
     * @param string $sId cache id
     *
     * @return mixed
     */
    public function cacheGet($sId)
    {
        $sPath = $this->getConfig()->getConfigParam('sCompileDir') . "/_{$sId}.cache";
        if (is_file($sPath)) {
            return @file_get_contents($sPath);
        }

        return false;
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
        return file_put_contents($this->getConfig()->getConfigParam('sCompileDir') . "/_{$sId}.cache", $sContent, LOCK_EX);
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
        $sPath = $this->getConfig()->getConfigParam('sCompileDir') . "/_{$sId}.cache";
        if (is_file($sPath)) {
            return @unlink($sPath);
        }
    }

    /**
     * sets cache ttl in seconds
     *
     * @param int $iTimeToLive cache timeout value in seconds
     *
     * @return null
     */
    public function cacheSetTTL($iTimeToLive)
    {
    }

    /**
     * Removes all cache entries if possible by backend
     */
    public function cacheClear()
    {
    }

    /**
     * check if this backend is available
     *
     * @return bool
     */
    public static function isAvailable()
    {
        return true;
    }
}
