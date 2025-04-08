<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */
namespace OxidEsales\EshopEnterprise\Tests\Unit\Core\Cache\Generic;

require_once TEST_LIBRARY_HELPERS_PATH . 'oxTestCacheConnector.php';

use OxidEsales\Eshop\Core\Cache\Generic\CacheItem;
use \oxTestCacheConnector;
use OxidEsales\Eshop\Core\Cache\Generic\Cache;
use OxidEsales\Eshop\Core\Cache\Generic\Connector\FileCacheConnector;
use \PHPUnit\Framework\MockObject\MockObject;
use OxidEsales\TestingLibrary\UnitTestCase;

/**
 * Test cache connector.
 * @package OxidEsales\Tests\Unit\Enterprise\Core\Cache\Generic
 */
class TestCacheConnector extends oxTestCacheConnector
{
    public function get($mKey)
    {
        $oData = oxNew(CacheItem::class);
        $oData->setData('dummy_' . $mKey);

        return $oData;
    }
}

/**
 * Test unavailable cache connector.
 * @package OxidEsales\Tests\Unit\Enterprise\Core\Cache\Generic
 */
class TestUnavailableCacheConnector extends oxTestCacheConnector
{
    public static function isAvailable()
    {
        return false;
    }
}

/**
 * Actual test class
 * @package OxidEsales\Tests\Unit\Enterprise\Core\Cache\Generic
 */
class CacheTest extends UnitTestCase
{
    /** @var Cache */
    public $cache;

    /** @var oxTestCacheConnector */
    public $cacheConnector;

    protected function setUp(): void
    {
        $this->cacheConnector = new oxTestCacheConnector();
        $this->cache = oxNew(Cache::class);
        $this->cache->registerConnector($this->cacheConnector);
        $this->cache->flush();
        parent::setUp();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        if ($this->cache) {
            $this->cache->flush();
        }
    }

    /**
     * Data provider for Cache::isActive().
     *
     * @return array
     */
    public static function isActive_dataProvider(): array
    {
        $testCacheConnector = new oxTestCacheConnector();
        $testUnavailableCacheConnector = new TestUnavailableCacheConnector();

        return [
            [$testCacheConnector, true, true],
            [$testCacheConnector, false, false],
            [$testUnavailableCacheConnector, true, false],
            [$testUnavailableCacheConnector, false, false],
        ];
    }

    /**
     * oxCacheBackEnd::isActive() test case.
     * Check if Memcached connector is turned on.
     *
     * @dataProvider isActive_dataProvider
     *
     * @param oxTestCacheConnector $connector    Connector
     * @param bool                 $cacheActive Cache Active
     * @param bool                 $result      Result
     */
    public function testIsActive($connector, $cacheActive, $result)
    {
        $this->setConfigParam("blCacheActive", $cacheActive);

        $cache = oxNew(Cache::class);
        $cache->registerConnector($connector);

        $this->assertEquals($result, $cache->isActive());
        $this->assertEquals($result, $cache->isActive());
    }

    public function canLoadDataFromCacheBackend_dataProvider(): array
    {
        return [
            [true, true, false],
            [true, false, true],
            [false, true, false],
            [false, false, false],
        ];
    }

    /**
     * @dataProvider canLoadDataFromCacheBackend_dataProvider
     *
     * @param bool $cacheActive
     * @param bool $isAdmin
     * @param bool $result
     */
    public function testCanLoadDataFromCacheBackend(bool $cacheActive, bool $isAdmin, bool $result): void
    {
        $this->setConfigParam("blCacheActive", $cacheActive);
        $this->setConfigParam( 'sDefaultCacheConnector', 'oxFileCacheConnector' );

        $cache = oxNew(Cache::class);
        $cache->setAdminMode($isAdmin);

        $this->assertEquals($result, $cache->canLoadDataFromCacheBackend());
    }

    /**
     * Test register Connector
     */
    public function testRegisterConnector()
    {
        $key = 'testKey';
        $cacheItem = oxNew(CacheItem::class);
        $cacheItem->setData(array('register', 'connector'));

        $this->cache->set($key, $cacheItem);
        $this->assertEquals($this->cache->get($key), $cacheItem);

        $connector = new TestCacheConnector();
        $this->cache->registerConnector($connector);
        $this->assertEquals($this->cache->get($key)->getData(), 'dummy_' . $key);
    }

    /**
     * Test register unavailable connector
     */
    public function testRegisterUnavailableConnector()
    {
        $key = 'testKey';
        $cacheItem = oxNew(CacheItem::class);
        $cacheItem->setData(array('register', 'connector'));

        $this->cache->set($key, $cacheItem);
        $this->assertEquals($this->cache->get($key), $cacheItem);

        $connector = new TestUnavailableCacheConnector();
        $this->cache->registerConnector($connector);
        $this->assertNull($this->cache->get($key));
        $this->assertFalse($this->cache->isActive());
    }

