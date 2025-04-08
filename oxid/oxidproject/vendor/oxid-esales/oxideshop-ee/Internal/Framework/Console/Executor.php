<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\EshopEnterprise\Internal\Framework\Console;

use OxidEsales\EshopCommunity\Internal\Framework\Console\CommandsProvider\CommandsProviderInterface;
use OxidEsales\EshopEnterprise\Internal\Transition\Adapter\EnterpriseShopAdapterInterface;
use OxidEsales\EshopCommunity\Internal\Framework\Console\ExecutorInterface;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\ConsoleOutputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Executor implements ExecutorInterface
{
    /**
     * @var Application
     */
    private $application;

    /**
     * @var CommandsProviderInterface
     */
    private $commandsProvider;

    /**
     * @var ConsoleOutputInterface
     */
    private $consoleOutput;

    /**
     * @var EnterpriseShopAdapterInterface
     */
    private $shopAdapter;

    /**
     * @param Application                    $application
     * @param ConsoleOutputInterface         $consoleOutput
     * @param CommandsProviderInterface      $commandsProvider
     * @param EnterpriseShopAdapterInterface $shopAdapter
     */
    public function __construct(
        Application $application,
        ConsoleOutputInterface $consoleOutput,
        CommandsProviderInterface $commandsProvider,
        EnterpriseShopAdapterInterface $shopAdapter
    ) {
        $this->application = $application;
        $this->consoleOutput = $consoleOutput;
        $this->commandsProvider = $commandsProvider;
        $this->shopAdapter = $shopAdapter;
    }

    public function execute(InputInterface $input = null, OutputInterface $output = null)
    {
        if (null === $input) {
            $input = new ArgvInput();
        }
        if (null === $output) {
            $output = new ConsoleOutput();
        }

        $shopId = (int) $input->getParameterOption('--' . \OxidEsales\EshopCommunity\Internal\Framework\Console\Executor::SHOP_ID_PARAMETER_OPTION_NAME);
        $shopId = $shopId === 0 ? 1 : $shopId;
        try {
            $this->shopAdapter->validateShopId($shopId);
            $this->application->addCommands($this->commandsProvider->getCommands());
            $this->application->run($input, $output);
        } catch (ShopSwitchException $shopSwitchException) {
            $output->writeln('<error>' . $shopSwitchException->getMessage() . '</error>');
            if ($this->application->isAutoExitEnabled()) {
                exit(1);
            }
        }
    }
}
