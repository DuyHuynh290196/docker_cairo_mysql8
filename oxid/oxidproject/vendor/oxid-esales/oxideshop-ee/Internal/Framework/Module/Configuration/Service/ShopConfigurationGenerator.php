<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\EshopEnterprise\Internal\Framework\Module\Configuration\Service;

use OxidEsales\EshopCommunity\Application\Controller\Admin\ModuleConfiguration;
use OxidEsales\EshopCommunity\Internal\Transition\Utility\BasicContextInterface;
use OxidEsales\EshopCommunity\Internal\Framework\Module\Configuration\Dao\ShopConfigurationDaoInterface;
use OxidEsales\EshopCommunity\Internal\Framework\Module\Configuration\DataObject\ShopConfiguration;

/**
 * @inheritDoc
 */
class ShopConfigurationGenerator implements ShopConfigurationGeneratorInterface
{
    /**
     * @var BasicContextInterface
     */
    private $context;

    /**
     * @var ShopConfigurationDaoInterface
     */
    private $shopConfigurationDao;

    /**
     * @param BasicContextInterface $context
     * @param ShopConfigurationDaoInterface $shopConfigurationDao
     */
    public function __construct(BasicContextInterface $context, ShopConfigurationDaoInterface $shopConfigurationDao)
    {
        $this->context = $context;
        $this->shopConfigurationDao = $shopConfigurationDao;
    }


    /**
     * @param int $shopId
     */
    public function generateForShop(int $shopId)
    {
        $shopConfiguration = $this->shopConfigurationDao->get(
            $this->context->getDefaultShopId()
        );

        $this->shopConfigurationDao->save(
            $this->prepareSubShopConfiguration($shopConfiguration),
            $shopId
        );
    }

    /**
     * Method modifies original module configurations and returns prepared sub-shop configuration.
     *
     * @param ShopConfiguration $shopConfiguration
     * @return ShopConfiguration
     */
    private function prepareSubShopConfiguration(ShopConfiguration $shopConfiguration): ShopConfiguration
    {
        $subShopConfiguration = clone $shopConfiguration;
        array_map(function ($moduleConfiguration) use ($subShopConfiguration) {
            $subShopConfiguration->addModuleConfiguration(clone $moduleConfiguration);
        }, $shopConfiguration->getModuleConfigurations());
        array_map(function ($subShopModuleConfiguration) {
            $subShopModuleConfiguration->setConfigured(false);
        }, $subShopConfiguration->getModuleConfigurations());
        return $subShopConfiguration;
    }
}
