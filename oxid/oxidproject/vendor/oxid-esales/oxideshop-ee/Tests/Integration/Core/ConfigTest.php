<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Tests\Integration\Core;

use OxidEsales\Eshop\Core\Registry;
use OxidEsales\EshopEnterprise\Tests\Integration\Internal\Framework\Console\SubShopManagerTrait;
use OxidEsales\EshopEnterprise\Tests\Integration\User\UserTestCase;

class ConfigTest extends UserTestCase
{
    use SubShopManagerTrait;

    /**
     * Data provider for testSaveSystemConfigurationParameter
     */
    public function getSystemConfigurationParameters()
    {
        return array(
            array('arr', 'aPraram', array(1, 3)),
            array('aarr', 'aAParam', array('a' => 1)),
            array('bool', 'blParam', true),
            array('num', 'numNum', 2),
            array('int', 'iNum1', 0),
            array('int', 'iNum2', 4),
        );
    }

    /**
     * @dataProvider getSystemConfigurationParameters
     */
    public function testSaveSystemConfigurationParameterInSubShop($sType, $sName, $sValue): void
    {
        $this->createSubShop(2);
        Registry::getConfig()->setShopId(2);

        $oConfig = oxNew('oxConfig');
        $oConfig->saveSystemConfigParameter($sType, $sName, $sValue);

        if ($sType == 'num') {
            $this->assertEquals((float) $sValue, $oConfig->getSystemConfigParameter($sName));
        } else {
            $this->assertEquals($sValue, $oConfig->getSystemConfigParameter($sName));
        }
    }
}
