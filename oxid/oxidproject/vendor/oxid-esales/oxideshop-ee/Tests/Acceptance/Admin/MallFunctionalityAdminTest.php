<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Tests\Acceptance\Admin;

use OxidEsales\EshopCommunity\Internal\Container\ContainerFactory;
use OxidEsales\EshopCommunity\Internal\Framework\Module\Configuration\Dao\ShopConfigurationDaoInterface;
use OxidEsales\EshopEnterprise\Tests\Acceptance\EnterpriseAcceptanceTestCase;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Core\DatabaseProvider;

/**
 * Mall functionality: subshops & inheritance.
 */
class MallFunctionalityAdminTest extends EnterpriseAcceptanceTestCase
{
    /**
     * Sets default language to English.
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->getTranslator()->setLanguage(1);
    }

    /**
     * #2230: If inherited assignment is removed in subshop - assignment is also removed for parentshop
     *
     * @group subshop
     */
    public function testAssigningProductsInInheritedCategory2()
    {
        $testConfig = $this->getTestConfig();
        if ($testConfig->isSubShop()) {
            $this->markTestSkipped('Test is not for subshops');
        }

        $sShopNr = $this->getShopVersionNumber();
        //creating subshop
        $this->loginAdmin("Master Settings", "Core Settings");

        $this->_createSubShop( 'subshop', true, true );

        //assign category to subshop
        $this->openListItem("link=OXID eShop ". $sShopNr ." (1)");
        $this->selectMenu("Administer Products", "Categories");
        $this->clickAndWait("link=Sorting");
        $this->openListItem("link=1 [EN] category šÄßüл");
        $this->openTab("Mall");
        $this->check("scdiv0_0");
        $this->clickAndWaitFrame("save");

        //assigning category to product
        $this->selectMenu("Administer Products", "Products");                $this->type("where[oxarticles][oxartnum]", "10012");
        $this->clickAndWaitFrame("submitit");
        $this->openListItem("link=10012");
        $this->openTab("Extended");
        $this->click("//input[@value='Assign Categories']");
        $this->usePopUp();
        $this->assertElementText("1 [EN] category šÄßüл", "//div[@id='container1_c']/table/tbody[2]/tr[1]/td[1]");
        //drag and drop 1 itm from one list to another
        $this->dragAndDrop("//div[@id='container1_c']/table/tbody[2]/tr[1]/td[1]", "container2");
        $this->assertElementText("1 [EN] category šÄßüл", "//div[@id='container2_c']/table/tbody[2]/tr[1]/td[1]");
        $this->close();

        //cheking assigement in subshop
        $this->loginSubshopAdmin("Administer Products", "Products");
        $this->frame("list");
        $this->type("where[oxarticles][oxartnum]", "10012");
        $this->clickAndWait("submitit");
        $this->openListItem("link=10012");
        $this->openTab("Extended");
        $this->click("//input[@value='Assign Categories']");
        //unassigning product to category
        $this->usePopUp("//div[@id='container2_c']/table/tbody[2]/tr[1]/td[1]");
        //drag and drop 1 itm from one list to another
        $this->dragAndDrop("//div[@id='container2_c']/table/tbody[2]/tr[1]/td[1]", "container1");
        $this->assertElementText("1 [EN] category šÄßüл", "//div[@id='container1_c']/table/tbody[2]/tr[1]/td[1]");
        $this->close();
        $this->logoutAdmin();

        //checking if assigment still exist
        $this->loginAdmin("Administer Products", "Products");
        $this->frame("list");
        $this->type("where[oxarticles][oxartnum]", "10012");
        $this->clickAndWait("submitit");
        $this->clickAndWaitFrame("link=10012", "edit");
        $this->openTab("Extended");
        $this->click("//input[@value='Assign Categories']");
        $this->usePopUp();
        $this->assertElementText("1 [EN] category šÄßüл", "//div[@id='container2_c']/table/tbody[2]/tr[1]/td[1]");
        $this->close();
    }

