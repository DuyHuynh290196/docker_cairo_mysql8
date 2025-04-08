<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Tests\Acceptance\Frontend;

use OxidEsales\EshopEnterprise\Tests\Acceptance\EnterpriseTestCase;

class NavigationTest extends EnterpriseTestCase
{
    /**
     * shop roles testing in frontend
     * @todo need to change _sc.php as it affect only first shop as it is active in _sc.php file.
     *
     * @group frontend
     */
    public function testFrontendShopRoles()
    {
        $this->activateTheme('azure');
        $this->callShopSC('oxConfig', null, null, [
            'blAllowSuggestArticle' => [
                'type' => 'bool',
                'value' => true,
            ],
        ]);
        $this->clearCache();
        $this->openShop();
        //no shop roles are active
        $this->assertElementPresent("newItems_2");
        $this->assertEquals("Test product 1 [EN] šÄßüл", $this->clearString($this->getText("newItems_2")));
        $this->clickAndWait("//ul[@id='newItems']/li[1]//button");
        $this->clickAndWait("//ul[@id='newItems']/li[2]//a");
        $this->assertEquals("Test product 1 long description [EN] šÄßüл", $this->getText("//*[@id='description']"));
        $this->assertEquals("100,00 € *", $this->getText("productPrice"));
        $this->assertEquals("Test product 1 short desc [EN] šÄßüл", $this->getText("productShortdesc"));
        $this->click("productLinks");
        $this->waitForItemAppear("suggest");
        $this->clickAndWait("suggest");
        $this->assertEquals("%YOU_ARE_HERE%: / %RECOMMEND_PRODUCT%", $this->getText("breadCrumb"));
        $this->assertEquals("%RECOMMEND_PRODUCT%", $this->getText("//h1"));

        //items are disabled for not logged in user
        $rolesParams = array("oxactive" => 1);
        $roleId = 'oxsubshopadminrole6';
        if (!isSUBSHOP) {
            $rolesParams['oxid'] = $roleId;
            $roleId = null;
        }
        $this->callShopSC("oxRole", "save", $roleId, $rolesParams);
        unset($rolesParams['oxid']);
        $this->callShopSC("oxRole", "save", "testadminrole6", $rolesParams);

        $this->clearCache();
        $this->openShop();
        $this->assertElementNotPresent("//ul[@id='newItems']/li[2]//button");
        $this->clickAndWait("//ul[@id='newItems']/li[2]//a");
        $this->assertElementNotPresent("//div[@id='description']");
        $this->assertElementNotPresent("productPrice");
        $this->assertElementNotPresent("productShortdesc");
        $this->click("productLinks");
        $this->waitForItemAppear("suggest");
        $this->clickAndWait("suggest");
        $this->assertTextPresent("%ERROR_MESSAGE_ACCESS_DENIED%");
        $this->assertEquals("%ERROR%", $this->getText("//h1[@class='pageHead']"));

        //items are disabled for other user
        $this->clearCache();
        $this->openShop();
        $this->loginInFrontend("example_test@oxid-esales.dev", "useruser");
        $this->assertElementNotPresent("//ul[@id='newItems']/li[2]//button");
        $this->clickAndWait("//ul[@id='newItems']/li[2]//a");
        $this->assertElementNotPresent("//div[@id='description']");
        $this->assertElementNotPresent("productPrice");
        $this->assertElementNotPresent("productShortdesc");

        //items are enabled for correct user
        $this->clickAndWait("link=%LOGOUT%");
        $this->clickAndWait("link=%HOME%");
        $this->loginInFrontend("admin@myoxideshop.com", "admin0303");

        $this->assertElementPresent("//ul[@id='newItems']/li[1]//input[@value='1000' and @name='aid']");
        $this->clickAndWait("//ul[@id='newItems']/li[1]//button");
        $this->clickAndWait("//ul[@id='newItems']/li[2]//a");
        $this->assertEquals("Test product 1 long description [EN] šÄßüл", $this->getText("//*[@id='description']"));
        $this->assertEquals("100,00 € *", $this->getText("productPrice"));
        $this->assertEquals("Test product 1 short desc [EN] šÄßüл", $this->getText("productShortdesc"));
        $this->click("productLinks");
        $this->waitForItemAppear("suggest");
        $this->clickAndWait("suggest");
        $this->assertEquals("%YOU_ARE_HERE%: / %RECOMMEND_PRODUCT%", $this->getText("breadCrumb"));
        $this->assertEquals("%RECOMMEND_PRODUCT%", $this->getText("//h1"));
    }
}
