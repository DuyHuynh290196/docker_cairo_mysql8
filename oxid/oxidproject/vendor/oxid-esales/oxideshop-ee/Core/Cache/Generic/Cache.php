<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Core\Cache\Generic;

use OxidEsales\Eshop\Application\Model\Contract\CacheConnectorInterface;
use OxidEsales\Eshop\Core\NamespaceInformationProvider;

/**
 * Generic Cache.
 */
class Cache extends \OxidEsales\Eshop\Core\Base
{
    /** @var array Object pool. */
    protected $_aPool = array();

    /** @var CacheConnectorInterface Cache connector. */
    protected $_oConnector;

    /** @var array Cache connectors. */
    protected $_aConnectors = array(
        'oxMemcachedCacheConnector',
        'oxZendShmCacheConnector',
        'oxZendDiskCacheConnector',
        'oxFileCacheConnector',
    );

    /** @var bool Cache enabled flag. */
    protected $_blActive = null;

    /** @var int Cache TTL. */
    protected $_iTTL = null;

    /**
     * Check if cache is active.
     *
     * @return bool
     */
    public function isActive()
    {
        if ($this->_blActive === null) {
            $oConfig = \OxidEsales\Eshop\Core\Registry::getConfig();
            $blActive = (bool) $oConfig->getConfigParam('blCacheActive');
            if ($blActive) {
                $oConnector = $this->_getConnector();
                $blActive = $oConnector && $oConnector->isAvailable();
            }

            $this->_blActive = $blActive;
        }

        return $this->_blActive;
    }

    /**
     * @return bool
     */
    public function canLoadDataFromCacheBackend(): bool
    {
        return !$this->isAdmin() && $this->isActive();
    }

    /**
     * Register cache connector.
     *
     * @param CacheConnectorInterface $oConnector cache connector
     */
    public function registerConnector(\OxidEsales\Eshop\Application\Model\Contract\CacheConnectorInterface $oConnector)
    {
        $this->_setConnector($oConnector);
        $this->_poolFlush();
    }

    /**
     * Get available cache connectors.
     *
     * @return array
     */
    public function getAvailableConnectors()
    {
        $aConnectors = array();
        $utilsObject = \OxidEsales\Eshop\Core\Registry::getUtilsObject();
        foreach ($this->_aConnectors as $sConnector) {
            $adjustedConnectorName = $sConnector;
            if (!NamespaceInformationProvider::isNamespacedClass($sConnector)) {
                $adjustedConnectorName = strtolower($sConnector);
            }
            $sRealConnectorClass = $utilsObject->getClassName($adjustedConnectorName);
            if (call_user_func(array($sRealConnectorClass, "isAvailable"))) {
                $aConnectors[] = $sConnector;
            }
        }

        return $aConnectors;
    }

    /**
     * Store single or multiple items.
     *
     * @param array|string    $mKey   key or array of cache items with keys.
     * @param CacheItem|int $mValue value or cache TTL (if mKey is array )
     * @param int             $iTTL   cache TTL
     */
    public function set($mKey, $mValue = null, $iTTL = null)
    {
        $blArray = is_array($mKey);
        if ($blArray && is_int($mValue)) {
            $iTTL = $mValue;
        }

        if ($this->_getConnector()) {
            $this->_getConnector()->set($mKey, $mValue, $this->_getTTL($iTTL));
        }

        $this->_poolSet($mKey, $mValue);
    }

    /**
     * Retrieve single or multiple cache items.
     *
     * @param mixed $mKey key or array of keys (if mKey is array)
     *
     * @return CacheItem|array[string]oxCacheItem
     */
    public function get($mKey)
    {
        $blArray = is_array($mKey);
        $aMissingKeys = array();
        $mPoolValue = $this->_poolGet($mKey, $aMissingKeys);

        if ($blArray) {
            $mKey = $aMissingKeys;
        }

        if ((count($aMissingKeys) || !$mPoolValue) && $this->_getConnector()) {
            $mValue = $this->_getConnector()->get($mKey);
            if ($blArray) {
                foreach ($mValue as $sKey => $oCacheItem) {
                    if ($oCacheItem) {
                        $mPoolValue[$sKey] = $oCacheItem;
                    }
                }
                $mValue = $mPoolValue;
                $this->_poolSet($mValue);
            } else {
                if ($mValue) {
                    $this->_poolSet($mKey, $mValue);
                }
            }
        } else {
            $mValue = $mPoolValue;
        }

        return $mValue;
    }

    /**
     * Invalidate single or multiple items.
     *
     * @param mixed $mKey key or array of keys (if mKey is array)
     */
    public function invalidate($mKey)
    {
        if ($this->_getConnector()) {
            $this->_getConnector()->invalidate($mKey);
        }
        $this->_poolInvalidate($mKey);
    }

    /**
     * Invalidate all items in the cache.
     */
    public function flush()
    {
        if ($this->_getConnector()) {
            $this->_getConnector()->flush();
        }
        $this->_poolFlush();
    }

