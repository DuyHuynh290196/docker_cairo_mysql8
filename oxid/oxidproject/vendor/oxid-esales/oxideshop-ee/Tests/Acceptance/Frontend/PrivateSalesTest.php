<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Tests\Acceptance\Frontend;

use OxidEsales\EshopEnterprise\Tests\Acceptance\EnterpriseTestCase;

/** Private sales related tests. */
class PrivateSalesTest extends EnterpriseTestCase
{
    /**
     * Private sales: login to subshops. each subshop should have its own agb check for same user
     *
     * @group privateSales
     */
    public function testPrivateShoppingLoginToSubshops()
    {
        if (isSUBSHOP) {
            $sShopNr = $this->getShopVersionNumber();
            //enabling login to subshops
            $this->callShopSC("oxConfig", null, null, array("blPsLoginEnabled" => array("type" => "bool",  "value" => 'true')), null, 1);
            $this->callShopSC("oxConfig", null, null, array("blPsLoginEnabled" => array("type" => "bool",  "value" => 'true')), null, 2);

            $this->openNewWindow(shopURL, true);
            $this->waitForElement("link=subshop");
            $this->assertElementNotPresent("link=%HOME%");
            $this->assertElementPresent("link=subshop");
            $this->assertElementPresent("link=OXID eShop ". $sShopNr);

            //login to main shop
            $this->clickAndWait("link=OXID eShop ". $sShopNr);
            $this->assertElementNotPresent("link=%HOME%");
            $this->assertElementNotPresent("link=subshop");
            $this->assertElementNotPresent("link=OXID eShop ". $sShopNr);
            $this->type("loginUser", "example_test@oxid-esales.dev");
            $this->type("loginPwd", "useruser");
            $this->clickAndWait("loginButton");
            $this->assertEquals("I agree to the Terms and Conditions. I have been informed about my Right of Withdrawal.", $this->clearString($this->getText("confirmLabel")));
            $this->check("orderConfirmAgb");
            $this->clickAndWait("confirmButton");
            $this->assertTextPresent("%YOU_ARE_HERE%: / %MY_ACCOUNT%");
            $this->assertTextPresent("%GREETING%");
            $this->assertElementPresent("breadCrumb");
            $this->assertElementPresent("topMenu");
            $this->assertElementNotPresent("link=subshop");
            $this->assertElementNotPresent("link=OXID eShop ". $sShopNr);
            $this->clickAndWait("logoutLink");

            //login to subshop
            $this->openShop();
            $this->assertTextNotPresent("%YOU_ARE_HERE%: / %MY_ACCOUNT%");
            $this->assertTextNotPresent("%GREETING%");
            $this->assertElementNotPresent("breadCrumb");
            $this->assertElementNotPresent("topMenu");
            $this->assertElementNotPresent("link=subshop");
            $this->assertElementNotPresent("link=OXID eShop ". $sShopNr);
            $this->type("loginUser", "example_test@oxid-esales.dev");
            $this->type("loginPwd", "useruser");
            $this->clickAndWait("loginButton");
            $this->assertEquals("I agree to the Terms and Conditions. I have been informed about my Right of Withdrawal.", $this->clearString($this->getText("confirmLabel")));
            $this->check("orderConfirmAgb");
            $this->clickAndWait("confirmButton");
            $this->assertTextPresent("%YOU_ARE_HERE%: / %MY_ACCOUNT%");
            $this->assertTextPresent("%GREETING%");
            $this->assertElementPresent("breadCrumb");
            $this->assertElementPresent("topMenu");
            $this->assertElementNotPresent("link=subshop");
            $this->assertElementNotPresent("link=OXID eShop ". $sShopNr);
            $this->clickAndWait("logoutLink");

            //login to main shop again, no agb confirmation needed anymore
            $this->openShop(true);
            $this->type("loginUser", "example_test@oxid-esales.dev");
            $this->type("loginPwd", "useruser");
            $this->clickAndWait("loginButton");
            $this->assertElementNotPresent("test_OrderConfirmAGBTop");
            $this->assertTextPresent("%YOU_ARE_HERE%: / %MY_ACCOUNT%");

            //changing agb version for main shop
            $this->callShopSC("oxContent", 'save', array('loadByIdent' => 'oxagb'), array("oxtermversion" => '2'), null, 1);

            //checking if user is requested to confirm agb for main shop
            $this->clearCache();
            $this->openShop(true);
            $this->type("loginUser", "example_test@oxid-esales.dev");
            $this->type("loginPwd", "useruser");
            $this->clickAndWait("loginButton");
            $this->assertEquals("I agree to the Terms and Conditions. I have been informed about my Right of Withdrawal.", $this->clearString($this->getText("confirmLabel")));

            //checking if subshop not requires agb confirmation
            $this->clearCache();
            $this->openShop();
            $this->type("loginUser", "example_test@oxid-esales.dev");
            $this->type("loginPwd", "useruser");
            $this->clickAndWait("loginButton");
            $this->assertTextPresent("%YOU_ARE_HERE%: / %MY_ACCOUNT%");
        }
    }
}
