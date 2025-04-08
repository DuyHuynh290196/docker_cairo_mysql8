<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Core\Cache\Generic\Connector;

use oxException;
use Memcached;
use OxidEsales\Eshop\Core\Cache\Generic\CacheItem;

/**
 * Memcached connector for generic cache.
 */
class MemcachedCacheConnector implements \OxidEsales\Eshop\Application\Model\Contract\CacheConnectorInterface
{
    /** @var Memcached Memcached object. */
    protected $_oMemcached;

    /**
     * Memcached cache connector.
     *
     * @throws oxException
     */
    public function __construct()
    {
        if (!self::isAvailable()) {
            throw oxNew(\OxidEsales\Eshop\Core\Exception\StandardException::class, EXCEPTION_NOMEMCACHED);
        }

        $this->_oMemcached = new Memcached();
        $this->_oMemcached->addServers($this->_getParsedServers());
    }

    /**
     * Check if connector is available.
     *
     * @return bool
     */
    public static function isAvailable()
    {
        $oConfig = \OxidEsales\Eshop\Core\Registry::getConfig();
        $oSerial = $oConfig->getSerial();

        return extension_loaded('Memcached') && $oSerial->isFlagEnabled('memcached_connector');
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
        if ($blArray && is_int($mValue)) {
            $iTTL = $mValue;
        }

        $iExpires = $iTTL ? (time() + $iTTL) : 0;
        if ($blArray) {
            $this->_oMemcached->setMulti($mKey, $iExpires);
        } else {
            $this->_oMemcached->set($mKey, $mValue, $iExpires);
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
        if ($blArray) {
            $mValue = $this->_oMemcached->getMulti($mKey);
        } else {
            $mValue = $this->_oMemcached->get($mKey);
        }

        if ($mValue !== false) {
            return $mValue;
        }
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
            $this->_oMemcached->delete($sKey);
        }
    }

    /**
     * Invalidate all items in the cache.
     */
    public function flush()
    {
        $this->_oMemcached->flush();
    }

    /**
     * Get parsed list of memcached servers, because in config they are stored in array("host@port@weight", ...)
     * aditionally works with preparsed 2 level array(array("host","port","weight"),...).
     *
     * @return array
     * @deprecated underscore prefix violates PSR12, will be renamed to "getParsedServers" in next major
     */
    protected function _getParsedServers() // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $oConfig = \OxidEsales\Eshop\Core\Registry::getConfig();
        $aMemcachedServers = $oConfig->getConfigParam('aMemcachedServers');

        $aServers = array();
        if (is_array($aMemcachedServers)) {
            foreach ($aMemcachedServers as $aServer) {
                if (!is_array($aServer)) {
                    $aServer = explode('@', $aServer);
                }
                if (is_array($aServer)) {
                    foreach ($aServer as &$sValue) {
                        $sValue = trim($sValue);
                    }
                    $aServers[] = $aServer;
                }
            }
        } else {
            // Defaults
            $aServers = array(array('localhost', '11211', '100'));
        }

        return $aServers;
    }
}
