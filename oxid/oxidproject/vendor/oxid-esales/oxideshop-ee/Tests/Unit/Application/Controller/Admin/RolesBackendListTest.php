<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */
namespace OxidEsales\EshopEnterprise\Tests\Unit\Application\Controller\Admin;

use OxidEsales\TestingLibrary\UnitTestCase;
use oxTestModules;
use Exception;

/**
 * Tests for Roles_BElist class
 */
class RolesBackendListTest extends UnitTestCase
{
    /**
     * Roles_BElist::Render() test case
     *
     * @return null
     */
    public function testRender()
    {
        // testing..
        $oView = oxNew(\OxidEsales\Eshop\Application\Controller\Admin\RolesBackendList::class);
        $this->assertEquals('roles_belist.tpl', $oView->render());
    }

    /**
     * Roles_BElist::DeleteEntry() test case
     *
     * @return null
     */
    public function testDeleteEntry()
    {
        $this->getConfig()->setConfigParam("blAllowSharedEdit", true);
        oxTestModules::addFunction('oxrole', 'delete', '{ throw new Exception( "delete" ); }');
        oxTestModules::addFunction('oxrole', 'isDerived', '{ return false; }');

        // testing..
        try {
            $oView = oxNew(\OxidEsales\Eshop\Application\Controller\Admin\RolesBackendList::class);
            $oView->deleteEntry();
        } catch (Exception $oExcp) {
            $this->assertEquals("delete", $oExcp->getMessage(), "error in Roles_BElist::deleteEntry()");

            return;
        }
        $this->fail("error in Roles_BElist::deleteEntry()");
    }

    /**
     * Roles_BElist::BuildWhere() test case
     *
     * @return null
     */
    public function testBuildWhere()
    {
        // testing..
        $oView = oxNew(\OxidEsales\Eshop\Application\Controller\Admin\RolesBackendList::class);
        $aWhere = $oView->buildWhere();

        $this->assertTrue(isset($aWhere["oxroles.oxarea"]));
        $this->assertEquals("0", $aWhere["oxroles.oxarea"]);
        $this->assertTrue(isset($aWhere["oxroles.oxshopid"]));
        $this->assertEquals($this->getConfig()->getShopId(), $aWhere["oxroles.oxshopid"]);
    }
}
