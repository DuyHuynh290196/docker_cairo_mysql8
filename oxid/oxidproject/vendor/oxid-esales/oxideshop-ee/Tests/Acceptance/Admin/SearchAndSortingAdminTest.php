<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Tests\Acceptance\Admin;

use OxidEsales\EshopEnterprise\Tests\Acceptance\EnterpriseAcceptanceTestCase;

class SearchAndSortingAdminTest extends EnterpriseAcceptanceTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        /**
         * This test uses hardcoded indexes and can't manage DB row inserts to certain tables.
         * Use the original data it was written for.
         */
        $this->loadTestFixtures();

        $this->getTranslator()->setLanguage(1);
    }

    /**
     * searching and sorting Admin Roles (EE version only)
     *
     * @group search_sort
     */
    public function testSearchSortAdminRoles()
    {
        $testConfig = $this->getTestConfig();
        if ($testConfig->isSubShop()) {
            $this->executeSql( "UPDATE `oxroles` SET `OXSHOPID` = ".oxSHOPID."  WHERE `OXAREA` = 0;" );
        }

        $this->loginAdmin("Administer Products", "Admin Roles");
        //search
        $this->type("where[oxroles][oxtitle]", "admin");
        $this->clickAndWait("submitit");
        $this->assertElementPresent("link=1 admin role šÄßüл");
        $this->assertElementPresent("link=2 admin role šÄßüл");
        $this->assertElementPresent("link=3 admin role šÄßüл");
        $this->assertElementPresent("link=4 admin role šÄßüл");
        $this->assertElementPresent("link=[last] admin role šÄßüл");
        $this->assertElementNotPresent("//tr[@id='row.6']/td[1]");
        $this->type("where[oxroles][oxtitle]", "4 admin šÄßüл");
        $this->clickAndWait("submitit");
        $this->assertEquals("4 admin role šÄßüл", $this->getText("//tr[@id='row.1']/td[1]"));
        $this->type("where[oxroles][oxtitle]", "");
        $this->clickAndWait("submitit");
        //sorting
        $this->clickAndWait("link=Title");
        $this->assertEquals("1 admin role šÄßüл", $this->getText("//tr[@id='row.1']/td[1]"));
        $this->assertEquals("2 admin role šÄßüл", $this->getText("//tr[@id='row.2']/td[1]"));
        $this->assertEquals("3 admin role šÄßüл", $this->getText("//tr[@id='row.3']/td[1]"));
        //$this->assertEquals("Page 1 / 2", $this->getText("nav.site"));
        $this->assertElementPresent("//a[@id='nav.page.1'][@class='pagenavigation pagenavigationactive']");
        $this->clickAndWait("nav.next");
        //$this->assertEquals("Page 2 / 2", $this->getText("nav.site"));
        $this->assertElementPresent("//a[@id='nav.page.2'][@class='pagenavigation pagenavigationactive']");
        $this->assertEquals("[last] admin role šÄßüл", $this->getText("//tr[@id='row.1']/td[1]"));
        $this->clickAndWait("nav.prev");
        //$this->assertEquals("Page 1 / 2", $this->getText("nav.site"));
        $this->assertElementPresent("//a[@id='nav.page.1'][@class='pagenavigation pagenavigationactive']");
        $this->assertEquals("1 admin role šÄßüл", $this->getText("//tr[@id='row.1']/td[1]"));
        $this->clickAndWait("nav.last");
        $this->assertEquals("[last] admin role šÄßüл", $this->getText("//tr[@id='row.1']/td[1]"));
        //$this->assertEquals("Page 2 / 2", $this->getText("nav.site"));
        $this->assertElementPresent("//a[@id='nav.page.2'][@class='pagenavigation pagenavigationactive']");
        $this->clickAndWait("nav.first");
        $this->assertEquals("1 admin role šÄßüл", $this->getText("//tr[@id='row.1']/td[1]"));
        //$this->assertEquals("Page 1 / 2", $this->getText("nav.site"));
        $this->assertElementPresent("//a[@id='nav.page.1'][@class='pagenavigation pagenavigationactive']");
        //deleting last item to check navigation
        $this->clickAndWait("nav.last");
        $this->assertEquals("[last] admin role šÄßüл", $this->getText("//tr[@id='row.1']/td[1]"));
        $this->clickDeleteListItem(1);
        $this->assertElementNotPresent("nav.page.1");
        $this->assertEquals("1 admin role šÄßüл", $this->getText("//tr[@id='row.1']/td[1]"));
        $this->logoutAdmin();
    }

    /**
     * searching and sorting Shop Roles (EE version only)
     *
     * @group search_sort
     */
    public function testSearchSortShopRoles()
    {
        $this->loginAdmin("Administer Products", "Shop Roles");
        //search
        $this->assertElementPresent("link=1 shop role šÄßüл");
        $this->assertElementPresent("link=10 shop role šÄßüл");
        $this->assertElementPresent("link=2 shop role šÄßüл");
        $this->type("where[oxroles][oxtitle]", "1 shop šÄßüл");
        $this->clickAndWait("submitit");
        $this->assertElementPresent("link=1 shop role šÄßüл");
        $this->assertElementPresent("link=10 shop role šÄßüл");
        $this->assertElementNotPresent("link=2 shop role šÄßüл");
        $this->assertEquals("1 shop šÄßüл", $this->getValue("where[oxroles][oxtitle]"));
        $this->type("where[oxroles][oxtitle]", "10 shop");
        $this->clickAndWait("submitit");
        $this->assertEquals("10 shop role šÄßüл", $this->getText("//tr[@id='row.1']/td[1]"));
        $this->assertElementNotPresent("//tr[@id='row.2']/td[1]");
        $this->type("where[oxroles][oxtitle]", "");
        $this->clickAndWait("submitit");
        //sorting
        $this->clickAndWait("link=Title");
        $this->assertEquals("1 shop role šÄßüл", $this->getText("//tr[@id='row.1']/td[1]"));
        $this->assertEquals("10 shop role šÄßüл", $this->getText("//tr[@id='row.2']/td[1]"));
        $this->assertEquals("2 shop role šÄßüл", $this->getText("//tr[@id='row.3']/td[1]"));
        $this->assertEquals("3 shop role šÄßüл", $this->getText("//tr[@id='row.4']/td[1]"));
        $this->assertEquals("Page 1 / 2", $this->getText("nav.site"));
        $this->assertElementPresent("//a[@id='nav.page.1'][@class='pagenavigation pagenavigationactive']");
        $this->clickAndWait("nav.next");
        $this->assertEquals("Page 2 / 2", $this->getText("nav.site"));
        $this->assertElementPresent("//a[@id='nav.page.2'][@class='pagenavigation pagenavigationactive']");
        $this->assertEquals("[last] shop role šÄßüл", $this->getText("//tr[@id='row.1']/td[1]"));
        $this->clickAndWait("nav.prev");
        $this->assertEquals("1 shop role šÄßüл", $this->getText("//tr[@id='row.1']/td[1]"));
        $this->assertEquals("Page 1 / 2", $this->getText("nav.site"));
        $this->assertElementPresent("//a[@id='nav.page.1'][@class='pagenavigation pagenavigationactive']");
        $this->clickAndWait("nav.last");
        $this->assertEquals("[last] shop role šÄßüл", $this->getText("//tr[@id='row.1']/td[1]"));
        $this->assertEquals("Page 2 / 2", $this->getText("nav.site"));
        $this->assertElementPresent("//a[@id='nav.page.2'][@class='pagenavigation pagenavigationactive']");
        $this->clickAndWait("nav.first");
        $this->assertEquals("1 shop role šÄßüл", $this->getText("//tr[@id='row.1']/td[1]"));
        $this->assertEquals("Page 1 / 2", $this->getText("nav.site"));
        $this->assertElementPresent("//a[@id='nav.page.1'][@class='pagenavigation pagenavigationactive']");
        //deleting last element to check navigation
        $this->clickAndWait("nav.last");
        $this->assertEquals("[last] shop role šÄßüл", $this->getText("//tr[@id='row.1']/td[1]"));
        $this->clickDeleteListItem(1);
        $this->assertElementNotPresent("nav.page.1");
        $this->assertEquals("1 shop role šÄßüл", $this->getText("//tr[@id='row.1']/td[1]"));
        $this->logoutAdmin();
    }

    /**
     * sorting CMS Pages for subshop
     *
     * @group search_sort
     */
    public function testSortCmsPagesForSubShop()
    {
        $testConfig = $this->getTestConfig();
        if (!$testConfig->isSubShop()) {
            $this->markTestSkipped('This test is for Subshops only');
        }

        $iLastPage = 5;
        $iPreviousPageBeforeLastPage = $iLastPage - 1;
        $iLastPageLastElementId = 9;
        $sLastPageLastElementId = "[last]testcontent";
        $sPreviuosPageBeforeLastPageText = "oxstdfooter";

        $this->loginAdmin("Customer Info", "CMS Pages");

        $iTitleCol = 2;
        $iIdentCol = 3;

        //sorting
        $this->clickAndWait("link=Title");
        $this->assertEquals("3 [EN] content šÄßüл", $this->getText("//tr[@id='row.1']/td[" . $iTitleCol . "]"));
        $this->assertEquals("About Us", $this->getText("//tr[@id='row.2']/td[" . $iTitleCol . "]"));
        $this->assertEquals("Credits", $this->getText("//tr[@id='row.5']/td[" . $iTitleCol . "]"));
        $this->assertEquals("1testcontent", $this->getText("//tr[@id='row.1']/td[" . $iIdentCol . "]"));
        $this->assertEquals("oximpressum", $this->getText("//tr[@id='row.2']/td[" . $iIdentCol . "]"));
        $this->assertEquals("oxcredits", $this->getText("//tr[@id='row.5']/td[" . $iIdentCol . "]"));
        $this->clickAndWait("link=Ident");
        $this->assertEquals("1testcontent", $this->getText("//tr[@id='row.1']/td[" . $iIdentCol . "]"));
        $this->assertEquals("oxadminorderemail", $this->getText("//tr[@id='row.2']/td[" . $iIdentCol . "]"));
        $this->assertElementNotPresent("link=oximpressum");
        $this->assertEquals("Page 1 / $iLastPage", $this->getText("nav.site"));
        $this->assertElementPresent("//a[@id='nav.page.1'][@class='pagenavigation pagenavigationactive']");
        $this->clickAndWait("nav.next");
        $this->assertEquals("Page 2 / $iLastPage", $this->getText("nav.site"));
        $this->assertElementPresent("//a[@id='nav.page.2'][@class='pagenavigation pagenavigationactive']");
        $this->assertElementPresent("link=oximpressum");
        $this->clickAndWait("nav.last");
        $this->assertEquals("Page $iLastPage / $iLastPage", $this->getText("nav.site"));
        $this->assertElementPresent("//a[@id='nav.page.$iLastPage'][@class='pagenavigation pagenavigationactive']");

        $this->assertEquals($sLastPageLastElementId,
            $this->getText("//tr[@id='row.$iLastPageLastElementId']/td[" . $iIdentCol . "]"));
        $this->clickAndWait("nav.prev");
        $this->assertEquals("Page $iPreviousPageBeforeLastPage / $iLastPage", $this->getText("nav.site"));
        $this->assertElementPresent("//a[@id='nav.page.$iPreviousPageBeforeLastPage'][@class='pagenavigation pagenavigationactive']");
        $this->assertElementPresent("link=$sPreviuosPageBeforeLastPageText");
        $this->clickAndWait("nav.first");
        $this->assertEquals("Page 1 / $iLastPage", $this->getText("nav.site"));
        $this->assertElementPresent("//a[@id='nav.page.1'][@class='pagenavigation pagenavigationactive']");
        $this->assertEquals("1testcontent", $this->getText("//tr[@id='row.1']/td[" . $iIdentCol . "]"));
        $this->selectAndWaitFrame("changelang", "label=Deutsch", "edit");
        $this->clickAndWait("link=Title");
        $this->assertEquals("1 [DE] content šÄßüл", $this->getText("//tr[@id='row.1']/td[" . $iTitleCol . "]"));
        $this->assertEquals("AGB", $this->getText("//tr[@id='row.2']/td[" . $iTitleCol . "]"));
        $this->assertEquals("Benutzer geblockt", $this->getText("//tr[@id='row.8']/td[" . $iTitleCol . "]"));
        $this->assertEquals("[last]testcontent", $this->getText("//tr[@id='row.1']/td[" . $iIdentCol . "]"));
        $this->assertEquals("oxagb", $this->getText("//tr[@id='row.2']/td[" . $iIdentCol . "]"));
        $this->assertEquals("oxblocked", $this->getText("//tr[@id='row.8']/td[" . $iIdentCol . "]"));
        $this->clickAndWait("link=Ident");
        $this->assertEquals("1testcontent", $this->getText("//tr[@id='row.1']/td[" . $iIdentCol . "]"));
        $this->assertEquals("oxadminorderemail", $this->getText("//tr[@id='row.2']/td[" . $iIdentCol . "]"));
        $this->assertElementNotPresent("link=oximpressum");
        $this->assertElementNotPresent("link=oxnewstlerinfo");
        $this->clickAndWait("nav.next");
        $this->assertElementPresent("link=oximpressum");
        $this->assertEquals("Page 2 / $iLastPage", $this->getText("nav.site"));
        $this->assertElementPresent("//a[@id='nav.page.2'][@class='pagenavigation pagenavigationactive']");
        $this->clickAndWait("nav.last");
        $this->assertEquals($sLastPageLastElementId,
            $this->getText("//tr[@id='row.$iLastPageLastElementId']/td[" . $iIdentCol . "]"));
        $this->clickAndWait("nav.prev");
        $this->assertElementPresent("link=$sPreviuosPageBeforeLastPageText");
        $this->assertEquals("Page $iPreviousPageBeforeLastPage / $iLastPage", $this->getText("nav.site"));
        $this->assertElementPresent("//a[@id='nav.page.$iPreviousPageBeforeLastPage'][@class='pagenavigation pagenavigationactive']");
        $this->clickAndWait("nav.first");
        $this->assertEquals("Page 1 / $iLastPage", $this->getText("nav.site"));
        $this->assertElementPresent("//a[@id='nav.page.1'][@class='pagenavigation pagenavigationactive']");
        $this->assertEquals("1testcontent", $this->getText("//tr[@id='row.1']/td[" . $iIdentCol . "]"));
        //deleting last element to check if navigation is correct
        $this->clickAndWait("nav.last");
        $this->assertEquals($sLastPageLastElementId,
            $this->getText("//tr[@id='row.$iLastPageLastElementId']/td[" . $iIdentCol . "]"));
        $this->clickAndConfirm("del.1");
        $this->assertEquals("Page $iLastPage / $iLastPage", $this->getText("nav.site"));
        $this->assertElementPresent("//a[@id='nav.page.$iLastPage'][@class='pagenavigation pagenavigationactive']");
        $this->assertElementPresent("//tr[@id='row.1']/td[" . $iIdentCol . "]");
        $this->assertElementNotPresent("//tr[@id='row.$sLastPageLastElementId']/td[" . $iIdentCol . "]");
        $this->logoutAdmin();
    }

    /**
     * searching CMS Pages
     *
     * @group search_sort
     */
    public function testSearchCmsPages()
    {
        $testConfig = $this->getTestConfig();
        if (!$testConfig->isSubShop()) {
            $this->markTestSkipped('This test is for Subshops only');
        }
        $iLastPage = 5;
        $iPagesCount = 2;

        $this->loginAdmin("Customer Info", "CMS Pages");

        $iTitleCol = 2;
        $iIdentCol = 3;

        //search
        $this->assertEquals("English", $this->getSelectedLabel("changelang"));
        $this->changeAdminListLanguage("Deutsch");
        $this->assertEquals("Page 1 / $iLastPage", $this->getText("nav.site"));
        $this->assertElementPresent("//a[@id='nav.page.1'][@class='pagenavigation pagenavigationactive']");
        $this->assertEquals("All", $this->getSelectedLabel("folder"));
        $this->selectAndWait("folder", "label=Customer information");
        $this->assertElementPresent("//tr[@id='row.1']/td[".$iTitleCol."]");
        $this->changeListSorting("link=Title");
        $this->assertElementPresent("link=1 [DE] content šÄßüл");
        $this->assertEquals("Customer information", $this->getSelectedLabel("folder"));
        $this->selectAndWaitFrame("folder", "label=None");
        $this->assertElementPresent("//tr[@id='row.1']/td[".$iTitleCol."]");
        $this->assertElementPresent("//tr[@id='row.3']/td[".$iTitleCol."]");
        $this->assertEquals("None", $this->getSelectedLabel("folder"));
        $this->selectAndWaitFrame("folder", "label=All");
        $this->assertEquals("Page 1 / $iLastPage", $this->getText("nav.site"));
        $this->assertElementPresent("//a[@id='nav.page.1'][@class='pagenavigation pagenavigationactive']");
        $this->type("where[oxcontents][oxtitle]", "[DE]");
        $this->clickAndWaitFrame("submitit");
        $this->assertElementPresent("link=1 [DE] content šÄßüл");
        $this->assertElementPresent("link=[last] [DE] content šÄßüл");
        $this->type("where[oxcontents][oxtitle]", "1 [DE] šÄßüл");
        $this->clickAndWaitFrame("submitit");
        $this->assertEquals("1 [DE] content šÄßüл", $this->getText("//tr[@id='row.1']/td[".$iTitleCol."]"));
        $this->assertEquals("[last]testcontent", $this->getText("//tr[@id='row.1']/td[".$iIdentCol."]"));
        $this->assertEquals("1 [DE] šÄßüл", $this->getValue("where[oxcontents][oxtitle]"));
        $this->assertElementNotPresent("//tr[@id='row.2']/td[".$iIdentCol."]");
        $this->type("where[oxcontents][oxtitle]", "");
        $this->type("where[oxcontents][oxloadid]", "testcontent");
        $this->clickAndWaitFrame("submitit");
        $this->assertElementPresent("link=1testcontent");
        $this->assertElementPresent("link=[last]testcontent");
        $this->type("where[oxcontents][oxloadid]", "1 testcontent");
        $this->clickAndWaitFrame("submitit");
        $this->assertEquals("1testcontent", $this->getText("//tr[@id='row.1']/td[".$iIdentCol."]"));
        $this->assertEquals("[last] [DE] content šÄßüл", $this->getText("//tr[@id='row.1']/td[".$iTitleCol."]"));
        $this->assertEquals("1 testcontent", $this->getValue("where[oxcontents][oxloadid]"));
        $this->assertElementNotPresent("//tr[@id='row.2']/td[".$iIdentCol."]");
        $this->changeAdminListLanguage("English");
        $this->assertEquals("1 testcontent", $this->getValue("where[oxcontents][oxloadid]"));
        $this->assertEquals("1testcontent", $this->getText("//tr[@id='row.1']/td[".$iIdentCol."]"));
        $this->assertEquals("3 [EN] content šÄßüл", $this->getText("//tr[@id='row.1']/td[".$iTitleCol."]"));
        $this->assertElementNotPresent("//tr[@id='row.2']/td[".$iIdentCol."]");
        $this->type("where[oxcontents][oxloadid]", "");
        $this->type("where[oxcontents][oxtitle]", "[EN] šÄßüл");
        $this->clickAndWaitFrame("submitit");
        $this->assertElementPresent("link=3 [EN] content šÄßüл");
        $this->assertElementPresent("link=[last] [EN] content šÄßüл");
        $this->type("where[oxcontents][oxtitle]", "3 [EN]");
        $this->clickAndWaitFrame("submitit");
        $this->assertEquals("3 [EN] content šÄßüл", $this->getText("//tr[@id='row.1']/td[".$iTitleCol."]"));
        $this->assertEquals("1testcontent", $this->getText("//tr[@id='row.1']/td[".$iIdentCol."]"));
        $this->assertElementNotPresent("//tr[@id='row.2']/td[".$iIdentCol."]");
        $this->assertEquals("3 [EN]", $this->getValue("where[oxcontents][oxtitle]"));
        $this->type("where[oxcontents][oxtitle]", "");
        $this->type("where[oxcontents][oxloadid]", "test");
        $this->clickAndWaitFrame("submitit");
        $this->assertElementPresent("link=1testcontent");
        $this->assertElementPresent("link=[last]testcontent");
        $this->type("where[oxcontents][oxloadid]", "1test");
        $this->clickAndWaitFrame("submitit");
        $this->assertEquals("1testcontent", $this->getText("//tr[@id='row.1']/td[".$iIdentCol."]"));
        $this->assertEquals("3 [EN] content šÄßüл", $this->getText("//tr[@id='row.1']/td[".$iTitleCol."]"));
        $this->assertElementNotPresent("//tr[@id='row.2']/td[".$iIdentCol."]");
        $this->assertEquals("1test", $this->getValue("where[oxcontents][oxloadid]"));
        $this->type("where[oxcontents][oxloadid]", "");
        $this->clickAndWaitFrame("submitit");
        $this->assertEquals("All", $this->getSelectedLabel("folder"));
        $this->selectAndWait("folder", "label=Customer information");
        $this->assertElementPresent("//tr[@id='row.1']/td[1]");
        $this->changeListSorting("link=Title");
        $this->assertEquals("Page 1 / $iPagesCount", $this->getText("nav.site"));
        $this->assertElementPresent("//a[@id='nav.page.1'][@class='pagenavigation pagenavigationactive']");
        $this->assertElementPresent("link=3 [EN] content šÄßüл");
        $this->assertEquals("Customer information", $this->getSelectedLabel("folder"));
        $this->selectAndWait("folder", "label=All");
        $this->assertEquals("Page 1 / $iLastPage", $this->getText("nav.site"));
        $this->assertElementPresent("//a[@id='nav.page.1'][@class='pagenavigation pagenavigationactive']");
        $this->logoutAdmin();
    }

    public function testSortPaymentMethods()
    {
        $testConfig = $this->getTestConfig();
        if (!$testConfig->isSubShop()) {
            $this->markTestSkipped('This test is for Subshops only');
}
        $this->loginAdmin("Shop Settings", "Payment Methods");
        $this->type("where[oxpayments][oxdesc]", "test");
        $this->clickAndWait("submitit");
        $paymentColumn = "contains(@class, 'payment_name')";
        //testing sorting and navigation between pages
        $this->assertEquals("Page 1 / 2", $this->getText("nav.site"));
        $this->assertElementPresent("//a[@id='nav.page.1'][@class='pagenavigation pagenavigationactive']");
        $this->clickAndWait("link=Name");
        $this->assertEquals("1 EN test payment šÄßüл", $this->getText("//tr[@id='row.1']/td[".$paymentColumn."]"));
        $this->assertEquals("2 EN test payment šÄßüл", $this->getText("//tr[@id='row.2']/td[".$paymentColumn."]"));
        $this->assertEquals("3 EN test payment šÄßüл", $this->getText("//tr[@id='row.3']/td[".$paymentColumn."]"));
        $this->clickAndWait("nav.next");
        $this->assertEquals("Page 2 / 2", $this->getText("nav.site"));
        $this->assertElementPresent("//a[@id='nav.page.2'][@class='pagenavigation pagenavigationactive']");
        $this->assertEquals("[last] EN test payment šÄßüл", $this->getText("//tr[@id='row.1']/td[".$paymentColumn."]/div"));
        $this->clickAndWait("nav.prev");
        $this->assertEquals("Page 1 / 2", $this->getText("nav.site"));
        $this->assertElementPresent("//a[@id='nav.page.1'][@class='pagenavigation pagenavigationactive']");
        $this->changeAdminListLanguage('Deutsch');
        $this->assertEquals("1 DE test payment šÄßüл", $this->getText("//tr[@id='row.1']/td[".$paymentColumn."]"));
        $this->assertEquals("2 DE test payment šÄßüл", $this->getText("//tr[@id='row.2']/td[".$paymentColumn."]"));
        $this->assertEquals("3 DE test payment šÄßüл", $this->getText("//tr[@id='row.3']/td[".$paymentColumn."]"));
        $this->clickAndWait("nav.last");
        $this->assertEquals("Page 2 / 2", $this->getText("nav.site"));
        $this->assertElementPresent("//a[@id='nav.page.2'][@class='pagenavigation pagenavigationactive']");
        $this->assertEquals("[last] DE test payment šÄßüл", $this->getText("//tr[@id='row.1']/td[".$paymentColumn."]/div"));
        $this->clickAndWait("nav.first");
        $this->assertEquals("Page 1 / 2", $this->getText("nav.site"));
        $this->assertElementPresent("//a[@id='nav.page.1'][@class='pagenavigation pagenavigationactive']");
        $this->logoutAdmin();
    }


    private function loadTestFixtures(): void
    {
        $this->executeSql('TRUNCATE TABLE `oxcontents`;');
        $this->executeSql(
            file_get_contents(
                __DIR__ . '/testSql/fixtures/search_and_sorting_admin_test.sql'
            )
        );
    }
}
