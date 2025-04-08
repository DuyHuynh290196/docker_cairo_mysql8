<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Tests\Unit\Core;

use \oxConfig;
use \oxLang;
use PHPUnit\Framework\MockObject\MockObject;
use \oxTestModules;

class Unit_Core_oxViewNameGeneratorTest extends \oxUnitTestCase
{
    public function testMultiShopTableViewNameGenerationWhenDefaultShopIdIsUsed()
    {
        /** @var oxConfig|MockObject $config */
        $config = $this->getMock(\OxidEsales\Eshop\Core\Config::class, array('getShopId', 'isMall'));
        $config->expects($this->any())->method('getShopId')->will($this->returnValue(2));
        $config->expects($this->any())->method('isMall')->will($this->returnValue(true));
        $config->setConfigParam('aMultiShopTables', array('test_table1', 'test_table2'));

        /** @var oxLang|MockObject $language */
        $language = $this->getMock(\OxidEsales\Eshop\Core\Language::class, array('getMultiLangTables', 'getBaseLanguage', 'getLanguageAbbr'));
        $language->expects($this->any())->method('getMultiLangTables')->will($this->returnValue(array()));

        $viewNameGenerator = oxNew(\OxidEsales\Eshop\Core\TableViewNameGenerator::class, $config, $language);
        $this->assertEquals('oxv_test_table1_2', $viewNameGenerator->getViewName('test_table1'));
    }

    public function testLanguageTableViewNameGenerationWhenLanguageIsPassed()
    {
        /** @var oxConfig|MockObject $config */
        $config = $this->getMock(\OxidEsales\Eshop\Core\Config::class, array('getShopId', 'isMall'));
        $config->expects($this->any())->method('getShopId')->will($this->returnValue(1));
        $config->expects($this->any())->method('isMall')->will($this->returnValue(true));
        $config->setConfigParam('aMultiShopTables', array('test_table1', 'test_table2'));

        /** @var oxLang|MockObject $language */
        $language = $this->getMock(\OxidEsales\Eshop\Core\Language::class, array('getMultiLangTables', 'getBaseLanguage', 'getLanguageAbbr'));
        $language->expects($this->any())->method('getMultiLangTables')->will($this->returnValue(array()));

        $viewNameGenerator = oxNew(\OxidEsales\Eshop\Core\TableViewNameGenerator::class, $config, $language);
        $this->assertEquals('oxv_test_table1_2', $viewNameGenerator->getViewName('test_table1', 1, 2));
    }
}