    /**
     * Test default connector.
     */
    public function testRegisterDefaultConnector()
    {
        $this->setConfigParam('sDefaultCacheConnector', 'oxFileCacheConnector');

        $cache = oxNew(Cache::class);
        $this->assertTrue($cache->UNITgetConnector() instanceof FileCacheConnector);
        $cache->get('testKey');
    }

    /**
     * Test default connector from config.
     */
    public function testRegisterDefaultConfigConnector()
    {
        $this->setConfigParam('sDefaultCacheConnector', 'oxTestCacheConnector');

        $cache = oxNew(Cache::class);
        //$this->assertInstanceOf('oxTestCacheConnector', $cache->UNITgetConnector());
        $this->assertTrue($cache->UNITgetConnector() instanceof oxTestCacheConnector);
        $cache->get('testKey');
    }

    /**
     * Test default unavailable connector from config.
     */
    public function testRegisterDefaultUnavailableConnector()
    {
        $this->setConfigParam('sDefaultCacheConnector', '\OxidEsales\EshopEnterprise\Tests\Unit\Core\Cache\Generic\TestUnavailableCacheConnector');

        $cache = oxNew(Cache::class);
        $this->assertNull($cache->UNITgetConnector());
        $cache->get('testKey');
    }

    /**
     * Test set() and get().
     */
    public function testSetGet()
    {
        $key = 'testKey';
        $cacheItem = oxNew(CacheItem::class);
        $cacheItem->setData(array('set', 'get', 'non', 'pooled'));

        $this->cache->set($key, $cacheItem);

        $this->assertEquals($this->cacheConnector->get($key), $cacheItem);
        $this->assertEquals($this->cache->get($key), $cacheItem);
    }

    /**
     * Test set() and get() pooled.
     */
    public function testSetGetPooled()
    {
        $key = 'testKey';
        $cacheItem = oxNew(CacheItem::class);
        $cacheItem->setData(array('set', 'get', 'pooled'));

        /** @var oxTestCacheConnector|MockObject $connector */
        $connector = $this->getMock("oxTestCacheConnector", array("get"));
        $connector->expects($this->never())->method('get')->will($this->returnValue($cacheItem));

        $cache = oxNew(Cache::class);
        $cache->registerConnector($connector);
        $cache->set($key, $cacheItem);

        $this->assertEquals($cache->get($key), $cacheItem);
        $this->assertEquals($cache->get($key), $cacheItem);
    }

    /**
     * Test set() and get() non pooled, use setter in connector.
     */
    public function testSetGetNonPooled()
    {
        $key = 'testKey';
        $cacheItem = oxNew(CacheItem::class);
        $cacheItem->setData(array('set', 'get', 'pooled'));

        /** @var oxTestCacheConnector|MockObject $connector */
        $connector = $this->getMock("oxTestCacheConnector", array("get"));
        $connector->expects($this->once())->method('get')->will($this->returnValue($cacheItem));

        $cache = oxNew(Cache::class);
        $cache->registerConnector($connector);
        $connector->set($key, $cacheItem);

        $this->assertEquals($cache->get($key), $cacheItem);
        $this->assertEquals($cache->get($key), $cacheItem);
    }


    /**
     * Test set() and get() pool only.
     */
    public function testSetGetPooledOnly()
    {
        $key = 'testKey';
        $cacheItem = oxNew(CacheItem::class);
        $cacheItem->setData(array('set', 'get', 'pooled', 'only'));

        $this->setConfigParam('sDefaultCacheConnector', null);

        $cache = oxNew(Cache::class);
        $cache->set($key, $cacheItem);

        $this->assertEquals($cache->get($key), $cacheItem);
    }

    /**
     * Test set() and invalidate() pool only.
     */
    public function testSetInvalidatePooledOnly()
    {
        $key = 'testKey';
        $cacheItem = oxNew(CacheItem::class);
        $cacheItem->setData(array('set', 'invalidate', 'pooled', 'only'));

        $this->setConfigParam('sDefaultCacheConnector', null);

        $cache = oxNew(Cache::class);
        $cache->set($key, $cacheItem);
        $cache->invalidate($key);

        $this->assertNull($cache->get($key));
    }

    /**
     * Test set() and get() with arrays.
     */
    public function testSetGetArrays()
    {

        $cacheItem = oxNew(CacheItem::class);
        $cacheItem->setData(array('set', 'get', 'non', 'pooled'));

        $data = array('TestKey1' => $cacheItem, 'TestKey2' => $cacheItem);
        $keys = array_keys($data);

        $this->cache->set($data);

        $this->assertEquals($this->cacheConnector->get($keys), $data);
        $this->assertEquals($this->cache->get($keys), $data);
    }

