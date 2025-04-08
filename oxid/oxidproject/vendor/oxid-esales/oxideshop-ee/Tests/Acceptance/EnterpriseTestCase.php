<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */
namespace OxidEsales\EshopEnterprise\Tests\Acceptance;

use OxidEsales\TestingLibrary\AcceptanceTestCase;

/**
 * Base class for acceptance tests.
 */
class EnterpriseTestCase extends AcceptanceTestCase
{
    /**
     * Checking if correct shop version is used for running tests.
     */
    protected function setUp(): void
    {
        if ($this->getTestConfig()->getShopEdition() !== 'EE') {
            $this->markTestSkipped("This test is for Enterprise editions only.");
        }
        parent::setUp();
        $this->activateTheme('azure');
    }

    /**
     * @inheritdoc
     */
    public function setUpTestsSuite($testSuitePath)
    {
        parent::setUpTestsSuite($testSuitePath);
    }
}
