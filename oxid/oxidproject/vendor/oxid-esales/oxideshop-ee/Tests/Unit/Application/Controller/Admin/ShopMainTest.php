<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Tests\Unit\Application\Controller\Admin;

use OxidEsales\Eshop\Application\Controller\Admin\ShopMain;
use OxidEsales\EshopCommunity\Internal\Container\ContainerFactory;
use OxidEsales\EshopCommunity\Internal\Transition\Utility\BasicContextInterface;
use OxidEsales\EshopCommunity\Internal\Framework\Module\Configuration\Dao\ProjectConfigurationDaoInterface;
use OxidEsales\EshopCommunity\Internal\Framework\Module\Configuration\Bridge\ShopConfigurationDaoBridgeInterface;
use OxidEsales\EshopCommunity\Internal\Framework\Module\Configuration\DataObject\ModuleConfiguration;
use OxidEsales\EshopCommunity\Internal\Framework\Module\Setup\Bridge\ModuleActivationBridgeInterface;
use OxidEsales\TestingLibrary\UnitTestCase;
use oxTestModules;
use PHPUnit\Framework\MockObject\MockObject;
use stdClass;
use Exception;

/**
 * Tests for ShopMain class.
 */
class ShopMainTest extends UnitTestCase
{
    /**
     * \OxidEsales\Eshop\Application\Controller\Admin\ShopMain::Render() test case
     */
    public function testRenderNewShop()
    {
        $oView = oxNew(\OxidEsales\Eshop\Application\Controller\Admin\ShopMain::class);
        $this->assertEquals('shop_main_new.tpl', $oView->render());
    }

    /**
     * \OxidEsales\Eshop\Application\Controller\Admin\ShopMain::Save() test case with updating inheritance
     *
     * @return null
     */
    public function testSaveUpdateInheritance()
    {
        // testing..
        oxTestModules::addFunction('oxshop', 'updateInheritance', '{ throw new Exception( "updateInheritance" ); }');

        $this->setRequestParameter("oxid", '-1');

        $oUser = new stdClass();

        $oUser->oxuser__oxrights = new \OxidEsales\Eshop\Core\Field('malladmin');

        /** @var Shop_Main|PHPUnit\Framework\MockObject\MockObject $oView */
        $oView = $this->getMock(\OxidEsales\Eshop\Application\Controller\Admin\ShopMain::class, array('getUser'));
        $oView->expects($this->once())->method('getUser')->will($this->returnValue($oUser));

        // testing..
        try {
            $oView->save();
        } catch (Exception $oExcp) {
            $this->assertEquals("updateInheritance", $oExcp->getMessage(), 'oxShop::updateInheritance() was expected to be called()');

            return;
        }
        $this->fail('oxShop::updateInheritance() was expected to be called');
    }

    /**
     * \OxidEsales\Eshop\Application\Controller\Admin\ShopMain::_getNonCopyConfigVars() test case
     */
    public function testGetNonCopyConfigVars()
    {
        $aNonCopyVars = array("aSerials", "IMS", "IMD", "IMA", "sBackTag", "sUtilModule", "aModulePaths", "aModuleFiles", "aModuleVersions", "aModuleTemplates", "aModules", "aDisabledModules");
        $oView = $this->getProxyClass("Shop_Main");
        $aNonCopyVarsRes = $oView->UNITgetNonCopyConfigVars();
        $this->assertEquals(array(), array_diff($aNonCopyVars, $aNonCopyVarsRes));
    }

    public function testCloneDefaultShopConfigurationForNewSubShop()
    {
        $container = ContainerFactory::getInstance()->getContainer();
        $container->get('oxid_esales.module.install.service.launched_shop_project_configuration_generator')->generate();

        $this->setRequestParameter('oxid', '-1');

        $user = new stdClass();
        $user->oxuser__oxrights = new \OxidEsales\Eshop\Core\Field('malladmin');

        /** @var ShopMain|MockObject $shopAdminController */
        $shopAdminController = $this
            ->getMockBuilder(ShopMain::class)
            ->setMethods(['getUser'])
            ->getMock();
        $shopAdminController
            ->method('getUser')
            ->willReturn($user);

        $shopAdminController->save();

        $projectConfiguration = $container
            ->get(ProjectConfigurationDaoInterface::class)
            ->getConfiguration();

        $defaultShopId = 1;
        $newSubShopId = 2;

        $this->assertEquals(
            $projectConfiguration->getShopConfiguration($defaultShopId),
            $projectConfiguration->getShopConfiguration($newSubShopId)
        );
    }

    public function testModuleIsNotActiveForNewSubShop()
    {
        $this->installAndActivateTestModuleForDefaultShop();

        $container = ContainerFactory::getInstance()->getContainer();
        $container->get('oxid_esales.module.install.service.launched_shop_project_configuration_generator')->generate();

        $this->setRequestParameter('oxid', '-1');

        $user = new stdClass();
        $user->oxuser__oxrights = new \OxidEsales\Eshop\Core\Field('malladmin');

        /** @var ShopMain|MockObject $shopAdminController */
        $shopAdminController = $this
            ->getMockBuilder(ShopMain::class)
            ->setMethods(['getUser'])
            ->getMock();
        $shopAdminController
            ->method('getUser')
            ->willReturn($user);

        $shopAdminController->save();

        $moduleActivationBridge = $container->get(ModuleActivationBridgeInterface::class);

        $defalutShopId = 1;
        $newSubShopId = 2;

        $this->assertTrue(
            $moduleActivationBridge->isActive('testModule', $defalutShopId)
        );

        $this->assertFalse(
            $moduleActivationBridge->isActive('testModule', $newSubShopId)
        );
    }

    private function installAndActivateTestModuleForDefaultShop()
    {
        $moduleConfiguration = new ModuleConfiguration();
        $moduleConfiguration
            ->setId('testModule')
            ->setPath('testModule');

        $container = ContainerFactory::getInstance()->getContainer();
        $shopConfigurationDaoBridge = $container->get(ShopConfigurationDaoBridgeInterface::class);


        $shopConfiguration = $shopConfigurationDaoBridge->get();
        $shopConfiguration->addModuleConfiguration($moduleConfiguration);

        $shopConfigurationDaoBridge->save($shopConfiguration);

        $container->get(ModuleActivationBridgeInterface::class)->activate('testModule', 1);
    }
}
