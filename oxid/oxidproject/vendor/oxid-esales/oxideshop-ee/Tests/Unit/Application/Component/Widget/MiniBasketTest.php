<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */
namespace OxidEsales\EshopEnterprise\Tests\Unit\Application\Component\Widget;

/**
 * Tests for MiniBasket class
 */
class MiniBasketTest extends \oxUnitTestCase
{
    /**
     * Testing oxwMiniBasket::isCacheable()
     *
     * @return null
     */
    public function testIsNotCacheable()
    {
        $miniBasket = oxNew(\OxidEsales\Eshop\Application\Component\Widget\MiniBasket::class);
        $this->assertFalse($miniBasket->isCacheable());
    }

    /**
     * Testing oxwMiniBasket::isCacheable()
     *
     * @return null
     */
    public function testIsCacheable()
    {
        $this->setRequestParameter("nocookie", 1);
        $miniBasket = oxNew(\OxidEsales\Eshop\Application\Component\Widget\MiniBasket::class);
        $this->assertTrue($miniBasket->isCacheable());
    }
}
