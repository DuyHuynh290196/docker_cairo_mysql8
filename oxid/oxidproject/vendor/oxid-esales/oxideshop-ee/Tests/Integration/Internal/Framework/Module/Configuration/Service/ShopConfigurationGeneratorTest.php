<?php declare(strict_types=1);
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Tests\Integration\Internal\Framework\Module\Configuration\Service;

use OxidEsales\EshopCommunity\Internal\Transition\Utility\BasicContext;
use OxidEsales\EshopCommunity\Internal\Transition\Utility\BasicContextInterface;
use OxidEsales\EshopCommunity\Internal\Framework\Module\Configuration\Dao\ShopConfigurationDaoInterface;
use OxidEsales\EshopEnterprise\Internal\Framework\Module\Configuration\Service\ShopConfigurationGenerator;
use OxidEsales\EshopCommunity\Internal\Framework\Module\Configuration\Dao\ProjectConfigurationDaoInterface;
use OxidEsales\EshopCommunity\Internal\Framework\Module\Configuration\DataObject\ModuleConfiguration;
use OxidEsales\EshopCommunity\Internal\Framework\Module\Configuration\DataObject\ProjectConfiguration;
use OxidEsales\EshopCommunity\Internal\Framework\Module\Configuration\DataObject\ShopConfiguration;
use OxidEsales\EshopCommunity\Internal\Transition\Utility\ContextInterface;
use OxidEsales\EshopCommunity\Tests\Integration\Internal\ContainerTrait;
use OxidEsales\EshopEnterprise\Internal\Framework\Module\Configuration\Service\ShopConfigurationGeneratorInterface;
use PHPUnit\Framework\TestCase;

/**
 * @covers \OxidEsales\EshopEnterprise\Internal\Framework\Module\Configuration\Service\ShopConfigurationGenerator
 */
class ShopConfigurationGeneratorTest extends TestCase
{
    use ContainerTrait;

    /**
     * @var BasicContextInterface
     */
    private $context;

    /**
     * @var ShopConfigurationDaoInterface
     */
    private $shopConfigurationDao;

    private $moduleId = 'test';

    protected function setUp(): void
    {
        parent::setUp();

        $this->context = $this->get(ContextInterface::class);
        $this->shopConfigurationDao = $this->get(ShopConfigurationDaoInterface::class);
    }

    public function testCloningWhenInClonedShopModuleBecomesInactive()
    {
        $this->prepareDefaultShopConfigurationWithTestModule();

        $this->get(ShopConfigurationGeneratorInterface::class)->generateForShop(2);

        $mainShopModuleConfiguration = $this
            ->shopConfigurationDao
            ->get($this->context->getDefaultShopId())
            ->getModuleConfiguration($this->moduleId);

        $subShopModuleConfiguration = $this
            ->shopConfigurationDao
            ->get(2)
            ->getModuleConfiguration($this->moduleId);

        $this->assertTrue($mainShopModuleConfiguration->isConfigured());
        $this->assertFalse($subShopModuleConfiguration->isConfigured());
    }

    private function prepareDefaultShopConfigurationWithTestModule(): void
    {
        $moduleConfiguration = new ModuleConfiguration();
        $moduleConfiguration
            ->setConfigured(true)
            ->setId($this->moduleId)
            ->setPath('any');

        $shopConfiguration = new ShopConfiguration();
        $shopConfiguration->addModuleConfiguration($moduleConfiguration);

        $this->shopConfigurationDao->save(
            $shopConfiguration,
            $this->context->getDefaultShopId()
        );
    }
}
