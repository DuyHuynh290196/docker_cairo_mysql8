<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */
namespace OxidEsales\EshopEnterprise\Tests\Unit\Application\Controller\Admin;

use OxidEsales\TestingLibrary\UnitTestCase;
use oxDb;

/**
 * Tests for Roles_Begroups_Ajax class
 */
class RolesFrontendGroupsAjaxTest extends UnitTestCase
{
    /**
     * Initialize the fixture.
     *
     * @return null
     */
    protected function setUp(): void
    {
        parent::setUp();

        \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute("insert into oxobject2role set oxid='_testRoleRemove1', oxobjectid='_testRoleRemove'");
        \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute("insert into oxobject2role set oxid='_testRoleRemove2', oxobjectid='_testRoleRemove'");

        \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute("insert into oxobject2role set oxid='_testRoleRemoveAll1', oxroleid='_testRoleRemoveAll', oxobjectid='_testGroup1', oxtype = 'oxgroups'");
        \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute("insert into oxobject2role set oxid='_testRoleRemoveAll2', oxroleid='_testRoleRemoveAll', oxobjectid='_testGroup2', oxtype = 'oxgroups'");
        \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute("insert into oxobject2role set oxid='_testRoleRemoveAll3', oxroleid='_testRoleRemoveAll', oxobjectid='_testGroup3', oxtype = 'oxgroups'");

        \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute("insert into oxgroups set oxid='_testGroup1', oxtitle='_testGroup1', oxactive=1");
        \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute("insert into oxgroups set oxid='_testGroup2', oxtitle='_testGroup2', oxactive=1");
        \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute("insert into oxgroups set oxid='_testGroup3', oxtitle='_testGroup3', oxactive=1");
    }

    /**
     * Tear down the fixture.
     *
     * @return null
     */
    protected function tearDown(): void
    {
        \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute("delete from oxobject2role where oxobjectid='_testRoleRemove'");
        \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute("delete from oxobject2role where oxroleid='_testRoleRemoveAll'");

        \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute("delete from oxobject2role where oxroleid='_testRoleAdd'");
        \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute("delete from oxobject2role where oxroleid='_testRoleAddAll'");

        \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute("delete from oxgroups where oxid='_testGroup1'");
        \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute("delete from oxgroups where oxid='_testGroup2'");
        \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute("delete from oxgroups where oxid='_testGroup3'");

        parent::tearDown();
    }

    /**
     * RolesBeGroupsAjax::_getQuery() test case
     *
     * @return null
     */
    public function testGetQuery()
    {
        $oView = oxNew(\OxidEsales\Eshop\Application\Controller\Admin\RolesBackendGroupsAjax::class);
        $this->assertEquals("FROM oxv_oxgroups_de WHERE 1", trim($oView->UNITgetQuery()));
    }

