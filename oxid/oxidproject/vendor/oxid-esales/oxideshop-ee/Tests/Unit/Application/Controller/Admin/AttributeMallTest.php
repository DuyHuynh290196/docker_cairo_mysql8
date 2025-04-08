<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Tests\Unit\Application\Controller\Admin;

use \OxidEsales\TestingLibrary\UnitTestCase;

/**
 * Tests for Attribute_Mall class.
 */
class AttributeMallTest extends UnitTestCase
{
    /**
     * Attribute_Mall::Render() test case.
     */
    public function testConstructor()
    {
        // testing..
        $view = $this->getProxyClass("Attribute_Mall");
        $this->assertEquals('oxattribute', $view->getNonPublicVar("_sMallTable"));
    }
}
