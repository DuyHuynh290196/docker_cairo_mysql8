<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Tests\Acceptance;

use OxidEsales\TestingLibrary\AcceptanceTestCase;

class EnterpriseAcceptanceTestCase extends AcceptanceTestCase
{
    protected function setUp(): void
    {
        if ($this->getTestConfig()->getShopEdition() !== 'EE') {
            $this->markTestSkipped('This test can be run only on Enterprise edition');
        }
        parent::setUp();
        $this->activateTheme('azure');
    }
}
