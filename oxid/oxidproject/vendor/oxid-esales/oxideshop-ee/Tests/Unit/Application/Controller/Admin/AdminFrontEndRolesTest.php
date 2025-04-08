<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */
namespace OxidEsales\EshopEnterprise\Tests\Unit\Application\Controllers\Admin;

use \PHPUnit\Framework\MockObject\MockObject;

/**
 * Tests for Admin_FERoles class
 */
class Unit_Admin_AdminFERolesTest extends \oxUnitTestCase
{
    /**
     * Admin_FERoles::Render() test case
     *
     * @return null
     */
    public function testRender()
    {
        $oView = oxNew(\OxidEsales\Eshop\Application\Controller\Admin\AdminFrontEndRoles::class);
        $this->assertEquals('admin_feroles.tpl', $oView->render());
    }
}
