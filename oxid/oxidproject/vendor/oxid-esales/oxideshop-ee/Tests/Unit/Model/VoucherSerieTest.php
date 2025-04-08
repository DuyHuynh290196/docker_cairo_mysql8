<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Tests\Unit\Model;

class VoucherSerieTest extends \OxidEsales\TestingLibrary\UnitTestCase
{
    /**
     * Trying to delete denied action by RR
     */
    public function testDeleteDeniedByRR()
    {
        $oSerie = $this->getMock(\OxidEsales\Eshop\Application\Model\VoucherSerie::class, array('canDelete', 'unsetUserGroups', 'deleteVoucherList'));
        $oSerie->expects($this->once())->method('canDelete')->will($this->returnValue(false));
        $oSerie->expects($this->never())->method('unsetUserGroups');
        $oSerie->expects($this->never())->method('deleteVoucherList');

        $this->assertFalse($oSerie->delete('_testOrderId'));
    }
}
