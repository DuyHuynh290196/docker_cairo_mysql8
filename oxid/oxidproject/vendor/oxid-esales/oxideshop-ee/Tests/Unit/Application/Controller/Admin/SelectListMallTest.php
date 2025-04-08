<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Tests\Unit\Application\Controller\Admin;

use OxidEsales\TestingLibrary\UnitTestCase;

/**
 * Tests for SelectList_Mall class.
 */
class SelectListMallTest extends UnitTestCase
{
    /**
     * SelectList_Mall::Render() test case.
     */
    public function testRender()
    {
        $view = $this->getProxyClass("SelectList_Mall");
        $this->assertEquals('oxselectlist', $view->getNonPublicVar("_sMallTable"));
        $this->assertEquals('admin_mall.tpl', $view->render());
    }
}
