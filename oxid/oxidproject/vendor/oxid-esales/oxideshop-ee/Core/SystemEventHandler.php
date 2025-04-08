<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Core;

use OxidEsales\Eshop\Core\Registry;
use OxidEsales\EshopEnterprise\Core\Database\MasterSlaveConnectionModerator;

/**
 * @inheritdoc
 */
class SystemEventHandler extends \OxidEsales\EshopProfessional\Core\SystemEventHandler
{
    /**
     * Perform shop startup related actions, like license check.
     */
    public function onShopStart()
    {
        $this
            ->getMasterSlaveConnectionModerator()
            ->moderate();

        return parent::onShopStart();
    }

    /**
     * Returns MasterSlaveConnectionValidator.
     *
     * @return MasterSlaveConnectionModerator
     */
    private function getMasterSlaveConnectionModerator()
    {
        $serial     = Registry::getConfig()->getSerial(true);
        $database   = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();

        return oxNew(
            MasterSlaveConnectionModerator::class,
            $serial,
            $database
        );
    }
}
