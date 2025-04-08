<?php declare(strict_types=1);
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Tests\Integration\Internal\Framework\Module\Command;

use OxidEsales\EshopCommunity\Internal\Framework\Module\Configuration\Dao\ShopConfigurationDaoInterface;
use OxidEsales\EshopCommunity\Internal\Framework\Module\Configuration\DataObject\ModuleConfiguration;
use OxidEsales\EshopCommunity\Internal\Framework\Module\Configuration\DataObject\ShopConfiguration;
use OxidEsales\EshopCommunity\Internal\Framework\Module\State\ModuleStateServiceInterface;
use OxidEsales\EshopCommunity\Internal\Transition\Utility\ContextInterface;
use OxidEsales\EshopCommunity\Tests\Integration\Internal\Framework\Module\Command\ModuleCommandsTestCase;
use OxidEsales\TestingLibrary\Services\Library\DatabaseRestorer\DatabaseRestorer;
use Symfony\Component\Console\Input\ArrayInput;

/**
 * @internal
 */
final class ActivateConfiguredModulesCommandTest extends ModuleCommandsTestCase
{
    /**
     * @var DatabaseRestorer
     */
    private $databaseRestorer;

    public function setup(): void
    {
        $this->databaseRestorer = new DatabaseRestorer();
        $this->databaseRestorer->dumpDB(__CLASS__);

        parent::setUp();
    }

    protected function tearDown(): void
    {
        $this->databaseRestorer->restoreDB(__CLASS__);

        parent::tearDown();
    }

    public function testActivateProperModulesOnlyInOneShop(): void
    {
        $this->prepareTestModuleConfigurations();

        $this->executeCommand([
            'command' => 'oe:module:apply-configuration',
            '--shop-id' => '2',
        ]);

        $moduleStateService = $this->get(ModuleStateServiceInterface::class);

        $this->assertFalse(
            $moduleStateService->isActive('test', 1)
        );

        $this->assertTrue(
            $moduleStateService->isActive('test', 2)
        );
    }

    private function prepareTestModuleConfigurations(): void
    {
        $module = new ModuleConfiguration();
        $module
            ->setId('test')
            ->setPath('any')
            ->setConfigured(true);

        $shopConfiguration = new ShopConfiguration();
        $shopConfiguration->addModuleConfiguration($module);

        $shopConfigurationDao = $this->get(ShopConfigurationDaoInterface::class);
        $shopConfigurationDao->save($shopConfiguration, 1);
        $shopConfigurationDao->save($shopConfiguration, 2);
    }

    private function executeCommand(array $input): void
    {
        $app = $this->getApplication();

        $this->execute(
            $app,
            $this->get('oxid_esales.console.commands_provider.services_commands_provider'),
            new ArrayInput($input)
        );
    }
}
