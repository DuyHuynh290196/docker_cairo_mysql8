<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */
namespace OxidEsales\EshopEnterprise\Tests\Unit\Application\Controller\Admin;

use OxidEsales\TestingLibrary\UnitTestCase;
use oxDb;

class ArticleRightsVisibleAjaxTest extends UnitTestCase
{
    /**
     * Initialize the fixture.
     *
     * @return null
     */
    protected function setUp(): void
    {
        parent::setUp();

        \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute("insert into oxobjectrights set oxid='_testObjectRightsRemove', oxobjectid='_testObjectRemove', oxgroupidx='3', oxaction=1");
        \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute("insert into oxobjectrights set oxid='_testObjectRightsRemoveAll', oxobjectid='_testObjectRemoveAll', oxgroupidx='1', oxaction=1");
    }

    /**
     * Tear down the fixture.
     *
     * @return null
     */
    protected function tearDown(): void
    {
        \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute("delete from oxobjectrights where oxid='_testObjectRightsRemove'");
        \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute("delete from oxobjectrights where oxid='_testObjectRightsRemoveAll'");

        \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute("delete from oxobjectrights where oxobjectid='_testObjectAdd'");
        \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute("delete from oxobjectrights where oxobjectid='_testObjectAddAll'");

        parent::tearDown();
    }

    /**
     * ArticleRightsVisibleAjax::_getQuery() test case
     *
     * @return null
     */
    public function testGetQuery()
    {
        $oView = oxNew(\OxidEsales\Eshop\Application\Controller\Admin\ArticleRightsVisibleAjax::class);
        $this->assertEquals("from oxv_oxgroups_de where 1", trim($oView->UNITgetQuery()));
    }

    /**
     * ArticleRightsVisibleAjax::_getQuery() test case
     *
     * @return null
     */
    public function testGetQueryOxid()
    {
        $sOxid = '_testArticleRightsVisibleOxid';
        $this->setRequestParameter("oxid", $sOxid);

        $oView = oxNew(\OxidEsales\Eshop\Application\Controller\Admin\ArticleRightsVisibleAjax::class);
        $this->assertEquals("from oxv_oxgroups_de, oxobjectrights where  oxobjectrights.oxobjectid = '$sOxid' and  oxobjectrights.oxoffset = (oxv_oxgroups_de.oxrrid div 31) and  oxobjectrights.oxgroupidx & (1 << (oxv_oxgroups_de.oxrrid mod 31)) and oxobjectrights.oxaction = 1", trim($oView->UNITgetQuery()));
    }

    /**
     * ArticleRightsVisibleAjax::_getQuery() test case
     *
     * @return null
     */
    public function testGetQuerySynchoxid()
    {
        $sSynchoxid = '_testArticleRightsVisibleSynchoxid';
        $this->setRequestParameter("synchoxid", $sSynchoxid);

        $oView = oxNew(\OxidEsales\Eshop\Application\Controller\Admin\ArticleRightsVisibleAjax::class);
        $this->assertEquals("from oxv_oxgroups_de left join oxobjectrights on  oxobjectrights.oxoffset = ( oxv_oxgroups_de.oxrrid div 31 ) and  oxobjectrights.oxgroupidx & (1 << ( oxv_oxgroups_de.oxrrid mod 31 ) ) and oxobjectrights.oxobjectid= '$sSynchoxid' and oxobjectrights.oxaction = 1  where oxobjectrights.oxobjectid != '$sSynchoxid' or ( oxobjectid is null )", trim($oView->UNITgetQuery()));
    }

