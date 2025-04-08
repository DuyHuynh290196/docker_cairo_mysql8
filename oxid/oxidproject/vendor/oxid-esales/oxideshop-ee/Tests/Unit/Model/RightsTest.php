<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Tests\Unit\Model;

use oxField;
use oxDb;
use stdClass;
use OxidEsales\Eshop\Core\Registry;

class RightsTest extends \OxidEsales\TestingLibrary\UnitTestCase
{
    /**
     * Initialize the fixture.
     *
     * @return null
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->oRole = oxNew(\OxidEsales\Eshop\Application\Model\Role::class);
        $this->oRole->setId('30be1fd555138bd0563d19bcf7a594b8');
        $this->oRole->oxroles__oxtitle = new \OxidEsales\Eshop\Core\Field('test');
        $this->oRole->oxroles__oxshopid = new \OxidEsales\Eshop\Core\Field($this->getConfig()->getBaseShopId());
        $this->oRole->oxroles__oxactive = new \OxidEsales\Eshop\Core\Field(1);
        $this->oRole->oxroles__oxarea = new \OxidEsales\Eshop\Core\Field(1);
        $this->oRole->save();

        $oOR = oxNew(\OxidEsales\Eshop\Core\Model\BaseModel::class);
        $oOR->init('oxobjectrights');
        $oOR->oxobjectrights__oxobjectid = new \OxidEsales\Eshop\Core\Field($this->oRole->getId());
        $oOR->oxobjectrights__oxgroupidx = new \OxidEsales\Eshop\Core\Field(512);
        $oOR->oxobjectrights__oxoffset = new \OxidEsales\Eshop\Core\Field(0);
        $oOR->oxobjectrights__oxaction = new \OxidEsales\Eshop\Core\Field(1);
        $oOR->save();

        $oRf = oxNew(\OxidEsales\Eshop\Core\Model\BaseModel::class);
        $oRf->init('oxrolefields');
        $oRf->setId('testrolefield');
        $oRf->oxrolefields__oxname = new \OxidEsales\Eshop\Core\Field('testname');
        $oRf->oxrolefields__oxparam = new \OxidEsales\Eshop\Core\Field(0);
        $oRf->save();

        $sQ = "insert into oxfield2role ( oxfieldid, oxtype, oxroleid, oxidx) values
           ( '42b44bc9950334951.12393781', '', '" . $this->oRole->getId() . "', 1 )";
        \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute($sQ);
        $sQ = "insert into oxfield2role ( oxfieldid, oxtype, oxroleid, oxidx) values
           ( '42b44bc99488c66b1.94059993', '', '" . $this->oRole->getId() . "', 1 )";
        \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute($sQ);
        $sQ = "insert into oxfield2role ( oxfieldid, oxtype, oxroleid, oxidx) values
           ( '42b44bc9941a46fd3.13180499', '', '" . $this->oRole->getId() . "', 1 )";
        \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute($sQ);
        $sQ = "insert into oxfield2role ( oxfieldid, oxtype, oxroleid, oxidx) values
           ( '42b44bc9934bdb406.85935627', '', '" . $this->oRole->getId() . "', 1 )";
        \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute($sQ);

        $sQ = "insert into oxobjectrights ( oxid, oxobjectid, oxgroupidx, oxoffset, oxaction)
           values ( 'test', 'yyy', 16384, 0, 1 ) ";
        \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute($sQ);

        $sQ = "insert into oxobjectrights ( oxid, oxobjectid, oxgroupidx, oxoffset, oxaction)
           values ( 'test', 'zzz', 16384, 0, 1 ) ";
        \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute($sQ);

        // cleanup
        $sVarName = 'oxrr' . $this->getConfig()->getShopId();
        $this->getSession()->setVariable($sVarName, null);
        $this->getSession()->setVariable('oxrrvarname', null);
        $_SESSION['oxrrvarname'] = null;
        $_SESSION[$sVarName] = null;

    }

    /**
     * Tear down the fixture.
     *
     * @return null
     */
    protected function tearDown(): void
    {
        $this->oRole->delete();
        $sQ = "delete from oxobjectrights where oxid='test'";
        \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute($sQ);

        $oRf = oxNew(\OxidEsales\Eshop\Core\Model\BaseModel::class);
        $oRf->init('oxrolefields');
        $oRf->delete('testrolefield');

        parent::tearDown();
    }

