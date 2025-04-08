<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

/**
 * Tests for VoucherSerie_Mall class
 */
class VoucherSerieMallTest extends OxidTestCase
{
    /**
     * VoucherSerie_Mall::Render() test case
     *
     * @return null
     */
    public function testRender()
    {
        // testing..
        $oView = oxNew(\OxidEsales\Eshop\Application\Controller\Admin\VoucherSerieMall::class);
        $this->assertEquals('admin_mall.tpl', $oView->render());
    }
}
