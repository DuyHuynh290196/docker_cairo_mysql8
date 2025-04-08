<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopProfessional\Tests\Unit\Core;

use OxidEsales\Eshop\Core\Edition\EditionSelector;

class EditionSelectorTest extends \oxUnitTestCase
{
    public function testCheckActiveEdition()
    {
        if ($this->getTestConfig()->getShopEdition() !== 'PE') {
            $this->markTestSkipped('This test is for Professional editions only.');
        }

        $editionSelector = new EditionSelector();

        $this->assertSame('PE', $editionSelector->getEdition());
        $this->assertTrue($editionSelector->isProfessional());
        $this->assertFalse($editionSelector->isCommunity());
        $this->assertFalse($editionSelector->isEnterprise());
    }
}
