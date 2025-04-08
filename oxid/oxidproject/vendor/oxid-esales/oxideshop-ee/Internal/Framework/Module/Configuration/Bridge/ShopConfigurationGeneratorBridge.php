<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\EshopEnterprise\Internal\Framework\Module\Configuration\Bridge;

use OxidEsales\EshopEnterprise\Internal\Framework\Module\Configuration\Service\ShopConfigurationGeneratorInterface;

class ShopConfigurationGeneratorBridge implements ShopConfigurationGeneratorBridgeInterface
{
    /**
     * @var ShopConfigurationGeneratorInterface
     */
    private $projectConfigurationCopyService;

    public function __construct(ShopConfigurationGeneratorInterface $projectConfigurationCopyService)
    {
        $this->projectConfigurationCopyService = $projectConfigurationCopyService;
    }

    public function generateForShop(int $shopId)
    {
        $this->projectConfigurationCopyService->generateForShop($shopId);
    }
}
