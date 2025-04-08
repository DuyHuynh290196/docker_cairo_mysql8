<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Tests\Unit\Model;

use \OxidEsales\TestingLibrary\UnitTestCase;

class DeliveryTest extends UnitTestCase
{
    /**
     * Trying to delete denied action by RR.
     */
    public function testDeleteDeniedByRR()
    {
        $deliveryMock = $this->getMock(\OxidEsales\Eshop\Application\Model\Delivery::class, array('canDelete'));
        $deliveryMock->expects($this->once())->method('canDelete')->will($this->returnValue(false));

        $this->assertFalse($deliveryMock->delete('anyOxid'));
    }
}
