<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Tests\Unit\Model;

use oxField;
use oxDb;


class RoleTest extends \OxidEsales\TestingLibrary\UnitTestCase
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
        $this->oRole->oxroles__oxtitle = new \OxidEsales\Eshop\Core\Field('test');
        $this->oRole->oxroles__oxshopid = new \OxidEsales\Eshop\Core\Field($this->getConfig()->getBaseShopId());
        $this->oRole->oxroles__oxactive = new \OxidEsales\Eshop\Core\Field(1);
        $this->oRole->oxroles__oxarea = new \OxidEsales\Eshop\Core\Field(1);
        $this->oRole->save();

        $sQ = "insert into oxfield2role ( oxfieldid, oxtype, oxroleid, oxidx) values
           ( 'test', 'test', '" . $this->oRole->getId() . "', 1 )";
        \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute($sQ);

        $oO2R = oxNew(\OxidEsales\Eshop\Core\Model\BaseModel::class);
        $oO2R->init('oxobject2role');
        $oO2R->oxobject2role__oxobjectid = new \OxidEsales\Eshop\Core\Field('test');
        $oO2R->oxobject2role__oxroleid = new \OxidEsales\Eshop\Core\Field($this->oRole->getId());
        $oO2R->oxobject2role__oxtype = new \OxidEsales\Eshop\Core\Field('test');
        $oO2R->save();

        $oOR = oxNew(\OxidEsales\Eshop\Core\Model\BaseModel::class);
        $oOR->init('oxobjectrights');
        $oOR->oxobjectrights__oxobjectid = new \OxidEsales\Eshop\Core\Field($this->oRole->getId());
        $oOR->oxobjectrights__oxgroupidx = new \OxidEsales\Eshop\Core\Field(1);
        $oOR->oxobjectrights__oxoffset = new \OxidEsales\Eshop\Core\Field(0);
        $oOR->oxobjectrights__oxaction = new \OxidEsales\Eshop\Core\Field(1);
        $oOR->save();
    }

    /**
     * Tear down the fixture.
     *
     * @return null
     */
    protected function tearDown(): void
    {
        $this->oRole->delete();
        parent::tearDown();
    }

    public function testLoad()
    {
        $oRole = oxNew(\OxidEsales\Eshop\Application\Model\Role::class);
        if (!$oRole->load($this->oRole->getId())) {
            $this->fail('can not load roles');
        }
        $this->assertEquals(1, $oRole->oxroles__oxshopid->value);
    }

    public function testFailedLoad()
    {
        $oRole = oxNew(\OxidEsales\Eshop\Application\Model\Role::class);
        $this->assertFalse($oRole->load('wrong'));
    }

    public function testDeleteNoId()
    {
        $oRole = oxNew(\OxidEsales\Eshop\Application\Model\Role::class);
        $this->assertFalse($oRole->delete());
    }

    public function testDelete()
    {
        $oDb = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();

        $oRole = oxNew(\OxidEsales\Eshop\Application\Model\Role::class);

        $this->assertTrue('1' == $oDb->getOne("select 1 from oxfield2role where oxroleid = '" . $this->oRole->getId() . "' "));
        $this->assertTrue('1' == $oDb->getOne("select 1 from oxobject2role where oxroleid = '" . $this->oRole->getId() . "' "));
        $this->assertTrue('1' == $oDb->getOne("select 1 from oxobjectrights where oxobjectid = '" . $this->oRole->getId() . "' "));

        $this->assertTrue($oRole->delete($this->oRole->getId()));

        $this->assertFalse($oDb->getOne("select 1 from oxfield2role where oxroleid = '" . $this->oRole->getId() . "' "));
        $this->assertFalse($oDb->getOne("select 1 from oxobject2role where oxroleid = '" . $this->oRole->getId() . "' "));
        $this->assertFalse($oDb->getOne("select 1 from oxobjectrights where oxobjectid = '" . $this->oRole->getId() . "' "));
    }
}
