<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Core\Database;

use OxidEsales\Eshop\Core\Database\Adapter\Doctrine\Database;
use OxidEsales\Eshop\Core\Serial;

/**
 * Master-slave connection validator class.
 *
 * @internal Do not make a module extension for this class.
 *
 * @ignore   This class will not be included in documentation.
 */
class MasterSlaveConnectionModerator
{
    /**
     * @var Serial
     */
    private $serial;

    /**
     * @var Database
     */
    private $database;

    /**
     * MasterSlaveConnectionValidator constructor.
     *
     * @param Serial    $serial
     * @param Database  $database
     */
    public function __construct(
        Serial $serial,
        Database $database
    ) {
        $this->serial = $serial;
        $this->database = $database;
    }

    /**
     * Moderates master-slave connection.
     */
    public function moderate()
    {
        if (
            $this->database->isMasterSlaveConnection()
            && !$this->serial->isMasterSlaveLicenseValid()
        ) {
            $this->database->forceMasterConnection();
        }
    }
}
