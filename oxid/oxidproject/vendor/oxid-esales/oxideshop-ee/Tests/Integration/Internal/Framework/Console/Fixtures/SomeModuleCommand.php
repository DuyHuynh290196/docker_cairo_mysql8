<?php declare(strict_types=1);
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Tests\Integration\Internal\Framework\Console\Fixtures;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class SomeModuleCommand extends \OxidEsales\EshopCommunity\Internal\Framework\Console\AbstractShopAwareCommand
{
    protected function configure()
    {
        $this->setName('oe:tests:test-command');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Command have been executed!');
        return 0;
    }
}
