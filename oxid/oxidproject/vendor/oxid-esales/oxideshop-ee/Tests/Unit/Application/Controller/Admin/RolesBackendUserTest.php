<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */
namespace OxidEsales\EshopEnterprise\Tests\Unit\Application\Controller\Admin;

use OxidEsales\TestingLibrary\UnitTestCase;

/**
 * Tests for Roles_BEuser class
 */
class RolesBackendUserTest extends UnitTestCase
{
    /**
     * Roles_BEuser::Render() test case
     *
     * @return null
     */
    public function testRender()
    {
        // testing..
        $oView = oxNew(\OxidEsales\Eshop\Application\Controller\Admin\RolesBackendUser::class);
        $this->assertEquals('roles_beuser.tpl', $oView->render());
    }
}
