<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\EshopCommunity\Internal\Framework\Config\Event;

use Symfony\Contracts\EventDispatcher\Event;

/**
 * @stable
 * @see OxidEsales/EshopCommunity/Internal/README.md
 */
class ShopConfigurationChangedEvent extends Event
{
    /**
     * @deprecated constant will be removed in v7.0.
     */
    const NAME = self::class;

    /**
     * Configuration variable that was changed.
     *
     * @var string
     */
    private $configurationVariable;

    /**
     * Shopid the configuration was changed for.
     *
     * @var integer
     */
    private $shopId;

    /**
     * ShopConfigurationChangedEvent constructor.
     *
     * @param string $configurationVariable Config varname.
     * @param int    $shopId                Shop id.
     */
    public function __construct(string $configurationVariable, int $shopId)
    {
        $this->configurationVariable = $configurationVariable;
        $this->shopId = $shopId;
    }

    /**
     * Getter for configuration variable name.
     *
     * @return string
     */
    public function getConfigurationVariable(): string
    {
        return $this->configurationVariable;
    }

    /**
     * Getter for shop id.
     *
     * @return integer
     */
    public function getShopId(): int
    {
        return $this->shopId;
    }
}
