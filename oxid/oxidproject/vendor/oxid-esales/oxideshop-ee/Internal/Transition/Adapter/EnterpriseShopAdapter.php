<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\EshopEnterprise\Internal\Transition\Adapter;

use OxidEsales\Eshop\Application\Model\Shop;
use OxidEsales\EshopEnterprise\Internal\Framework\Console\ShopSwitchException;

class EnterpriseShopAdapter implements EnterpriseShopAdapterInterface
{
    /**
     * @inheritdoc
     */
    public function validateShopId(int $shopId)
    {
        $shopModel = oxNew(Shop::class);
        $shopModel->load($shopId);
        if (!$shopModel->isLoaded()) {
            throw new ShopSwitchException('Failed to switch to subshop with id - ' . $shopId . '.'
            . ' Does this subshop exist?');
        }
    }
}
