<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Tests\Unit\Application\Controller\Admin;

use OxidEsales\TestingLibrary\UnitTestCase;

/**
 * Tests for Roles_FEuser class
 */
class RolesFrontendUserTest extends UnitTestCase
{
    /**
     * Roles_FEuser::Render() test case.
     */
    public function testRender()
    {
        $oView = oxNew(\OxidEsales\Eshop\Application\Controller\Admin\RolesFrontendUser::class);
        $this->assertEquals('roles_feuser.tpl', $oView->render());
    }
}
