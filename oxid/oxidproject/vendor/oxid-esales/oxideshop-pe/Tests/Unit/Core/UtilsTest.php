<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopProfessional\Tests\Unit\Core;

use OxidEsales\EshopProfessional\Core\Utils;

class UtilsTest extends \oxUnitTestCase
{
    protected function setUp(): void
    {
        if ($this->getTestConfig()->getShopEdition() !== 'PE') {
            $this->markTestSkipped('This test is for Professional edition only.');
        }
    }

    /**
     * Test if we will get correct prefix depending on version
     */
    public function testGetEditionCacheFilePrefix()
    {
        $utils = new Utils();
        $expected = 'pe';
        $this->assertSame($expected, $utils->getEditionCacheFilePrefix());
    }
}
