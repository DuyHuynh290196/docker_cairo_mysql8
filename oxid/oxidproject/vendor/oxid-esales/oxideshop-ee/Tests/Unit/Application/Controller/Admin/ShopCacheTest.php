<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Tests\Unit\Application\Controller\Admin;

use OxidEsales\TestingLibrary\UnitTestCase;

/**
 * Tests for Shop_Cache class.
 */
class ShopCacheTest extends UnitTestCase
{
    /**
     * Shop_Cache::Render() test case.
     */
    public function testRender()
    {
        $oView = oxNew(\OxidEsales\Eshop\Application\Controller\Admin\ShopCache::class);
        $this->assertEquals('shop_cache.tpl', $oView->render());
    }

    /**
     * Shop_Cache::flushContentCache() test case.
     */
    public function testFlushContentCache()
    {
        $oView = $this->getMock(\OxidEsales\Eshop\Application\Controller\Admin\ShopCache::class, array("resetContentCache"));
        $oView->expects($this->once())->method('resetContentCache')->with($this->equalTo(true));
        $oView->flushContentCache();
    }
}
