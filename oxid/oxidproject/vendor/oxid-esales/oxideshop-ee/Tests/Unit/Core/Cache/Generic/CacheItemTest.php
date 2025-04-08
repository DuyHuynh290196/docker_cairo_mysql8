<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */
namespace OxidEsales\EshopEnterprise\Tests\Unit\Core\Cache\Generic;

class CacheItemTest extends \oxUnitTestCase
{
    public function setDataProvider()
    {
        return array(
            array(1),
            array('testString'),
            array(0.13),
            array(array(1, 2.01, 'testString'))
        );
    }

    /**
     * testSettingAndGettingDataForCache
     * Test for oxCacheItem::setData(), oxCacheItem::getData()
     *
     * @dataProvider setDataProvider
     *
     * @param mixed $mData Data
     *
     */
    public function testSettingAndGettingDataForCache($mData)
    {
        $oCacheItem = oxNew(\OxidEsales\Eshop\Core\Cache\Generic\CacheItem::class);
        $oCacheItem->setData($mData);

        $this->assertEquals($mData, $oCacheItem->getData());
    }
}
