<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */
namespace OxidEsales\EshopEnterprise\Tests\Unit\Application\Controller;

use OxidEsales\TestingLibrary\UnitTestCase;

/**
 * Testing MallStart class.
 */
class MallStartTest extends UnitTestCase
{
    /**
     * Test get shop links.
     */
    public function testGetShopLinks()
    {
        $mallStart = $this->getProxyClass('MallStart');

        $shopLinks = $mallStart->getShopLinks();
        $this->assertEquals($this->getConfig()->getShopConfVar('sMallShopURL', 1), $shopLinks[1]);
    }

    /**
     * Test get default shop languages.
     */
    public function testGetShopDefaultLangs()
    {
        $mallStart = $this->getProxyClass('MallStart');
        $this->assertEquals(array('1' => '0'), $mallStart->getShopDefaultLangs());
    }

    /**
     * Test get shop list.
     */
    public function testGetShopList()
    {
        $mallStart = $this->getProxyClass('MallStart');
        $shops = $mallStart->getShopList();
        $this->assertEquals('1', $shops[1]->getId());
    }
}
