<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Tests\Unit\Core;

use OxidEsales\Eshop\Core\Edition\EditionSelector;

class EditionSelectorTest extends \oxUnitTestCase
{
    public function testCheckActiveEdition()
    {
        $editionSelector = new EditionSelector();

        $this->assertSame('EE', $editionSelector->getEdition());
        $this->assertTrue($editionSelector->isEnterprise());
        $this->assertFalse($editionSelector->isProfessional());
        $this->assertFalse($editionSelector->isCommunity());
    }
}
