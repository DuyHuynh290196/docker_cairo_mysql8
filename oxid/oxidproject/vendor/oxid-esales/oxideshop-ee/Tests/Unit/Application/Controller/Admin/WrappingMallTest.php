<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

/**
 * Tests for Wrapping_Mall class
 */
class WrappingMallTest extends OxidTestCase
{
    /**
     * Wrapping_Mall::Render() test case
     *
     * @return null
     */
    public function testRender()
    {
        // testing..
        $oView = oxNew(\OxidEsales\Eshop\Application\Controller\Admin\WrappingMall::class);
        $this->assertEquals('admin_mall.tpl', $oView->render());
    }
}
