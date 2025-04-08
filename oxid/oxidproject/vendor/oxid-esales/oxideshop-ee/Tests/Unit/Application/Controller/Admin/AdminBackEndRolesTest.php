<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */
namespace OxidEsales\EshopEnterprise\Tests\Unit\Application\Controllers\Admin;

use \PHPUnit\Framework\MockObject\MockObject;

/**
 * Tests for Admin_BERoles class
 */
class AdminBackEndRolesTest extends \oxUnitTestCase
{
    /**
     * Admin_BERoles::Render() test case
     *
     * @return null
     */
    public function testRender()
    {
        $oView = oxNew(\OxidEsales\Eshop\Application\Controller\Admin\AdminBackEndRoles::class);
        $this->assertEquals('admin_beroles.tpl', $oView->render());
    }
}