    public function testHasViewRights()
    {
        $oRights = $this->getProxyClass('oxrights');
        $oRights->setNonPublicVar('_aRights', array('xxx' => 1));
        $this->assertFalse($oRights->hasViewRights('xxx'));
        $this->assertTrue($oRights->hasViewRights('yyy'));
    }

    public function testProcessViewNoRightsLoaded()
    {
        $oRights = $this->getMock(\OxidEsales\Eshop\Application\Model\Rights::class, array('hasViewRights'));
        $oRights->expects($this->never())->method('hasViewRights');

        $oRights->processView(new stdClass());
    }

    public function testProcessViewRestrictedClass()
    {
        try {
            $oView = oxNew(\OxidEsales\Eshop\Core\Controller\BaseController::class);
            $oView->setClassName('xxx');

            $oRights = $this->getProxyClass('oxrights');
            $oRights->setNonPublicVar('_aRights', array('xxx' => 1));
            $oRights->processView($oView);
        } catch (\OxidEsales\EshopEnterprise\Core\Exception\AccessRightException $oEx) {
            return;
        }

        $this->fail('Failure while running testProcessViewRestrictedClass test');
    }

    public function testProcessViewRestrictedFnc()
    {
        try {
            $oView = oxNew(\OxidEsales\Eshop\Core\Controller\BaseController::class);

            $oRights = $this->getProxyClass('oxrights');
            $oRights->setNonPublicVar('_aRights', array('xxx' => 1));
            $oRights->processView($oView, 'xxx');
        } catch (\OxidEsales\EshopEnterprise\Core\Exception\AccessRightException $oEx) {
            return;
        }

        $this->fail('Failure while running testProcessViewRestrictedFnc test');
    }

    public function testProcessViewRestrictedIdentsFnc()
    {
        try {
            $oView = oxNew(\OxidEsales\Eshop\Core\Controller\BaseController::class);

            $oRights = $this->getProxyClass('oxrights');
            $oRights->setNonPublicVar('_aRights', array('xxx' => array('yyy')));
            $oRights->processView($oView, 'yyy');
        } catch (\OxidEsales\EshopEnterprise\Core\Exception\AccessRightException $oEx) {
            return;
        }

        $this->fail('Failure while running testProcessViewRestrictedFnc test');
    }

    public function testProcessViewRestrictedIdentsClass()
    {
        try {
            $oView = oxNew(\OxidEsales\Eshop\Core\Controller\BaseController::class);
            $oView->setClassName('yyy');

            $oRights = $this->getProxyClass('oxrights');
            $oRights->setNonPublicVar('_aRights', array('xxx' => array('yyy')));
            $oRights->processView($oView, 'zzz');
        } catch (\OxidEsales\EshopEnterprise\Core\Exception\AccessRightException $oEx) {
            return;
        }

        $this->fail('Failure while running testProcessViewRestrictedFnc test');
    }

    public function testProcessViewNoFncNoClass()
    {
        $oRights = $this->getProxyClass('oxrights');
        $oRights->setNonPublicVar('_aRights', array('xxx' => array('yyy')));

        $this->assertNull($oRights->processView(oxNew(\OxidEsales\Eshop\Core\Controller\BaseController::class)));
    }

    public function testLoadRightsNoUser()
    {
        $oRights = oxNew(\OxidEsales\Eshop\Application\Model\Rights::class);
        $oRights->UNITloadRights();

        $aRights = array('TOBASKET'             => array('tobasket', 'basket'),
                         'SHOWLONGDESCRIPTION'  => 1,
                         'SHOWARTICLEPRICE'     => 1,
                         'SHOWSHORTDESCRIPTION' => 1);

        $this->assertEquals($aRights, $oRights->getViewRights());
    }

    public function testLoadRightsAdminUser()
    {
        $oUser = oxNew(\OxidEsales\Eshop\Application\Model\User::class);
        $oUser->load('oxdefaultadmin');

        $oRights = $this->getMock(\OxidEsales\Eshop\Application\Model\Rights::class, array('getUser'));
        $oRights->expects($this->atLeastOnce())->method('getUser')->will($this->returnValue($oUser));
        $oRights->UNITloadRights();

        $this->assertEquals(array(), $oRights->getViewRights());
    }


