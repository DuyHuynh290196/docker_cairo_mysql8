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
class RolesBackendUserAjaxTest extends UnitTestCase
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

        \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute("insert into oxobject2role set oxid='_testRoleRemoveAll1', oxroleid='_testRoleRemoveAll', oxobjectid='_testUser1', oxtype = 'oxuser'");
        \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute("insert into oxobject2role set oxid='_testRoleRemoveAll2', oxroleid='_testRoleRemoveAll', oxobjectid='_testUser2', oxtype = 'oxuser'");
        \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute("insert into oxobject2role set oxid='_testRoleRemoveAll3', oxroleid='_testRoleRemoveAll', oxobjectid='_testUser3', oxtype = 'oxuser'");

        \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute("insert into oxuser set oxid='_testUser1', oxusername='_testUser1', oxactive=1");
        \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute("insert into oxuser set oxid='_testUser2', oxusername='_testUser2', oxactive=1");
        \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute("insert into oxuser set oxid='_testUser3', oxusername='_testUser3', oxactive=1");
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

        \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute("delete from oxuser where oxid='_testUser1'");
        \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute("delete from oxuser where oxid='_testUser2'");
        \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute("delete from oxuser where oxid='_testUser3'");

        parent::tearDown();
    }

    /**
     * RolesBeUsersAjax::_getQuery() test case
     *
     * @return null
     */
    public function testGetQuery()
    {
        $oView = oxNew(\OxidEsales\Eshop\Application\Controller\Admin\RolesBackendUserAjax::class);
        $this->assertEquals("from oxuser where 1  and (oxshopid = '1' OR oxrights='malladmin')", trim($oView->UNITgetQuery()));
    }

    /**
     * RolesBeUsersAjax::_getQuery() test case
     *
     * @return null
     */
    public function testGetQueryMallUsers()
    {
        $this->setRequestParameter("blMallUsers", true);
        $oView = oxNew(\OxidEsales\Eshop\Application\Controller\Admin\RolesBackendUserAjax::class);
        $this->assertEquals("from oxuser where 1", trim($oView->UNITgetQuery()));
    }

    /**
     * RolesBeUsersAjax::_getQuery() test case
     *
     * @return null
     */
    public function testGetQuerySynchoxid()
    {
        $sSynchoxid = '_testAction';
        $this->setRequestParameter("synchoxid", $sSynchoxid);

        $oView = oxNew(\OxidEsales\Eshop\Application\Controller\Admin\RolesBackendUserAjax::class);
        $this->assertEquals("from oxuser where 1  and (oxshopid = '1' OR oxrights='malladmin') and oxuser.oxid not in ( select oxobject2role.oxobjectid  from oxobject2role where oxobject2role.oxtype = 'oxuser' and oxobject2role.oxroleid = '" . $sSynchoxid . "' )", trim($oView->UNITgetQuery()));
    }

    /**
     * RolesBeUsersAjax::_getQuery() test case
     *
     * @return null
     */
    public function testGetQuerySynchoxidMallUsers()
    {
        $this->setRequestParameter("blMallUsers", true);
        $sSynchoxid = '_testAction';
        $this->setRequestParameter("synchoxid", $sSynchoxid);

        $oView = oxNew(\OxidEsales\Eshop\Application\Controller\Admin\RolesBackendUserAjax::class);
        $this->assertEquals("from oxuser where 1  and oxuser.oxid not in ( select oxobject2role.oxobjectid  from oxobject2role where oxobject2role.oxtype = 'oxuser' and oxobject2role.oxroleid = '" . $sSynchoxid . "' )", trim($oView->UNITgetQuery()));
    }

    /**
     * RolesBeUsersAjax::_getQuery() test case
     *
     * @return null
     */
    public function testGetQueryOxid()
    {
        $sOxid = '_testAction';
        $this->setRequestParameter("oxid", $sOxid);

        $oView = oxNew(\OxidEsales\Eshop\Application\Controller\Admin\RolesBackendUserAjax::class);
        $this->assertEquals("from oxobject2role, oxuser where oxobject2role.oxtype = 'oxuser' and  oxobject2role.oxroleid = '" . $sOxid . "' and oxuser.oxid=oxobject2role.oxobjectid", trim($oView->UNITgetQuery()));
    }

    /**
     * RolesBeUsersAjax::_getQuery() test case
     *
     * @return null
     */
    public function testGetQueryOxidSynchoxid()
    {
        $sOxid = '_testAction';
        $sSynchoxid = '_testActionSynch';
        $this->setRequestParameter("oxid", $sOxid);
        $this->setRequestParameter("synchoxid", $sSynchoxid);

        $oView = oxNew(\OxidEsales\Eshop\Application\Controller\Admin\RolesBackendUserAjax::class);
        $this->assertEquals("from oxobject2group inner join oxuser on oxuser.oxid = oxobject2group.oxobjectid  where oxobject2group.oxgroupsid = '" . $sOxid . "' and oxuser.oxshopid = '1'  and oxuser.oxid not in ( select oxobject2role.oxobjectid  from oxobject2role where oxobject2role.oxtype = 'oxuser' and oxobject2role.oxroleid = '" . $sSynchoxid . "' )", trim($oView->UNITgetQuery()));
    }

    /**
     * RolesBeUsersAjax::_getQuery() test case
     *
     * @return null
     */
    public function testGetQueryOxidSynchoxidMallUsers()
    {
        $this->setRequestParameter("blMallUsers", true);
        $sOxid = '_testAction';
        $sSynchoxid = '_testActionSynch';
        $this->setRequestParameter("oxid", $sOxid);
        $this->setRequestParameter("synchoxid", $sSynchoxid);

        $oView = oxNew(\OxidEsales\Eshop\Application\Controller\Admin\RolesBackendUserAjax::class);
        $this->assertEquals("from oxobject2group inner join oxuser on oxuser.oxid = oxobject2group.oxobjectid  where oxobject2group.oxgroupsid = '" . $sOxid . "' and oxuser.oxid not in ( select oxobject2role.oxobjectid  from oxobject2role where oxobject2role.oxtype = 'oxuser' and oxobject2role.oxroleid = '" . $sSynchoxid . "' )", trim($oView->UNITgetQuery()));
    }

    /**
     * RolesBeUsersAjax::removeUserFromBeroles() test case
     *
     * @return null
     */
    public function testRemoveUserFromBeroles()
    {
        $oView = $this->getMock(\OxidEsales\Eshop\Application\Controller\Admin\RolesBackendUserAjax::class, array("_getActionIds"));
        $oView->expects($this->any())->method('_getActionIds')->will($this->returnValue(array('_testRoleRemove1', '_testRoleRemove2')));

        $sSql = "select count(oxid) from oxobject2role where oxid in ('_testRoleRemove1', '_testRoleRemove2')";
        $this->assertEquals(2, \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->getOne($sSql));
        $oView->removeUserFromBeroles();
        $this->assertEquals(0, \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->getOne($sSql));
    }

    /**
     * RolesBeUsersAjax::removeUserFromBeroles() test case
     *
     * @return null
     */
    public function testRemoveUserFromBerolesAll()
    {
        $sOxid = '_testRoleRemoveAll';
        $this->setRequestParameter("oxid", $sOxid);
        $this->setRequestParameter("all", true);

        $sSql = "select count(oxid) from oxobject2role where oxroleid = '" . $sOxid . "'";
        $oView = oxNew(\OxidEsales\Eshop\Application\Controller\Admin\RolesBackendUserAjax::class);
        $this->assertEquals(3, \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->getOne($sSql));
        $oView->removeUserFromBeroles();
        $this->assertEquals(0, \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->getOne($sSql));
    }

    /**
     * RolesBeUsersAjax::addUserToBeroles() test case
     *
     * @return null
     */
    public function testAddUserToBeroles()
    {
        $sSynchoxid = '_testRoleAdd';
        $this->setRequestParameter("synchoxid", $sSynchoxid);

        $sSql = "select count(oxid) from oxobject2role where oxroleid='$sSynchoxid'";
        $this->assertEquals(0, \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->getOne($sSql));

        $oView = $this->getMock(\OxidEsales\Eshop\Application\Controller\Admin\RolesBackendUserAjax::class, array("_getActionIds"));
        $oView->expects($this->any())->method('_getActionIds')->will($this->returnValue(array('_testRoleAdd1', '_testRoleAdd2')));

        $oView->addUserToBeroles();
        $this->assertEquals(2, \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->getOne($sSql));
    }

    /**
     * RolesBeUsersAjax::addUserToBeroles() test case
     *
     * @return null
     */
    public function testAddUserToBerolesAll()
    {
        $sSynchoxid = '_testRoleAddAll';
        $this->setRequestParameter("synchoxid", $sSynchoxid);
        $this->setRequestParameter("all", true);

        //count how much articles gets filtered
        $iCount = \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->getOne("select count(oxuser.oxid) from oxuser where 1  and oxshopid = '1'  and oxuser.oxid not in ( select oxobject2role.oxobjectid  from oxobject2role where oxobject2role.oxtype = 'oxuser' and oxobject2role.oxroleid = '" . $sSynchoxid . "' )");

        $sSql = "select count(oxid) from oxobject2role where oxroleid='$sSynchoxid'";
        $this->assertEquals(0, \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->getOne($sSql));

        $oView = $this->getMock(\OxidEsales\Eshop\Application\Controller\Admin\RolesBackendUserAjax::class, array("_getActionIds"));
        $oView->expects($this->any())->method('_getActionIds')->will($this->returnValue(array('_testRoleAdd1', '_testRoleAdd2')));

        $oView->addUserToBeroles();
        $this->assertEquals($iCount, \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->getOne($sSql));
    }
}
