<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\EshopEnterprise\Tests\CodeceptionAdmin;

use OxidEsales\EshopEnterprise\Tests\Codeception\AcceptanceAdminTester;

final class NavigationCest
{
    public function shopsStartPageButtonOnSubShopCreate(AcceptanceAdminTester $I): void
    {
        $I->wantToTest('"Shop\'s start page" button on sub-shop creation');
        $testSubShopName = 'Shop #2';
        $adminPanel = $I->loginAdmin();
        $coreSettings = $adminPanel->openCoreSettings();
        $coreSettings->createNewShop($testSubShopName);
        $adminPanel->openShopsStartPage();
        $I->seeInCurrentUrl('shp=2');
    }

    public function shopsStartPageButtonObSubShopSelect(AcceptanceAdminTester $I): void
    {
        $I->wantToTest('"Shop\'s start page" button on existing sub-shop selection');
        $testSubShopName = 'Shop #2';
        $adminPanel = $I->loginAdmin();
        $coreSettings = $adminPanel->openCoreSettings();
        $coreSettings->createNewShop($testSubShopName);
        $coreSettings->createNewShop('Shop #3');
        $coreSettings->selectShopInList($testSubShopName);
        $adminPanel->openShopsStartPage();
        $I->seeInCurrentUrl('shp=2');
    }
}
