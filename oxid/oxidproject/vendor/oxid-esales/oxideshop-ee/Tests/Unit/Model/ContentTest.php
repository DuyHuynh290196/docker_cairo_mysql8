<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Tests\Unit\Model;

use \OxidEsales\TestingLibrary\UnitTestCase;

class ContentTest extends UnitTestCase
{
    public function testAssignDeniedByRR()
    {
        $oContent = $this->getMock(\OxidEsales\Eshop\Application\Model\Content::class, array('canRead'));
        $oContent->expects($this->once())->method('canRead')->will($this->returnValue(false));

        $this->assertFalse($oContent->assign(array()));
    }

    // for second shop (buglist_327)
    public function testLoadByIdentSecondShop()
    {
        $this->getConfig()->setShopId(2);
        $oObj = oxNew(\OxidEsales\Eshop\Application\Model\Content::class);
        $this->assertFalse($oObj->loadByIdent('_testLoadId'));
    }
}