    /**
     * creating subshop: inherit. checking assigning to subshop
     *
     * @group subshop
     */
    public function testCreateSubshopInheritAssign()
    {
        $testConfig = $this->getTestConfig();
        if ($testConfig->isSubShop()) {
            $this->markTestSkipped('Test is not for subshops');
        }

        $iShopsToCreate = 65;

        for ($iShop=2; $iShop<$iShopsToCreate; $iShop++) {
            $this->addSubshop($iShop);
        }

        $sShopNr = $this->getShopVersionNumber();

        //creating subshop
        $this->loginAdmin("Master Settings", "Core Settings");

        $this->clickCreateNewItem( 'btn.new' );
        $this->assertTextNotPresent("Attention: Creating this sub shop you have to update views, otherwise shops will not work! While generating views, the performance of OXID eShop could be affected.");

        $this->selectMenu("Master Settings", "Core Settings");
        $this->_createSubShop( 'subshop' );
        $this->logoutAdmin();

        //assign category to subshop
        $this->clearCookies();
        $this->loginAdmin("Administer Products", "Categories");

        $this->clickAndWaitFrame("link=Test category 0 [EN] šÄßüл", "edit");
        $this->openTab("Mall");
        $this->check("scdiv0_63");
        $this->clickAndWait("save");
        $this->logoutAdmin();

        //checking frontend
        $this->clearCookies();
        $this->openShop(false, false, true);

        $this->clickAndWait("link=Test category 0 [EN] šÄßüл");
        $this->assertEquals("Test category 0 [EN] šÄßüл", $this->getHeadingText("//h1"));
        $this->assertElementPresent("attributeFilter[testattribute1]");

        $this->clickAndWait("moreSubCat_1");
        $this->assertEquals("Test category 1 [EN] šÄßüл", $this->getHeadingText("//h1"));
        $this->assertEquals("Test product 3 [EN] šÄßüл", $this->getText("productList_1"));

        $this->clickAndWait("//form[@name='tobasketproductList_1']//a");
        $this->assertEquals("Test product 3 [EN] šÄßüл", $this->getText("//h1"));

        $this->searchFor("100");
        $this->assertEquals("Test product 0 [EN] šÄßüл", $this->getText("searchList_1"));
        $this->assertEquals("Test product 1 [EN] šÄßüл", $this->getText("searchList_2"));
        $this->assertEquals("Test product 2 [EN] šÄßüл", $this->getText("searchList_3"));
        $this->assertEquals("Test product 3 [EN] šÄßüл", $this->getText("searchList_4"));
        $this->clickAndWait("//dl[@id='footerManufacturers']//a[text()='Manufacturer [EN] šÄßüл']");
        $this->assertEquals("Manufacturer [EN] šÄßüл", $this->getText("//h1"));
        $this->assertEquals("Manufacturer description [EN] šÄßüл", $this->getText("catDesc"));
        $this->assertEquals("Test product 0 [EN] šÄßüл", $this->getText("productList_1"));
        $this->assertEquals("Test product 1 [EN] šÄßüл", $this->getText("productList_2"));
        $this->assertEquals("Test product 2 [EN] šÄßüл", $this->getText("productList_3"));
        $this->assertEquals("Test product 3 [EN] šÄßüл", $this->getText("productList_4"));

        //unassigning category from subshop
        $this->clearCookies();
        $this->loginSubshopAdmin("Administer Products", "Categories");
        $this->assertEquals("English", $this->getSelectedLabel("changelang"));
        $this->changeAdminListLanguage('Deutsch');
        $this->clickAndWait("link=Title");
        $this->waitForFrameAfterAction('edit');
        $this->waitForFrameAfterAction('list');
        $this->assertEquals("Test category 0 [DE] šÄßüл", $this->getText("//tr[@id='row.1']/td[3]"));

        $this->click("una.1");
        sleep(3);
        $this->getConfirmation();
        $this->waitForFrameAfterAction('edit');
        $this->waitForFrameAfterAction('list');
        $this->assertElementNotPresent("//tr[@id='row.1']");
    }

    /**
     * deleting subshop
     *
     * @group subshop
     */
    public function testDeleteSubshop()
    {
        $testConfig = $this->getTestConfig();
        if ($testConfig->isSubShop()) {
            $this->markTestSkipped('Test is not for subshops');
        }

        $this->loginAdmin("Master Settings", "Core Settings");

        $this->_createSubShop( 'subshop_1' );

        $this->waitForFrameToLoad('navigation', 5000, true);
        $this->check("editval[oxshops__oxactive]");
        $this->clickAndWaitFrame("save", "list");
        $this->frame("list");

        // Delete subshop from Main Shop.
        // Deleting active shop is potentially harmful when there is a module activated in main shop.
        // Templates would be loaded from main Shop but the module classes wouldn't.
        $this->loginAdmin("Master Settings", "Core Settings", true);
        //filter
        $this->type("where[oxshops][oxname]", "subshop_1");
        $this->clickAndWaitFrame("submitit");
        $this->assertEquals("subshop_1 (2)", $this->getText("//tr[@id='row.1']/td[1]"));
        //exit;

        $this->assertElementNotPresent("//tr[@id='row.2']/td[1]", 'subshop_1 (2) was not found');
        $this->clickDeleteListItem(1);
        $this->assertElementNotPresent("//tr[@id='row.1']/td[1]");
        //check in frontend if everything is ok
        $this->clearCache();
        $this->openShop();
        $this->assertElementNotPresent("link=subshop");
        $this->waitForElement("newItems", 5);
        $this->assertElementPresent("newItems");
    }


