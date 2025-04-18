<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\EshopCommunity\Internal\Framework\Module\Setup\Bridge;

use OxidEsales\Eshop\Core\Registry;
use OxidEsales\EshopCommunity\Internal\Framework\Module\Setup\Service\ExtensionChainServiceInterface;

/**
 * @deprecated will be completely removed.
 */
class ClassExtensionChainBridge implements ClassExtensionChainBridgeInterface
{
    /**
     * @var ExtensionChainServiceInterface
     */
    private $classExtensionChainService;

    /**
     * ClassExtensionChainBridge constructor.
     * @param ExtensionChainServiceInterface $classExtensionChainService
     */
    public function __construct(ExtensionChainServiceInterface $classExtensionChainService)
    {
        $this->classExtensionChainService = $classExtensionChainService;
    }

    /**
     * @param int $shopId
     */
    public function updateChain(int $shopId)
    {
        $this->classExtensionChainService->updateChain($shopId);
        Registry::getConfig()->reinitialize();
    }
}