    /**
     * ArticleRightsVisibleAjax::_getQuery() test case
     *
     * @return null
     */
    public function testGetQueryOxidSynchoxid()
    {
        $sOxid = '_testArticleRightsVisibleOxid';
        $this->setRequestParameter("oxid", $sOxid);
        $sSynchoxid = '_testArticleRightsBuyableSynchoxid';
        $this->setRequestParameter("synchoxid", $sSynchoxid);

        $oView = oxNew(\OxidEsales\Eshop\Application\Controller\Admin\ArticleRightsVisibleAjax::class);
        $this->assertEquals("from oxv_oxgroups_de left join oxobjectrights on  oxobjectrights.oxoffset = ( oxv_oxgroups_de.oxrrid div 31 ) and  oxobjectrights.oxgroupidx & (1 << ( oxv_oxgroups_de.oxrrid mod 31 ) ) and oxobjectrights.oxobjectid= '$sSynchoxid' and oxobjectrights.oxaction = 1  where oxobjectrights.oxobjectid != '$sSynchoxid' or ( oxobjectid is null )", trim($oView->UNITgetQuery()));
    }

    /**
     * ArticleRightsVisibleAjax::removeGroupFromView() test case
     *
     * @return null
     */
    public function testRemoveGroupFromView()
    {
        $oDb = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();
        $sOxid = '_testObjectRemove';
        $this->setRequestParameter("oxid", $sOxid);
        $this->setRequestParameter("all", true);

        $oView = $this->getMock(\OxidEsales\Eshop\Application\Controller\Admin\ArticleRightsVisibleAjax::class, array("_getActionIds"));
        $oView->expects($this->any())->method('_getActionIds')->will($this->returnValue(array(0, 1)));

        $this->assertEquals(1, $oDb->getOne("select count(oxid) from oxobjectrights where oxid='_testObjectRightsRemove'"));
        $oView->removeGroupFromView();
        $this->assertEquals(0, $oDb->getOne("select count(oxid) from oxobjectrights where oxid='_testObjectRightsRemove'"));
    }

    /**
     * ArticleRightsVisibleAjax::removeGroupFromView() test case
     *
     * @return null
     */
    public function testRemoveGroupFromViewAll()
    {
        $oDb = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();
        $sOxid = '_testObjectRemoveAll';
        $this->setRequestParameter("oxid", $sOxid);
        $this->setRequestParameter("all", true);

        $this->assertEquals(1, $oDb->getOne("select count(oxid) from oxobjectrights where oxobjectid='$sOxid'"));
        $oView = oxNew(\OxidEsales\Eshop\Application\Controller\Admin\ArticleRightsVisibleAjax::class);
        $oView->removeGroupFromView();
        $this->assertEquals(0, $oDb->getOne("select count(oxid) from oxobjectrights where oxobjectid='$sOxid'"));
    }

    /**
     * ArticleRightsVisibleAjax::addGroupToView() test case
     *
     * @return null
     */
    public function testAddGroupToView()
    {
        $oDb = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();
        $sSynchoxid = '_testObjectAdd';
        $this->setRequestParameter("synchoxid", $sSynchoxid);

        $oView = $this->getMock(\OxidEsales\Eshop\Application\Controller\Admin\ArticleRightsVisibleAjax::class, array("_getActionIds"));
        $oView->expects($this->any())->method('_getActionIds')->will($this->returnValue(array(0, 1)));

        $this->assertEquals(0, $oDb->getOne("select count(oxid) from oxobjectrights where oxobjectid='$sSynchoxid'"));
        $oView->addGroupToView();
        $this->assertEquals(1, $oDb->getOne("select count(oxid) from oxobjectrights where oxobjectid='$sSynchoxid'"));
    }

    /**
     * ArticleRightsVisibleAjax::addGroupToView() test case
     *
     * @return null
     */
    public function testAddGroupToViewAll()
    {
        $oDb = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();
        $sSynchoxid = '_testObjectAddAll';
        $this->setRequestParameter("synchoxid", $sSynchoxid);
        $this->setRequestParameter("all", true);

        $oView = $this->getMock(\OxidEsales\Eshop\Application\Controller\Admin\ArticleRightsVisibleAjax::class, array("_getActionIds"));
        $oView->expects($this->any())->method('_getActionIds')->will($this->returnValue(array(0, 1)));

        $this->assertEquals(0, $oDb->getOne("select count(oxid) from oxobjectrights where oxobjectid='$sSynchoxid'"));
        $oView->addGroupToView();
        $this->assertEquals(131071, $oDb->getOne("select oxgroupidx from oxobjectrights where oxobjectid='$sSynchoxid'"));
    }
}
