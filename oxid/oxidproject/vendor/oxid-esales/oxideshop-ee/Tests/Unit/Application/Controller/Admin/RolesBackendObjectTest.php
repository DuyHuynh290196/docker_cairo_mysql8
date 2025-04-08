<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */
namespace OxidEsales\EshopEnterprise\Tests\Unit\Application\Controller\Admin;

use OxidEsales\TestingLibrary\UnitTestCase;
use oxTestModules;
use Exception;
use DOMDocument;

/**
 * Tests for Roles_BEobject class
 */
class RolesBackendObjectTest extends UnitTestCase
{
    /**
     * Roles_BEobject::Render() test case
     *
     * @return null
     */
    public function testRender()
    {
        $oNavTree = $this->getMock(\OxidEsales\Eshop\Application\Controller\Admin\NavigationTree::class, array("getDomXml"));
        $oNavTree->expects($this->any())->method('getDomXml')->will($this->returnValue(new DOMDocument));

        // testing..
        $oView = $this->getMock(\OxidEsales\Eshop\Application\Controller\Admin\RolesBackendObject::class, array("getNavigation", "getRights"));
        $oView->expects($this->any())->method('getNavigation')->will($this->returnValue($oNavTree));
        $oView->expects($this->any())->method('getRights')->will($this->returnValue(oxNew(\OxidEsales\Eshop\Core\AdminRights::class)));
        $this->assertEquals('roles_beobject.tpl', $oView->render());
    }

    /**
     * Roles_BEobject::Save() test case
     *
     * @return null
     */
    public function testSave()
    {
        oxTestModules::addFunction('oxrole', 'save', '{ throw new Exception( "save" ); }');
        $this->getConfig()->setConfigParam('blAllowSharedEdit', true);

        // testing..
        try {
            $oView = oxNew(\OxidEsales\Eshop\Application\Controller\Admin\RolesBackendObject::class);
            $oView->save();
        } catch (Exception $oExcp) {
            $this->assertEquals("save", $oExcp->getMessage(), "error in Roles_BEobject::save()");

            return;
        }
        $this->fail("error in Roles_BEobject::save()");
    }
}
