<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Tests\Unit\Setup;

use OxidEsales\EshopCommunity\Setup\Core;
use OxidEsales\EshopEnterprise\Setup\Setup;

class SetupTest extends \OxidEsales\TestingLibrary\UnitTestCase
{
    /**
     * Testing Setup::getDefaultSerial()
     */
    public function testGetDefaultSerial()
    {
        $setup = $this->getSetup();;
        $this->assertEquals('EF7FV-B9TA8-3R3SD-MZNU4-7NWM3-AN7AU', $setup->getDefaultSerial());
    }

    /**
     * Testing Setup::getEdition()
     */
    public function testGetEdition()
    {
        $setup = $this->getSetup();
        $this->assertEquals(2, $setup->getEdition());
    }

    /**
     * @return Setup
     */
    protected function getsetup()
    {
        $core = new Core();
        return $core->getInstance('Setup');
    }
}
