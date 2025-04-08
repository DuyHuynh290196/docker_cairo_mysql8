<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

/**
 * Register functions to global namespace.
 */
namespace {
    function zend_disk_cache_fetch($sId)
    {
        return "zend_disk_cache_fetch($sId)";
    }

    function zend_disk_cache_store($sId, $sContent, $iTtl)
    {
        return "zend_disk_cache_store( $sId, $sContent, $iTtl )";
    }

    function zend_disk_cache_delete($sId)
    {
        return "zend_disk_cache_delete( $sId )";
    }

    function zend_disk_cache_clear()
    {
        throw new Exception("zend_disk_cache_clear( )");
    }
}

/**
 * Actual test case namespace.
 */
namespace OxidEsales\EshopEnterprise\Tests\Unit\Core\Cache\DynamicContent\Connector {

    use OxidEsales\EshopEnterprise\Application\Model\Contract\CacheBackendInterface;
    use OxidEsales\EshopEnterprise\Core\Cache\DynamicContent\Connector\ZendDiskCacheConnector;
    use \Exception;

    use \PHPUnit\Framework\MockObject\MockObject;
    use \oxTestModules;

    class ZendDiskCacheConnectorTest extends \oxUnitTestCase
    {
        public function testCacheSetTTL()
        {
            oxTestModules::addFunction('oxCacheBackendZSDisk', 'getVar($var)', '{return $this->$var;}');
            $o = oxNew(\OxidEsales\Eshop\Core\Cache\DynamicContent\Connector\ZendDiskCacheConnector::class);
            $o->cacheSetTTL(64);
            $this->assertEquals(64, $o->getVar('_iTtl'));
        }

        public function testCachePut()
        {
            $o = oxNew(\OxidEsales\Eshop\Core\Cache\DynamicContent\Connector\ZendDiskCacheConnector::class);
            $o->cacheSetTTL(61);
            $this->assertEquals('zend_disk_cache_store( $sId, $sContent, 61 )', $o->cachePut('$sId', '$sContent'));
        }

        public function testCacheGet()
        {
            $o = oxNew(\OxidEsales\Eshop\Core\Cache\DynamicContent\Connector\ZendDiskCacheConnector::class);
            $o->cacheSetTTL(61);
            $this->assertEquals('zend_disk_cache_fetch($sId)', $o->cacheGet('$sId'));
        }

        public function testCacheRemoveKey()
        {
            $o = oxNew(\OxidEsales\Eshop\Core\Cache\DynamicContent\Connector\ZendDiskCacheConnector::class);
            $o->cacheSetTTL(61);
            $this->assertEquals('zend_disk_cache_delete( $sId )', $o->cacheRemoveKey('$sId'));
        }

        public function testCacheClear()
        {
            $o = oxNew(\OxidEsales\Eshop\Core\Cache\DynamicContent\Connector\ZendDiskCacheConnector::class);
            $o->cacheSetTTL(61);
            try {
                $this->assertEquals(null, $o->cacheClear());
            } catch (Exception $e) {
                $this->assertEquals('zend_disk_cache_clear( )', $e->getMessage());

                return;
            }
            $this->fail("exception lost");
        }

        public function testisAvailable()
        {
            $this->assertTrue(ZendDiskCacheConnector::isAvailable());
        }

        public function testisRightInstance()
        {
            $o = new ZendDiskCacheConnector();
            $this->assertTrue($o instanceof CacheBackendInterface);
        }
    }
}
