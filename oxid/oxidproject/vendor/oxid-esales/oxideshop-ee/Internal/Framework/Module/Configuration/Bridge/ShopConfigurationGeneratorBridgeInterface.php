<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\EshopEnterprise\Internal\Framework\Module\Configuration\Bridge;

interface ShopConfigurationGeneratorBridgeInterface
{
    public function generateForShop(int $shopId);
}
