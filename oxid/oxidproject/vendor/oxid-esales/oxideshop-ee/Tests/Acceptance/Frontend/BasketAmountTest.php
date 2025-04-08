<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Tests\Acceptance\Frontend;

use OxidEsales\EshopEnterprise\Tests\Acceptance\EnterpriseTestCase;

class BasketAmountTest extends EnterpriseTestCase
{
    public function tearDown(): void
    {
        $this->activateTheme('azure');
        parent::tearDown();
    }

    /**
     * Test that cookie with basket items amount is set.
     *
     * @group frontend
     * @group flow-theme
     */
    public function testRemoveFromBasketItemsIsVisible()
    {
        $productId = '1000';
        $this->startMinkSession('selenium');

        $this->activateTheme('flow');

        $this->openShop();
        $this->assertBasketEmpty();

        $this->addToBasket($productId, 2);
        $this->openShop();
        $this->assertSame(
            '2',
            $this->getMenuBasketAmount(),
            'Basket amount should be 2 in navigation menu.'
        );

        $this->changeBasket($productId, 0);
        $this->openShop();
        $this->assertBasketEmpty();
    }

    /**
     * @return string
     */
    private function getMenuBasketAmount()
    {
        return $this->getText('navigation-basket-amount');
    }

    /*
     * Assert that basket is empty.
     * Form error message if not.
     */
    private function assertBasketEmpty()
    {
        $this->assertSame(
            '',
            $this->getMenuBasketAmount(),
            'Basket is empty, however amount is: ' . $this->getMenuBasketAmount()
        );
    }
}
