<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Tests\Integration\Core;

use OxidEsales\Eshop\Core\Routing\ShopControllerMapProvider;
use OxidEsales\TestingLibrary\UnitTestCase;

/**
 * The functionality of a controllerProviders is implemented with inheritance for the different shop editions.
 * The controller keys for the different editions are merged into one array.
 * This test class should cover edge cases where e.g. one controller has different values in different editions.
 *
 * @package OxidEsales\EshopEnterprise\Tests\Integration\Core
 */
class ShopControllerMapProviderTest extends UnitTestCase
{
    /**
     * @dataProvider dataProviderEdgeCaseProviderKeys
     *
     * @param string $controllerKey
     * @param string $expectedClass
     */
    public function testGetControllerMapMergeAllEditions($controllerKey, $expectedClass)
    {
        $controllerProvider = oxNew(ShopControllerMapProvider::class);
        $controllerMap = $controllerProvider->getControllerMap();
        $this->assertEquals($expectedClass, $controllerMap[$controllerKey]);
    }

    /**
     * @return array
     */
    public function dataProviderEdgeCaseProviderKeys()
    {
        return [
            // key only in CE
            ['language', \OxidEsales\Eshop\Application\Controller\Admin\LanguageController::class],

            // key only in EE
            ['delivery_mall', \OxidEsales\Eshop\Application\Controller\Admin\DeliveryMall::class],
        ];
    }
}
