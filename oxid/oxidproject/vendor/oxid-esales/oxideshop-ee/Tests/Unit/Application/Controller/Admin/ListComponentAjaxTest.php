<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Tests\Unit\Application\Controller\Admin;

use \oxTestModules;

class ListComponentAjaxTest extends \OxidEsales\TestingLibrary\UnitTestCase
{
    /**
     * ajaxListComponent::resetContentCache() test case
     *
     * @return null
     */
    public function testResetContentCache()
    {
        $config = $this->getMock(\OxidEsales\Eshop\Core\Config::class, array("getConfigParam"));
        $config->expects($this->any())->method('getConfigParam')->will($this->returnValue(false));

        $ajaxListComponent = $this->getMock(\OxidEsales\Eshop\Application\Controller\Admin\ListComponentAjax::class, array("getConfig"));
        $ajaxListComponent->expects($this->once())->method('getConfig')->will($this->returnValue($config));

        oxTestModules::addFunction('oxcache', 'reset', '{ throw new Exception( "reset" ); }');

        try {
            $ajaxListComponent->resetContentCache();
        } catch (\Exception $oExcp) {
            $this->assertEquals("reset", $oExcp->getMessage(), "error in ajaxListComponent::resetContentCache()");

            return;
        }
        $this->fail("error in ajaxListComponent::resetContentCache()");
    }
}