<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */
namespace OxidEsales\EshopEnterprise\Tests\Unit\Core\Cache\Generic\Connector;

class FileCacheConnectorTest extends \oxUnitTestCase
{
    /**
     * Calling parent constructor, to fix possible problems with dataprovider
     *
     * @param  string $name
     * @param  array  $data
     * @param  string $dataName
     */
    public function __construct($name = null, array $data = array(), $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
    }

    public static function cacheDataProvider()
    {
        // key and value
        return array(
            array('key_1', 1),
            array('key_2', 'testString'),
            array('key_3', 0.13),
            array('key_4', array(1, 2.01, 'testString'))
        );
    }

    /**
     * Test for data storing to and getting from cache
     *
     * @dataProvider cacheDataProvider
     *
     * @param string $sKey   Key
     * @param mixed  $mValue Value
     */
    public function testSettingAndGettingData($sKey, $mValue)
    {
        $oCacheConnector = oxNew(\OxidEsales\Eshop\Core\Cache\Generic\Connector\FileCacheConnector::class);
        $oCacheItem = oxNew(\OxidEsales\Eshop\Core\Cache\Generic\CacheItem::class);
        $oCacheItem->setData($mValue);
        $oCacheConnector->set($sKey, $oCacheItem);
        $this->assertEquals($mValue, $oCacheConnector->get($sKey)->getData());
    }

    /**
     * Test get expired
     */
    public function testGetExpired()
    {
        $oCacheConnector = oxNew(\OxidEsales\Eshop\Core\Cache\Generic\Connector\FileCacheConnector::class);
        $oCacheItem = oxNew(\OxidEsales\Eshop\Core\Cache\Generic\CacheItem::class);
        $oCacheConnector->set('expired_key', $oCacheItem, -3600);
        $this->assertNull($oCacheConnector->get('expired_key'));
    }

    /**
     * Test get not expired
     */
    public function testGetNotExpired()
    {
        $oCacheConnector = oxNew(\OxidEsales\Eshop\Core\Cache\Generic\Connector\FileCacheConnector::class);
        $oCacheItem = oxNew(\OxidEsales\Eshop\Core\Cache\Generic\CacheItem::class);
        $oCacheConnector->set('non_expired_key', $oCacheItem, 3600);
        $this->assertEquals($oCacheItem, $oCacheConnector->get('non_expired_key'));
    }

    /**
     * Test get  not existing data
     */
    public function testGetNotExistingData()
    {
        $oCacheConnector = oxNew(\OxidEsales\Eshop\Core\Cache\Generic\Connector\FileCacheConnector::class);
        $this->assertNull($oCacheConnector->get('not_exist_key'));
    }

    /**
     * Test Set and get cache directory
     */
    public function testSetAndGetCacheDirectory()
    {
        $oCacheConnector = oxNew(\OxidEsales\Eshop\Core\Cache\Generic\Connector\FileCacheConnector::class);
        $oCacheConnector->setCacheDir('tmp');
        $this->assertEquals('tmp', $oCacheConnector->getCacheDir());
    }

    /**
     * Test invalidate cache
     */
    public function testInvalidateFromCache()
    {
        $oCacheConnector = oxNew(\OxidEsales\Eshop\Core\Cache\Generic\Connector\FileCacheConnector::class);
        $oCacheConnector->flush();
        $oCacheItem = oxNew(\OxidEsales\Eshop\Core\Cache\Generic\CacheItem::class);
        $oCacheItem->setData('del_data');
        $oCacheConnector->set('del_key', $oCacheItem);
        $oCacheItem->setData('stay_data');
        $oCacheConnector->set('stay_key', $oCacheItem);

        $oCacheConnector->invalidate('del_key');

        $this->assertEquals('stay_data', $oCacheConnector->get('stay_key')->getData());
        $this->assertNull($oCacheConnector->get('del_key'));
    }

    /**
     * Test for cache flush
     */
    public function testFlushCache()
    {
        $oCacheConnector = oxNew(\OxidEsales\Eshop\Core\Cache\Generic\Connector\FileCacheConnector::class);
        $oCacheItem = oxNew(\OxidEsales\Eshop\Core\Cache\Generic\CacheItem::class);
        $oCacheItem->setData('data');
        $oCacheConnector->set('key1', $oCacheItem);
        $oCacheItem->setData('data');
        $oCacheConnector->set('key2', $oCacheItem);

        $oCacheConnector->flush();

        $this->assertNull($oCacheConnector->get('key1'));
        $this->assertNull($oCacheConnector->get('key2'));

        $aFiles = glob(getShopBasePath() . $oCacheConnector->getCacheDir() . '/*');
        $this->assertEquals(0, count($aFiles));
    }
}
