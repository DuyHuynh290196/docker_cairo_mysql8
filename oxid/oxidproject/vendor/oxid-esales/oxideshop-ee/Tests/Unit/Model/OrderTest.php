<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Tests\Unit\Model;

use \OxidEsales\TestingLibrary\UnitTestCase;
use \oxField;

class OrderTest extends UnitTestCase
{
    /**
     * Trying to delete denied action by RR
     */
    public function testDeleteDeniedByRR()
    {

        $oOrder = $this->getMock(\OxidEsales\Eshop\Application\Model\Order::class, array('canDelete', 'load'));
        $oOrder->expects($this->once())->method('load')->will($this->returnValue(true));
        $oOrder->expects($this->once())->method('canDelete')->will($this->returnValue(false));

        $this->assertFalse($oOrder->delete('_testOrderId'));
    }

    /**
     * Trying to assign denied action by RR
     */
    public function testAssignDeniedByRR()
    {
        $oOrder = $this->getMock(\OxidEsales\Eshop\Application\Model\Order::class, array('canRead'));
        $oOrder->expects($this->once())->method('canRead')->will($this->returnValue(false));

        $oOrder->load("_testOrderId");
        $this->assertFalse($oOrder->assign(array()));
    }

    public function testSetUser()
    {
        //load user
        $oUser = oxNew(\OxidEsales\Eshop\Application\Model\User::class);
        $oUser->load("oxdefaultadmin");
        $oUser->oxuser__oxustidstatus = new \OxidEsales\Eshop\Core\Field('5');

        $oOrder = $this->getProxyClass("oxOrder");
        $oOrder->UNITsetUser($oUser);

        $this->assertEquals(5, $oOrder->oxorder__oxbillustidstatus->value);
    }

}
