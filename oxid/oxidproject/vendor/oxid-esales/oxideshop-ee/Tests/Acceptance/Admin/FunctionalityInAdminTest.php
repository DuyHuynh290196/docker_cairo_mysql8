<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Tests\Acceptance\Admin;

use OxidEsales\EshopEnterprise\Tests\Acceptance\EnterpriseAcceptanceTestCase;
use OxidEsales\Eshop\Core\Registry;

/**
 * Functionality in admin test
 */
class FunctionalityInAdminTest extends EnterpriseAcceptanceTestCase
{
    private $originalLanguage = null;

    private $originalSerialKey = null;

    /**
     * Sets default language to English.
     * Set's values to restore after test.
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->originalLanguage = $this->getTranslator()->getLanguage();
        $this->getTranslator()->setLanguage(1);
    }

    protected function tearDown(): void
    {
        if (!is_null($this->originalLanguage)) {
            $this->getTranslator()->setLanguage($this->originalLanguage);
        }
        parent::tearDown();
    }

    /**
     * Testing staging mode and demo mode license functionality
     * login with admin:admin, orange banners and info in html source code
     *
     * @group adminFunctionality
     */
    public function testStagingDemoModes()
    {
        $testConfig = $this->getTestConfig();
        if ($testConfig->isSubShop()) {
            $this->markTestSkipped("Test is not for SubShop");
        }

        // skip CE edition as it has no license
        $this->loginAdmin("Administer Users", "Users");
        $this->openListItem("link=Doe John");
        $this->type("editval[oxuser__oxusername]", "test");
        $this->type("newPassword", "test");
        $this->clickAndWaitFrame("save");
        $this->assertEquals("test", $this->getValue("editval[oxuser__oxusername]"));

        $this->setOriginalSerialKey();

        $this->selectMenu("Master Settings", "Core Settings");
        $this->openTab("License");
        $this->clickAndConfirm("//a[@class='delete']");
        $this->type("editval[oxnewserial]", "D5CK8-LK8VP-77DTS-NMF8A-WG64G-RS582");
        $this->clickAndWaitFrame("save", "edit");
        $this->waitForElement("tShopLicense");
        // Get demo license as it might be different with different installation.
        $sDemoLicense = $this->getText("//table[@id='tShopLicense']/tbody/tr/td[2]/table/tbody/tr/td[1]");
        $this->assertTextPresent($sDemoLicense);

        $this->type("editval[oxnewserial]", "LTUPF-RAQNU-LKQLN-QVN2A-V3PL8-63T8H");
        $this->clickAndWaitFrame("save", "edit");
        $this->assertTextPresent("LTUPF-RAQNU-LKQLN-QVN2A-V3PL8-63T8H");
        $this->logoutAdmin("link=Logout");

        // loggin with admin/admin
        $this->assertTextPresent("Functionality is limited in staging mode");

        $this->loginAdmin(null, null, false, "admin", "admin");
        $this->waitForText("Welcome to the OXID eShop Admin");
        $this->logoutAdmin("link=Logout");
        $this->assertTextPresent("Functionality is limited in staging mode");

        // loggin with test/test
        $this->open(shopURL."admin");
        $this->checkForErrors();
        $this->loginAdmin(null, null, false, "test", "test");
        $this->waitForText("Welcome to the OXID eShop Admin");

        // Open frontend and in source code search for (Staging Mode)
        $this->clearCache();
        $this->openShop();
        $sHtmlSource = $this->getHtmlSource();
        $this->assertStringContainsString( "(Staging Mode)", $sHtmlSource, 'Staging Mode not found in page source' );

        # Cannot use admin/admin here cause with InnoDB we might get a malladmin with limited rights
        # depending on the malladmin oxids.
        # Staging mode admin/admin simply logs in the first malladmin it gets from database.
        # If we get one that has no access to 'Master Settings' test will fail.
        $this->loginAdmin("Master Settings", "Core Settings", false, "test", "test");
        $this->openTab("License");
        $this->assertTextPresent($sDemoLicense);
        $this->assertTextPresent("LTUPF-RAQNU-LKQLN-QVN2A-V3PL8-63T8H");

        //Delete both license keys  and add new Demo key
        $this->clickAndConfirm("//a[@class='delete']");
        $this->clickAndConfirm("//a[@class='delete']");
        $this->assertTextNotPresent($sDemoLicense);
        $this->assertTextNotPresent("LTUPF-RAQNU-LKQLN-QVN2A-V3PL8-63T8H");
        $this->type("editval[oxnewserial]", "9VW9Q-ZLZ6H-6L9Q9-J2KBB-CTU9H-SNS6H");
        $this->clickAndWaitFrame("save", "edit");
        $this->assertTextPresent("9VW9Q-ZLZ6H-6L9Q9-J2KBB-CTU9H-SNS6H");
        $this->logoutAdmin("link=Logout");

        // login with admin/admin
        $this->loginAdmin(null, null, false, "admin", "admin");
        $this->waitForText("Welcome to the OXID eShop Admin");
    }