    public function testLoad()
    {
        $oUser = oxNew(\OxidEsales\Eshop\Application\Model\User::class);

        $sVarName = 'oxrr' . $oUser->getId() . $this->getConfig()->getShopId();

        //
        $this->assertNull(Registry::getSession()->getVariable($sVarName));
        $this->assertNull(Registry::getSession()->getVariable('oxrrvarname'));

        $oRights = $this->getMock(\OxidEsales\Eshop\Application\Model\Rights::class, array('getUser', 'getConfig', '_checkStatus', '_loadRights'));
        $oRights->expects($this->once())->method('getUser')->will($this->returnValue($oUser));
        $oRights->expects($this->exactly(2))->method('getConfig')->will($this->returnValue($this->getConfig()));
        $oRights->expects($this->once())->method('_checkStatus')->will($this->returnValue(true));
        $oRights->expects($this->once())->method('_loadRights')->will($this->returnValue(null));

        $oRights->load();

        // checkign if all expected variables are set
        $this->assertNull(Registry::getSession()->getVariable($sVarName));
        $this->assertEquals($sVarName, Registry::getSession()->getVariable('oxrrvarname'));


    }

    public function testGetUserGroupIndex()
    {
        $oUser = oxNew(\OxidEsales\Eshop\Application\Model\User::class);
        $oUser->load('oxdefaultadmin');

        $oRights = $this->getMock(\OxidEsales\Eshop\Application\Model\Rights::class, array('getUser'));
        $oRights->expects($this->once())->method('getUser')->will($this->returnValue($oUser));

        $this->assertEquals(array(512), $oRights->getUserGroupIndex());
    }

    public function testGetUserGroupIndexNoUser()
    {
        $oRights = oxNew(\OxidEsales\Eshop\Application\Model\Rights::class);
        $this->assertEquals(null, $oRights->getUserGroupIndex());
    }

    public function testGetViewRights()
    {
        $oRights = $this->getMock(\OxidEsales\Eshop\Application\Model\Rights::class, array('load'));
        $oRights->expects($this->once())->method('load');

        $this->assertEquals(null, $oRights->getViewRights());
    }

    public function testCheckStatusFalse()
    {
        $this->getSession()->setVariable('oxrrvarname', 'xxx');

        $oRights = oxNew(\OxidEsales\Eshop\Application\Model\Rights::class);
        $this->assertFalse($oRights->UNITcheckStatus());
    }

    public function testCheckStatusTrue()
    {
        $this->getSession()->setVariable('oxrrvarname', 'oxrr' . $this->getConfig()->getShopId());

        $oRights = oxNew(\OxidEsales\Eshop\Application\Model\Rights::class);
        $this->assertTrue($oRights->UNITcheckStatus());
    }

    public function testHasObjectRightsNoRightsSet()
    {
        $aGroupIdx = array('0' => '1', '2' => '3');

        $oRights = $this->getMock(\OxidEsales\Eshop\Application\Model\Rights::class, array('getUserGroupIndex'));
        $oRights->expects($this->once())->method('getUserGroupIndex')->will($this->returnValue($aGroupIdx));
        $this->assertTrue($oRights->hasObjectRights('"xxx"', 1));
    }

    public function testHasObjectRightsRightsSetNoRights()
    {
        $aGroupIdx = array('0' => '1', '2' => '3');

        $oRights = $this->getMock(\OxidEsales\Eshop\Application\Model\Rights::class, array('getUserGroupIndex'));
        $oRights->expects($this->once())->method('getUserGroupIndex')->will($this->returnValue($aGroupIdx));
        $this->assertFalse($oRights->hasObjectRights('"yyy"', 1));
    }

    public function testHasObjectRightsRightsSet()
    {
        $aGroupIdx = array('0' => '16384', '2' => '3');

        $oRights = $this->getMock(\OxidEsales\Eshop\Application\Model\Rights::class, array('getUserGroupIndex'));
        $oRights->expects($this->once())->method('getUserGroupIndex')->will($this->returnValue($aGroupIdx));
        $this->assertTrue($oRights->hasObjectRights('"zzz"', 1));
    }
}