    /**
     * creating subshop: inherit. checking info unassigning from subshop
     *
     * @group subshop
     */
    public function testCreateSubshopInheritUnassign()
    {
        $testConfig = $this->getTestConfig();
        if ($testConfig->isSubShop()) {
            $this->markTestSkipped('Test is not for subshops');
        }

        //creating subshop
        $this->loginAdmin("Master Settings", "Core Settings");

        $this->_createSubShop( 'subshop' );

        //checking if in shop admin unassign works correctly
        $this->loginSubshopAdmin("Master Settings", "Distributors");
        $this->assertEquals("English", $this->getSelectedLabel("changelang"));
        $this->changeAdminListLanguage('Deutsch');
        $this->clickAndWait("link=Title");
        $this->assertEquals("1 DE distributor šÄßüл", $this->getText("//tr[@id='row.1']/td[2]"));
        $this->clickAndConfirm("una.1");
        $this->assertNotEquals("1 DE distributor šÄßüл", $this->getText("//tr[@id='row.1']/td[2]"));

        $this->selectMenu("Master Settings", "Brands/Manufacturers");
        $this->clickAndWait("link=Title");
        $this->assertEquals("1 DE manufacturer šÄßüл", $this->getText("//tr[@id='row.1']/td[2]"));
        $this->clickAndConfirm("una.1");
        $this->assertNotEquals("1 DE manufacturer šÄßüл", $this->getText("//tr[@id='row.1']/td[2]"));

        $this->selectMenu("Shop Settings", "Discounts");
        $this->clickAndWait("link=Name");
        $this->assertEquals("1 DE test discount šÄßüл", $this->getText("//tr[@id='row.1']/td[3]"));
        $this->clickAndConfirm("una.1");
        $this->assertNotEquals("1 DE test discount šÄßüл", $this->getText("//tr[@id='row.1']/td[3]"));

        $this->selectMenu("Shop Settings", "Shipping Methods");
        $this->clickAndWait("link=Name");

        $this->assertEquals("1 DE test S&H set šÄßüл", $this->getText("//tr[@id='row.1']/td[2]"));
        $this->clickAndConfirm("una.1");
        $this->assertNotEquals("1 DE test S&H set šÄßüл", $this->getText("//tr[@id='row.1']/td[2]"));

        $this->selectMenu("Shop Settings", "Shipping Cost Rules");
        $this->clickAndWait("link=Name");
        $this->assertEquals("1 DE S&H šÄßüл", $this->getText("//tr[@id='row.1']/td[3]"));
        $this->clickAndConfirm("una.1");
        $this->assertNotEquals("1 DE S&H šÄßüл", $this->getText("//tr[@id='row.1']/td[3]"));

        $this->selectMenu("Shop Settings", "Coupon Series");
        $this->clickAndWait("link=Name");
        $this->assertEquals("1 Coupon šÄßüл", $this->getText("//tr[@id='row.1']/td[2]"));
        $this->clickAndConfirm("una.1");
        $this->assertNotEquals("1 Coupon šÄßüл", $this->getText("//tr[@id='row.1']/td[2]"));

        $this->selectMenu("Shop Settings", "Gift Wrapping");
        $this->clickAndWait("link=Name");
        $this->assertEquals("1 DE Gift Wrapping šÄßüл", $this->getText("//tr[@id='row.1']/td[3]"));
        $this->clickAndConfirm("una.1");
        $this->assertNotEquals("1 DE Gift Wrapping šÄßüл", $this->getText("//tr[@id='row.1']/td[3]"));

        $this->selectMenu("Administer Products", "Products");
        $this->clickAndWait("link=Prod.No.");
        $this->assertEquals("1000", $this->getText("//tr[@id='row.1']/td[2]"));
        $this->clickAndConfirm("una.1");
        $this->assertNotEquals("1000", $this->getText("//tr[@id='row.1']/td[2]"));

        $this->selectMenu("Administer Products", "Attributes");
        $this->clickAndWait("link=Name");
        $this->assertEquals("1 [DE] Attribute šÄßüл", $this->getText("//tr[@id='row.1']/td[1]"));
        $this->clickAndConfirm("una.1");
        $this->assertNotEquals("1 [DE] Attribute šÄßüл", $this->getText("//tr[@id='row.1']/td[1]"));

        $this->selectMenu("Administer Products", "Selection Lists");
        $this->clickAndWait("link=Title");
        $this->waitForElement("una.1");
        $this->assertEquals("1 [DE] sellist šÄßüл", $this->getText("//tr[@id='row.1']/td[1]"));
        $this->clickAndConfirm("una.1");
        $this->assertNotEquals("1 [DE] sellist šÄßüл", $this->getText("//tr[@id='row.1']/td[1]"));

        $this->selectMenu("Customer Info", "News");
        $this->clickAndWait("link=Title");
        $this->assertEquals("1 [DE] Test news šÄßüл", $this->getText("//tr[@id='row.1']/td[3]"));
        $this->clickAndConfirm("una.1");
        $this->assertNotEquals("1 [DE] Test news šÄßüл", $this->getText("//tr[@id='row.1']/td[3]"));

        $this->selectMenu("Customer Info", "Links");
        $this->clickAndWait("link=URL");
        $this->assertEquals("http://www.1google.com", $this->getText("//tr[@id='row.1']/td[3]"));
        $this->clickAndConfirm("una.1");
        $this->assertNotEquals("http://www.1google.com", $this->getText("//tr[@id='row.1']/td[3]"));

        $this->waitForPageToLoad();
    }