    /**
     * Administer Users -> Admin Roles (Edit objects rights)
     *
     * @group adminFunctionality
     */
    public function testAdminObjectsRoles()
    {
        $testConfig = $this->getTestConfig();
        if ($testConfig->isSubShop()) {
            $this->executeSql("UPDATE `oxroles` SET `OXSHOPID` = " . $testConfig->getShopId() . " WHERE `OXAREA` = 0;");
        }
        //assigning selection list to parent product
        $this->loginAdmin("Administer Users", "Admin Roles");
        $this->openListItem("link=Bildredaktion");
        $this->assertEquals("Bildredaktion", $this->getValue("editval[oxroles__oxtitle]"));
        $this->assertTrue($this->isChecked("aFields[mxmanageprod]_cust"));
        $this->click("//input[@name='aFields[mxmanageprod]' and @value='2']");
        $this->mouseDown("//input[@name='aFields[mxmanageprod]' and @value='2']");
        $this->assertFalse($this->isChecked("aFields[mxmanageprod]_cust"));
        $this->clickAndWaitFrame("save", "list");
        $this->openTab("Objects");
        $this->assertEquals("off", $this->getValue("//input[@name='aFields[oxarticles][oxarticles][]' and @value='8']"));
        $this->assertEquals("off", $this->getValue("//input[@name='aFields[oxarticles][oxarticles][]' and @value='4']"));
        $this->assertEquals("off", $this->getValue("//input[@name='aFields[oxcategories][oxcategories][]' and @value='8']"));
        $this->assertEquals("off", $this->getValue("//input[@name='aFields[oxcategories][oxcategories][]' and @value='4']"));
        $this->assertTrue($this->isChecked("//input[@name='aFields[oxarticles][oxarticles][]' and @value='3']"));
        $this->assertTrue($this->isChecked("//input[@name='aFields[oxcategories][oxcategories][]' and @value='3']"));
        $this->assertEquals("off", $this->getValue("//input[@id='oxarticlesstate']"));
        $this->assertEquals("off", $this->getValue("//input[@id='oxcategoriesstate']"));
        //enabling X for article and I for category
        $this->click("//input[@name='aFields[oxarticles][oxarticles][]' and @value='8']");
        $this->click("//input[@name='aFields[oxcategories][oxcategories][]' and @value='4']");
        //changing some editable fields for article
        $this->click("link=»");
        //artnum read only
        $this->click("//input[@name='aFields[oxarticles][oxartnum]' and @value='1']");
        $this->mouseDown("//input[@name='aFields[oxarticles][oxartnum]' and @value='1']");
        //oxean disabled
        $this->click("//input[@name='aFields[oxarticles][oxean]' and @value='0']");
        $this->mouseDown("//input[@name='aFields[oxarticles][oxean]' and @value='0']");
        //close options list
        $this->click("//div[@class='closebutton']");
        $this->assertEquals("on", $this->getValue("//input[@name='aFields[oxarticles][oxarticles][]' and @value='8']"));
        $this->assertEquals("off", $this->getValue("//input[@name='aFields[oxarticles][oxarticles][]' and @value='4']"));
        $this->assertEquals("off", $this->getValue("//input[@name='aFields[oxcategories][oxcategories][]' and @value='8']"));
        $this->assertEquals("on", $this->getValue("//input[@name='aFields[oxcategories][oxcategories][]' and @value='4']"));
        $this->assertTrue($this->isChecked("//input[@name='aFields[oxarticles][oxarticles][]' and @value='3']"));
        $this->assertTrue($this->isChecked("//input[@name='aFields[oxcategories][oxcategories][]' and @value='3']"));
        $this->assertEquals("on", $this->getValue("//input[@id='oxarticlesstate']"));
        $this->assertEquals("off", $this->getValue("//input[@id='oxcategoriesstate']"));
        $this->clickAndWait("save");
        $this->assertEquals("on", $this->getValue("//input[@name='aFields[oxarticles][oxarticles][]' and @value='8']"));
        $this->assertEquals("off", $this->getValue("//input[@name='aFields[oxarticles][oxarticles][]' and @value='4']"));
        $this->assertEquals("off", $this->getValue("//input[@name='aFields[oxcategories][oxcategories][]' and @value='8']"));
        $this->assertEquals("on", $this->getValue("//input[@name='aFields[oxcategories][oxcategories][]' and @value='4']"));
        $this->assertTrue($this->isChecked("//input[@name='aFields[oxarticles][oxarticles][]' and @value='3']"));
        $this->assertTrue($this->isChecked("//input[@name='aFields[oxcategories][oxcategories][]' and @value='3']"));
        $this->assertEquals("on", $this->getValue("//input[@id='oxarticlesstate']"), "checkbox customized value is resetted in Object tab");
        $this->assertEquals("off", $this->getValue("//input[@id='oxcategoriesstate']"));
        $this->checkForErrors();
        $this->selectMenu("Master Settings", "Core Settings");
        $this->selectMenu("Shop Settings", "Payment Methods");
        $this->logoutAdmin("link=Logout");
        $this->loginAdmin("Administer Products", "Products", false, "bild@house.com", "useruser");
        $this->type("where[oxarticles][oxartnum]", "10010");
        $this->clickAndWait("submitit");
        $this->openListItem("link=10010");
        $this->assertTrue($this->isEditable("editval[oxarticles__oxtitle]"));
        $this->assertFalse($this->isEditable("editval[oxarticles__oxartnum]"));
        $this->assertElementNotPresent("editval[oxarticles__oxean]");
        $this->assertElementNotPresent("btn.new");
        $this->frame("list");
        $this->assertElementPresent("link=10010");
        $this->clickDeleteListItem(1);
        $this->assertElementNotPresent("link=10010");
        $this->selectMenu("Administer Products", "Categories");
        $this->frame("edit");
        $this->assertElementPresent("btn.new");
        $this->clickCreateNewItem();
        $this->type("editval[oxcategories__oxtitle]", "test_create");
        $this->clickAndWaitFrame("save", "list");
        $this->frame("list");
        $this->type("where[oxcategories][oxtitle]", "test_create");
        $this->clickAndWaitFrame("submitit", 'navigation');
        $this->assertEquals("test_create", $this->getText("//tr[@id='row.1']/td[3]/div"));
        $this->assertElementNotPresent("//tr[@id='row.1']/td[4]/a");
        $this->assertElementNotPresent("del.1");
        //checking if other menu items are not loaded
        $this->clickAndWaitFrame("submitit", 'navigation');
        $this->checkForErrors();
        $this->assertElementNotPresent("link=Master Settings");
        $this->assertElementNotPresent("link=Shop Settings");
    }

