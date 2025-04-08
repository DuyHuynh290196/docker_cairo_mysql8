<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Tests\Unit\Core;

use OxidEsales\TestingLibrary\UnitTestCase;

class I18nTest extends UnitTestCase
{
    /**
     * base test
     */
    public function testGetViewName()
    {
        $i18nObject = oxNew(\OxidEsales\Eshop\Core\Model\MultiLanguageModel::class);
        $i18nObject->init('oxarticles');

        $i18nObject->setLanguage(1);
        $i18nObject->setEnableMultilang(false);

        $i18nObject->setForceCoreTableUsage(true);
        $this->assertEquals(getViewName('oxarticles', -1, -1), $i18nObject->getViewName());
        $this->assertEquals(getViewName('oxarticles', -1, 1), $i18nObject->getViewName(0));
        $this->assertEquals(getViewName('oxarticles', -1, -1), $i18nObject->getViewName(1));
        $this->assertEquals(getViewName('oxarticles', -1, -1), $i18nObject->getViewName());

        $i18nObject->setEnableMultilang(true);
        $this->assertEquals(getViewName('oxarticles', 1, -1), $i18nObject->getViewName());
        $this->assertEquals(getViewName('oxarticles', 1, -1), $i18nObject->getViewName(1));
        $this->assertEquals(getViewName('oxarticles', 1, 1), $i18nObject->getViewName(0));
        $this->assertEquals(getViewName('oxarticles', 1, -1), $i18nObject->getViewName());
    }

    /**
     * This test is for bugfix #4536.
     *
     * Tests if item is loaded from another subshop.
     * This test is important for certain cases when item is loaded from different subshops
     */
    public function testLoadItemFromAnyShop()
    {
        $this->getSession()->setVariable("actshop", 16);

        $oBaseObject = oxNew(\OxidEsales\Eshop\Core\Model\BaseModel::class);
        $oBaseObject->init("oxarticles");
        $oBaseObject->load('1126');

        $this->assertEquals("Bar-Set ABSINTH", $oBaseObject->oxarticles__oxtitle->value);

    }
}
