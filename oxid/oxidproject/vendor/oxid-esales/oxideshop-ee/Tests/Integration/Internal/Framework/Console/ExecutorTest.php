<?php declare(strict_types=1);
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Tests\Integration\Internal\Framework\Console;

use OxidEsales\EshopCommunity\Internal\Framework\Console\CommandsProvider\CommandsProviderInterface;
use OxidEsales\EshopEnterprise\Tests\Integration\Internal\Framework\Console\Fixtures\TestForActiveSubshopCommand;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;

class ExecutorTest extends TestCase
{
    use ConsoleTrait;
    use SubShopManagerTrait;

    public function testIfShopIdInGlobalOptionsList()
    {
        /** @var Application $application */
        $consoleOutput = $this->execute(
            $this->getApplication(),
            $this->makeCommandsProvider([]),
            new ArrayInput(['command' => 'list'])
        );

        $this->assertRegexp('/--shop-id/', $consoleOutput);
    }

    public function testIfSubshopIsSwitched()
    {
        $this->createSubshopEntry();
        $_POST['shp'] = 2;
        $consoleOutput = $this->execute(
            $this->getApplication(),
            $this->makeCommandsProvider([new TestForActiveSubshopCommand()]),
            new ArrayInput(['command' => 'oe:tests:get-subshop', '--shop-id' => '2'])
        );

        $this->assertSame('Active shop 2'.PHP_EOL, $consoleOutput);
        $this->deleteSubshopEntry();
    }

    public function testErrorIfSubshopDoesNotExist()
    {
        $consoleOutput = $this->execute(
            $this->getApplication(),
            $this->makeCommandsProvider([new TestForActiveSubshopCommand()]),
            new ArrayInput(['command' => 'oe:tests:get-subshop', '--shop-id' => '2'])
        );

        $this->assertSame('Failed to switch to subshop with id - 2. Does this subshop exist?'.PHP_EOL, $consoleOutput);
    }

    /**
     * @return Application
     */
    private function getApplication(): Application
    {
        $application = $this->get('oxid_esales.console.symfony.component.console.application');
        $application->setAutoExit(false);

        return $application;
    }

    /**
     * @param array $commands
     * @return CommandsProviderInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private function makeCommandsProvider(array $commands)
    {
        $testCommandProvider = $this->getMockBuilder(CommandsProviderInterface::class)->getMock();
        $testCommandProvider->method('getCommands')->willReturn($commands);
        return $testCommandProvider;
    }
}
