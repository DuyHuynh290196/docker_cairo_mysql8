<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Tests\Unit\Model;

use \OxidEsales\TestingLibrary\UnitTestCase;
use \oxField;
use \oxBase;
use \oxDb;

class GroupsTest extends UnitTestCase
{
    /**
     * Initialize the fixture.
     *
     * @return null
     */
    protected function setUp(): void
    {
        parent::setUp();
        $group = oxNew(\OxidEsales\Eshop\Application\Model\Groups::class);
        $group->setId('testgroup');
        $group->oxgroups__oxtitle = new \OxidEsales\Eshop\Core\Field('testgroup');
        $group->oxgroups__oxactive = new \OxidEsales\Eshop\Core\Field(1);
        $group->save();

        // EE only
        $objectToRole = new \OxidEsales\Eshop\Core\Model\BaseModel();
        $objectToRole->init('oxobject2role');
        $objectToRole->oxobject2role__oxobjectid = new \OxidEsales\Eshop\Core\Field($group->getId());
        $objectToRole->oxobject2role__oxroleid = new \OxidEsales\Eshop\Core\Field('testrole');
        $objectToRole->oxobject2role__oxtype = new \OxidEsales\Eshop\Core\Field('oxgroups');
        $objectToRole->save();

        $offset = ( int ) ($group->oxgroups__oxrrid->value / 31);
        $bitmap = 1 << ($group->oxgroups__oxrrid->value % 31);

        $objectToRights = new \OxidEsales\Eshop\Core\Model\BaseModel();
        $objectToRights->init('oxobjectrights');
        $objectToRights->oxobjectrights__oxobjectid = new \OxidEsales\Eshop\Core\Field('xxx');
        $objectToRights->oxobjectrights__oxgroupidx = new \OxidEsales\Eshop\Core\Field($bitmap);
        $objectToRights->oxobjectrights__oxoffset = new \OxidEsales\Eshop\Core\Field($offset);
        $objectToRights->oxobjectrights__oxaction = new \OxidEsales\Eshop\Core\Field(1);
        $objectToRights->save();
    }

    /**
     * Tear down the fixture.
     *
     * @return null
     */
    protected function tearDown(): void
    {
        $group = oxNew(\OxidEsales\Eshop\Application\Model\Groups::class);
        $group->delete('testgroup');
        $group->delete('testgroup2');
        parent::tearDown();
    }

    /**
     * Testing if insert creates correct rights index (only for EE)
     */
    public function testInsert()
    {
        $group = oxNew(\OxidEsales\Eshop\Application\Model\Groups::class);
        $group->setId('testgroup2');
        $group->oxgroups__oxtitle = new \OxidEsales\Eshop\Core\Field('testgroupdesc');
        $group->save(); //no delete needed, as the db is cleaned up automatically

        $this->assertNotNull($group->oxgroups__oxrrid->value);

        $rrid = $group->oxgroups__oxrrid->value;

        //check that the given rrid is unique
        $myDb = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();
        $res = $myDb->GetAll("select oxid from oxgroups where oxrrid = $rrid ");
        $this->assertEquals(count($res), 1);
    }

    // 3. trying to delete denied action by RR (EE only)
    public function testDeleteDeniedByRR()
    {
        $oGroups = $this->getMock(\OxidEsales\Eshop\Application\Model\Groups::class, array('canDelete'));
        $oGroups->expects($this->once())->method('canDelete')->will($this->returnValue(false));

        $this->assertFalse($oGroups->delete('testDelete'));
    }

    public function testCanRead()
    {
        $groups = oxNew(\OxidEsales\Eshop\Application\Model\Groups::class);
        $this->assertTrue($groups->canRead());
    }

    public function testDelete()
    {
        $myDB = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();

        // selecting count from DB
        $group = oxNew(\OxidEsales\Eshop\Application\Model\Groups::class);
        $group->Load('testgroup');
        $group->delete();

        $offset = ( int ) ($group->oxgroups__oxrrid->value / 31);
        $bitmap = 1 << ($group->oxgroups__oxrrid->value % 31);

        $this->assertEquals(0, $myDB->getOne("select count(*) from oxobject2role where oxobjectid='testgroup'"));
        $this->assertEquals(0, $myDB->getOne("select count(*) from oxobjectrights where oxoffset = $offset and oxgroupidx & $bitmap "));
    }

    // if this function begins to return false, probably shop does not run at all :)
    public function testCanReadField()
    {
        $oGroups = oxNew(\OxidEsales\Eshop\Application\Model\Groups::class);
        $this->assertTrue($oGroups->canReadField('xxx'));
    }
}
