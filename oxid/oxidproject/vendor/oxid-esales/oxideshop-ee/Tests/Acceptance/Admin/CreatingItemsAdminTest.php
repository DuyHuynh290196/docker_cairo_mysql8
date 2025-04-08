<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */
namespace OxidEsales\EshopEnterprise\Tests\Acceptance\Admin\Acceptance;

use OxidEsales\EshopEnterprise\Tests\Acceptance\EnterpriseTestCase;

/** Creating and deleting items. */
class CreatingItemsAdminTest extends EnterpriseTestCase
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
     * creating subshop
     *
     * @group creatingitems
     * @group quarantine
     */
    public function testCreateSubshop()
    {
        $sShopNr = $this->getShopVersionNumber();
        $shopName = "-- OXID eShop ". $sShopNr ." (1)";
        $shopName .= $this->getTestConfig()->isSubShop() ? " subshop (2)" : "";
        $iNewSubshopId = $this->getTestConfig()->isSubShop() ? 3 : 2;

        //creating subshop
        $this->loginAdmin("Master Settings", "Core Settings", true);
        $this->openListItem("link=OXID eShop $sShopNr (1)");
        $this->waitForFrameAfterAction("edit");
        $this->clickCreateNewItem();
        $this->waitForFrameAfterAction("edit");
        $this->waitForElement('shopname', 10, true);
        $this->assertElementPresent('shopname');
        $this->type("shopname", "create_new_subshop_šÄßüл");
        $this->assertEquals($shopName, $this->clearString($this->getText("shopparent")));
        $this->uncheck("isinherited");
        $this->check("//input[@name='editval[oxshops__oxissupershop]' and @value='1']");
        $this->check("//input[@name='editval[oxshops__oxismultishop]' and @value='1']");
        $this->select("shopparent", "label=OXID eShop ". $sShopNr ." (1)");
        $this->clickAndWaitFrame("save", "list");
        $this->assertEquals("off", $this->getValue("editval[oxshops__oxproductive]"));
        $this->assertEquals("off", $this->getValue("editval[oxshops__oxactive]"));
        $this->check("editval[oxshops__oxproductive]");
        $this->check("editval[oxshops__oxactive]");
        $this->assertEquals("Your Company Name", $this->getValue("editval[oxshops__oxcompany]"));
        $this->type("editval[oxshops__oxcompany]", "Ihr Firmenname1_šÄßüл");
        $this->assertEquals("John", $this->getValue("editval[oxshops__oxfname]"));
        $this->type("editval[oxshops__oxfname]", "Hans1_šÄßüл");
        $this->assertEquals("Doe", $this->getValue("editval[oxshops__oxlname]"));
        $this->type("editval[oxshops__oxlname]", "Mustermann1_šÄßüл");
        $this->assertEquals("2425 Maple Street", $this->getValue("editval[oxshops__oxstreet]"));
        $this->type("editval[oxshops__oxstreet]", "Musterstr. 101_šÄßüл");
        $this->assertEquals("9041", $this->getValue("editval[oxshops__oxzip]"));
        $this->type("editval[oxshops__oxzip]", "790981_šÄßüл");
        $this->assertEquals("Any City, CA", $this->getValue("editval[oxshops__oxcity]"));
        $this->type("editval[oxshops__oxcity]", "Musterstadt1_šÄßüл");
        $this->assertEquals("United States", $this->getValue("editval[oxshops__oxcountry]"));
        $this->type("editval[oxshops__oxcountry]", "Deutschland1_šÄßüл");
        $this->assertEquals("217-8918712", $this->getValue("editval[oxshops__oxtelefon]"));
        $this->type("editval[oxshops__oxtelefon]", "0800 12345671_šÄßüл");
        $this->assertEquals("217-8918713", $this->getValue("editval[oxshops__oxtelefax]"));
        $this->type("editval[oxshops__oxtelefax]", "0800 12345671_šÄßüл");
        $this->assertEquals("www.myoxideshop.com", $this->getValue("editval[oxshops__oxurl]"));
        $this->type("editval[oxshops__oxurl]", "www.meineshopurl1.com_šÄßüл");
        $this->assertEquals("Bank of America", $this->getValue("editval[oxshops__oxbankname]"));
        $this->type("editval[oxshops__oxbankname]", "Volksbank Musterstadt1_šÄßüл");
        $this->assertEquals("900 1234567", $this->getValue("editval[oxshops__oxbankcode]"));
        $this->type("editval[oxshops__oxbankcode]", "900 12345671_šÄßüл");
        $this->assertEquals("1234567890", $this->getValue("editval[oxshops__oxbanknumber]"));
        $this->type("editval[oxshops__oxbanknumber]", "12345678901_šÄßüл");
        $this->assertEquals("", $this->getValue("editval[oxshops__oxvatnumber]"));
        $this->type("editval[oxshops__oxvatnumber]", "111_šÄßüл");
        $this->assertEquals("", $this->getValue("editval[oxshops__oxbiccode]"));
        $this->type("editval[oxshops__oxbiccode]", "1111_šÄßüл");
        $this->assertEquals("", $this->getValue("editval[oxshops__oxibannumber]"));
        $this->type("editval[oxshops__oxibannumber]", "11111_šÄßüл");
        $this->assertEquals("", $this->getValue("editval[oxshops__oxhrbnr]"));
        $this->type("editval[oxshops__oxhrbnr]", "111111_šÄßüл");
        $this->assertEquals("", $this->getValue("editval[oxshops__oxcourt]"));
        $this->type("editval[oxshops__oxcourt]", "1111111_šÄßüл");
        $this->assertTextPresent("Shop is multishop (loads all products from all shops)");
        $this->assertTextPresent("Shop is supershop (you can assign products to any shop).");
        $this->assertTextPresent("OXID eShop ". $sShopNr ."(1)");
        $this->assertEquals("create_new_subshop_šÄßüл", $this->getValue("editval[oxshops__oxname]"));
        $this->type("editval[oxshops__oxname]", "create_new_subshop1_šÄßüл");
        $this->assertEquals("localhost", $this->getValue("editval[oxshops__oxsmtp]"));
        $this->assertEquals("", $this->getValue("editval[oxshops__oxsmtpuser]"));
        $this->type("editval[oxshops__oxsmtpuser]", "user_šÄßüл");
        $this->assertEquals("", $this->getValue("oxsmtppwd"));
        $this->type("oxsmtppwd", "pass");
        $this->assertEquals("example_test@oxid-esales.dev", $this->getValue("editval[oxshops__oxinfoemail]"));
        $this->type("editval[oxshops__oxinfoemail]", "");
        $this->assertEquals("example_test@oxid-esales.dev", $this->getValue("editval[oxshops__oxorderemail]"));
        $this->type("editval[oxshops__oxorderemail]", "");
        $this->assertEquals("example_test@oxid-esales.dev", $this->getValue("editval[oxshops__oxowneremail]"));
        $this->type("editval[oxshops__oxowneremail]", "");
        $this->assertEquals($this->getSelectedLabel("subjlang"), "English");
        $this->clickAndWaitFrame("save", 'list');
        $this->changeAdminEditLanguage("Deutsch", "subjlang");
        $this->assertEquals($this->getSelectedLabel("subjlang"), "Deutsch");
        $this->assertEquals("Ihre Bestellung bei OXID eSales", $this->getValue("editval[oxshops__oxordersubject]"));
        $this->assertEquals("Ihr Passwort im OXID eShop", $this->getValue("editval[oxshops__oxforgotpwdsubject]"));
        $this->assertEquals("Ihre OXID eSales Bestellung wurde versandt", $this->getValue("editval[oxshops__oxsendednowsubject]"));
        $this->type("editval[oxshops__oxordersubject]", "Ihre Bestellung bei OXID eSales1_šÄßüл");
        $this->type("editval[oxshops__oxregistersubject]", "Vielen Dank fur Ihre Registrierung im OXID eShop1_šÄßüл");
        $this->type("editval[oxshops__oxforgotpwdsubject]", "Ihr Passwort im OXID eShop1_šÄßüл");
        $this->type("editval[oxshops__oxsendednowsubject]", "Ihre OXID eSales Bestellung wurde versandt1_šÄßüл");
        $this->clickAndWaitFrame("save", "edit");

        $this->assertEquals("on", $this->getValue("editval[oxshops__oxproductive]"));
        $this->assertEquals("on", $this->getValue("editval[oxshops__oxactive]"));
        $this->assertEquals("Ihr Firmenname1_šÄßüл", $this->getValue("editval[oxshops__oxcompany]"));
        $this->assertEquals("Hans1_šÄßüл", $this->getValue("editval[oxshops__oxfname]"));
        $this->assertEquals("Mustermann1_šÄßüл", $this->getValue("editval[oxshops__oxlname]"));
        $this->assertEquals("Musterstr. 101_šÄßüл", $this->getValue("editval[oxshops__oxstreet]"));
        $this->assertEquals("790981_šÄßüл", $this->getValue("editval[oxshops__oxzip]"));
        $this->assertEquals("Musterstadt1_šÄßüл", $this->getValue("editval[oxshops__oxcity]"));
        $this->assertEquals("Deutschland1_šÄßüл", $this->getValue("editval[oxshops__oxcountry]"));
        $this->assertEquals("0800 12345671_šÄßüл", $this->getValue("editval[oxshops__oxtelefon]"));
        $this->assertEquals("0800 12345671_šÄßüл", $this->getValue("editval[oxshops__oxtelefax]"));
        $this->assertEquals("www.meineshopurl1.com_šÄßüл", $this->getValue("editval[oxshops__oxurl]"));
        $this->assertEquals("Volksbank Musterstadt1_šÄßüл", $this->getValue("editval[oxshops__oxbankname]"));
        $this->assertEquals("900 12345671_šÄßüл", $this->getValue("editval[oxshops__oxbankcode]"));
        $this->assertEquals("12345678901_šÄßüл", $this->getValue("editval[oxshops__oxbanknumber]"));
        $this->assertEquals("111_šÄßüл", $this->getValue("editval[oxshops__oxvatnumber]"));
        $this->assertEquals("1111_šÄßüл", $this->getValue("editval[oxshops__oxbiccode]"));
        $this->assertEquals("11111_šÄßüл", $this->getValue("editval[oxshops__oxibannumber]"));
        $this->assertEquals("111111_šÄßüл", $this->getValue("editval[oxshops__oxhrbnr]"));
        $this->assertEquals("1111111_šÄßüл", $this->getValue("editval[oxshops__oxcourt]"));
        $this->assertEquals("create_new_subshop1_šÄßüл", $this->getValue("editval[oxshops__oxname]"));
        $this->assertEquals("$iNewSubshopId", $this->getText("//form[@id='myedit']/table/tbody/tr/td[2]/table/tbody/tr[4]/td[2]"));
        $this->assertEquals("OXID eShop ". $sShopNr ."(1)", $this->getText("//form[@id='myedit']/table/tbody/tr/td[2]/table/tbody/tr[3]/td[2]"));
        $this->assertEquals("create_new_subshop1_šÄßüл", $this->getValue("editval[oxshops__oxname]"));
        $this->assertEquals("localhost", $this->getValue("editval[oxshops__oxsmtp]"));
        $this->assertEquals("user_šÄßüл", $this->getValue("editval[oxshops__oxsmtpuser]"));
        $this->assertEquals("", $this->getValue("oxsmtppwd"));
        $this->assertEquals("", $this->getValue("editval[oxshops__oxinfoemail]"));
        $this->assertEquals("", $this->getValue("editval[oxshops__oxorderemail]"));
        $this->assertEquals("", $this->getValue("editval[oxshops__oxowneremail]"));
        $this->assertEquals("Ihre Bestellung bei OXID eSales1_šÄßüл", $this->getValue("editval[oxshops__oxordersubject]"));
        $this->assertEquals("Vielen Dank fur Ihre Registrierung im OXID eShop1_šÄßüл", $this->getValue("editval[oxshops__oxregistersubject]"));
        $this->assertEquals("Ihr Passwort im OXID eShop1_šÄßüл", $this->getValue("editval[oxshops__oxforgotpwdsubject]"));
        $this->assertEquals("Ihre OXID eSales Bestellung wurde versandt1_šÄßüл", $this->getValue("editval[oxshops__oxsendednowsubject]"));
        $this->waitForFrameToLoad("list");
        $this->assertEquals($this->getSelectedLabel("subjlang"), "Deutsch");
        $this->changeAdminEditLanguage("English", "subjlang");
        $this->assertEquals($this->getSelectedLabel("subjlang"), "English");
        $this->waitForElementText("Your order at OXID eShop", "editval[oxshops__oxordersubject]");
        $this->type("editval[oxshops__oxordersubject]", "Your order from OXID eShop1");
        $this->type("editval[oxshops__oxregistersubject]", "Thank you for your registration in OXID eShop1");
        $this->type("editval[oxshops__oxforgotpwdsubject]", "Your OXID eShop password1");
        $this->type("editval[oxshops__oxsendednowsubject]", "Your OXID eSales Order has been shipped1");
        $this->type("oxsmtppwd", "-");
        $this->clickAndWaitFrame("save");
        $this->assertEquals("Your order from OXID eShop1", $this->getValue("editval[oxshops__oxordersubject]"));
        $this->assertEquals("Thank you for your registration in OXID eShop1", $this->getValue("editval[oxshops__oxregistersubject]"));
        $this->assertEquals("Your OXID eShop password1", $this->getValue("editval[oxshops__oxforgotpwdsubject]"));
        $this->assertEquals("Your OXID eSales Order has been shipped1", $this->getValue("editval[oxshops__oxsendednowsubject]"));
        $this->waitForPageToLoad();
        $this->changeAdminEditLanguage("Deutsch", "subjlang");
        $this->waitForElementText("Ihre Bestellung bei OXID eSales1_šÄßüл", "editval[oxshops__oxordersubject]");
        $this->assertEquals("Vielen Dank fur Ihre Registrierung im OXID eShop1_šÄßüл", $this->getValue("editval[oxshops__oxregistersubject]"));
        $this->assertEquals("Ihr Passwort im OXID eShop1_šÄßüл", $this->getValue("editval[oxshops__oxforgotpwdsubject]"));
        $this->assertEquals("Ihre OXID eSales Bestellung wurde versandt1_šÄßüл", $this->getValue("editval[oxshops__oxsendednowsubject]"));
        $this->assertEquals("", $this->getValue("oxsmtppwd"));
        //testing if other tabs are working
        $this->openTab("Settings");
        $this->openTab("System");
        $this->openTab("Mall");
        $this->assertElementPresent("//input[@name='confbools[blMultishopInherit_oxcategories]' and @value='true']");
        $this->assertEquals("on", $this->getValue("//input[@name='confbools[blMallCustomPrice]' and @value='true']"));
        $this->openTab("SEO");
        $this->openTab("License");
        $this->openTab("Perform.");
        $this->openTab("Caching");
        $this->frame("list");
        $this->type("where[oxshops][oxname]", "create");
        $this->clickAndWaitFrame("submitit");
        $this->assertEquals("create_new_subshop1_šÄßüл ($iNewSubshopId)", $this->getText("//tr[@id='row.1']/td[1]"));
        $this->assertElementNotPresent("//tr[@id='row.2']/td[1]");
        $this->clickDeleteListItem(1);
        $this->assertElementNotPresent("//tr[@id='row.1']/td[1]");
        $this->type("where[oxshops][oxname]", "");
        $this->clickAndWaitFrame("submitit", 'edit');
        $this->assertEquals("OXID eShop ". $sShopNr ." (1)", $this->getText("//tr[@id='row.1']/td[1]"));
    }

    /**
     * creating Admin Roles
     *
     * @group creatingitems
     */
    public function testCreateAdminRoles()
    {
        //creating admin role
        $this->loginAdmin("Administer Users", "Admin Roles");
        $this->frame("edit");
        $this->clickCreateNewItem();
        $this->assertEquals("off", $this->getValue("editval[oxroles__oxactive]"));
        $this->type("editval[oxroles__oxtitle]", "create_delete admin role_šÄßüл");
        $this->check("editval[oxroles__oxactive]");
        $this->clickAndWaitFrame("save", "list");
        $this->assertEquals("on", $this->getValue("editval[oxroles__oxactive]"));
        $this->assertEquals("create_delete admin role_šÄßüл", $this->getValue("editval[oxroles__oxtitle]"));
        //setting options
        $this->assertEquals("Master Settings", $this->getText("//tr[@id='mxmainmenu']/td[2]"));
        $this->assertFalse($this->isChecked("aFields[mxmainmenu]_cust"));
        $this->click("//tr[@id='mxmainmenu']/td[1]/a");    //expand Master Settings
        $this->assertEquals("Core Settings", $this->getText("//tr[@id='mxcoresett']/td[2]"));
        $this->click("//input[@name='aFields[mxcoresett]' and @value='0']");
        $this->assertEquals("Countries", $this->getText("//tr[@id='mxcountries']/td[2]"));
        $this->click("//input[@name='aFields[mxcountries]' and @value='1']");
        $this->assertFalse($this->isChecked("aFields[mxcountries]_cust"));
        $this->click("//tr[@id='mxcountries']/td[1]/a");    //expand Countries
        $this->click("//input[@name='aFields[tbclcountry_main]' and @value='0']");
        $this->click("//tr[@id='mxcountries']/td[1]/table/tbody/tr[1]/td[6]/div");  //close Countries
        $this->assertTrue($this->isChecked("aFields[mxcountries]_cust"));
        $this->assertEquals("Distributors", $this->getText("//tr[@id='mxvendor']/td[2]"));
        $this->assertFalse($this->isChecked("aFields[mxvendor]_cust"));
        $this->click("//tr[@id='mxvendor']/td[1]/a");    //expand Manufacturers
        $this->click("//input[@name='aFields[tbclvendor_main]' and @value='1']");
        $this->click("//input[@name='aFields[tbclvendor_mall]' and @value='0']");
        $this->click("//tr[@id='mxvendor']/td[1]/table/tbody/tr[1]/td[6]/div");  //close Manufacturers
        $this->assertTrue($this->isChecked("aFields[mxvendor]_cust"));
        $this->click("//tr[@id='mxmainmenu']/td/table/tbody/tr/td[6]/div"); //close Master settings
        $this->assertTrue($this->isChecked("aFields[mxmainmenu]_cust"));
        $this->assertEquals("Shop Settings", $this->getText("//tr[@id='mxshopsett']/td[2]"));
        $this->assertFalse($this->isChecked("aFields[mxshopsett]_cust"));
        $this->click("//input[@name='aFields[mxshopsett]' and @value='0']");
        $this->assertFalse($this->isChecked("aFields[mxshopsett]_cust"));
        $this->assertEquals("Administer Products", $this->getText("//tr[@id='mxmanageprod']/td[2]"));
        $this->click("//input[@name='aFields[mxmanageprod]' and @value='1']");
        $this->assertFalse($this->isChecked("aFields[mxmanageprod]_cust"));
        $this->click("//tr[@id='mxmanageprod']/td[1]/a");    //expand Administer products
        $this->assertFalse($this->isChecked("aFields[mxarticles]_cust"));
        $this->click("//tr[@id='mxarticles']/td[1]/a");    //expand Products
        $this->click("//input[@name='aFields[tbclarticle_main]' and @value='0']");
        $this->click("//tr[@id='mxarticles']/td[1]/table/tbody/tr[1]/td[6]/div");  //close Products
        $this->assertTrue($this->isChecked("aFields[mxarticles]_cust"));
        $this->assertEquals("Attributes", $this->getText("//tr[@id='mxattributes']/td[2]"));
        $this->assertFalse($this->isChecked("aFields[mxattributes]_cust"));
        $this->click("//input[@name='aFields[mxattributes]' and @value='0']");
        $this->click("//tr[@id='mxmanageprod']/td[1]/table/tbody/tr[1]/td[6]/div");  //close Administer Products
        $this->assertTrue($this->isChecked("aFields[mxmanageprod]_cust"));
        $this->clickAndWaitFrame("save", "list");
        //checking if saved correctly
        $this->assertEquals("Master Settings", $this->getText("//tr[@id='mxmainmenu']/td[2]"));
        $this->assertTrue($this->isChecked("aFields[mxmainmenu]_cust"));
        $this->assertEquals("2", $this->getValue("//input[@name='aFields[mxmainmenu]']"));
        $this->assertTrue($this->isChecked("aFields[mxmainmenu]_cust"));
        $this->click("//tr[@id='mxmainmenu']/td[1]/a");    //expand Master Settings
        $this->assertEquals("Core Settings", $this->getText("//tr[@id='mxcoresett']/td[2]"));
        $this->assertEquals("0", $this->getValue("//input[@name='aFields[mxcoresett]']"));
        $this->assertFalse($this->isChecked("aFields[mxcoresett]_cust"));
        $this->click("//tr[@id='mxcoresett']/td[1]/a");    //expand Core Settings
        $this->assertEquals("Main", $this->getText("//tr[@id='tbclshop_main']/td[2]"));
        $this->assertFalse($this->isEditable("//input[@name='aFields[tbclshop_main]' and @value='2']"));
        $this->assertFalse($this->isEditable("//input[@name='aFields[tbclshop_main]' and @value='1']"));
        $this->assertEquals("0", $this->getValue("//input[@name='aFields[tbclshop_main]']"));
        $this->click("//tr[@id='mxcoresett']/td[1]/table/tbody/tr[1]/td[6]/div");    //close Core Settings
        $this->assertEquals("Countries", $this->getText("//tr[@id='mxcountries']/td[2]"));
        $this->assertEquals("1", $this->getValue("//input[@name='aFields[mxcountries]']"));
        $this->assertTrue($this->isChecked("aFields[mxcountries]_cust"));
        $this->click("//tr[@id='mxcountries']/td[1]/a");    //expand Countries
        $this->assertEquals("Main", $this->getText("//tr[@id='tbclcountry_main']/td[2]"));
        $this->assertFalse($this->isEditable("//input[@name='aFields[tbclcountry_main]' and @value='2']"));
        $this->assertEquals("0", $this->getValue("//input[@name='aFields[tbclcountry_main]']"));
        $this->click("//tr[@id='mxcountries']/td[1]/table/tbody/tr[1]/td[6]/div");  //close Countries
        $this->assertEquals("Distributors", $this->getText("//tr[@id='mxvendor']/td[2]"));
        $this->assertEquals("2", $this->getValue("//input[@name='aFields[mxvendor]']"));
        $this->assertTrue($this->isChecked("aFields[mxvendor]_cust"));
        $this->click("//tr[@id='mxvendor']/td[1]/a");    //expand Manufacturers
        $this->assertEquals("Main", $this->getText("//tr[@id='tbclvendor_main']/td[2]"));
        $this->assertEquals("1", $this->getValue("//input[@name='aFields[tbclvendor_main]']"));
        $this->assertEquals("Mall", $this->getText("//tr[@id='tbclvendor_mall']/td[2]"));
        $this->assertEquals("0", $this->getValue("//input[@name='aFields[tbclvendor_mall]']"));
        $this->click("//tr[@id='mxvendor']/td[1]/table/tbody/tr[1]/td[6]/div");  //close Manufacturers
        $this->click("//tr[@id='mxmainmenu']/td/table/tbody/tr/td[6]/div"); //close Master settings
        $this->assertEquals("Shop Settings", $this->getText("//tr[@id='mxshopsett']/td[2]"));
        $this->assertEquals("0", $this->getValue("//input[@name='aFields[mxshopsett]']"));
        $this->assertFalse($this->isChecked("aFields[mxshopsett]_cust"));
        $this->click("//tr[@id='mxshopsett']/td[1]/a");    //expand Shop Settings
        $this->assertEquals("Payment Methods", $this->getText("//tr[@id='mxpaymeth']/td[2]"));
        $this->assertFalse($this->isEditable("//input[@name='aFields[mxpaymeth]' and @value='2']"));
        $this->assertFalse($this->isEditable("//input[@name='aFields[mxpaymeth]' and @value='1']"));
        $this->assertEquals("0", $this->getValue("//input[@name='aFields[mxpaymeth]']"));
        $this->assertFalse($this->isChecked("aFields[mxpaymeth]_cust"));
        $this->click("//tr[@id='mxpaymeth']/td[1]/a");    //expand Payment Methods
        $this->assertEquals("Main", $this->getText("//tr[@id='tbclpayment_main']/td[2]"));
        $this->assertFalse($this->isEditable("//input[@name='aFields[tbclpayment_main]' and @value='2']"));
        $this->assertFalse($this->isEditable("//input[@name='aFields[tbclpayment_main]' and @value='1']"));
        $this->assertEquals("0", $this->getValue("//input[@name='aFields[tbclpayment_main]']"));
        $this->click("//tr[@id='mxpaymeth']/td[1]/table/tbody/tr[1]/td[6]/div");  //close Payment Methods
        $this->click("//tr[@id='mxshopsett']/td[1]/table/tbody/tr[1]/td[6]/div");  //close Shop Settings
        $this->assertEquals("Administer Products", $this->getText("//tr[@id='mxmanageprod']/td[2]"));
        $this->assertEquals("1", $this->getValue("//input[@name='aFields[mxmanageprod]']"));
        $this->assertTrue($this->isChecked("aFields[mxmanageprod]_cust"));
        $this->click("//tr[@id='mxmanageprod']/td[1]/a");    //expand Administer products
        $this->assertEquals("Products", $this->getText("//tr[@id='mxarticles']/td[2]"));
        $this->assertFalse($this->isEditable("//input[@name='aFields[mxarticles]' and @value='2']"));
        $this->assertEquals("1", $this->getValue("//input[@name='aFields[mxarticles]']"));
        $this->assertTrue($this->isChecked("aFields[mxarticles]_cust"));
        $this->click("//tr[@id='mxarticles']/td[1]/a");    //expand Products
        $this->assertEquals("Main", $this->getText("//tr[@id='tbclarticle_main']/td[2]"));
        $this->assertFalse($this->isEditable("//input[@name='aFields[tbclarticle_main]' and @value='2']"));
        $this->assertEquals("0", $this->getValue("//input[@name='aFields[tbclarticle_main]']"));
        $this->assertEquals("Extended", $this->getText("//tr[@id='tbclarticle_extend']/td[2]"));
        $this->assertFalse($this->isEditable("//input[@name='aFields[tbclarticle_extend]' and @value='2']"));
        $this->assertEquals("1", $this->getValue("//input[@name='aFields[tbclarticle_extend]']"));
        $this->click("//tr[@id='mxarticles']/td[1]/table/tbody/tr[1]/td[6]/div");  //close Products
        $this->assertEquals("Attributes", $this->getText("//tr[@id='mxattributes']/td[2]"));
        $this->assertFalse($this->isEditable("//input[@name='aFields[mxattributes]' and @value='2']"));
        $this->assertEquals("0", $this->getValue("//input[@name='aFields[mxattributes]']"));
        $this->assertFalse($this->isChecked("aFields[mxattributes]_cust"));
        $this->click("//tr[@id='mxattributes']/td[1]/a");    //expand Attributes
        $this->assertEquals("Main", $this->getText("//tr[@id='tbclattribute_main']/td[2]"));
        $this->assertFalse($this->isEditable("//input[@name='aFields[tbclattribute_main]' and @value='2']"));
        $this->assertFalse($this->isEditable("//input[@name='aFields[tbclattribute_main]' and @value='1']"));
        $this->assertEquals("0", $this->getValue("//input[@name='aFields[tbclattribute_main]']"));
        $this->assertEquals("Category", $this->getText("//tr[@id='tbclattribute_category']/td[2]"));
        $this->assertFalse($this->isEditable("//input[@name='aFields[tbclattribute_category]' and @value='2']"));
        $this->assertFalse($this->isEditable("//input[@name='aFields[tbclattribute_category]' and @value='1']"));
        $this->assertEquals("0", $this->getValue("//input[@name='aFields[tbclattribute_category]']"));
        $this->assertEquals("Mall", $this->getText("//tr[@id='tbclattribute_mall']/td[2]"));
        $this->assertFalse($this->isEditable("//input[@name='aFields[tbclattribute_mall]' and @value='2']"));
        $this->assertFalse($this->isEditable("//input[@name='aFields[tbclattribute_mall]' and @value='1']"));
        $this->assertEquals("0", $this->getValue("//input[@name='aFields[tbclattribute_mall]']"));
        $this->click("//tr[@id='mxattributes']/td[1]/table/tbody/tr[1]/td[6]/div");  //close Attributes
        $this->click("//tr[@id='mxmanageprod']/td[1]/table/tbody/tr[1]/td[6]/div");  //close Administer Products
        //testing if other tabs are working
        $this->openTab("Users");
        $this->openTab("Objects");
        //checking if created item can be found
        $this->frame("list");
        $this->type("where[oxroles][oxtitle]", "create_delete admin role_šÄßüл");
        $this->clickAndWait("submitit");
        $this->assertEquals("create_delete admin role_šÄßüл", $this->getText("//tr[@id='row.1']/td[1]"));
        $this->assertElementNotPresent("//tr[@id='row.2']/td[1]");
    }

    /**
     * creating Shop Roles
     *
     * @group creatingitems
     * @group quarantine
     */
    public function testCreateShopRoles()
    {
        //creating shop role
        $this->loginAdmin("Administer Users", "Shop Roles");
        $this->frame("edit");
        $this->clickCreateNewItem();
        $this->assertEquals("off", $this->getValue("editval[oxroles__oxactive]"));
        $this->check("editval[oxroles__oxactive]");
        $this->type("editval[oxroles__oxtitle]", "create_delete shop role_šÄßüл");
        $this->clickAndWaitFrame("save", "list");
        $this->assertEquals("on", $this->getValue("editval[oxroles__oxactive]"));
        $this->assertEquals("create_delete shop role_šÄßüл", $this->getValue("editval[oxroles__oxtitle]"));
        $this->assertEquals("off", $this->getValue("//td[@id='_rolescontent']/table/tbody/tr/td/div/table/tbody/tr[2]/td[2]/input[2]"));
        $this->assertEquals("off", $this->getValue("//td[@id='_rolescontent']/table/tbody/tr/td/div/table/tbody/tr[3]/td[2]/input[2]"));
        $this->assertEquals("off", $this->getValue("//td[@id='_rolescontent']/table/tbody/tr/td/div/table/tbody/tr[4]/td[2]/input[2]"));
        $this->assertEquals("off", $this->getValue("//td[@id='_rolescontent']/table/tbody/tr/td/div/table/tbody/tr[5]/td[2]/input[2]"));
        $this->uncheck("editval[oxroles__oxactive]");
        $this->type("editval[oxroles__oxtitle]", "create_delete shop role1");
        $this->clickAndWaitFrame("save", "list");
        $this->type("newfnc", "IDENT&class;function");
        $this->clickAndWait("//input[@value='Add field']");
        $this->assertEquals("off", $this->getValue("editval[oxroles__oxactive]"));
        $this->assertEquals("create_delete shop role1", $this->getValue("editval[oxroles__oxtitle]"));

        // Find IDENT index in list.
        $identIndex = null;
        for ($i = 1; $i <= 7; $i++) {
            if ("IDENT (class;function)" == $this->getText("//td[@id='_rolescontent']/table/tbody/tr/td/div/table/tbody/tr[{$i}]/td")) {
                $identIndex = $i;
            }
        }
        if (!$identIndex) {
            $this->fail("IDENT (class;function) is not found in list");
        }

        $this->assertEquals("off", $this->getValue("//td[@id='_rolescontent']/table/tbody/tr/td/div/table/tbody/tr[{$identIndex}]/td[2]/input[2]"));
        $this->check("//td[@id='_rolescontent']/table/tbody/tr/td/div/table/tbody/tr[2]/td[2]/input[2]");
        $this->check("//td[@id='_rolescontent']/table/tbody/tr/td/div/table/tbody/tr[6]/td[2]/input[2]");
        $this->uncheck("editval[oxroles__oxactive]");
        $this->type("editval[oxroles__oxtitle]", "create_delete shop role");
        $this->clickAndWaitFrame("save", "list");
        $this->openTab("Main");
        $this->assertEquals("off", $this->getValue("editval[oxroles__oxactive]"));
        $this->assertEquals("create_delete shop role", $this->getValue("editval[oxroles__oxtitle]"));
        $this->assertEquals("on", $this->getValue("//td[@id='_rolescontent']/table/tbody/tr/td/div/table/tbody/tr[2]/td[2]/input[2]"));
        $this->assertEquals("off", $this->getValue("//td[@id='_rolescontent']/table/tbody/tr/td/div/table/tbody/tr[3]/td[2]/input[2]"));
        $this->assertEquals("off", $this->getValue("//td[@id='_rolescontent']/table/tbody/tr/td/div/table/tbody/tr[4]/td[2]/input[2]"));
        $this->assertEquals("off", $this->getValue("//td[@id='_rolescontent']/table/tbody/tr/td/div/table/tbody/tr[5]/td[2]/input[2]"));
        $this->assertEquals("on", $this->getValue("//td[@id='_rolescontent']/table/tbody/tr/td/div/table/tbody/tr[6]/td[2]/input[2]"));
        $this->clickAndWaitFrame("//td[@id='_rolescontent']/table/tbody/tr/td/div/table/tbody/tr[{$identIndex}]/td[3]/a", "edit");
        $this->assertElementNotPresent("//td[@id='_rolescontent']/table/tbody/tr/td/div/table/tbody/tr[7]/td[2]/input[2]");
        $this->assertTextNotPresent("IDENT (class;function)");
        //testing if other tabs are working
        $this->openTab("Users");
        //checking if created item can be found
        $this->frame("list");
        $this->type("where[oxroles][oxtitle]", "create_delete");
        $this->clickAndWait("submitit");
        $this->assertEquals("create_delete shop role", $this->getText("//tr[@id='row.1']/td[1]"));
        $this->assertElementNotPresent("//tr[@id='row.2']/td[1]");
    }

    /**
     * creating Product. Inventory tab
     *
     * @group creatingitems
     */
    public function testCreateProductInventory()
    {
        $this->loginAdmin("Administer Products", "Products");
        $this->frame("edit");
        //Product tab
        $this->type("editval[oxarticles__oxtitle]", "create_delete product");
        $this->type("editval[oxarticles__oxartnum]", "10000");
        $this->type("editval[oxarticles__oxprice]", "5.9");
        $this->clickAndWaitFrame("saveArticle", "list");
        $this->clickAndWaitFrame("//input[@name='save' and @value='Copy to']", "list");
        $this->selectAndWait("test_editlanguage", "label=English");
        // Inventory tab
        $this->openTab("Stock");
        $this->assertEquals("", $this->getValue("editval[oxarticles__oxorderinfo]"));
        $this->assertEquals("1", $this->getValue("editval[oxarticles__oxvpe]"));
        $this->type("editval[oxarticles__oxorderinfo]", "info in order confirmation mail_šÄßüл");
        $this->type("editval[oxarticles__oxvpe]", "3");
        $this->clickAndWait("save");
        $this->assertEquals("info in order confirmation mail_šÄßüл", $this->getValue("editval[oxarticles__oxorderinfo]"));
        $this->assertEquals("3", $this->getValue("editval[oxarticles__oxvpe]"));
    }

    /**
     * creating Category
     *
     * @group creatingitems
     */
    public function testCreateCategory()
    {
        $this->loginAdmin("Administer Products", "Categories");
        $this->changeAdminListLanguage('Deutsch');
        $this->clickAndWait("link=Title");
        $this->openListItem("link=1 [DE] category šÄßüл");
        $this->assertEquals("1 [DE] category šÄßüл", $this->getValue("editval[oxcategories__oxtitle]"));
        $this->assertEquals("Deutsch", $this->getSelectedLabel("test_editlanguage"));
        $this->frame("list");
        $this->changeAdminListLanguage('English');
        $this->frame("edit");
        $this->assertEquals("[last] [EN] category šÄßüл", $this->getValue("editval[oxcategories__oxtitle]"));
        $this->assertEquals("English", $this->getSelectedLabel("test_editlanguage"));
        $this->clickCreateNewItem();
        $this->assertEquals("off", $this->getValue("/descendant::input[@name='editval[oxcategories__oxskipdiscounts]'][2]"));
        $this->check("/descendant::input[@name='editval[oxcategories__oxskipdiscounts]'][2]");
        $this->clickAndWaitFrame("save", "list");
        $this->assertEquals("on", $this->getValue("/descendant::input[@name='editval[oxcategories__oxskipdiscounts]'][2]"));
        $this->uncheck("/descendant::input[@name='editval[oxcategories__oxskipdiscounts]'][2]");
        $this->clickAndWaitFrame("save", "list");
        $this->assertEquals("off", $this->getValue("/descendant::input[@name='editval[oxcategories__oxskipdiscounts]'][2]"));
    }

    /**
     * creating Product. Copy product
     *
     * @group creatingitems
     */
    public function testCreateProductCopy()
    {
        $this->loginAdmin("Administer Products", "Products");
        $this->frame("edit");
        $this->type("editval[oxarticles__oxtitle]", "create_delete product [EN]_šÄßüл");
        $this->type("editval[oxarticles__oxartnum]", "10001_šÄßüл");
        $this->clickAndWaitFrame("saveArticle", "list");
        $this->clickAndWaitFrame("/descendant::input[@name='save'][2]", "list");
        //Extended tab
        $this->frame("list");
        // Inventory tab
        $this->openTab("Stock");
        $this->type("editval[oxarticles__oxorderinfo]", "info in order confirmation mail");
        $this->type("editval[oxarticles__oxvpe]", "3");
        $this->clickAndWait("save");
        $this->frame("list");
        $this->type("where[oxarticles][oxartnum]", "10001");
        $this->clickAndWait("submitit");
        $this->openTab("Stock");
        $this->assertEquals("info in order confirmation mail", $this->getValue("editval[oxarticles__oxorderinfo]"));
        $this->assertEquals("3", $this->getValue("editval[oxarticles__oxvpe]"));
    }
}