    /**
    * creating subshop: supershop
    *
    * @group subshop
    */
    public function testCreateSubshopSupershopAssign()
    {
        $testConfig = $this->getTestConfig();
        if ($testConfig->isSubShop()) {
            $this->markTestSkipped('Test is not for subshops');
        }

        $sShopNr = $this->getShopVersionNumber();
        //make base shop as supershop
        $aParams = array("oxissupershop" => 1);
        $this->callShopSC("oxShop", "save", 1, $aParams);

        $this->loginAdmin("Master Settings", "Core Settings");

        //creating simple shop
        $this->_createSubShop( 'subshop', false, false, false, false );

        $this->openListItem("link=OXID eShop ". $sShopNr ." (1)");

        //Distributors
        $this->selectMenu("Master Settings", "Distributors");
        $this->assertEquals("English", $this->getSelectedLabel("changelang"));
        $this->changeAdminListLanguage('Deutsch');
        $this->clickAndWait("link=Title");
        $this->clickAndWaitFrame("link=1 DE distributor šÄßüл", "edit");
        $this->openTab("Mall");
        $this->assertElementPresent("//input[@id='scdiv0_0' and @value='2']");
        $this->click("scdiv0_0");
        $this->clickAndWait("save");
        //Manufacturers
        $this->selectMenu("Master Settings", "Brands/Manufacturers");
        $this->clickAndWait("link=Title");
        $this->clickAndWaitFrame("link=1 DE manufacturer šÄßüл", "edit");
        $this->openTab("Mall");
        $this->assertElementPresent("//input[@id='scdiv0_0' and @value='2']");
        $this->click("scdiv0_0");
        $this->clickAndWait("save");
        //Discounts
        $this->selectMenu("Shop Settings", "Discounts");
        $this->clickAndWait("link=Name");
        $this->clickAndWaitFrame("link=1 DE test discount šÄßüл", "edit");
        $this->openTab("Mall");
        $this->assertElementPresent("//input[@id='scdiv0_0' and @value='2']");
        $this->click("scdiv0_0");
        $this->clickAndWait("save");
        //Shipping Methods
        $this->selectMenu("Shop Settings", "Shipping Methods");
        $this->clickAndWait("link=Name");
        $this->clickAndWaitFrame("link=1 DE test S&H set šÄßüл", "edit");
        $this->openTab("Mall");
        $this->assertElementPresent("//input[@id='scdiv0_0' and @value='2']");
        $this->click("scdiv0_0");
        $this->clickAndWait("save");
        //Shipping Cost Rules
        $this->selectMenu("Shop Settings", "Shipping Cost Rules");
        $this->clickAndWait("link=Name");
        $this->clickAndWaitFrame("link=1 DE S&H šÄßüл", "edit");
        $this->openTab("Mall");
        $this->assertElementPresent("//input[@id='scdiv0_0' and @value='2']");
        $this->click("scdiv0_0");
        $this->clickAndWait("save");
        //Coupon Series
        $this->selectMenu("Shop Settings", "Coupon Series");
        $this->clickAndWait("link=Name");
        $this->clickAndWaitFrame("link=Test coupon 1 šÄßüл", "edit");
        $this->openTab("Mall");
        $this->assertElementPresent("//input[@id='scdiv0_0' and @value='2']");
        $this->click("scdiv0_0");
        $this->clickAndWait("save");
        //Gift Wrapping
        $this->selectMenu("Shop Settings", "Gift Wrapping");
        $this->clickAndWait("link=Type");
        $this->clickAndWaitFrame("link=Greeting Card", "edit");
        $this->openTab("Mall");
        $this->assertElementPresent("//input[@id='scdiv0_0' and @value='2']");
        $this->click("scdiv0_0");
        $this->clickAndWait("save");
        //Products
        $this->selectMenu("Administer Products", "Products");
        $this->clickAndWait("link=Prod.No.");
        $this->clickAndWaitFrame("link=1000", "edit");
        $this->openTab("Mall");
        $this->assertElementPresent("//input[@id='scdiv0_0' and @value='2']");
        $this->click("scdiv0_0");
        $this->clickAndWait("save");
        //Attributes
        $this->selectMenu("Administer Products", "Attributes");
        $this->clickAndWait("link=Name");
        $this->clickAndWaitFrame("link=1 [DE] Attribute šÄßüл", "edit");
        $this->openTab("Mall");
        $this->assertElementPresent("//input[@id='scdiv0_0' and @value='2']");
        $this->click("scdiv0_0");
        $this->clickAndWait("save");
        //Categories
        $this->selectMenu("Administer Products", "Categories");
        $this->clickAndWait("link=Title");
        $this->clickAndWaitFrame("link=1 [DE] category šÄßüл", "edit");
        $this->openTab("Mall");
        $this->assertElementPresent("//input[@id='scdiv0_0' and @value='2']");
        $this->click("scdiv0_0");
        $this->clickAndWait("save");
        //Selection Lists
        $this->selectMenu("Administer Products", "Selection Lists");
        $this->clickAndWait("link=Title");
        $this->clickAndWaitFrame("link=1 [DE] sellist šÄßüл", "edit");
        $this->openTab("Mall");
        $this->assertElementPresent("//input[@id='scdiv0_0' and @value='2']");
        $this->click("scdiv0_0");
        $this->clickAndWait("save");
    }