    /**
     * RolesBeGroupsAjax::_getQuery() test case
     *
     * @return null
     */
    public function testGetQuerySynchoxid()
    {
        $sSynchoxid = '_testAction';
        $this->setRequestParameter("synchoxid", $sSynchoxid);

        $oView = oxNew(\OxidEsales\Eshop\Application\Controller\Admin\RolesBackendGroupsAjax::class);
        $expectedQuery =
            "FROM oxv_oxgroups_de WHERE 1  AND oxv_oxgroups_de.oxid not in (
                SELECT oxobject2role.oxobjectid
                FROM oxobject2role
                WHERE oxobject2role.oxtype = 'oxgroups'
                AND oxobject2role.oxroleid = '$sSynchoxid' )";
        $this->assertEquals($expectedQuery, trim($oView->UNITgetQuery()));
    }

    /**
     * RolesBeGroupsAjax::_getQuery() test case
     *
     * @return null
     */
    public function testGetQueryOxid()
    {
        $sOxid = '_testAction';
        $this->setRequestParameter("oxid", $sOxid);

        $oView = oxNew(\OxidEsales\Eshop\Application\Controller\Admin\RolesBackendGroupsAjax::class);
        $expectedQuery =
            "FROM oxobject2role, oxv_oxgroups_de
                WHERE oxobject2role.oxtype = 'oxgroups'
                AND oxobject2role.oxroleid = '$sOxid'
                AND oxv_oxgroups_de.oxid=oxobject2role.oxobjectid";
        $this->assertEquals($expectedQuery, trim($oView->UNITgetQuery()));
    }

    /**
     * RolesBeGroupsAjax::_getQuery() test case
     *
     * @return null
     */
    public function testGetQueryOxidSynchoxid()
    {
        $sOxid = '_testAction';
        $sSynchoxid = '_testActionSynch';
        $this->setRequestParameter("oxid", $sOxid);
        $this->setRequestParameter("synchoxid", $sSynchoxid);

        $oView = oxNew(\OxidEsales\Eshop\Application\Controller\Admin\RolesBackendGroupsAjax::class);
        $expectedQuery =
            "FROM oxobject2role, oxv_oxgroups_de
                WHERE oxobject2role.oxtype = 'oxgroups'
                AND oxobject2role.oxroleid = '$sOxid'
                AND oxv_oxgroups_de.oxid=oxobject2role.oxobjectid  AND oxv_oxgroups_de.oxid not in (
                SELECT oxobject2role.oxobjectid
                FROM oxobject2role
                WHERE oxobject2role.oxtype = 'oxgroups'
                AND oxobject2role.oxroleid = '$sSynchoxid' )";
        $this->assertEquals($expectedQuery, trim($oView->UNITgetQuery()));
    }

    /**
     * RolesBeGroupsAjax::removeGroupFromBeroles() test case
     *
     * @return null
     */
    public function testRemoveGroupFromBeroles()
    {
        $oView = $this->getMock(\OxidEsales\Eshop\Application\Controller\Admin\RolesBackendGroupsAjax::class, array("_getActionIds"));
        $oView->expects($this->any())->method('_getActionIds')->will($this->returnValue(array('_testRoleRemove1', '_testRoleRemove2')));

        $sSql = "select count(oxid) FROM oxobject2role WHERE oxid IN ('_testRoleRemove1', '_testRoleRemove2')";
        $this->assertEquals(2, \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->getOne($sSql));
        $oView->removeGroupFromBeroles();
        $this->assertEquals(0, \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->getOne($sSql));
    }

    /**
     * RolesBeGroupsAjax::removeGroupFromBeroles() test case
     *
     * @return null
     */
    public function testRemoveGroupFromBerolesAll()
    {
        $sOxid = '_testRoleRemoveAll';
        $this->setRequestParameter("oxid", $sOxid);
        $this->setRequestParameter("all", true);

        $sSql = "select count(oxid) FROM oxobject2role WHERE oxroleid = '" . $sOxid . "'";
        $oView = oxNew(\OxidEsales\Eshop\Application\Controller\Admin\RolesBackendGroupsAjax::class);
        $this->assertEquals(3, \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->getOne($sSql));
        $oView->removeGroupFromBeroles();
        $this->assertEquals(0, \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->getOne($sSql));
    }

    /**
     * RolesBeGroupsAjax::addGroupToBeroles() test case
     *
     * @return null
     */
    public function testAddGroupToBeroles()
    {
        $sSynchoxid = '_testRoleAdd';
        $this->setRequestParameter("synchoxid", $sSynchoxid);

        $sSql = "select count(oxid) FROM oxobject2role WHERE oxroleid='$sSynchoxid'";
        $this->assertEquals(0, \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->getOne($sSql));

        $oView = $this->getMock(\OxidEsales\Eshop\Application\Controller\Admin\RolesBackendGroupsAjax::class, array("_getActionIds"));
        $oView->expects($this->any())->method('_getActionIds')->will($this->returnValue(array('_testRoleAdd1', '_testRoleAdd2')));

        $oView->addGroupToBeroles();
        $this->assertEquals(2, \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->getOne($sSql));
    }

    /**
     * RolesBeGroupsAjax::addGroupToBeroles() test case
     *
     * @return null
     */
    public function testAddGroupToBerolesAll()
    {
        $sSynchoxid = '_testRoleAddAll';
        $this->setRequestParameter("synchoxid", $sSynchoxid);
        $this->setRequestParameter("all", true);

        //count how much articles gets filtered
        $iCount = \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->getOne("select count(oxv_oxgroups_de.oxid) from oxv_oxgroups_de where 1  and oxv_oxgroups_de.oxid not in (  select oxobject2role.oxobjectid from oxobject2role where oxobject2role.oxtype = 'oxgroups' and  oxobject2role.oxroleid = '" . $sSynchoxid . "' )");

        $sSql = "select count(oxid) from oxobject2role where oxroleid='$sSynchoxid'";
        $this->assertEquals(0, \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->getOne($sSql));

        $oView = $this->getMock(\OxidEsales\Eshop\Application\Controller\Admin\RolesBackendGroupsAjax::class, array("_getActionIds"));
        $oView->expects($this->any())->method('_getActionIds')->will($this->returnValue(array('_testRoleAdd1', '_testRoleAdd2')));

        $oView->addGroupToBeroles();
        $this->assertEquals($iCount, \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->getOne($sSql));
    }
}
