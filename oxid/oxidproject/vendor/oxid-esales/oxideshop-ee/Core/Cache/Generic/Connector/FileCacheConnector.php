<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Core\Cache\Generic\Connector;

use oxRegistry;
use OxidEsales\Eshop\Core\Cache\Generic\CacheItem;

/**
 * File connector for generic cache.
 */
class FileCacheConnector implements \OxidEsales\Eshop\Application\Model\Contract\CacheConnectorInterface
{
    /** @var string Cache directory */
    protected $_sCacheDir;

    /** @var string Default cache directory ( sCacheDir = getShopBasePath + sDefaultCacheDir). */
    protected $_sDefaultCacheDir = 'cache';

    /** @var int Cache default expires time. */
    protected $_iDefaultExpires = 157784630; // 5 years

    /**
     * Check if connector is available.
     *
     * @return bool
     */
    public static function isAvailable()
    {
        return true;
    }

    /**
     * Cache directory getter.
     *
     * @return string
     *
     * @return null
     */
    public function getCacheDir()
    {
        if (!$this->_sCacheDir) {
            $oConfig = \OxidEsales\Eshop\Core\Registry::getConfig();
            $sCacheDir = $oConfig->getConfigParam('sCacheDir');
            if (!$sCacheDir) {
                // Defaults
                $sCacheDir = $this->_sDefaultCacheDir;
            }

            // Add shop base path if needed
            if (!is_dir($sCacheDir)) {
                $sCacheDir = getShopBasePath() . $sCacheDir;
            }
            $this->_sCacheDir = $sCacheDir;
        }

        return $this->_sCacheDir;
    }

    /**
     * Cache directory setter.
     *
     * @param string $sCacheDir directory path from base shop dir
     *
     * @return null
     */
    public function setCacheDir($sCacheDir)
    {
        $this->_sCacheDir = $sCacheDir;
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

        $iExpires = $iTTL ? (time() + $iTTL) : (time() + $this->_iDefaultExpires);
        foreach ($mKey as $sKey => $oCacheItem) {
            $this->_writeFile($this->_getFilePath($sKey), $oCacheItem, $iExpires);
        }
    }

    /**
     * Retrieve single or multiple cache items.
     *
     * @param array|string $mKey key or array of keys (if mKey is array)
     *
     * @return CacheItem|array[string]oxCacheItem
     *
     * @return null
     */
    public function get($mKey)
    {
        $blArray = is_array($mKey);
        if (!$blArray) {
            $mKey = array($mKey);
        }

        $mValue = array();
        foreach ($mKey as $sKey) {
            $sFilePath = $this->_getFilePath($sKey);
            if (file_exists($sFilePath)) {
                if (filemtime($sFilePath) > time()) {
                    $sContent = $this->_readFile($sFilePath);
                    $mValue[$sKey] = unserialize($sContent);
                } else {
                    $this->_deleteFile($sFilePath);
                }
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
     * @param string|array $mKey key or array of keys (if mKey is array)
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
            $this->_deleteFile($this->_getFilePath($sKey));
        }
    }

    /**
     * Invalidate all items in the cache.
     *
     * @return null
     */
    public function flush()
    {
        $aFiles = glob($this->_getPath() . '*.cache');

        if (is_array($aFiles)) {
            foreach ($aFiles as $sFile) {
                unlink($sFile);
            }
        }
    }

    /**
     * Create file path
     *
     * @param string $sKey key
     *
     * @return string
     * @deprecated underscore prefix violates PSR12, will be renamed to "getFilePath" in next major
     */
    protected function _getFilePath($sKey) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        return $this->getCacheDir() . '/' . strtolower($sKey) . '.cache';
    }

    /**
     * Returns full path to cache dir
     *
     * @return string
     * @deprecated underscore prefix violates PSR12, will be renamed to "getPath" in next major
     */
    protected function _getPath() // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        return $this->getCacheDir() . '/';
    }

    /**
     * Reads and returns cache file contents
     *
     * @param string $sFilePath cache file path
     *
     * @return string
     * @deprecated underscore prefix violates PSR12, will be renamed to "readFile" in next major
     */
    protected function _readFile($sFilePath) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $sContent = file_get_contents($sFilePath);

        return $sContent;
    }

    /**
     * Reads and returns cache file contents
     *
     * @param string $sFilePath cache file path
     *
     * @return string
     * @deprecated underscore prefix violates PSR12, will be renamed to "deleteFile" in next major
     */
    protected function _deleteFile($sFilePath) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        return @unlink($sFilePath);
    }

    /**
     * Releases file lock and returns release state
     *
     * @param string      $sFilePath  file path
     * @param CacheItem $oCacheItem cache data
     * @param int         $iExpires   expire time
     *
     * @return null
     * @deprecated underscore prefix violates PSR12, will be renamed to "writeFile" in next major
     */
    protected function _writeFile($sFilePath, $oCacheItem, $iExpires) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $rHandle = fopen($sFilePath, "w");
        flock($rHandle, LOCK_EX);
        fwrite($rHandle, serialize($oCacheItem));
        flock($rHandle, LOCK_UN);
        fclose($rHandle);

        touch($sFilePath, $iExpires);
    }
}