    /**
     * creating subshop: supershop
     *
     * @group subshop
     */
    public function testCreateSubshopSupershop()
    {
        $testConfig = $this->getTestConfig();
        if ($testConfig->isSubShop()) {
            $this->markTestSkipped('Test is not for subshops');
        }

        $sShopNr = $this->getShopVersionNumber();
        //creating subshop
        $this->loginAdmin("Master Settings", "Core Settings");

        $this->_createSubShop('subshop', false, true);
        $this->check("editval[oxshops__oxactive]");
        $this->clickAndWaitFrame("save", "navigation");
        $this->openTab("Mall");
        $this->click("//input[@name='confbools[blMallInherit_oxarticles]' and @value='true']");
        $this->click("//input[@name='confbools[blMallInherit_oxattribute]' and @value='true']");
        $this->click("//input[@name='confbools[blMallInherit_oxdiscount]' and @value='true']");
        $this->click("//input[@name='confbools[blMallInherit_oxdelivery]' and @value='true']");
        $this->click("//input[@name='confbools[blMallInherit_oxlinks]' and @value='true']");
        $this->click("//input[@name='confbools[blMallInherit_oxvoucherseries]' and @value='true']");
        $this->click("//input[@name='confbools[blMallInherit_oxnews]' and @value='true']");
        $this->click("//input[@name='confbools[blMallInherit_oxselectlist]' and @value='true']");
        $this->click("//input[@name='confbools[blMallInherit_oxvendor]' and @value='true']");
        $this->click("//input[@name='confbools[blMallInherit_oxmanufacturers]' and @value='true']");
        $this->click("//input[@name='confbools[blMallInherit_oxwrapping]' and @value='true']");
        $this->clickAndWait("//input[@name='save' and @value='Save inheritance information']");

        $this->assertEquals("on", $this->getValue("//input[@name='confbools[blMallInherit_oxarticles]' and @value='true']"));
        $this->assertEquals("on", $this->getValue("//input[@name='confbools[blMallInherit_oxattribute]' and @value='true']"));
        $this->assertEquals("on", $this->getValue("//input[@name='confbools[blMallInherit_oxdiscount]' and @value='true']"));
        $this->assertEquals("on", $this->getValue("//input[@name='confbools[blMallInherit_oxdelivery]' and @value='true']"));
        $this->assertEquals("on", $this->getValue("//input[@name='confbools[blMallInherit_oxlinks]' and @value='true']"));
        $this->assertEquals("on", $this->getValue("//input[@name='confbools[blMallInherit_oxvoucherseries]' and @value='true']"));
        $this->assertEquals("on", $this->getValue("//input[@name='confbools[blMallInherit_oxnews]' and @value='true']"));
        $this->assertEquals("on", $this->getValue("//input[@name='confbools[blMallInherit_oxselectlist]' and @value='true']"));
        $this->assertEquals("on", $this->getValue("//input[@name='confbools[blMallInherit_oxvendor]' and @value='true']"));
        $this->assertEquals("on", $this->getValue("//input[@name='confbools[blMallInherit_oxmanufacturers]' and @value='true']"));
        $this->assertEquals("on", $this->getValue("//input[@name='confbools[blMallInherit_oxwrapping]' and @value='true']"));
        $this->frame("list");

        $this->clickAndWaitFrame("link=OXID eShop ". $sShopNr ." (1)", "edit");
        $this->selectMenu("Administer Products", "Categories");
        $this->assertEquals("English", $this->getSelectedLabel("changelang"));
        $this->changeAdminListLanguage('Deutsch');
        $this->clickAndWaitFrame("link=Test category 0 [DE] šÄßüл", "edit");
        $this->openTab("Mall");
        $this->check("scdiv0_0");
        $this->clickAndWait("save");

        //checking frontend
        $this->clearCache();
        $this->openShop(false, true, true);
        $this->assertElementPresent("newItems");
        $this->clickAndWait("link=Test category 0 [EN] šÄßüл");
        $this->assertEquals("Test category 0 [EN] šÄßüл", $this->getHeadingText("//h1"));
        $this->assertElementPresent("attributeFilter[testattribute1]");
        $this->clickAndWait("moreSubCat_1");
        $this->assertEquals("Test category 1 [EN] šÄßüл", $this->getHeadingText("//h1"));
        $this->assertEquals("Test product 3 [EN] šÄßüл", $this->getText("productList_1"));
        $this->clickAndWait("//form[@name='tobasketproductList_1']//a");
        $this->assertEquals("Test product 3 [EN] šÄßüл", $this->getText("//h1"));

        $this->searchFor("100");
        $this->assertEquals("Test product 0 [EN] šÄßüл", $this->getText("searchList_1"));
        $this->assertEquals("Test product 1 [EN] šÄßüл", $this->getText("searchList_2"));
        $this->assertEquals("Test product 2 [EN] šÄßüл", $this->getText("searchList_3"));
        $this->assertEquals("Test product 3 [EN] šÄßüл", $this->getText("searchList_4"));

        $this->clickAndWait("link=Manufacturer [EN] šÄßüл");
        $this->assertEquals("Manufacturer [EN] šÄßüл", $this->getHeadingText("//h1"));
        $this->assertEquals("Manufacturer description [EN] šÄßüл", $this->getText("catDesc"));
        $this->assertEquals("Test product 0 [EN] šÄßüл", $this->getText("productList_1"));
        $this->assertEquals("Test product 1 [EN] šÄßüл", $this->getText("productList_2"));
        $this->assertEquals("Test product 2 [EN] šÄßüл", $this->getText("productList_3"));
        $this->assertEquals("Test product 3 [EN] šÄßüл", $this->getText("productList_4"));
    }


