<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Tests\Unit\Application\Controller\Admin;

class AdminLinksMallTest extends \OxidEsales\TestingLibrary\UnitTestCase
{
    public function testRender()
    {
        $oView = $this->getProxyClass("Adminlinks_Mall");
        $this->assertEquals('oxlinks', $oView->getNonPublicVar("_sMallTable"));
    }
}