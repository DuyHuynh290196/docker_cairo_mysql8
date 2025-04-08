<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Tests\Acceptance\Admin;

use OxidEsales\EshopEnterprise\Tests\Acceptance\EnterpriseAcceptanceTestCase;

/**
 * oxArticle integration test
 */
class AjaxFunctionalityAdminTest extends EnterpriseAcceptanceTestCase
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
     * ajax: Products -> Assign Groups ( Exclusively visible )
     *
     * @group ajax
     */
    public function testAjaxProductsAssignGroupsVisible()
    {
        $this->loginAdmin("Administer Products", "Products");
        $this->changeAdminListLanguage('Deutsch');
        $this->clickAndWait("link=Prod.No.");
        $this->clickAndWaitFrame("link=10011", "edit");
        $this->openTab("Rights");
        $this->click("//input[@value='Assign User Groups (Exclusively visible)']");
        $this->usePopUp();
        $this->assertElementText("[last] user Group šÄßüл", "//div[@id='container1_c']/table/tbody[2]/tr[21]/td[1]");
        $this->assertElementText("1 user Group šÄßüл", "//div[@id='container1_c']/table/tbody[2]/tr[1]/td[1]");
        //assignAll btn
        $this->click("container1_btn");
        $this->assertElementText("1 user Group šÄßüл", "//div[@id='container2_c']/table/tbody[2]/tr[1]/td[1]");
        // Browser sees elements on both containers. Need some wait time to continue test execution.
        $this->assertElementText("", "//div[@id='container1_c']/table/tbody[2]", "Error: elements were not moved to other container.");
        $this->assertElementText("[last] user Group šÄßüл", "//div[@id='container2_c']/table/tbody[2]/tr[21]/td[1]");
        //drag and drop 1 itm from one list to another
        $this->dragAndDrop("//div[@id='container2_c']/table/tbody[2]/tr[3]/td[1]", "container1");
        $this->assertElementText("3 user Group šÄßüл", "//div[@id='container1_c']/table/tbody[2]/tr[1]/td[1]");
        $this->assertElementText("1 user Group šÄßüл", "//div[@id='container2_c']/table/tbody[2]/tr[1]/td[1]");
        $this->assertElementText("[last] user Group šÄßüл", "//div[@id='container2_c']/table/tbody[2]/tr[20]/td[1]");
        $this->dragAndDrop("//div[@id='container1_c']/table/tbody[2]/tr[1]/td[1]", "container2");
        $this->assertElementText("3 user Group šÄßüл", "//div[@id='container2_c']/table/tbody[2]/tr[3]/td[1]");
        $this->assertElementText("1 user Group šÄßüл", "//div[@id='container2_c']/table/tbody[2]/tr[1]/td[1]");
        $this->assertElementText("[last] user Group šÄßüл", "//div[@id='container2_c']/table/tbody[2]/tr[21]/td[1]");
        //unassignAll btn
        $this->click("container2_btn");
        $this->assertElementText("1 user Group šÄßüл", "//div[@id='container1_c']/table/tbody[2]/tr[1]/td[1]");
        $this->assertElementText("[last] user Group šÄßüл", "//div[@id='container1_c']/table/tbody[2]/tr[21]/td[1]");
        $this->close();
    }

    /**
     * ajax: Products -> Assign Groups ( Exclusively buyable )
     *
     * @group ajax
     */
    public function testAjaxProductsAssignGroupsBuyable()
    {
        $this->loginAdmin("Administer Products", "Products");
        $this->changeAdminListLanguage('Deutsch');
        $this->clickAndWait("link=Prod.No.");
        $this->clickAndWaitFrame("link=10011", "edit");
        $this->openTab("Rights");
        $this->click("//input[@value='Assign User Groups (Exclusively buyable)']");
        $this->usePopUp();
        $this->assertElementText("[last] user Group šÄßüл", "//div[@id='container1_c']/table/tbody[2]/tr[21]/td[1]");
        $this->assertElementText("1 user Group šÄßüл", "//div[@id='container1_c']/table/tbody[2]/tr[1]/td[1]");
        //assignAll btn
        $this->click("container1_btn");
        $this->assertElementText("1 user Group šÄßüл", "//div[@id='container2_c']/table/tbody[2]/tr[1]/td[1]");
        $this->assertElementText("[last] user Group šÄßüл", "//div[@id='container2_c']/table/tbody[2]/tr[21]/td[1]");
        //drag and drop 1 itm from one list to another
        $this->dragAndDrop("//div[@id='container2_c']/table/tbody[2]/tr[3]/td[1]", "container1");
        $this->assertElementText("3 user Group šÄßüл", "//div[@id='container1_c']/table/tbody[2]/tr[1]/td[1]");
        $this->assertElementText("1 user Group šÄßüл", "//div[@id='container2_c']/table/tbody[2]/tr[1]/td[1]");
        $this->assertElementText("[last] user Group šÄßüл", "//div[@id='container2_c']/table/tbody[2]/tr[20]/td[1]");
        $this->dragAndDrop("//div[@id='container1_c']/table/tbody[2]/tr[1]/td[1]", "container2");
        $this->assertElementText("3 user Group šÄßüл", "//div[@id='container2_c']/table/tbody[2]/tr[3]/td[1]");
        $this->assertElementText("1 user Group šÄßüл", "//div[@id='container2_c']/table/tbody[2]/tr[1]/td[1]");
        $this->assertElementText("[last] user Group šÄßüл", "//div[@id='container2_c']/table/tbody[2]/tr[21]/td[1]");
        //unassignAll btn
        $this->click("container2_btn");
        $this->assertElementText("1 user Group šÄßüл", "//div[@id='container1_c']/table/tbody[2]/tr[1]/td[1]");
        $this->assertElementText("[last] user Group šÄßüл", "//div[@id='container1_c']/table/tbody[2]/tr[21]/td[1]");
        $this->close();
    }

    /**
     * ajax: Categories -> Assign Groups ( Exclusively visible )
     *
     * @group ajax
     */
    public function testAjaxCategoriesAssignGroupsVisible()
    {
        $this->loginAdmin("Administer Products", "Categories");
        $this->changeAdminListLanguage('Deutsch');
        $this->clickAndWait("link=Sorting");
        $this->clickAndWaitFrame("link=Test category 0 [DE] šÄßüл", "edit");
        $this->openTab("Rights");
        $this->click("//input[@value='Assign User Groups (Exclusively visible)']");
        $this->usePopUp();
        $this->assertElementText("1 user Group šÄßüл", "//div[@id='container1_c']/table/tbody[2]/tr[1]/td[1]");
        $this->assertElementText("[last] user Group šÄßüл", "//div[@id='container1_c']/table/tbody[2]/tr[21]/td[1]");
        //assignAll btn
        $this->click("container1_btn");
        $this->assertElementText("1 user Group šÄßüл", "//div[@id='container2_c']/table/tbody[2]/tr[1]/td[1]");
        $this->assertElementText("[last] user Group šÄßüл", "//div[@id='container2_c']/table/tbody[2]/tr[21]/td[1]");
        //drag and drop 1 itm from one list to another
        $this->dragAndDrop("//div[@id='container2_c']/table/tbody[2]/tr[3]/td[1]", "container1");
        $this->assertElementText("3 user Group šÄßüл", "//div[@id='container1_c']/table/tbody[2]/tr[1]/td[1]");
        $this->assertElementText("1 user Group šÄßüл", "//div[@id='container2_c']/table/tbody[2]/tr[1]/td[1]");
        $this->assertElementText("[last] user Group šÄßüл", "//div[@id='container2_c']/table/tbody[2]/tr[20]/td[1]");
        $this->dragAndDrop("//div[@id='container1_c']/table/tbody[2]/tr[1]/td[1]", "container2");
        $this->assertElementText("3 user Group šÄßüл", "//div[@id='container2_c']/table/tbody[2]/tr[3]/td[1]");
        $this->assertElementText("1 user Group šÄßüл", "//div[@id='container2_c']/table/tbody[2]/tr[1]/td[1]");
        $this->assertElementText("[last] user Group šÄßüл", "//div[@id='container2_c']/table/tbody[2]/tr[21]/td[1]");
        //unassignAll btn
        $this->click("container2_btn");
        $this->assertElementText("1 user Group šÄßüл", "//div[@id='container1_c']/table/tbody[2]/tr[1]/td[1]");
        $this->assertElementText("[last] user Group šÄßüл", "//div[@id='container1_c']/table/tbody[2]/tr[21]/td[1]");
        $this->close();
    }

    /**
     * ajax: Categories -> Assign Groups ( Exclusively buyable )
     *
     * @group ajax
     */
    public function testAjaxCategoriesAssignGroupsBuyable()
    {
        $this->loginAdmin("Administer Products", "Categories");
        $this->changeAdminListLanguage('Deutsch');
        $this->clickAndWait("link=Sorting");
        $this->clickAndWaitFrame("link=Test category 0 [DE] šÄßüл", "edit");
        $this->openTab("Rights");
        $this->click("//input[@value='Assign User Groups (Exclusively buyable)']");
        $this->usePopUp();
        $this->assertElementText("1 user Group šÄßüл", "//div[@id='container1_c']/table/tbody[2]/tr[1]/td[1]");
        $this->assertElementText("[last] user Group šÄßüл", "//div[@id='container1_c']/table/tbody[2]/tr[21]/td[1]");
        //assignAll btn
        $this->click("container1_btn");
        $this->assertElementText("1 user Group šÄßüл", "//div[@id='container2_c']/table/tbody[2]/tr[1]/td[1]");
        $this->assertElementText("[last] user Group šÄßüл", "//div[@id='container2_c']/table/tbody[2]/tr[21]/td[1]");
        //drag and drop 1 itm from one list to another
        $this->dragAndDrop("//div[@id='container2_c']/table/tbody[2]/tr[3]/td[1]", "container1");
        $this->assertElementText("3 user Group šÄßüл", "//div[@id='container1_c']/table/tbody[2]/tr[1]/td[1]");
        $this->assertElementText("1 user Group šÄßüл", "//div[@id='container2_c']/table/tbody[2]/tr[1]/td[1]");
        $this->assertElementText("[last] user Group šÄßüл", "//div[@id='container2_c']/table/tbody[2]/tr[20]/td[1]");
        $this->dragAndDrop("//div[@id='container1_c']/table/tbody[2]/tr[1]/td[1]", "container2");
        $this->assertElementText("3 user Group šÄßüл", "//div[@id='container2_c']/table/tbody[2]/tr[3]/td[1]");
        $this->assertElementText("1 user Group šÄßüл", "//div[@id='container2_c']/table/tbody[2]/tr[1]/td[1]");
        $this->assertElementText("[last] user Group šÄßüл", "//div[@id='container2_c']/table/tbody[2]/tr[21]/td[1]");
        //unassignAll btn
        $this->click("container2_btn");
        $this->assertElementText("1 user Group šÄßüл", "//div[@id='container1_c']/table/tbody[2]/tr[1]/td[1]");
        $this->assertElementText("[last] user Group šÄßüл", "//div[@id='container1_c']/table/tbody[2]/tr[21]/td[1]");
        $this->close();
    }

    /**
     * ajax: Admin Roles -> Assign Groups
     *
     * @group ajax
     */
    public function testAjaxAdminRolesAssignGroups()
    {
        $testConfig = $this->getTestConfig();
        if ($testConfig->isSubShop()) {
            $this->executeSql("UPDATE `oxroles` SET `OXSHOPID` = ".oxSHOPID."  WHERE `OXAREA` = 0;");
        }
        $this->loginAdmin("Administer Users", "Admin Roles");
        $this->clickAndWait("link=Title");
        $this->clickAndWaitFrame("link=2 admin role šÄßüл", "edit");
        $this->openTab("Users");
        $this->click("//input[@value='Assign User Groups']");
        $this->usePopUp();
        $this->assertElementText("1 user Group šÄßüл", "//div[@id='container1_c']/table/tbody[2]/tr[1]/td[1]");
        $this->assertElementText("[last] user Group šÄßüл", "//div[@id='container1_c']/table/tbody[2]/tr[21]/td[1]");
        //assignAll btn
        $this->click("container1_btn");
        $this->assertElementText("1 user Group šÄßüл", "//div[@id='container2_c']/table/tbody[2]/tr[1]/td[1]");
        $this->assertElementText("[last] user Group šÄßüл", "//div[@id='container2_c']/table/tbody[2]/tr[21]/td[1]");
        //drag and drop 1 itm from one list to another
        $this->dragAndDrop("//div[@id='container2_c']/table/tbody[2]/tr[3]/td[1]", "container1");
        $this->assertElementText("3 user Group šÄßüл", "//div[@id='container1_c']/table/tbody[2]/tr[1]/td[1]");
        $this->assertElementText("1 user Group šÄßüл", "//div[@id='container2_c']/table/tbody[2]/tr[1]/td[1]");
        $this->assertElementText("[last] user Group šÄßüл", "//div[@id='container2_c']/table/tbody[2]/tr[20]/td[1]");
        $this->dragAndDrop("//div[@id='container1_c']/table/tbody[2]/tr[1]/td[1]", "container2");
        $this->assertElementText("3 user Group šÄßüл", "//div[@id='container2_c']/table/tbody[2]/tr[3]/td[1]");
        $this->assertElementText("1 user Group šÄßüл", "//div[@id='container2_c']/table/tbody[2]/tr[1]/td[1]");
        $this->assertElementText("[last] user Group šÄßüл", "//div[@id='container2_c']/table/tbody[2]/tr[21]/td[1]");
        //unassignAll btn
        $this->click("container2_btn");
        $this->assertElementText("1 user Group šÄßüл", "//div[@id='container1_c']/table/tbody[2]/tr[1]/td[1]");
        $this->assertElementText("[last] user Group šÄßüл", "//div[@id='container1_c']/table/tbody[2]/tr[21]/td[1]");
        $this->close();
    }

    /**
     * ajax: Admin Roles -> Assign Users
     *
     * @group ajax
     */
    public function testAjaxAdminRolesAssignUsers()
    {
        $testConfig = $this->getTestConfig();
        if ($testConfig->isSubShop()) {
            $this->executeSql("UPDATE `oxroles` SET `OXSHOPID` = ".oxSHOPID."  WHERE `OXAREA` = 0;");
        }
        $this->loginAdmin("Administer Users", "Admin Roles");
        $this->clickAndWait("link=Title");
        $this->clickAndWaitFrame("link=2 admin role šÄßüл", "edit");
        $this->openTab("Users");
        $this->click("//input[@value='Assign Users']");
        $this->usePopUp();
        //filter
        $this->type("_0", "exa");
        $this->keyUp("_0", "r");
        $this->assertElementText("example_test@oxid-esales.dev", "//div[@id='container1_c']/table/tbody[2]/tr[11]/td[1]");
        $this->assertElementText("example02@oxid-esales.dev", "//div[@id='container1_c']/table/tbody[2]/tr[1]/td[1]");
        //assignAll btn
        $this->click("container1_btn");
        $this->assertElementText("example02@oxid-esales.dev", "//div[@id='container2_c']/table/tbody[2]/tr[1]/td[1]");
        $this->assertElementText("example_test@oxid-esales.dev", "//div[@id='container2_c']/table/tbody[2]/tr[11]/td[1]");
        //drag and drop 1 itm from one list to another
        $this->dragAndDrop("//div[@id='container2_c']/table/tbody[2]/tr[1]/td[1]", "container1");
        $this->assertElementText("example02@oxid-esales.dev", "//div[@id='container1_c']/table/tbody[2]/tr[1]/td[1]");
        $this->assertElementText("example03@oxid-esales.dev", "//div[@id='container2_c']/table/tbody[2]/tr[1]/td[1]");
        $this->assertElementText("example_test@oxid-esales.dev", "//div[@id='container2_c']/table/tbody[2]/tr[10]/td[1]");
        $this->dragAndDrop("//div[@id='container1_c']/table/tbody[2]/tr[1]/td[1]", "container2");
        $this->assertElementText("example02@oxid-esales.dev", "//div[@id='container2_c']/table/tbody[2]/tr[1]/td[1]");
        $this->assertElementText("example03@oxid-esales.dev", "//div[@id='container2_c']/table/tbody[2]/tr[2]/td[1]");
        $this->assertElementText("example_test@oxid-esales.dev", "//div[@id='container2_c']/table/tbody[2]/tr[11]/td[1]");
        //unassignAll btn
        $this->click("container2_btn");
        $this->assertElementText("example02@oxid-esales.dev", "//div[@id='container1_c']/table/tbody[2]/tr[1]/td[1]");
        $this->assertElementText("example_test@oxid-esales.dev", "//div[@id='container1_c']/table/tbody[2]/tr[11]/td[1]");
        //drop down list for groups
        $this->assertTextPresent("Price A");
        $this->assertTextPresent("1 user Group šÄßüл");
        $this->select("artcat", "label=Price A");
        $this->assertElementText("example0a@oxid-esales.dev", "//div[@id='container1_c']/table/tbody[2]/tr[1]/td[1]");
        $this->assertElementNotPresent("//div[@id='container1_c']/table/tbody[2]/tr[2]/td[1]");
        $this->close();
    }

    /**
     * ajax: Shop Roles -> Assign Groups
     *
     * @group ajax
     */
    public function testAjaxShopRolesAssignGroups()
    {
        //delete group admin_demo
        $this->loginAdmin("Administer Users", "Shop Roles");
        $this->clickAndWait("link=Title");
        $this->clickAndWaitFrame("link=2 shop role šÄßüл", "edit");
        $this->openTab("Users");
        $this->click("//input[@value='Assign User Groups']");
        $this->usePopUp();
        $this->assertElementText("1 user Group šÄßüл", "//div[@id='container1_c']/table/tbody[2]/tr[1]/td[1]");
        $this->assertElementText("[last] user Group šÄßüл", "//div[@id='container1_c']/table/tbody[2]/tr[21]/td[1]");
        //assignAll btn
        $this->click("container1_btn");
        $this->assertElementText("1 user Group šÄßüл", "//div[@id='container2_c']/table/tbody[2]/tr[1]/td[1]");
        $this->assertElementText("[last] user Group šÄßüл", "//div[@id='container2_c']/table/tbody[2]/tr[21]/td[1]");
        //drag and drop 1 itm from one list to another
        $this->dragAndDrop("//div[@id='container2_c']/table/tbody[2]/tr[3]/td[1]", "container1");
        $this->assertElementText("3 user Group šÄßüл", "//div[@id='container1_c']/table/tbody[2]/tr[1]/td[1]");
        $this->assertElementText("1 user Group šÄßüл", "//div[@id='container2_c']/table/tbody[2]/tr[1]/td[1]");
        $this->assertElementText("[last] user Group šÄßüл", "//div[@id='container2_c']/table/tbody[2]/tr[20]/td[1]");
        $this->dragAndDrop("//div[@id='container1_c']/table/tbody[2]/tr[1]/td[1]", "container2");
        $this->assertElementText("3 user Group šÄßüл", "//div[@id='container2_c']/table/tbody[2]/tr[3]/td[1]");
        $this->assertElementText("1 user Group šÄßüл", "//div[@id='container2_c']/table/tbody[2]/tr[1]/td[1]");
        $this->assertElementText("[last] user Group šÄßüл", "//div[@id='container2_c']/table/tbody[2]/tr[21]/td[1]");
        //unassignAll btn
        $this->click("container2_btn");
        $this->assertElementText("1 user Group šÄßüл", "//div[@id='container1_c']/table/tbody[2]/tr[1]/td[1]");
        $this->assertElementText("[last] user Group šÄßüл", "//div[@id='container1_c']/table/tbody[2]/tr[21]/td[1]");
        $this->close();
    }

}