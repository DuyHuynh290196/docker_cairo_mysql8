<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Tests\Unit\Core;

use OxidEsales\TestingLibrary\UnitTestCase;

class FunctionsTest extends UnitTestCase
{
    public function testGetViewName()
    {
        $this->getConfig()->setConfigParam('aMultiShopTables', array('xxx', 'yyy'));

        $this->assertEquals('oxv_xxx_' . $this->getConfig()->getBaseShopId(), getViewName('xxx'));
        $this->assertEquals('zzz', getViewName('zzz'));

        $this->getConfig()->setConfigParam('blSkipViewUsage', true);
        $this->assertEquals('xxx', getViewName('xxx', 'xxx'));
    }
}
