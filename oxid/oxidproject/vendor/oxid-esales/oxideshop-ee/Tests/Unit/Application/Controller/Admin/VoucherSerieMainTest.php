<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Tests\Unit\Application\Controller\Admin;

use OxidEsales\TestingLibrary\UnitTestCase;
use oxTestModules;
use Exception;

/**
 * Tests for Vendor_Mall class
 */
class VoucherSerieMainTest extends UnitTestCase
{
    /**
     * VoucherSerie_Main::Save() test case
     *
     * @return null
     */
    public function testSaveDerived()
    {
        oxTestModules::addFunction('oxvoucherserie', 'save', '{ throw new Exception( "save" ); }');
        oxTestModules::addFunction('oxvoucherserie', 'load', '{ $this->_blIsDerived = true; return true; }');

        // testing..
        try {
            $oView = oxNew(\OxidEsales\Eshop\Application\Controller\Admin\VoucherSerieMain::class);
            $oView->save();
        } catch (Exception $oExcp) {
            $this->fail("error in VoucherSerie_Main::save()");
        }
    }
}
