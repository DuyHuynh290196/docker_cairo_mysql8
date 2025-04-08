<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\EshopEnterprise\Internal\Framework\Module\Configuration\Service;

/**
 * Service is responsible for copying whole environment configuration from main shop to defined sub-shop.
 */
interface ShopConfigurationGeneratorInterface
{
    /**
     * @param int $shopId
     */
    public function generateForShop(int $shopId);
}
