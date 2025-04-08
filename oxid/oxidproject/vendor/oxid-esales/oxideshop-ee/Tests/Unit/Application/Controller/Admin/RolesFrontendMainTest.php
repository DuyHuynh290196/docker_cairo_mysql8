<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Tests\Unit\Application\Controller\Admin;

use OxidEsales\TestingLibrary\UnitTestCase;
use oxDb;
use oxTestModules;
use Exception;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Core\UtilsObject;

/**
 * Tests for Roles_FEmain class
 */
class RolesFrontendMainTest extends UnitTestCase
{
    /**
     * Tear down the fixture.
     *
     * @return null
     */
    protected function tearDown(): void
    {
        \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute("delete from oxrolefields where oxid like 'test%'");
        \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute("delete from oxfield2role where oxfieldid like 'test%'");

        parent::tearDown();
    }

    /**
     * Roles_FEmain::Render() test case
     *
     * @return null
     */
    public function testRender()
    {
        // testing..
        $oView = oxNew(\OxidEsales\Eshop\Application\Controller\Admin\RolesFrontendMain::class);
        $this->assertEquals('roles_femain.tpl', $oView->render());
    }

    /**
     * Roles_FEmain::Save() test case
     *
     * @return null
     */
    public function testSave()
    {
        oxTestModules::addFunction('oxrole', 'save', '{ throw new Exception( "save" ); }');
        $this->getConfig()->setConfigParam('blAllowSharedEdit', true);

        // testing..
        try {
            $oView = oxNew(\OxidEsales\Eshop\Application\Controller\Admin\RolesFrontendMain::class);
            $oView->save();
        } catch (Exception $oExcp) {
            $this->assertEquals("save", $oExcp->getMessage(), "error in Roles_FEmain::save()");

            return;
        }
        $this->fail("error in Roles_FEmain::save()");
    }

    /**
     * Roles_FEmain::AddField() test case
     *
     * @return null
     */
    public function testAddField()
    {
        $utilsObject = $this->getMock(UtilsObject::class, array('generateUID'));
        $utilsObject->expects($this->any())->method('generateUID')->will($this->returnValue('testRecord'));
        Registry::set(\OxidEsales\Eshop\Core\UtilsObject::class, $utilsObject);

        oxTestModules::addFunction('oxrole', 'load', '{ return true; }');
        $this->setRequestParameter("oxparam", '&a&a&');

        // testing..
        $oView = oxNew(\OxidEsales\Eshop\Application\Controller\Admin\RolesFrontendMain::class);
        $oView->addField();

        $this->assertEquals("1", \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->getOne("select 1 from oxrolefields where oxid = 'testRecord'"));
    }

    /**
     * Roles_FEmain::DeleteField() test case
     *
     * @return null
     */
    public function testDeleteField()
    {
        $oDb = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();

        $this->setRequestParameter('oxparam', "testRecord");
        $oDb->execute("insert into oxrolefields (`OXID`, `OXNAME`, `OXPARAM`) values ( 'testRecord', 'testName', 'testParam' )");
        $oDb->execute("insert into oxfield2role (`OXFIELDID`, `OXTYPE`, `OXROLEID`, `OXIDX`) values( 'testRecord', 'oxview', 'testRoleId', '0' )");

        // testing..
        $oView = oxNew(\OxidEsales\Eshop\Application\Controller\Admin\RolesFrontendMain::class);
        $oView->deleteField();

        $this->assertFalse($oDb->getOne("select 1 from oxrolefields where oxid = 'testRecord'"));
        $this->assertFalse($oDb->getOne("select 1 from oxfield2role where oxfieldid = 'testRecord'"));
    }
}
