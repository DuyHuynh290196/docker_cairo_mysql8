<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */
namespace OxidEsales\EshopEnterprise\Tests\Unit\Application\Component\Widget;

/**
 * Tests for ServiceMenu class
 */
class ServiceMenuTest extends \oxUnitTestCase
{
    /**
     * Testing ServiceMenu::isCacheable()
     *
     * @return null
     */
    public function testIsNotCacheable()
    {
        $serviceMenu = oxNew(\OxidEsales\Eshop\Application\Component\Widget\ServiceMenu::class);
        $this->assertFalse($serviceMenu->isCacheable());
    }

    /**
     * Testing ServiceMenu::isCacheable()
     *
     * @return null
     */
    public function testIsCacheable()
    {
        $this->setRequestParameter("nocookie", 1);
        $serviceMenu = oxNew(\OxidEsales\Eshop\Application\Component\Widget\ServiceMenu::class);
        $this->assertTrue($serviceMenu->isCacheable());
    }
}
