<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Tests\Unit\Model;

use \OxidEsales\TestingLibrary\UnitTestCase;

class DeliverySetTest extends UnitTestCase
{
    /**
     * Trying to delete denied action by RR
     */
    public function testDeleteDeniedByRR()
    {
        $deliverySetMock = $this->getMock(\OxidEsales\Eshop\Application\Model\DeliverySet::class, array('canDelete'));
        $deliverySetMock->expects($this->once())->method('canDelete')->will($this->returnValue(false));

        $this->assertFalse($deliverySetMock->delete('someId'));
    }
}