    /**
     * Test set() and get() with array non pooled.
     */
    public function testSetGetArrayNonPooled()
    {

        $cacheItem = oxNew(CacheItem::class);
        $cacheItem->setData(array('set', 'get', 'non', 'pooled'));

        $data = array('TestKey1' => $cacheItem, 'TestKey2' => $cacheItem);
        $keys = array_keys($data);

        $this->cache->set($data);
        $this->cache->UNITpoolInvalidate('TestKey1');
        $this->cache->UNITpoolInvalidate('TestKey2');

        $this->assertEquals($this->cacheConnector->get($keys), $data);
        $this->assertEquals($this->cache->get($keys), $data);
    }

    /**
     * Test set() and invalidate() with arrays.
     */
    public function testSetInvalidateArrays()
    {
        $cacheItem = oxNew(CacheItem::class);
        $cacheItem->setData(array('set', 'get', 'non', 'pooled'));

        $data = array('TestKey1' => $cacheItem, 'TestKey2' => $cacheItem);
        $aKeys = array_keys($data);

        $this->cache->set($data);
        $this->cache->invalidate($aKeys);

        $this->assertEquals($this->cacheConnector->get($aKeys), array());
        $this->assertEquals($this->cache->get($aKeys), array());
    }

    /**
     * Test set() and flush() pool only.
     */
    public function testSetFlushPooledOnly()
    {
        $key = 'testKey';
        $cacheItem = oxNew(CacheItem::class);
        $cacheItem->setData(array('set', 'flush', 'pooled', 'only'));

        $this->setConfigParam('sDefaultCacheConnector', null);

        $cache = oxNew(Cache::class);
        $cache->set($key, $cacheItem);
        $cache->invalidate($key);

        $this->assertNull($cache->get($key));
    }

    /**
     * Test set() for existing.
     */
    public function testSetExisting()
    {
        $key = 'testKey';

        $cacheItem = oxNew(CacheItem::class);
        $cacheItem->setData(array('set', 'existing'));

        $this->cache->set($key, $cacheItem);
        $this->assertEquals($this->cache->get($key), $cacheItem);

        $cacheItem2 = oxNew(CacheItem::class);
        $cacheItem2->setData(array('set', 'existing', 'again'));

        $this->cache->set($key, $cacheItem2);
        $this->assertEquals($this->cache->get($key), $cacheItem2);
    }

    /**
     * Test get() for non existing.
     */
    public function testGetNonExisting()
    {
        $key = 'testKey';

        $this->assertNull($this->cacheConnector->get($key));
        $this->assertNull($this->cache->get($key));
    }

    /**
     * Test set(), invalidate(), get().
     */
    public function testSetInvalidateGet()
    {
        $key = 'testKey';
        $cacheItem = oxNew(CacheItem::class);
        $cacheItem->setData(array('set', 'invalidate', 'get'));

        $this->cache->set($key, $cacheItem);
        $this->assertEquals($this->cache->get($key), $cacheItem);

        $this->cache->invalidate($key);
        $this->assertNull($this->cache->get($key));
    }

    /**
     * Test set(), set(), flush().
     */
    public function testSetSetFlush()
    {
        $key1 = 'testKey1';
        $key2 = 'testKey1';

        $data = oxNew(CacheItem::class);
        $data->setData(array('set', 'set', 'flush'));

        $this->cache->set($key1, $data);
        $this->cache->set($key2, $data);

        $this->assertEquals($this->cache->get($key1), $data);
        $this->assertEquals($this->cache->get($key2), $data);

        $this->cache->flush();
        $this->assertNull($this->cache->get($key1));
        $this->assertNull($this->cache->get($key2));
    }

    /**
     * #0005761
     */
    public function testUseDefaultTtl_defined()
    {
        $defaultTtl = 34;
        $this->getConfig()->setConfigParam('iDefaultCacheTTL', $defaultTtl);

        $cacheItem = oxNew(CacheItem::class);
        $cacheItem->setData('value');

        $cacheConnector = $this->getMock(FileCacheConnector::class, array("set"));
        $cacheConnector->expects($this->once())->method('set')->with($this->equalTo('key'), $this->equalTo($cacheItem), $this->equalTo($defaultTtl));

        /** @var Cache|MockObject $oCacheBackend */
        $cacheBackend = $this->getMock(Cache::class, array("_getConnector"));
        $cacheBackend->expects($this->any())->method('_getConnector')->will($this->returnValue($cacheConnector));

        $cacheBackend->set('key', $cacheItem);
    }

    /**
     * #0005761
     */
    public function testUseDefaultTtl_notDefined()
    {
        $cacheItem = oxNew(CacheItem::class);
        $cacheItem->setData('value');

        $cacheConnector = $this->getMock(FileCacheConnector::class, array("set"));
        $cacheConnector->expects($this->once())->method('set')->with($this->equalTo('key'), $this->equalTo($cacheItem), $this->equalTo(0));

        /** @var Cache|MockObject $cacheBackend */
        $cacheBackend = $this->getMock(Cache::class, array("_getConnector"));
        $cacheBackend->expects($this->any())->method('_getConnector')->will($this->returnValue($cacheConnector));

        $cacheBackend->set('key', $cacheItem);
    }

}