    /**
     * check main shop details in shop edit page
     *
     * @group adminFunctionality
     */
    public function testEditShopName()
    {
        $sShopNr = $this->getShopVersionNumber();
        $this->loginAdmin("Master Settings", "Core Settings");
        $this->frame("edit");

        $testConfig = $this->getTestConfig();
        if ($testConfig->isSubShop()) {
            $this->assertTextPresent("Shop is multishop (loads all products from all shops)");
            $this->assertTextPresent("OXID eShop " . $sShopNr . "(1)");
        } else {
            $this->assertTextPresent("Shop is supershop (you can assign products to any shop).");
            $this->assertEquals("OXID eShop " . $sShopNr, $this->getValue("editval[oxshops__oxname]"));
        }
    }

    /**
     * editing main shop details
     *
     * @group adminFunctionality
     */
    public function testEditShopMallTab()
    {
        $testConfig = $this->getTestConfig();
        if ($testConfig->isSubShop()) {
            $this->markTestSkipped("Test is not for SubShop");
        }

        $this->loginAdmin("Master Settings", "Core Settings");
        $this->openTab("Mall");
        $this->assertEquals("Show shop selector", $this->clearString($this->getSelectedLabel("confstrs[iMallMode]")));
        $this->assertEquals("off", $this->getValue("//input[@name='confbools[blSeparateNumbering]' and @value='true']"));
        $this->assertEquals("0", $this->getValue("confstrs[iMallPriceAddition]"));
        $this->assertEquals("%", $this->clearString($this->getSelectedLabel("confbools[blMallPriceAdditionPercent]")));
        $this->assertEquals("on", $this->getValue("//input[@name='confbools[blMallCustomPrice]' and @value='true']"));
        $this->assertEquals("on", $this->getValue("//input[@name='confbools[blMallUsers]' and @value='true']"));

        $this->check("//input[@name='confbools[blSeparateNumbering]' and @value='true']");
        $this->uncheck("//input[@name='confbools[blMallCustomPrice]' and @value='true']");
        $this->uncheck("//input[@name='confbools[blMallUsers]' and @value='true']");
        $this->type("confstrs[iMallPriceAddition]", "10");
        $this->select("confbools[blMallPriceAdditionPercent]", "label=EUR");
        $this->select("confstrs[iMallMode]", "label=Show main shop front page");
        $this->clickAndWait("save");

        $this->assertEquals("Show main shop front page", $this->clearString($this->getSelectedLabel("confstrs[iMallMode]")));
        $this->assertEquals("on", $this->getValue("//input[@name='confbools[blSeparateNumbering]' and @value='true']"));
        $this->assertEquals("10", $this->getValue("confstrs[iMallPriceAddition]"));
        $this->assertEquals("EUR", $this->clearString($this->getSelectedLabel("confbools[blMallPriceAdditionPercent]")));
        $this->assertEquals("off", $this->getValue("//input[@name='confbools[blMallCustomPrice]' and @value='true']"));
        $this->assertEquals("off", $this->getValue("//input[@name='confbools[blMallUsers]' and @value='true']"));

        //updating db views
        $this->clickAndConfirm("//input[@value='Update DB Views now']");
        $this->assertEquals("10", $this->getValue("confstrs[iMallPriceAddition]"));
        $this->assertEquals("on", $this->getValue("//input[@name='confbools[blSeparateNumbering]' and @value='true']"));
        $this->assertEquals("off", $this->getValue("//input[@name='confbools[blMallCustomPrice]' and @value='true']"));
        $this->assertEquals("off", $this->getValue("//input[@name='confbools[blMallUsers]' and @value='true']"));
        $this->frame("list");
    }

