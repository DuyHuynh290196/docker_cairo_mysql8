<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Tests\Acceptance\Admin;

use OxidEsales\EshopCommunity\Internal\Container\ContainerFactory;
use OxidEsales\EshopCommunity\Internal\Framework\Module\Install\Service\ModuleConfigurationInstallerInterface;
use OxidEsales\EshopEnterprise\Tests\Acceptance\EnterpriseAcceptanceTestCase;
use oxDb;
use OxidEsales\Eshop\Core\Registry;

/**
 * Mall functionality: subshops & inheritance.
 */
class ModuleForSubshopTest extends EnterpriseAcceptanceTestCase
{
    const TEST_MODULE_FOLDER = 'vendor1ControllerRouting';
    const TEST_MODULE_TITLE = 'Test metadata v2 vendor1 controllers feature for EE';
    const TEST_MODULE_ID = 'vendor1ControllerRouting';

    const OTHER_TEST_MODULE_FOLDER = 'vendor2controllerrouting';
    const OTHER_TEST_MODULE_TITLE = 'Test metadata v2 vendor2 controllers feature for EE';
    const OTHER_TEST_MODULE_ID = 'vendor2ControllerRouting';


    /**
     * Set up fixture.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->installModule('vendor1controllerrouting');
        $this->installModule('vendor2controllerrouting');

        $this->getTranslator()->setLanguage(1);

        $testConfig = $this->getTestConfig();
        if ($testConfig->isSubShop()) {
            $this->markTestSkipped('Test is not for subshops');
        }
    }

    /**
     * Test module activation in subshop.
     *
     * @group modules
     */
    public function testModuleActivationInSubshop()
    {
        //create subshop
        $this->loginAdmin("Master Settings", "Core Settings");
        $this->createSubShop('subshop', true, true);
        $this->selectMenu('Extensions', 'Modules');
        $this->activateModule(self::TEST_MODULE_TITLE);
        $this->activateModule(self::OTHER_TEST_MODULE_TITLE);
        $this->assertNoProblem();
        $this->checkFrontend();

        // module is not active in main shop
        $this->loginAdmin("Master Settings", "Core Settings");
        $this->selectMenu('Extensions', 'Modules');
        $this->openListItem(self::TEST_MODULE_TITLE);
        $this->frame("edit");
        $this->assertElementPresent("//form[@id='myedit']//input[@value='Activate']");
        $this->checkFrontendForMainShop();

        $this->assertLoggedException(
            \OxidEsales\Eshop\Core\Exception\SystemComponentException::class,
            "EXCEPTION_SYSTEMCOMPONENT_CLASSNOTFOUND vendor1_controllerrouting_mymodulecontroller"
        );
    }

    /**
     * Test module activation in subshop.
     *
     * @group modules
     */
     public function testModulesDeactivateOne()
     {
        //create subshop
        $this->loginAdmin("Master Settings", "Core Settings");
        $this->createSubShop('subshop', true, true);
        $this->selectMenu('Extensions', 'Modules');
        $this->activateModule(self::TEST_MODULE_TITLE);
        $this->activateModule(self::OTHER_TEST_MODULE_TITLE);
        $this->deactivateModule(self::OTHER_TEST_MODULE_TITLE);
        $this->assertNoProblem();
        $this->checkFrontend(false);

         $this->assertLoggedException(
             \OxidEsales\Eshop\Core\Exception\SystemComponentException::class,
             "EXCEPTION_SYSTEMCOMPONENT_CLASSNOTFOUND vendor2_controllerrouting_mymodulecontroller"
         );
    }

    /**
     * Test module activation and deactivation.
     *
     * @group modules
     */
     public function testModulesActivateDeactivateActivate()
    {
        //create subshop
        $this->loginAdmin("Master Settings", "Core Settings");
        $this->createSubShop('subshop', true, true);
        $this->selectMenu('Extensions', 'Modules');
        $this->activateModule(self::TEST_MODULE_TITLE);
        $this->deactivateModule(self::TEST_MODULE_TITLE);
        $this->activateModule(self::TEST_MODULE_TITLE);
        $this->assertNoProblem();
    }

    /**
     * Test module activation in subshop.
     *
     * @group modules
     */
     public function testModuleActivationInSubshopAndMainShop()
    {
        //create subshop
        $this->loginAdmin("Master Settings", "Core Settings");
        $this->createSubShop('subshop', true, true);
        $this->selectMenu('Extensions', 'Modules');
        $this->activateModule(self::TEST_MODULE_TITLE);
        $this->activateModule(self::OTHER_TEST_MODULE_TITLE);
        $this->assertNoProblem();

        // activate modules in main shop
        $this->clearCookies();
        $this->loginAdmin("Master Settings", "Core Settings");
        $this->selectMenu('Extensions', 'Modules');
        $this->openListItem(self::TEST_MODULE_TITLE);
        $this->activateModule(self::TEST_MODULE_TITLE);
        $this->activateModule(self::OTHER_TEST_MODULE_TITLE);
        $this->assertNoProblem();
    }

