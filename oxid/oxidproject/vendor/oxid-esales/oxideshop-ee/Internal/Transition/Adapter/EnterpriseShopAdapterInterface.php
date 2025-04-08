<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\EshopEnterprise\Internal\Transition\Adapter;

use OxidEsales\EshopEnterprise\Internal\Framework\Console\ShopSwitchException;

interface EnterpriseShopAdapterInterface
{
    /**
     * Validates shoo ID.
     * @throws ShopSwitchException
     * @param int $shopId
     */
    public function validateShopId(int $shopId);
}
