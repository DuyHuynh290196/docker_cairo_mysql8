<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Tests\Acceptance\International;

use OxidEsales\Eshop\Core\Edition\EditionPathProvider;
use OxidEsales\Eshop\Core\Edition\EditionRootPathProvider;
use OxidEsales\Eshop\Core\Edition\EditionSelector;
use OxidEsales\EshopEnterprise\Tests\Acceptance\EnterpriseAcceptanceTestCase;

/** Selenium tests for UTF-8 shop version. */
class InternationalTest extends EnterpriseAcceptanceTestCase
{
    /**
     * creating subshop
     * @group international
     * @group quarantine
     */
    public function testCreateSubshopInternational()
    {
        $shopNr = $this->getShopVersionNumber();

        //creating subshop with UTF-8 chars
        $this->loginAdmin("Master Settings", "Core Settings");
        $this->waitForFrameToLoad('list');
        $this->openListItem("link=OXID eShop ". $shopNr .' (1)');
        $this->assertEquals("Your Company Name", $this->getValue("editval[oxshops__oxcompany]"));
        $this->clickCreateNewItem();
        $this->waitForFrameAfterAction('edit');
        $this->waitForElement('shopname', 10, true);
        $this->assertElementPresent('shopname');
        $this->type("shopname", "create_new_subshop_šųößлы");

        $shopName = "-- OXID eShop " . $shopNr . " (1)";
        $iNewSubshopId = 2;
        if (isSUBSHOP) {
            $shopName = "-- OXID eShop " . $shopNr . " (1) subshop (2)";
            $iNewSubshopId = 3;
        }

        $this->assertEquals($shopName, $this->clearString($this->getText("shopparent")));
        $this->uncheck("isinherited");
        $this->check("//input[@name='editval[oxshops__oxissupershop]' and @value='1']");
        $this->check("//input[@name='editval[oxshops__oxismultishop]' and @value='1']");
        $this->select("shopparent", "label=OXID eShop ". $shopNr ." (1)");
        $this->clickAndWaitFrame("save", 'navigation');

        $this->check("editval[oxshops__oxactive]");
        $this->type("editval[oxshops__oxcompany]", "Ihr Firmenname1_šųößлы");
        $this->type("editval[oxshops__oxfname]", "Hans1_šųößлы");
        $this->type("editval[oxshops__oxlname]", "Mustermann1_šųößлы");
        $this->type("editval[oxshops__oxstreet]", "Musterstr. 101_šųößлы");
        $this->type("editval[oxshops__oxzip]", "790981_šųößлы");
        $this->type("editval[oxshops__oxcity]", "Musterstadt1_šųößлы");
        $this->type("editval[oxshops__oxcountry]", "Deutschland1_šųößлы");
        $this->type("editval[oxshops__oxtelefon]", "0800 12345671_šųößлы");
        $this->type("editval[oxshops__oxtelefax]", "0800 12345671_šųößлы");
        $this->type("editval[oxshops__oxurl]", "www.meineshopurl1.com_šųößлы");
        $this->type("editval[oxshops__oxbankname]", "Volksbank Musterstadt1_šųößлы");
        $this->type("editval[oxshops__oxbankcode]", "900 12345671_šųößлы");
        $this->type("editval[oxshops__oxbanknumber]", "12345678901_šųößлы");
        $this->type("editval[oxshops__oxvatnumber]", "111_šųößлы");
        $this->type("editval[oxshops__oxbiccode]", "1111_šųößлы");
        $this->type("editval[oxshops__oxibannumber]", "11111_šųößлы");
        $this->type("editval[oxshops__oxhrbnr]", "111111_šųößлы");
        $this->type("editval[oxshops__oxcourt]", "1111111_šųößлы");
        $this->type("editval[oxshops__oxname]", "create_new_subshop1_šųößлы");
        $this->type("editval[oxshops__oxsmtp]", "localhost");
        $this->type("editval[oxshops__oxsmtpuser]", "user_šųößлы");
        $this->type("oxsmtppwd", "pass");
        $this->type("editval[oxshops__oxinfoemail]", "");
        $this->type("editval[oxshops__oxorderemail]", "");
        $this->type("editval[oxshops__oxowneremail]", "");
        $this->clickAndWaitFrame("save", 'navigation');

        $this->assertEquals($this->getSelectedLabel("subjlang"), "English");
        $this->changeAdminEditLanguage("Deutsch", "subjlang");
        $this->assertEquals($this->getSelectedLabel("subjlang"), "Deutsch");
        $this->waitForFrameToLoad('edit');
        $this->type("editval[oxshops__oxordersubject]", "Ihre Bestellung bei OXID eSales1_šųößлы");
        $this->type("editval[oxshops__oxregistersubject]", "Vielen Dank fur Ihre Registrierung im OXID eShop1_šųößлы");
        $this->type("editval[oxshops__oxforgotpwdsubject]", "Ihr Passwort im OXID eShop1_šųößлы");
        $this->type("editval[oxshops__oxsendednowsubject]", "Ihre OXID eSales Bestellung wurde versandt1_šųößлы");
        $this->clickAndWaitFrame("save", 'navigation');

        $this->waitForFrameAfterAction('edit');
        $this->assertEquals("off", $this->getValue("editval[oxshops__oxproductive]"));
        $this->assertEquals("on", $this->getValue("editval[oxshops__oxactive]"));
        $this->assertEquals("Ihr Firmenname1_šųößлы", $this->getValue("editval[oxshops__oxcompany]"));
        $this->assertEquals("Hans1_šųößлы", $this->getValue("editval[oxshops__oxfname]"));
        $this->assertEquals("Mustermann1_šųößлы", $this->getValue("editval[oxshops__oxlname]"));
        $this->assertEquals("Musterstr. 101_šųößлы", $this->getValue("editval[oxshops__oxstreet]"));
        $this->assertEquals("790981_šųößлы", $this->getValue("editval[oxshops__oxzip]"));
        $this->assertEquals("Musterstadt1_šųößлы", $this->getValue("editval[oxshops__oxcity]"));
        $this->assertEquals("Deutschland1_šųößлы", $this->getValue("editval[oxshops__oxcountry]"));
        $this->assertEquals("0800 12345671_šųößлы", $this->getValue("editval[oxshops__oxtelefon]"));
        $this->assertEquals("0800 12345671_šųößлы", $this->getValue("editval[oxshops__oxtelefax]"));
        $this->assertEquals("www.meineshopurl1.com_šųößлы", $this->getValue("editval[oxshops__oxurl]"));
        $this->assertEquals("Volksbank Musterstadt1_šųößлы", $this->getValue("editval[oxshops__oxbankname]"));
        $this->assertEquals("900 12345671_šųößлы", $this->getValue("editval[oxshops__oxbankcode]"));
        $this->assertEquals("12345678901_šųößлы", $this->getValue("editval[oxshops__oxbanknumber]"));
        $this->assertEquals("111_šųößлы", $this->getValue("editval[oxshops__oxvatnumber]"));
        $this->assertEquals("1111_šųößлы", $this->getValue("editval[oxshops__oxbiccode]"));
        $this->assertEquals("11111_šųößлы", $this->getValue("editval[oxshops__oxibannumber]"));
        $this->assertEquals("111111_šųößлы", $this->getValue("editval[oxshops__oxhrbnr]"));
        $this->assertEquals("1111111_šųößлы", $this->getValue("editval[oxshops__oxcourt]"));
        $this->assertEquals("create_new_subshop1_šųößлы", $this->getValue("editval[oxshops__oxname]"));
        $this->assertEquals("$iNewSubshopId", $this->getText("//form[@id='myedit']/table/tbody/tr/td[2]/table/tbody/tr[4]/td[2]"));
        $this->assertEquals("OXID eShop ". $shopNr ."(1)", $this->getText("//form[@id='myedit']/table/tbody/tr/td[2]/table/tbody/tr[3]/td[2]"));
        $this->assertEquals("localhost", $this->getValue("editval[oxshops__oxsmtp]"));
        $this->assertEquals("user_šųößлы", $this->getValue("editval[oxshops__oxsmtpuser]"));
        $this->assertEquals("", $this->getValue("oxsmtppwd"));
        $this->assertEquals("", $this->getValue("editval[oxshops__oxinfoemail]"));
        $this->assertEquals("", $this->getValue("editval[oxshops__oxorderemail]"));
        $this->assertEquals("", $this->getValue("editval[oxshops__oxowneremail]"));
        $this->assertEquals("Ihre Bestellung bei OXID eSales1_šųößлы", $this->getValue("editval[oxshops__oxordersubject]"));
        $this->assertEquals("Vielen Dank fur Ihre Registrierung im OXID eShop1_šųößлы", $this->getValue("editval[oxshops__oxregistersubject]"));
        $this->assertEquals("Ihr Passwort im OXID eShop1_šųößлы", $this->getValue("editval[oxshops__oxforgotpwdsubject]"));
        $this->assertEquals("Ihre OXID eSales Bestellung wurde versandt1_šųößлы", $this->getValue("editval[oxshops__oxsendednowsubject]"));

        $this->assertEquals($this->getSelectedLabel("subjlang"), "Deutsch");
        $this->changeAdminEditLanguage("English", "subjlang");
        $this->assertEquals($this->getSelectedLabel("subjlang"), "English");

        $this->type("editval[oxshops__oxordersubject]", "Your order from OXID eShop1");
        $this->type("editval[oxshops__oxregistersubject]", "Thank you for your registration in OXID eShop1");
        $this->type("editval[oxshops__oxforgotpwdsubject]", "Your OXID eShop password1");
        $this->type("editval[oxshops__oxsendednowsubject]", "Your OXID eSales Order has been shipped1");
        $this->type("oxsmtppwd", "-");
        $this->clickAndWaitFrame("save", 'edit');

        $this->assertEquals("Your order from OXID eShop1", $this->getValue("editval[oxshops__oxordersubject]"));
        $this->assertEquals("Thank you for your registration in OXID eShop1", $this->getValue("editval[oxshops__oxregistersubject]"));
        $this->assertEquals("Your OXID eShop password1", $this->getValue("editval[oxshops__oxforgotpwdsubject]"));
        $this->assertEquals("Your OXID eSales Order has been shipped1", $this->getValue("editval[oxshops__oxsendednowsubject]"));

        $this->changeAdminEditLanguage("Deutsch", "subjlang");
        $this->assertEquals("Ihre Bestellung bei OXID eSales1_šųößлы", $this->getValue("editval[oxshops__oxordersubject]"));
        $this->assertEquals("Vielen Dank fur Ihre Registrierung im OXID eShop1_šųößлы", $this->getValue("editval[oxshops__oxregistersubject]"));
        $this->assertEquals("Ihr Passwort im OXID eShop1_šųößлы", $this->getValue("editval[oxshops__oxforgotpwdsubject]"));
        $this->assertEquals("Ihre OXID eSales Bestellung wurde versandt1_šųößлы", $this->getValue("editval[oxshops__oxsendednowsubject]"));
        $this->assertEquals("", $this->getValue("oxsmtppwd"));
        // testing SEO
        $this->frame("list");
        $this->openTab("SEO");
        $this->assertEquals("English", $this->getSelectedLabel("test_editlanguage"));

        $this->type("editval[oxshops__oxtitleprefix]", "prefix EN šųößлы");
        $this->type("editval[oxshops__oxtitlesuffix]", "suffix EN šųößлы");
        $this->type("editval[oxshops__oxstarttitle]", "title EN šųößлы");
        $this->clickAndWait("//input[@name='save' and @value='Save']");
        $this->assertEquals("prefix EN šųößлы", $this->getValue("editval[oxshops__oxtitleprefix]"));
        $this->assertEquals("suffix EN šųößлы", $this->getValue("editval[oxshops__oxtitlesuffix]"));
        $this->assertEquals("title EN šųößлы", $this->getValue("editval[oxshops__oxstarttitle]"));

        //resetting seo ID's'
        $this->clickAndConfirm("//input[@name='save' and @value='Update SEO URLs']");

        //Checking shop frontend
        $this->clearCache();
        // Open newly created subshop
        $this->openShop(false, true, "link=create_new_subshop1_šųößлы");
        $this->assertElementPresent("panel");
        $this->assertEquals("prefix EN šųößлы | title EN šųößлы", $this->getTitle());
    }
}
