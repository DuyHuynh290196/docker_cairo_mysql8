<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Core;

use OxidEsales\Eshop\Core\Database\Adapter\Doctrine\Database as DatabaseAdapter;

/**
 * Database connection class
 *
 * @deprecated since v6.4.0 (2019-09-24) use QueryBuilderFactoryInterface
 * @see \OxidEsales\EshopCommunity\Internal\Framework\Database\QueryBuilderFactoryInterface
 */
class DatabaseProvider extends \OxidEsales\EshopProfessional\Core\DatabaseProvider
{
    /**
     * A singleton instance of this class or a sub class of this class
     *
     * @var null|DatabaseProvider
     */
    protected static $instance = null;

    /**
     * @inheritdoc
     */
    protected function createDatabase()
    {
        /** Call to fetchConfigFile redirects to setup wizard, if shop has not been configured. */
        $configFile = $this->fetchConfigFile();

        /** Validate the configuration file */
        $this->validateConfigFile($configFile);

        /** Set config file to be able to read shop configuration within the class */
        $this->setConfigFile($configFile);

        /** @var array $connectionParameters Parameters needed for the database connection */
        $connectionParameters = $this->getConnectionParameters();

        $databaseAdapter = new DatabaseAdapter();
        $databaseAdapter->setConnectionParameters($connectionParameters);
        $databaseAdapter->connect();

        return $databaseAdapter;
    }

    /**
     * @inheritdoc
     */
    protected function onPostConnect()
    {
        parent::onPostConnect();

        /**
         * If this is a master-slave connection, commands executed in parent::onPostConnect() may force the connection
         * to pick the master. As the connection would stay on the master from this point, the connection will be forced
         * to the slave here.
         */
        if (static::$db->isMasterSlaveConnection()) {
            static::$db->forceSlaveConnection();
        }
    }

    /**
     * @inheritdoc
     *
     * @return array
     */
    protected function getConnectionParameters()
    {
        $connectionParameters = parent::getConnectionParameters();
        $slaveConnectionParameters = $this->getSlaveConnectionParameters($connectionParameters);

        if (count($slaveConnectionParameters) > 0) {
            $connectionParameters['slaves'] = $slaveConnectionParameters;
        }

        return $connectionParameters;
    }

    /*
     * Get the parameters from config.inc.php file for slave configuration.
     *
     * @param array $connectionParameters
     * @return array
     */
    protected function getSlaveConnectionParameters($connectionParameters)
    {
        $slaveConnectionParameters = array();
        $slaveHostsConfigParameter = $this->getConfigParam('aSlaveHosts');

        if (!is_array($slaveHostsConfigParameter) || count($slaveHostsConfigParameter) === 0) {
            return $slaveConnectionParameters;
        }

        for ($i = 0; $i < count($slaveHostsConfigParameter); $i++) {
            $slaveConnectionParameters[] = array(
                'databaseHost'     => $slaveHostsConfigParameter[$i],
                'databasePort'     => $connectionParameters['default']['databasePort'],
                'databaseName'     => $connectionParameters['default']['databaseName'],
                'databaseUser'     => $connectionParameters['default']['databaseUser'],
                'databasePassword' => $connectionParameters['default']['databasePassword'],
            );
        }

        return $slaveConnectionParameters;
    }

    /**
     * @inheritdoc
     * The result will be fetched from the eShop file cache.
     *
     * @param string $tableName
     *
     * @return array
     */
    protected function fetchTableDescription($tableName)
    {
        $utils = \OxidEsales\Eshop\Core\Registry::getUtils();
        $fields = $utils->fromFileCache("tbdsc_" . $tableName);

        if (!$fields) {
            $fields = self::getDb()->metaColumns($tableName);
            $utils->toFileCache("tbdsc_" . $tableName, $fields);
        }

        return $fields;
    }
}
