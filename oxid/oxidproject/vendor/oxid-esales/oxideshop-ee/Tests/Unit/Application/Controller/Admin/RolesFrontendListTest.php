<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */
namespace OxidEsales\EshopEnterprise\Tests\Unit\Application\Controller\Admin;

use OxidEsales\TestingLibrary\UnitTestCase;

/**
 * Tests for Roles_FElist class
 */
class RolesFrontendListTest extends UnitTestCase
{
    /**
     * Roles_FElist::BuildWhere() test case
     *
     * @return null
     */
    public function testBuildWhere()
    {
        // testing..
        $oView = oxNew(\OxidEsales\Eshop\Application\Controller\Admin\RolesFrontendList::class);
        $aWhere = $oView->buildWhere();

        $this->assertTrue(isset($aWhere["oxroles.oxarea"]));
        $this->assertEquals("1", $aWhere["oxroles.oxarea"]);
        $this->assertTrue(isset($aWhere["oxroles.oxshopid"]));
        $this->assertEquals($this->getConfig()->getShopId(), $aWhere["oxroles.oxshopid"]);
    }

    /**
     * Roles_FElist::Render() test case
     *
     * @return null
     */
    public function testRender()
    {
        // testing..
        $oView = oxNew(\OxidEsales\Eshop\Application\Controller\Admin\RolesFrontendList::class);
        $this->assertEquals('roles_felist.tpl', $oView->render());
    }
}