    /**
     * creating subshop: multishop
     *
     * @group subshop
     */
    public function testCreateSubshopMultishop()
    {
        $testConfig = $this->getTestConfig();
        if ($testConfig->isSubShop()) {
            $this->markTestSkipped('Test is not for subshops');
        }

        //creating subshop
        $this->loginAdmin("Master Settings", "Core Settings");

        $this->_createSubShop( 'subshop', false, false, true, false );

        $this->openTab("Mall");
        $this->click("//input[@name='confbools[blMultishopInherit_oxcategories]' and @value='true']");
        $this->clickAndWait("//input[@name='save' and @value='Save inheritance information']");
        //checking frontend
        $this->clearCache();
        $this->openShop(false, false, true);
        $this->waitForElement("newItems");
        $this->clickAndWait("link=Test category 0 [EN] šÄßüл");
        $this->assertEquals("Test category 0 [EN] šÄßüл", $this->getHeadingText("//h1"));
        $this->assertElementPresent("attributeFilter[testattribute1]");
        $this->clickAndWait("moreSubCat_1");
        $this->assertEquals("Test category 1 [EN] šÄßüл", $this->getHeadingText("//h1"));
        $this->assertEquals("Test product 3 [EN] šÄßüл", $this->getText("productList_1"));
        $this->clickAndWait("//form[@name='tobasketproductList_1']//a");
        $this->assertEquals("Test product 3 [EN] šÄßüл", $this->getText("//h1"));
        $this->searchFor("100");
        $this->assertEquals("Test product 0 [EN] šÄßüл", $this->getText("searchList_1"));
        $this->assertEquals("Test product 1 [EN] šÄßüл", $this->getText("searchList_2"));
        $this->assertEquals("Test product 2 [EN] šÄßüл", $this->getText("searchList_3"));
        $this->assertEquals("Test product 3 [EN] šÄßüл", $this->getText("searchList_4"));
        $this->clickAndWait("link=Manufacturer [EN] šÄßüл");
        $this->assertEquals("Manufacturer [EN] šÄßüл", $this->getHeadingText("//h1"));
        $this->assertEquals("Manufacturer description [EN] šÄßüл", $this->getText("catDesc"));
        $this->assertEquals("Test product 0 [EN] šÄßüл", $this->getText("productList_1"));
        $this->assertEquals("Test product 1 [EN] šÄßüл", $this->getText("productList_2"));
        $this->assertEquals("Test product 2 [EN] šÄßüл", $this->getText("productList_3"));
        $this->assertEquals("Test product 3 [EN] šÄßüл", $this->getText("productList_4"));
    }

    /**
     * subshop admin
     *
     * @group subshop
     */
    public function testLoginAsSubshopAdmin()
    {
        $testConfig = $this->getTestConfig();
        if ($testConfig->isSubShop()) {
            $this->markTestSkipped('Test is not for subshops');
        }

        $sShopNr = $this->getShopVersionNumber();
        //creating subshop
        $this->loginAdmin("Master Settings", "Core Settings");

        $this->_createSubShop( 'subshop' );

        $this->openListItem("link=OXID eShop ". $sShopNr ." (1)");
        //creating subshop admin
        $this->selectMenu("Administer Users", "Users");
        $this->clickCreateNewItem();
        $this->click("editval[oxuser__oxactive]");
        $this->select("editval[oxuser__oxrights]", "label=Admin ( subshop )");
        $this->type("editval[oxuser__oxusername]", "subshopadmin");
        $this->type("newPassword", "subshopadmin");
        $this->clickAndWaitFrame("save", "list");
        $this->logoutAdmin("link=Logout");
        $this->loginAdmin("Master Settings", "Core Settings", false, 'subshopadmin', 'subshopadmin' );
        $this->assertElementPresent("link=subshop (2)");
        $this->assertElementNotPresent("link=OXID eShop ". $sShopNr);
    }

    /**
     * subshop assigning products. #1691 from mantis
     *
     * @group subshop
     */
    public function testAssignVariantToSubshop()
    {
        $testConfig = $this->getTestConfig();
        if ($testConfig->isSubShop()) {
            $this->markTestSkipped('Test is not for subshops');
        }

        $sShopNr = $this->getShopVersionNumber();
        //creating subshop
        $this->loginAdmin("Master Settings", "Core Settings");

        $this->_createSubShop( 'subshop', false );

        $this->frame("list");
        $this->clickAndWaitFrame("link=OXID eShop ". $sShopNr ." (1)", "edit");
        //assigning variant parent to subshop
        $this->selectMenu("Administer Users", "Products", "btn.help", "where[oxarticles][oxartnum]");
        $this->type("where[oxarticles][oxartnum]", "1002");
        $this->clickAndWait("submitit");
        $this->clickAndWaitFrame("link=1002", "edit");
        $this->openTab("Mall");
        $this->click("scdiv0_0");
        $this->clickAndWait("save");

        $this->clearCache();
        $this->openShop();
        $this->clickAndWait("link=subshop");
        $this->assertElementNotPresent("test_smallHeader_WeekSpecial_1");
        //search for assigned variant
        $this->searchFor("100");
        $this->selectDropDown("viewOptions", "%line%");
        $this->assertElementNotPresent("searchList_2");
        $this->assertEquals("Test product 2 [EN] šÄßüл", $this->getText("searchList_1"));

        $this->assertElementPresent("variantselector_searchList_1");
        $this->clickAndWait("searchList_1");
        $this->assertEquals("Test product 2 [EN] šÄßüл", $this->getText("//h1"));
        $this->assertElementPresent("//div[@id='variants']//ul");
        $this->assertEquals("var1 [EN] šÄßüл", $this->clearString($this->getText("//div[@id='variants']//ul/li[1]")));
        $this->assertEquals("var2 [EN] šÄßüл", $this->clearString($this->getText("//div[@id='variants']//ul/li[2]")));
        $this->selectVariant("variants", 1, "var2 [EN] šÄßüл", "var2 [EN] šÄßüл");
        $this->assertEquals("Test product 2 [EN] šÄßüл var2 [EN] šÄßüл", $this->getText("//h1"));

        $this->clickAndWait("toBasket");
        $this->openBasket();
        $this->assertEquals("Test product 2 [EN] šÄßüл, var2 [EN] šÄßüл", $this->getText("//tr[@id='cartItem_1']/td[3]//a"));
    }