    /**
     * checking if information saving in subshop is wokrking when subshop has diff url
     * bug #2842
     *
     * @group adminFunctionality
     */
    public function testSubshopWithDifferentUrl()
    {
        $testConfig = $this->getTestConfig();
        if (!$testConfig->isSubShop()) {
            $this->markTestSkipped("Test is for SubShop only");
        }

        $sShopNr = $this->getShopVersionNumber();
        $this->loginAdmin("Master Settings", "Core Settings");
        //checking subshop
        $this->openTab("Mall");
        $this->type("confstrs[sMallShopURL]", "http://www.eshop.com");
        $this->clickAndWait("save");
        $this->assertEquals("http://www.eshop.com", $this->getValue("confstrs[sMallShopURL]"));
        $this->selectMenu("Customer Info", "CMS Pages");
        $this->openListItem("link=oxstdfooter");
        $this->type("editval[oxcontents__oxtitle]", "standard footer1");
        $this->clickAndWait("saveContent");
        $this->assertTextNotPresent("ERROR! Identcode already in use!");
        $this->assertEquals("standard footer1", $this->getValue("editval[oxcontents__oxtitle]"));

        //checking if main shop was not affected
        $this->frame("navigation");
        $this->selectAndWaitFrame("selectshop", "label=OXID eShop ". $sShopNr, "edit");
        $this->waitForElement("link=Customer Info");
        $this->selectMenu("Customer Info", "CMS Pages");
        $this->type("where[oxcontents][oxloadid]", "oxstdfooter");
        $this->clickAndWaitFrame("submitit");
        $this->openListItem("link=oxstdfooter");
        $this->assertEquals("standard footer", $this->getValue("editval[oxcontents__oxtitle]"));
    }

