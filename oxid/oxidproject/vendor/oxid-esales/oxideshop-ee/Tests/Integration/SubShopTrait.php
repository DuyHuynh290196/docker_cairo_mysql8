<?php declare(strict_types=1);
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Tests\Integration;

use OxidEsales\EshopCommunity\Internal\Container\ContainerFactory;
use OxidEsales\EshopCommunity\Internal\Framework\Module\Configuration\Dao\ShopConfigurationDaoInterface;
use OxidEsales\EshopCommunity\Internal\Transition\Utility\BasicContextInterface;

/**
 * @internal
 */
trait SubShopTrait
{
    protected function createSubShop(int $shopId): void
    {
        $shop = oxNew(\OxidEsales\Eshop\Application\Model\Shop::class);
        $shop->setId($shopId);
        $shop->save();

        $container = ContainerFactory::getInstance()->getContainer();
        $baseShopId = $container->get(BasicContextInterface::class)->getDefaultShopId();
        $shopConfigurationDao = $container->get(ShopConfigurationDaoInterface::class);
        $shopConfiguration = $shopConfigurationDao->get($baseShopId);
        $shopConfigurationDao->save($shopConfiguration, $shopId);
    }
}
