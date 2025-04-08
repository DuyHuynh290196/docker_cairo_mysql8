<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Tests\Unit\Core;

use OxidEsales\EshopEnterprise\Core\Utils;

class UtilsTest extends \oxUnitTestCase
{
    /**
     * Test if we will get correct prefix depending on version
     */
    public function testGetEditionCacheFilePrefix()
    {
        $utils = new Utils();
        $expected = 'ee';
        $this->assertSame($expected, $utils->getEditionCacheFilePrefix());
    }
}