    /**
     * Set cache connector.
     *
     * @param CacheConnectorInterface $oConnector cache connector
     * @deprecated underscore prefix violates PSR12, will be renamed to "setConnector" in next major
     */
    protected function _setConnector(\OxidEsales\Eshop\Application\Model\Contract\CacheConnectorInterface $oConnector) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        if ($oConnector->isAvailable()) {
            $this->_oConnector = $oConnector;
            $this->_blActive = null;
        } else {
            $this->_oConnector = null;
            $this->_blActive = false;
        }
    }

    /**
     * Get cache connector.
     *
     * @return CacheConnectorInterface
     * @deprecated underscore prefix violates PSR12, will be renamed to "getConnector" in next major
     */
    protected function _getConnector() // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        if (!$this->_blActive && !$this->_oConnector) {
            $this->_registerDefaultConnector();
        }

        return $this->_oConnector;
    }

    /**
     * Get TTL
     *
     * @param int $iTTL cache TTL
     *
     * @return int
     * @deprecated underscore prefix violates PSR12, will be renamed to "getTTL" in next major
     */
    protected function _getTTL($iTTL = null) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        if (!is_null($iTTL)) {
            return $iTTL;
        }
        if (is_null($iTTL) && is_null($this->_iTTL)) {
            $oConfig = \OxidEsales\Eshop\Core\Registry::getConfig();
            $iDefaultCacheTTL = $oConfig->getConfigParam('iDefaultCacheTTL');
            if ($iDefaultCacheTTL) {
                $this->_iTTL = $iDefaultCacheTTL;
            } else {
                $this->_iTTL = 0;
            }
        }

        return $this->_iTTL;
    }

    /**
     * Register default cache connector.
     * @deprecated underscore prefix violates PSR12, will be renamed to "registerDefaultConnector" in next major
     */
    protected function _registerDefaultConnector() // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $oConfig = \OxidEsales\Eshop\Core\Registry::getConfig();
        $sDefaultConnector = $oConfig->getConfigParam('sDefaultCacheConnector');
        $utilsObject = \OxidEsales\Eshop\Core\Registry::getUtilsObject();
        if (!NamespaceInformationProvider::isNamespacedClass($sDefaultConnector)) {
            $sDefaultConnector = strtolower($sDefaultConnector);
        }
        $sDefaultConnector = $utilsObject->getClassName($sDefaultConnector);

        if ($sDefaultConnector && call_user_func(array($sDefaultConnector, "isAvailable"))) {
            /** @var CacheConnectorInterface $oConnector */
            $oConnector = oxNew($sDefaultConnector);
            $this->_setConnector($oConnector);
        } else {
            // No available default connector.
            $this->_oConnector = null;
            $this->_blActive = false;
        }
    }

    /**
     * Clean object pool.
     * @deprecated underscore prefix violates PSR12, will be renamed to "poolFlush" in next major
     */
    protected function _poolFlush() // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $this->_aPool = array();
    }

    /**
     * Set single or multiple items to object pool.
     *
     * @param array|string     $mKey   cache key
     * @param CacheItem|null $mValue data
     * @deprecated underscore prefix violates PSR12, will be renamed to "poolSet" in next major
     */
    protected function _poolSet($mKey, $mValue = null) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        if (is_array($mKey)) {
            $this->_aPool = array_merge($this->_aPool, $mKey);
        } else {
            $this->_aPool[$mKey] = $mValue;
        }
    }

    /**
     * Get single or multiple objects from pool.
     *
     * @param mixed $mKey          cache key or array of keys.
     * @param array &$aMissingKeys array missing of keys.
     *
     * @return CacheItem|array
     * @deprecated underscore prefix violates PSR12, will be renamed to "poolGet" in next major
     */
    protected function _poolGet($mKey, &$aMissingKeys = null) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $blArray = is_array($mKey);

        if (!$blArray) {
            $mKey = array($mKey);
        }

        $aKeys = array_values($mKey);
        $aItems = array_intersect_key($this->_aPool, array_flip($aKeys));

        if (count($aItems) < count($aKeys)) {
            $aMissingKeys = array_diff($aKeys, array_keys($aItems));
        }

        if (!$blArray) {
            if (count($aItems)) {
                $aItems = reset($aItems);
            } else {
                $aItems = null;
            }
        }

        return $aItems;
    }

    /**
     * Invalidate single or multiple object from pool.
     *
     * @param mixed $mKey cache key or array of keys.
     * @deprecated underscore prefix violates PSR12, will be renamed to "poolInvalidate" in next major
     */
    protected function _poolInvalidate($mKey) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $blArray = is_array($mKey);
        if (!$blArray) {
            $mKey = array($mKey);
        }

        $aKeys = array_values($mKey);
        $this->_aPool = array_diff_key($this->_aPool, array_flip($aKeys));
    }
}