    /**
    * #2230: If inherited assignment is removed in subshop - assignment is also removed for parent shop
     *
    * @group subshop
    */
    public function testAssigningProductsInInheritedCategory1()
    {
        $testConfig = $this->getTestConfig();
        if ($testConfig->isSubShop()) {
            $this->markTestSkipped('Test is not for subshops');
        }

        $sShopNr = $this->getShopVersionNumber();
        //creating subshop
        $this->loginAdmin("Master Settings", "Core Settings");

        $this->_createSubShop( 'subshop', true, true );

        //assign category to subshop
        $this->frame("list");
        $this->clickAndWaitFrame("link=OXID eShop ". $sShopNr ." (1)", "edit");
        $this->selectMenu("Administer Products", "Categories");
        $this->clickAndWaitFrame("link=1 [EN] category šÄßüл", "edit");
        $this->openTab("Mall");
        $this->check("scdiv0_0");
        $this->clickAndWait("save");

        //assigning product to category
        $this->openTab("Main");
        $this->click("//input[@value='Assign Products']");
        $this->usePopUp();
        $this->type("_0", "10012");
        $this->keyUp("_0", "2");
        $this->assertElementText("10012", "//div[@id='container1_c']/table/tbody[2]/tr[1]/td[1]");
        //drag and drop 1 itm from one list to another
        $this->dragAndDrop("//div[@id='container1_c']/table/tbody[2]/tr[1]/td[1]", "container2");
        $this->assertElementText("10012", "//div[@id='container2_c']/table/tbody[2]/tr[1]/td[1]");
        $this->close();

        //cheking assigement in subshop
        $this->loginSubshopAdmin("Administer Products", "Categories");
        $this->clickAndWaitFrame("link=1 [EN] category šÄßüл", "edit");
        $this->openTab("Main");
        //unassigning product to category
        $this->assertElementPresent("//input[@value='Assign Products']");
        $this->click("//input[@value='Assign Products']");
        $this->usePopUp();
        $this->type("_0", "10012");
        $this->keyUp("_0", "2");
        //drag and drop 1 itm from one list to another
        $this->dragAndDrop("//div[@id='container2_c']/table/tbody[2]/tr[1]/td[1]", "container1");
        $this->assertElementText("10012", "//div[@id='container1_c']/table/tbody[2]/tr[1]/td[1]");
        $this->close();
        $this->logoutAdmin();

        //checking if assigment still exist
        $this->loginAdmin("Administer Products", "Categories");
        $this->clickAndWaitFrame("link=1 [EN] category šÄßüл", "edit");
        $this->openTab("Main");
        $this->click("//input[@value='Assign Products']");
        $this->usePopUp();
        $this->assertElementText("10012", "//div[@id='container2_c']/table/tbody[2]/tr[1]/td[1]");
        $this->close();
    }

    /**
    * testing automaticaly price update in Sub shop when is unchecked "Allow custom price editing for inherited products
     *
    * @group subshop
    */
    public function testPriceUpdateInSubshop()
    {
        $testConfig = $this->getTestConfig();
        if ($testConfig->isSubShop()) {
            $this->markTestSkipped('Test is not for subshops');
        }

        $sShopNr = $this->getShopVersionNumber();
        //creating subshop
        $this->loginAdmin("Master Settings", "Core Settings");

        $this->_createSubShop( 'subshop3', true, true );

        $this->openTab("Mall");
        $this->uncheck("//input[@name='confbools[blMallCustomPrice]' and @value='true']");
        $this->clickAndWait("save");

        $this->selectMenu("Administer Products", "Products");
        $this->frame("list");
        $this->type("where[oxarticles][oxartnum]", "1402");
        $this->clickAndWait("submitit");
        $this->clickAndWaitFrame("link=1402", "edit");
        $this->openTab("Extended");
        $this->type("editval[oxarticles__oxupdateprice]", "5");
        $this->type("editval[oxarticles__oxupdatepricea]", "6");
        $this->type("editval[oxarticles__oxupdatepriceb]", "7");
        $this->type("editval[oxarticles__oxupdatepricec]", "8");
        $this->type("editval[oxarticles__oxupdatepricetime]", "2010-10-10 01-01-01");
        $this->clickAndWait("save");
        $this->openTab("Main");
        $this->assertEquals("159", $this->getValue("editval[oxarticles__oxprice]"));
        $this->assertEquals("0", $this->getValue("editval[oxarticles__oxpricea]"));
        $this->assertEquals("0", $this->getValue("editval[oxarticles__oxpriceb]"));
        $this->assertEquals("0", $this->getValue("editval[oxarticles__oxpricec]"));
    }

