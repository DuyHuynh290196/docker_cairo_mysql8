<?php declare(strict_types=1);
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Tests\Unit\Application\Model;

use OxidEsales\Eshop\Core\Field;

class UserTest extends \OxidEsales\TestingLibrary\UnitTestCase
{
    public function testGetIdByUserNameForMallUsers()
    {
        $oUser = oxNew('oxUser');
        $oUser->setId("_testId_1");
        $oUser->oxuser__oxusername = new Field("aaa@bbb.lt", Field::T_RAW);
        $oUser->oxuser__oxshopid = new Field($this->getConfig()->getBaseShopId(), Field::T_RAW);
        $oUser->save();

        $oUser = oxNew('oxUser');
        $oUser->setId("_testId_2");
        $oUser->oxuser__oxusername = new Field("bbb@ccc.lt", Field::T_RAW);
        $oUser->oxuser__oxshopid = new Field('xxx');
        $oUser->save();


        $this->getConfig()->setConfigParam('blMallUsers', false);
        $oU = oxNew('oxUser');
        $this->assertEquals('_testId_1', $oU->getIdByUserName('aaa@bbb.lt'));
        $this->assertFalse($oU->getIdByUserName('bbb@ccc.lt'));

        $this->getConfig()->setConfigParam('blMallUsers', true);
        $oU = oxNew('oxUser');
        $this->assertEquals('_testId_1', $oU->getIdByUserName('aaa@bbb.lt'));
        $this->assertEquals('_testId_2', $oU->getIdByUserName('bbb@ccc.lt'));
    }
}

