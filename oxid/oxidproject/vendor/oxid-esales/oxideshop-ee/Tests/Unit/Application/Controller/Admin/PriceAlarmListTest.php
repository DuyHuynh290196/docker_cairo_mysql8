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
class PriceAlarmListTest extends UnitTestCase
{
    /**
     * PriceAlarm_List::BuildWhere() test case
     *
     * @return null
     */
    public function testBuildWhere()
    {
        $this->setRequestParameter('where', array("oxpricealarm" => array("oxprice" => 15), "oxarticles" => array("oxprice" => 15)));

        $oView = $this->getMock(\OxidEsales\Eshop\Application\Controller\Admin\PriceAlarmList::class, array("_authorize"));
        $oView->expects($this->any())->method('_authorize')->will($this->returnValue(true));
        $oView->init();

        $queryWhereParts = $oView->buildWhere();
        $this->assertEquals($this->getConfig()->getShopId(), $queryWhereParts[getViewName("oxpricealarm") . '.oxshopid']);
    }
}
