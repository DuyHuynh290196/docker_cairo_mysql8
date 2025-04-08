<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Core\Database\Adapter\Doctrine;

use OxidEsales\Eshop\Core\Registry;

/**
 * The doctrine implementation of our database.
 *
 * @deprecated since v6.4.0 (2019-09-24); Use OxidEsales\Eshop\Internal\Framework\Database\QueryBuilderFactoryInterface
 */
class Database extends \OxidEsales\EshopProfessional\Core\Database\Adapter\Doctrine\Database
{
    /**
     * @inheritdoc
     */
    public function setConnectionParameters(array $connectionParameters)
    {
        parent::setConnectionParameters($connectionParameters);

        if (array_key_exists('slaves', $connectionParameters)) {
            $this->setConnectionParametersForMasterSlave($connectionParameters['slaves']);
        }
    }

    /**
     * @inheritdoc
     */
    public function forceMasterConnection()
    {
        parent::forceMasterConnection();

        if ($this->isMasterSlaveConnection()) {
            $this->connection->connect('master');
        }
    }

    /**
     * @inheritdoc
     */
    public function forceSlaveConnection()
    {
        parent::forceSlaveConnection();

        if ($this->isMasterSlaveConnection()) {
            $this->connection->connect('slave');
        }
    }

    /**
     * The connection parameters have to be specified in a different format for a
     * Doctrine\DBAL\Connections\MasterSlaveConnection than for a Doctrine\DBAL\Connection
     *
     * @param array $connectionParametersForSlaves
     */
    protected function setConnectionParametersForMasterSlave(array $connectionParametersForSlaves)
    {
        $connectionCharset = $this->connectionParameters['charset'];

        $this->connectionParameters = array(
            'wrapperClass'  => 'Doctrine\DBAL\Connections\MasterSlaveConnection',
            'keepSlave'     => true,
            'driver'        => 'pdo_mysql',
            'master'        => array(
                'host'          => $this->connectionParameters['host'],
                'dbname'        => $this->connectionParameters['dbname'],
                'user'          => $this->connectionParameters['user'],
                'password'      => $this->connectionParameters['password'],
                'port'          => $this->connectionParameters['port']
            ),
            'slaves'        => array(),
            'driverOptions' => $this->connectionParameters['driverOptions'],
        );

        foreach ($connectionParametersForSlaves as $connectionParametersForSlave) {
            $this->connectionParameters['slaves'][] = array(
                'host'          => $connectionParametersForSlave['databaseHost'],
                'port'          => $connectionParametersForSlave['databasePort'],
                'dbname'        => $connectionParametersForSlave['databaseName'],
                'user'          => $connectionParametersForSlave['databaseUser'],
                'password'      => $connectionParametersForSlave['databasePassword'],
            );
        }
        $this->addDriverOptions($this->connectionParameters);
        $this->addConnectionCharsetForMasterSlave($connectionCharset);
    }

    /**
     * The connection charset has to be specified in another place for a
     * Doctrine\DBAL\Connections\MasterSlaveConnection than for a Doctrine\DBAL\Connection
     *
     * @param string $connectionCharset
     */
    protected function addConnectionCharsetForMasterSlave($connectionCharset)
    {
        $sanitizedCharset = trim(strtolower((string) $connectionCharset));

        if (
            empty($sanitizedCharset) ||
            !is_array($this->connectionParameters['master']) ||
            !is_array($this->connectionParameters['slaves'])
        ) {
            return;
        }

        $this->addConnectionCharset($this->connectionParameters['master'], $sanitizedCharset);
        for ($i = 0; $i < count($this->connectionParameters['slaves']); $i++) {
            $this->addConnectionCharset($this->connectionParameters['slaves'][$i], $sanitizedCharset);
        }
    }

    /**
     * @inheritdoc
     */
    protected function isConnectionEstablished($connection)
    {
        if ($this->isMasterSlaveConnection($connection)) {
            /**
             * The method isConnected will always return null for a master-slave connection and there is no other _cheap_
             * way to test, if the connection "is connected".
             */
            return true;
        } else {
            return parent::isConnectionEstablished($connection);
        }
    }

    /**
     * Check if the database connection is of type Doctrine\DBAL\Connections\MasterSlaveConnection.
     *
     * @param Doctrine\DBAL\Driver\Connection $connection
     *
     * @return bool
     */
    public function isMasterSlaveConnection($connection = null)
    {
        $isMasterSlaveConnection = false;

        if (is_null($connection)) {
            $connection = $this->getConnection();
        }

        if ($connection) {
            $isMasterSlaveConnection = is_a($connection, 'Doctrine\DBAL\Connections\MasterSlaveConnection');
        }

        return $isMasterSlaveConnection;
    }
}
