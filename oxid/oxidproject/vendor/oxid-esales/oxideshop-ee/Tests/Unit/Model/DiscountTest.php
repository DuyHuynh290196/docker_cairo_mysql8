<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Tests\Unit\Model;

use \OxidEsales\TestingLibrary\UnitTestCase;

class DiscountTest extends UnitTestCase
{
    /**
     * Trying to delete denied action by RR
     */
    public function testDeleteDeniedByRR()
    {
        $oDiscount = $this->getMock(\OxidEsales\Eshop\Application\Model\Discount::class, array('canDelete'));
        $oDiscount->expects($this->once())->method('canDelete')->will($this->returnValue(false));

        $this->assertFalse($oDiscount->delete('testDelete'));
    }
}
