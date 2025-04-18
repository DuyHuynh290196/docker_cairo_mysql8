<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\EshopCommunity\Internal\Framework\Module\Setup\Service;

/**
 * @deprecated will be removed completely in 7.0
 */
interface ModuleServicesActivationServiceInterface
{
    /**
     * @param string $moduleId
     * @param int    $shopId
     * @return void
     */
    public function activateModuleServices(string $moduleId, int $shopId);

    /**
     * @param string $moduleId
     * @param int    $shopId
     * @return void
     */
    public function deactivateModuleServices(string $moduleId, int $shopId);
}
