<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Tests\Unit\Application\Controller\Admin;

use OxidEsales\TestingLibrary\UnitTestCase;

/**
 * Tests for News_Mall class.
 */
class Unit_Admin_NewsMallTest extends UnitTestCase
{
    /**
     * News_Mall::Render() test case.
     */
    public function testRender()
    {
        $oView = $this->getProxyClass("News_Mall");
        $this->assertEquals('oxnews', $oView->getNonPublicVar("_sMallTable"));
        $this->assertEquals('admin_mall.tpl', $oView->render());
    }
}
