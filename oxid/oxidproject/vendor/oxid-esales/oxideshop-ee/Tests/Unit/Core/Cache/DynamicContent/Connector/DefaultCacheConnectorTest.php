<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */
namespace OxidEsales\EshopEnterprise\Tests\Unit\Core\Cache\DynamicContent\Connector;

use OxidEsales\EshopEnterprise\Application\Model\Contract\CacheBackendInterface;
use OxidEsales\EshopEnterprise\Core\Cache\DynamicContent\Connector\DefaultCacheConnector;
use \oxDb;

use \PHPUnit\Framework\MockObject\MockObject;
use \oxTestModules;

class DefaultCacheConnectorTest extends \oxUnitTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    /**
     * Tear down the fixture.
     *
     * @return null
     */
    protected function tearDown(): void
    {
        // cleaning up cache table
        \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute('delete from oxcache');

        // removing cache files
        $sFilePath = $this->getConfig()->getConfigParam('sCompileDir') . "/_*.cache";
        $aPathes = glob($sFilePath);
        if (is_array($aPathes)) {
            foreach ($aPathes as $sFilename) {
                // delete all the files
                @unlink($sFilename);
            }
        }
        parent::tearDown();
    }

    /**
     * test if the cache getter works right. if the cache key doesn't exist,
     * no value have to returned.
     *
     * @return null
     */
    public function testCachePutAndCacheGet()
    {
        $oCache = new DefaultCacheConnector();
        $oCache->cachePut('xxx', 'yyy');

        $this->assertFalse($oCache->cacheGet('yyy'));
        $this->assertEquals('yyy', $oCache->cacheGet('xxx'));
    }

    /**
     * test if cache remover works fine.
     *
     * @return null
     */
    public function testCacheRemoveKey()
    {
        $oCache = new DefaultCacheConnector();
        $oCache->cachePut('xxx', 'yyy');

        $this->assertEquals('yyy', $oCache->cacheGet('xxx'));

        $oCache->cacheRemoveKey('xxx');
        $this->assertFalse($oCache->cacheGet('xxx'));
    }

    /**
     * test if cache is available.
     *
     * @return null
     */
    public function testisAvailable()
    {
        $this->assertTrue(DefaultCacheConnector::isAvailable());
    }

    /**
     * test if an right instance will be created.
     *
     * @return null
     */
    public function testisRightInstance()
    {
        $o = new DefaultCacheConnector();
        $this->assertTrue($o instanceof CacheBackendInterface);
    }
}
