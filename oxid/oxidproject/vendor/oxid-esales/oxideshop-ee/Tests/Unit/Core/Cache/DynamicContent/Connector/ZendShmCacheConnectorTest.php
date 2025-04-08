<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

/**
 * Register functions to global namespace.
 */
namespace {
    function zend_shm_cache_fetch($sId)
    {
        if (OxidEsales\EshopEnterprise\Tests\Unit\Core\Cache\DynamicContent\Connector\ZendShmCacheConnectorTest::$blTestActive) {
            return "zend_shm_cache_fetch($sId)";
        }
    }

    function zend_shm_cache_store($sId, $sContent, $iTtl)
    {
        if (OxidEsales\EshopEnterprise\Tests\Unit\Core\Cache\DynamicContent\Connector\ZendShmCacheConnectorTest::$blTestActive) {
            return "zend_shm_cache_store( $sId, $sContent, $iTtl )";
        }
    }

    function zend_shm_cache_delete($sId)
    {
        if (OxidEsales\EshopEnterprise\Tests\Unit\Core\Cache\DynamicContent\Connector\ZendShmCacheConnectorTest::$blTestActive) {
            return "zend_shm_cache_delete( $sId )";
        }
    }

    function zend_shm_cache_clear()
    {
        if (OxidEsales\EshopEnterprise\Tests\Unit\Core\Cache\DynamicContent\Connector\ZendShmCacheConnectorTest::$blTestActive) {
            throw new Exception("zend_shm_cache_clear( )");
        }
    }
}

/**
 * Actual test case namespace.
 */
namespace OxidEsales\EshopEnterprise\Tests\Unit\Core\Cache\DynamicContent\Connector {

    use OxidEsales\EshopEnterprise\Application\Model\Contract\CacheBackendInterface;
    use OxidEsales\EshopEnterprise\Core\Cache\DynamicContent\Connector\ZendShmCacheConnector;
    use \Exception;

    use \PHPUnit\Framework\MockObject\MockObject;
    use \oxTestModules;

    class ZendShmCacheConnectorTest extends \oxUnitTestCase
    {
        /** @var bool Whether test is active. */
        public static $blTestActive = false;

        /**
         * Initialize the fixture.
         *
         * @return null
         */
        protected function setUp(): void
        {
            self::$blTestActive = true;
            parent::setUp();
        }

        /**
         * Tear down the fixture.
         *
         * @return null
         */
        protected function tearDown(): void
        {
            self::$blTestActive = false;
            parent::tearDown();
        }

        public function testCacheSetTTL()
        {
            oxTestModules::addFunction('oxCacheBackendZSShm', 'getVar($var)', '{return $this->$var;}');
            $o = oxNew(\OxidEsales\Eshop\Core\Cache\DynamicContent\Connector\ZendShmCacheConnector::class);
            $o->cacheSetTTL(64);
            $this->assertEquals(64, $o->getVar('_iTtl'));
        }

        public function testCachePut()
        {
            $o = oxNew(\OxidEsales\Eshop\Core\Cache\DynamicContent\Connector\ZendShmCacheConnector::class);
            $o->cacheSetTTL(61);
            $this->assertEquals('zend_shm_cache_store( $sId, $sContent, 61 )', $o->cachePut('$sId', '$sContent'));
        }

        public function testCacheGet()
        {
            $o = oxNew(\OxidEsales\Eshop\Core\Cache\DynamicContent\Connector\ZendShmCacheConnector::class);
            $o->cacheSetTTL(61);
            $this->assertEquals('zend_shm_cache_fetch($sId)', $o->cacheGet('$sId'));
        }

        public function testCacheRemoveKey()
        {
            $o = oxNew(\OxidEsales\Eshop\Core\Cache\DynamicContent\Connector\ZendShmCacheConnector::class);
            $o->cacheSetTTL(61);
            $this->assertEquals('zend_shm_cache_delete( $sId )', $o->cacheRemoveKey('$sId'));
        }

        public function testCacheClear()
        {
            $o = oxNew(\OxidEsales\Eshop\Core\Cache\DynamicContent\Connector\ZendShmCacheConnector::class);
            $o->cacheSetTTL(61);
            try {
                $this->assertEquals(null, $o->cacheClear());
            } catch (Exception $e) {
                $this->assertEquals('zend_shm_cache_clear( )', $e->getMessage());

                return;
            }
            $this->fail("exception lost");
        }

        public function testisAvailable()
        {
            $this->assertTrue(ZendShmCacheConnector::isAvailable());
        }

        public function testisRightInstance()
        {
            $o = new ZendShmCacheConnector();
            $this->assertTrue($o instanceof CacheBackendInterface);
        }
    }
}
