<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */
namespace OxidEsales\EshopEnterprise\Tests\Unit\Application\Controller;

use OxidEsales\TestingLibrary\UnitTestCase;

class CompareTest extends UnitTestCase
{
    /**
     * Testing \OxidEsales\Eshop\Application\Controller\CompareController::isCacheable()
     */
    public function testIsCacheable()
    {
        $oCompare = oxNew(\OxidEsales\Eshop\Application\Controller\CompareController::class);
        $this->assertFalse($oCompare->isCacheable());
    }
}
