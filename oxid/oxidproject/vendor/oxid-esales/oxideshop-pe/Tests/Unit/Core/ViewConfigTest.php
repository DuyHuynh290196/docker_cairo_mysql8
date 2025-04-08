<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopProfessional\Tests\Unit\Core;

//use OxidEsales\EshopProfessional\Core\Utils;

class ViewConfigTest extends \oxUnitTestCase
{
    /**
     * Checks if shop licenze is in staging mode
     */
    public function testHasDemoKey()
    {
        $oConfig = $this->getMock(\OxidEsales\Eshop\Core\Config::class, array("hasDemoKey"));
        $oConfig->expects($this->once())->method("hasDemoKey")->will($this->returnValue(true));

        $oViewConfig = $this->getMock(\OxidEsales\Eshop\Core\ViewConfig::class, array('getConfig'));
        $oViewConfig->expects($this->any())->method('getConfig')->will($this->returnValue($oConfig));

        $this->assertTrue($oViewConfig->hasDemoKey());
    }
}
