<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopCommunity\Tests\Acceptance\Frontend;

/*
 * Test that a user can delete its own account in the frontend.
 */
class MyAccountDeleteAccountTest extends \OxidEsales\EshopCommunity\Tests\Acceptance\FlowThemeTestCase
{

    /**
     * It should be possible for mallusers to delete their account in any shop.
     *
     * @group flow-theme
     */
    public function testDeleteMyAccount()
    {
        $this->addShopUser();
        $this->setConfigToAllowMallLogin();
        $this->setConfigToAllowUsersDeleteTheirAccount();
        $this->openMyAccountPage();
        $this->clickDeleteAccount();
        $this->assertDeletionOfAccountWasSuccessful();
    }

    /**
     * Even with is_subshop = true we add a user to the mainshop and test that the main shop user can be
     * deleted in the subshop (because blMallUsers is configured to true)
     */
    private function addShopUser()
    {
        $sql = "INSERT INTO `oxuser` (`OXID`,`OXACTIVE`,`OXRIGHTS`,`OXSHOPID`,`OXUSERNAME`,`OXPASSWORD`,`OXPASSSALT`,
                                      `OXCUSTNR`,`OXUSTID`,`OXUSTIDSTATUS`,`OXCOMPANY`,`OXFNAME`,`OXLNAME`,`OXSTREET`,
                                      `OXSTREETNR`,`OXADDINFO`,`OXCITY`,`OXCOUNTRYID`,`OXSTATEID`,`OXZIP`,`OXFON`,
                                      `OXFAX`,`OXSAL`,`OXBONI`,`OXCREATE`,`OXREGISTER`,`OXPRIVFON`,`OXMOBFON`,
                                      `OXBIRTHDATE`,`OXURL`,`OXLDAPKEY`,`OXWRONGLOGINS`,`OXUPDATEKEY`,`OXUPDATEEXP`,
                                      `OXPOINTS`,`OXTIMESTAMP`) VALUES (
                                      'toBeDeleted',1,'user',1,'account@oxid-esales.dev',
                                      'ab126694bd75dbd489f1142980a9945165e59067c796b349544b1c4be5db9cfcc9ad507a4c6fc626e1d7eb401d11bd4f7201e510beb4793a9f92f565955f29a7',
                                      '8c54ade1ab6797d749a085d2c4c4c9a0',0,'',0,'OXID eSales','Firstname','Lastname',
                                      'Musterstr.įÄк','1','','Musterstadt įÄк','a7c40f631fc920687.20179984','','79098',
                                      '','','MR',500,'2008-02-05 14:42:42','2008-02-05 14:42:42','0800 111113',
                                      '0800 111114','0000-00-00','','',0,'',0,0,'2018-04-05 14:51:14');";

        $database = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();
        $database->execute($sql);
    }

    private function openMyAccountPage()
    {
        $this->openShop();
        $this->loginInFrontend('account@oxid-esales.dev', 'useruser');
        $this->click("//div[contains(@class, 'service-menu')]/button");
        $this->waitForItemAppear("services");
        $this->clickAndWait("//ul[@id='services']/li/a");
    }

    private function clickDeleteAccount()
    {
        $this->assertDeleteButtonPresent();
        $this->clickDeleteButton();
        $this->confirmDeleteAccount();
    }

    private function assertDeleteButtonPresent()
    {
        $this->assertElementPresent('//button[@data-target="#delete_my_account_confirmation"]');
    }

    private function clickDeleteButton()
    {
        $this->click("//button[@data-target='#delete_my_account_confirmation']");
    }

    private function confirmDeleteAccount()
    {
        $this->waitForItemAppear("//div[@id='delete_my_account_confirmation']");
        $this->clickAndWait("//form[@name='delete_my_account']/button[@class='btn btn-danger']");
    }

    private function assertDeletionOfAccountWasSuccessful()
    {
        $this->assertElementPresent("//form[@name='login']", "Deletion of account was not successful");
        $this->assertTextPresent('%DD_DELETE_MY_ACCOUNT_SUCCESS%');
        $this->loginInFrontend('account@oxid-esales.dev', 'useruser', false);
        $this->assertElementPresent("//form[@name='login']", "Deletion of account was not successful");
    }

    private function setConfigToAllowMallLogin()
    {
        $this->callShopSC(
            "oxConfig",
            null,
            null,
            [
                'blMallUsers' => [
                    "type"  => "bool",
                    "value" => true,
                ],
            ]
        );
    }

    private function setConfigToAllowUsersDeleteTheirAccount()
    {
        $this->callShopSC(
            "oxConfig",
            null,
            null,
            [
                'blAllowUsersToDeleteTheirAccount' => [
                    "type" => "bool",
                    "value" => true,
                ],
            ]
        );
    }
}