    /**
     * checking if prices for variants in subshop can be saved. For bug#2570
     *
     * @group adminFunctionality
     */
    public function testProductVariantsInSubshopAllowCustomPrice()
    {
        $testConfig = $this->getTestConfig();

        if (!$testConfig->isSubShop()) {
            $this->markTestSkipped("Test is for SubShop only");
        }

        $this->loginAdmin("Master Settings", "Core Settings");
        $this->openTab("Mall");
        $this->check("//input[@name='confbools[blMallCustomPrice]' and @value='true']");
        $this->clickAndWait("save");
        $this->selectMenu("Administer Products", "Products");
        $this->type("where[oxarticles][oxartnum]", "1002");
        $this->clickAndWait("submitit");
        $this->clickAndWaitFrame("link=1002", "edit");
        $this->openTab("Variants");
        $this->assertEquals("55", $this->getValue("editval[1002-1][oxarticles__oxprice]"));
        $this->type("editval[1002-1][oxarticles__oxprice]", "20");
        $this->clickAndWait("//input[@value=' Save Variants']");
        $this->assertEquals("20", $this->getValue("editval[1002-1][oxarticles__oxprice]"));
        $this->clearCache();
        $this->openShop();
        $this->searchFor("1002");
        $this->assertEquals("%PRICE_FROM% 20,00 €", $this->getText("//form[@name='tobasketsearchList_1']//span[@class='price']"));
        $this->clickAndWait("searchList_1");

        $this->selectVariant("variants", 1, "var1 [EN] šÄßüл", "var1 [EN] šÄßüл ");
        $this->assertEquals("20,00 € *", $this->getText("productPrice"));
        $this->selectVariant("variants", 1, "var2 [EN] šÄßüл", "var2 [EN] šÄßüл ");
        $this->assertEquals("67,00 € *", $this->getText("productPrice"));
    }

    /**
     * checking if prices for variants in subshop can not be saved. For bug#2570
     *
     * @group adminFunctionality
     */
    public function testProductVariantsInSubshopNotAllowCustomPrice()
    {
        $testConfig = $this->getTestConfig();
        if (!$testConfig->isSubShop()) {
            $this->markTestSkipped("Test is for SubShop only");
        }

        $this->loginAdmin("Master Settings", "Core Settings");
        $this->openTab("Mall");
        $this->uncheck("//input[@name='confbools[blMallCustomPrice]' and @value='true']");
        $this->clickAndWait("save");
        $this->selectMenu("Administer Products", "Products");
        $this->type("where[oxarticles][oxartnum]", "1002");
        $this->clickAndWait("submitit");
        $this->clickAndWaitFrame("link=1002", "edit");
        $this->openTab("Variants");
        $this->assertEquals("55", $this->getValue("editval[1002-1][oxarticles__oxprice]"));
        $this->assertEquals("67", $this->getValue("editval[1002-2][oxarticles__oxprice]"));
        $this->clearCache();
        $this->openShop();
        $this->searchFor("1002");
        $this->assertEquals("%PRICE_FROM% 55,00 €", $this->getText("//form[@name='tobasketsearchList_1']//span[@class='price']"));
        $this->clickAndWait("searchList_1");
        $this->selectVariant("variants", 1, "var1 [EN] šÄßüл", "var1 [EN] šÄßüл ");
        $this->assertEquals("55,00 € *", $this->getText("productPrice"));
        $this->selectVariant("variants", 1, "var2 [EN] šÄßüл", "var2 [EN] šÄßüл ");
        $this->assertEquals("67,00 € *", $this->getText("productPrice"));
        $this->loginAdmin("Master Settings", "Core Settings");
        $this->openTab("Mall");
        $this->check("//input[@name='confbools[blMallCustomPrice]' and @value='true']");
        $this->clickAndWait("save");
        $this->selectMenu("Administer Products", "Products");
        $this->type("where[oxarticles][oxartnum]", "1002");
        $this->clickAndWait("submitit");
        $this->clickAndWaitFrame("link=1002", "edit");
        $this->openTab("Variants");
        $this->assertEquals("55", $this->getValue("editval[1002-1][oxarticles__oxprice]"));
        $this->assertEquals("67", $this->getValue("editval[1002-2][oxarticles__oxprice]"));
        $this->type("editval[1002-1][oxarticles__oxprice]", "40");
        $this->clickAndWait("//input[@value=' Save Variants']");
        $this->assertEquals("40", $this->getValue("editval[1002-1][oxarticles__oxprice]"));
    }

    /**
     * Set serial key from Shop to test class parameter.
     * It is used in a test which changes serial key.
     */
    private function setOriginalSerialKey()
    {
        $this->selectMenu("Master Settings", "Core Settings");
        $this->openTab("License");
        $this->originalSerialKey = $this->getText(".//*[@id='tShopLicense']/tbody/tr[1]/td[2]/table/tbody/tr/td[1]");
    }
}
