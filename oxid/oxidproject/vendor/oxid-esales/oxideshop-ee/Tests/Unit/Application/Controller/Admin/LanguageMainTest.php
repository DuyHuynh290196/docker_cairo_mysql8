<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Tests\Unit\Application\Controller\Admin;

use OxidEsales\TestingLibrary\UnitTestCase;

/**
 * Tests for Discount_Mall class
 */
class LanguageMainTest extends UnitTestCase
{
    /**
     * Language_Main::Render() test case
     *
     * @return null
     */
    public function testRender()
    {
        $oView = oxNew(\OxidEsales\Eshop\Application\Controller\Admin\LanguageMain::class);
        $oView->render();

        $aViewData = $oView->getViewData();
        $this->assertTrue($aViewData["readonly"]);
    }
}