    /**
    * testing automatically price update in parent shop when is checked "Allow custom price editing for inherited products
     *
    * @group subshop
    */
    public function testPriceUpdateInParentShop()
    {
        $this->loginAdmin("Master Settings", "Core Settings");
        $this->openTab("Mall");
        $this->check("//input[@name='confbools[blMallCustomPrice]' and @value='true']");
        $this->clickAndWait("save");

        $this->selectMenu("Administer Products", "Products");
        $this->frame("list");
        $this->type("where[oxarticles][oxartnum]", "1402");
        $this->clickAndWait("submitit");
        $this->clickAndWaitFrame("link=1402", "edit");
        $this->openTab("Extended");
        $this->type("editval[oxarticles__oxupdateprice]", "5");
        $this->type("editval[oxarticles__oxupdatepricea]", "6");
        $this->type("editval[oxarticles__oxupdatepriceb]", "7");
        $this->type("editval[oxarticles__oxupdatepricec]", "8");
        $this->type("editval[oxarticles__oxupdatepricetime]", "2010-10-10 01-01-01");
        $this->clickAndWait("save");
        $this->openTab("Main");
        $this->assertEquals("5", $this->getValue("editval[oxarticles__oxprice]"));
        $this->assertEquals("6", $this->getValue("editval[oxarticles__oxpricea]"));
        $this->assertEquals("7", $this->getValue("editval[oxarticles__oxpriceb]"));
        $this->assertEquals("8", $this->getValue("editval[oxarticles__oxpricec]"));
    }

    /**
     * Change domain name and check if subshop still accessible.
     * Create name for subshop, check is both shops and shop select still accessible.
     *
     * @group subshop
     */
    public function testWithDifferentDomain()
    {
        $testConfig = $this->getTestConfig();
        if (!$testConfig->isSubShop()) {
            $this->markTestSkipped('Test is for subshops only');
        }

        // Form different domain as we have alias with dot instead of minus.
        $oConfig = Registry::getConfig();
        $sShopURL = $oConfig->getConfigParam('sShopURL');
        $iPos = stripos($sShopURL, '-');
        $this->assertTrue($iPos !== false, 'Shop host must consist with minus to change it to dot to get different domain name.');
        $sNewShopUrl = substr($sShopURL, 0, $iPos) .'.'. substr($sShopURL, $iPos+1);

        // Add different domain for subshop.
        $this->loginAdmin("Master Settings", "Core Settings");
        $this->openTab("Mall");
        $this->type("confstrs[sMallShopURL]", $sNewShopUrl);
        $this->clickAndWait("save");

        // Create category only in sybshop.
        // This should be done with sc.
        $this->selectMenu("Administer Products", "Categories");
        $this->frame("edit");
        $this->type("editval[oxcategories__oxtitle]", "subshop_category_0");
        $this->check("editval[oxcategories__oxactive]");
        $this->clickAndWait("save");
        $this->clearCookies();
        
        // Check if category is only in subshop and not main shop.
        // This will ensure that subshop is reachable with new address.
        $this->openShop(false, false, true);
        $this->assertElementPresent('link=subshop_category_0', 'Category subshop_category_0 must be visible in suhshop.');
        $this->clearCache();
        $this->openShop(true);
        $this->assertElementNotPresent('link=subshop_category_0', 'Category subshop_category_0 must NOT be visible in main shop as it belongs to subshop.');
    }

    /**
     * @param $sName
     * @param bool $blIsInherited
     * @param bool $blIsSuperShop
     * @param bool $blIsMultishop
     * @param bool $blIsChild
     */
    protected function _createSubShop( $sName, $blIsInherited = true, $blIsSuperShop = false, $blIsMultishop = false, $blIsChild = true ) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $sShopNr = $this->getShopVersionNumber();

        $this->clickCreateNewItem('btn.new', true);

        $this->waitForElement('shopname', 10, true);
        $this->assertElementPresent('shopname');
        $this->type("shopname", $sName);

        if ( $blIsInherited ) {
            $this->check("isinherited");
        }
        if ( $blIsSuperShop ) {
            $this->check("//input[@name='editval[oxshops__oxissupershop]' and @value='1']");
        }
        if ( $blIsMultishop ) {
            $this->check("//input[@name='editval[oxshops__oxismultishop]' and @value='1']");
        }
        if ( $blIsChild ) {
            $this->select("shopparent", "label=OXID eShop " . $sShopNr . " (1)");
            $this->click("shopparent");
        }

        $this->clickAndWaitFrame("save", 'edit');
        $this->waitForElement("editval[oxshops__oxactive]");

        $shopId = $this->getValue('oxid');

        $this->check("editval[oxshops__oxactive]");
        $this->clickAndWaitFrame("save", 'list');
        $this->waitForFrameToLoad('list', 20000, true);

        $this->callShopSC('oxShop', 'generateViews', $shopId, [], [], $shopId, 'en');
        $this->callShopSC('oxShop', 'generateViews', $shopId, [], [], $shopId, 'de');

        $this->activateModules($shopId);

        ContainerFactory::resetContainer();
        ContainerFactory::getInstance()->getContainer();
    }

    /**
     * Test helper to add subshop.
     *
     * @param int $subshopId
     */
    private function addSubshop(int $subshopId)
    {
        $query = "INSERT INTO `oxshops` SET `OXID` = '" . $subshopId . "', `OXPARENTID` = '" .
                 ($subshopId-1) . "', `OXISINHERITED` = '1', `OXNAME` = 'Subshop" . $subshopId ."'";

        DatabaseProvider::getDb()->execute($query);
    }
}
