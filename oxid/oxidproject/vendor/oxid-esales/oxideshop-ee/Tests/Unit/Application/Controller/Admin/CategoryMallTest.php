<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

/**
 * Tests for Category_Mall class
 */
class Unit_Admin_CategoryMallTest extends OxidTestCase
{
    /**
     * Category_Mall::Render() test case
     */
    public function testRender()
    {
        // testing..
        $oView = oxNew(\OxidEsales\Eshop\Application\Controller\Admin\CategoryMall::class);
        $this->assertEquals('category_mall_nonparent.tpl', $oView->render());
    }
}
