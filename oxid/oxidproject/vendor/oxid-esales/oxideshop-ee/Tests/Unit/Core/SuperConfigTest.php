<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Tests\Unit\Core;

use oxDb;
use OxidEsales\EshopEnterprise\Core\AdminRights;
use OxidEsales\TestingLibrary\UnitTestCase;

class SuperConfigTest extends UnitTestCase
{
    protected function tearDown(): void
    {
        $this->cleanUpTable('oxobjectrights');

        parent::tearDown();
    }

    public function testGetRightsRRisOff()
    {
        $this->getConfig()->setConfigParam('blUseRightsRoles', 0);

        $oCfg = oxNew(\OxidEsales\Eshop\Core\Base::class);
        $this->assertNull($oCfg->getRights());
    }

    public function testGetRightsAdminRR()
    {
        $this->getConfig()->setConfigParam('blUseRightsRoles', 1);

        $oCfg = oxNew(\OxidEsales\Eshop\Core\Base::class);
        $oCfg->setAdminMode(true);

        $this->assertTrue($oCfg->getRights() instanceof AdminRights);
    }

    public function testGetRightsShopRR()
    {
        $this->getConfig()->setConfigParam('blUseRightsRoles', 2);

        // empty demo data..
        $oCfg = oxNew(\OxidEsales\Eshop\Core\Base::class);
        $oCfg->setAdminMode(false);
        $this->assertFalse($oCfg->getRights());

        // inserting test record..
        $sQ = "insert into oxobjectrights (oxid,oxobjectid,oxgroupidx,oxoffset,oxaction) values ('_testId','_testObjectId','0','0','0')";
        \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute($sQ);

        // testing
        $oCfg = oxNew(\OxidEsales\Eshop\Core\Base::class);
        $oCfg->setAdminMode(false);
        $rightsClass = get_class(oxNew(\OxidEsales\Eshop\Application\Model\Rights::class));
        $this->assertTrue($oCfg->getRights() instanceof $rightsClass);
    }

    public function testSetRightsAndGetRights()
    {
        $oCfg = oxNew(\OxidEsales\Eshop\Core\Base::class);
        $oCfg->setRights('xxx');
        $this->assertEquals('xxx', $oCfg->getRights());
    }
}
