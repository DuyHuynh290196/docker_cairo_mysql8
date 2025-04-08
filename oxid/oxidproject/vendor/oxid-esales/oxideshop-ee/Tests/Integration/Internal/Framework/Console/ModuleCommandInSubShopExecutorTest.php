<?php declare(strict_types=1);
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Tests\Integration\Internal\Framework\Console;

use OxidEsales\Eshop\Core\Registry;
use OxidEsales\EshopCommunity\Internal\Framework\DIContainer\ContainerBuilder;
use OxidEsales\EshopCommunity\Internal\Framework\Console\ExecutorInterface;
use OxidEsales\EshopCommunity\Internal\Transition\Utility\BasicContext;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\StreamOutput;

class ModuleCommandInSubShopExecutorTest extends TestCase
{
    use SubShopManagerTrait;

    public function testExecutionWhenCommandRegisteredInShop2()
    {
        $this->createSubshopEntry();
        Registry::getConfig()->setShopId(2);

        $executor = $this->makeExecutor();

        $output = new StreamOutput(fopen('php://memory', 'w', false));

        $executor->execute(new ArrayInput(['command' => 'oe:tests:test-command', '--shop-id' => '2']), $output);

        $this->assertSame('Command have been executed!'.PHP_EOL, $this->getOutputFromStream($output));
        $this->deleteSubshopEntry();
        Registry::getConfig()->setShopId(1);
    }

    public function testExecutionWhenCommandNotRegisteredInShop1()
    {
        $this->createSubshopEntry();

        $executor = $this->makeExecutor();

        $output = new StreamOutput(fopen('php://memory', 'w', false));

        $executor->execute(new ArrayInput(['command' => 'oe:tests:test-command']), $output);

        $this->assertRegExp('/There are no commands defined/'.PHP_EOL, $this->getOutputFromStream($output));
        $this->deleteSubshopEntry();
    }

    private function makeExecutor(): ExecutorInterface
    {
        $context = $this
            ->getMockBuilder(BasicContext::class)
            ->disableOriginalConstructor()
            ->setMethods(['getGeneratedServicesFilePath', 'getCommunityEditionSourcePath'])
            ->getMock();

        $context
            ->method('getGeneratedServicesFilePath')
            ->willReturn(__DIR__ . '/Fixtures/generated_project.yaml');

        $context
            ->method('getCommunityEditionSourcePath')
            ->willReturn((new BasicContext)->getCommunityEditionSourcePath());

        $containerBuilder = new ContainerBuilder($context);

        $container = $containerBuilder->getContainer();
        $definition = $container->getDefinition('oxid_esales.console.symfony.component.console.application');
        $definition->addMethodCall('setAutoExit', [false]);

        $container->compile();

        return $container->get(ExecutorInterface::class);
    }

    /**
     * @param StreamOutput $output
     * @return bool|string
     */
    private function getOutputFromStream($output)
    {
        $stream = $output->getStream();
        rewind($stream);
        $display = stream_get_contents($stream);
        return $display;
    }
}