    /**
     * Test if frontend module functionality works
     */
    protected function checkFrontend($secondModuleActive = true)
    {
        $this->clearCookies();

        $this->openShop();
        $this->clickAndWait('link=subshop');
        $message = 'some message to controller test module';

        $this->open(shopURL . '/index.php?shp=2&cl=vendor1_controllerrouting_mymodulecontroller');
        $this->type("mymodule_message", $message);
        $this->clickAndWait("//button[text()='%SUBMIT%']");
        $this->assertTextPresent('Test module for controller routing vendor 1 - ' . $message . ' 2');

        $this->open(shopURL . '/index.php?shp=2&cl=vendor2_controllerrouting_mymodulecontroller');

        if ($secondModuleActive) {
            $this->type("mymodule_message", $message);
            $this->clickAndWait("//button[text()='%SUBMIT%']");
            $this->assertTextPresent('Test module for controller routing vendor 2 - ' . $message . ' 2');
        } else {
            $this->assertTextNotPresent('Test module for controller routing vendor 2');
        }
    }

    /**
     * Test if frontend module functionality works
     */
    protected function checkFrontendForMainShop()
    {
        $this->clearCookies();

        $this->openShop();
        $shopNr = $this->getShopVersionNumber();
        $this->clickAndWait("link=OXID eShop {$shopNr}");

        $this->open(shopURL . '/index.php?shp=1&cl=vendor1_controllerRouting_MyModuleController');
        $this->assertTextNotPresent('Test module for controller routing vendor 1');
    }

    /**
     * Check for problematic extensions
     */
    protected function assertNoProblem()
    {
        $this->selectMenu('Extensions', 'Modules');
        $this->frame('edit');
        $this->assertTextNotPresent('Problematic Files');
    }

    /**
     * Helper function for module activation
     *
     * @param string $module
     */
    protected function activateModule($moduleTitle)
    {
        $this->openListItem($moduleTitle);
        $this->frame("edit");
        $this->clickAndWait("//form[@id='myedit']//input[@value='Activate']", "list");
        $this->waitForFrameToLoad('list');
        $this->assertElementPresent("//form[@id='myedit']//input[@value='Deactivate']");
        $this->assertTextPresent($moduleTitle);
        $this->assertTextPresent("1.0");
        $this->assertTextPresent("OXID");
        $this->assertTextPresent("-");
        $this->assertTextPresent("-");
    }

    /**
     * @param $sName
     * @param bool $blIsInherited
     * @param bool $blIsSuperShop
     * @param bool $blIsMultishop
     * @param bool $blIsChild
     */
    protected function createSubShop( $sName, $blIsInherited = true, $blIsSuperShop = false, $blIsMultishop = false, $blIsChild = true )
    {
        $sShopNr = $this->getShopVersionNumber();

        $this->clickCreateNewItem( 'btn.new', true );

        $this->waitForElement('shopname', 10, true);
        $this->assertElementPresent('shopname');
        $this->type( "shopname", $sName );

        if ( $blIsInherited ) {
            $this->check( "isinherited" );
        }
        if ( $blIsSuperShop ) {
            $this->check("//input[@name='editval[oxshops__oxissupershop]' and @value='1']");
        }
        if ( $blIsMultishop ) {
            $this->check("//input[@name='editval[oxshops__oxismultishop]' and @value='1']");
        }
        if ( $blIsChild ) {
            $this->select( "shopparent", "label=OXID eShop " . $sShopNr . " (1)" );
            $this->click( "shopparent" );
        }
        $this->clickAndWaitFrame( "save", 'navigation' );
        $this->check( "editval[oxshops__oxactive]" );
        $this->clickAndWaitFrame( "save", 'list' );
        $this->waitForFrameToLoad( 'list', 20000, true );
    }

    /**
     * Helper function for module deactivation
     *
     * @param string $module
     */
    protected function deactivateModule($moduleTitle)
    {
        $this->openListItem($moduleTitle);
        $this->frame("edit");
        $this->clickAndWait("//form[@id='myedit']//input[@value='Deactivate']", "list");
        $this->waitForFrameToLoad('list');
        $this->assertElementPresent("//form[@id='myedit']//input[@value='Activate']");
    }

    private function installModule(string $path)
    {
        $moduleConfigurationInstaller = ContainerFactory::getInstance()
            ->getContainer()
            ->get(ModuleConfigurationInstallerInterface::class);

        $moduleConfigurationInstaller->install(
            __DIR__ . '/testData/modules/' . $path,
            $path
        );
    }
}
