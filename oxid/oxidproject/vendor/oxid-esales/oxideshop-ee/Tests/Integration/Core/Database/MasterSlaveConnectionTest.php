<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Tests\Integration\Core\Database;

use OxidEsales\Eshop\Core\Registry;
use OxidEsales\EshopEnterprise\Core\DatabaseProvider;
use OxidEsales\TestingLibrary\UnitTestCase;

/**
 * Class MasterSlaveConnectionTest
 *
 * @covers OxidEsales\EshopCommunity\Core\Database
 */
class MasterSlaveConnectionTest extends UnitTestCase
{
    /** @var mixed Backing up for earlier value of database link object */
    private $dbObjectBackup = null;

    /**
     * @var array backup original value
     */
    private $originalSlaveHosts = null;

    /**
     * Initialize the fixture.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->dbObjectBackup = $this->getProtectedClassProperty(DatabaseProvider::getInstance(), 'db');

        $this->setProtectedClassProperty(DatabaseProvider::getInstance(), 'db', null);
        $this->assertNull($this->getProtectedClassProperty(DatabaseProvider::getInstance(), 'db'));

        $configFile = Registry::get(\OxidEsales\Eshop\Core\ConfigFile::class);
        $this->originalSlaveHosts = $configFile->aSlaveHosts;
    }

    /**
     * Executed after test is down.
     */
    protected function tearDown(): void
    {
        $configFile = Registry::get(\OxidEsales\Eshop\Core\ConfigFile::class);
        $configFile->aSlaveHosts = $this->originalSlaveHosts;
        Registry::set(\OxidEsales\Eshop\Core\ConfigFile::class, $configFile);

        DatabaseProvider::getDb()->closeConnection();

        $this->setProtectedClassProperty(DatabaseProvider::getInstance(), 'db', $this->dbObjectBackup);

        DatabaseProvider::getDb()->closeConnection();

        parent::tearDown();
    }

    /**
     * Test case that we have no master slave setup.
     */
    public function testGetDbNoMasterSlavesetup(): void
    {
        $configFile = Registry::get(\OxidEsales\Eshop\Core\ConfigFile::class);
        $configFile->aSlaveHosts = null;

        $connection = DatabaseProvider::getDb();
        $this->assertTrue(is_a($connection, 'OxidEsales\EshopCommunity\Core\Database\Adapter\Doctrine\Database'));

        $dbConnection = $this->getProtectedClassProperty($connection, 'connection');
        $this->assertTrue(is_a($dbConnection, 'Doctrine\DBAL\Connection'));
        $this->assertFalse(is_a($dbConnection, 'Doctrine\DBAL\Connections\MasterSlaveConnection'));
        $this->assertSame($this->getConfig()->getConfigParam('dbHost'), $dbConnection->getHost());
    }

    /**
     * Test case that we have no master slave setup.
     */
    public function testGetMasterNoMasterSlavesetup(): void
    {
        $configFile = Registry::get(\OxidEsales\Eshop\Core\ConfigFile::class);
        $configFile->aSlaveHosts = null;

        $connection = DatabaseProvider::getMaster();
        $this->assertTrue(is_a($connection, 'OxidEsales\EshopCommunity\Core\Database\Adapter\Doctrine\Database'));

        $dbConnection = $this->getProtectedClassProperty($connection, 'connection');
        $this->assertTrue(is_a($dbConnection, 'Doctrine\DBAL\Connection'));
        $this->assertFalse(is_a($dbConnection, 'Doctrine\DBAL\Connections\MasterSlaveConnection'));
        $this->assertSame($this->getConfig()->getConfigParam('dbHost'), $dbConnection->getHost());
    }

    /**
     * Test forcing connection to database master.
     */
    public function testGetDbNotYetConnectedToMaster()
    {
        $connection = DatabaseProvider::getDb();
        $this->assertTrue(is_a($connection, 'OxidEsales\EshopCommunity\Core\Database\Adapter\Doctrine\Database'));

        $connection = $this->setMasterSlaveConnectionParameters($connection);
        $connection->connect();

        $dbConnection = $this->getProtectedClassProperty($connection, 'connection');
        $this->assertTrue(is_a($dbConnection, 'Doctrine\DBAL\Connection'));
        $this->assertTrue(is_a($dbConnection, 'Doctrine\DBAL\Connections\MasterSlaveConnection'));

        //first read on brand new connection should pick the slave
        $query = 'SELECT count(*) FROM oxuser';
        $dbConnection->executeQuery($query);
        $this->assertFalse($dbConnection->isConnectedToMaster());

        //now force master
        $connection = DatabaseProvider::getMaster();
        $dbConnection = $this->getProtectedClassProperty($connection, 'connection');
        $this->assertTrue(is_a($dbConnection, 'Doctrine\DBAL\Connections\MasterSlaveConnection'));
        $this->assertTrue($dbConnection->isConnectedToMaster());
    }

    /**
     * Test forcing connection to database master.
     */
    public function testGetMasterImmediately()
    {
        $connection = DatabaseProvider::getDb();
        $connection = $this->setMasterSlaveConnectionParameters($connection);
        $connection->connect();
        $this->setProtectedClassProperty(DatabaseProvider::getInstance(), 'db', $connection);

        $connection = DatabaseProvider::getMaster();

        $dbConnection = $this->getProtectedClassProperty($connection, 'connection');
        $this->assertTrue(is_a($dbConnection, 'Doctrine\DBAL\Connection'));
        $this->assertTrue(is_a($dbConnection, 'Doctrine\DBAL\Connections\MasterSlaveConnection'));

        $this->assertTrue($dbConnection->isConnectedToMaster());
    }

    /**
     * Test case that we force connect to master.
     */
    public function testForceMaster()
    {
        $connection = DatabaseProvider::getDb();
        $connection = $this->setMasterSlaveConnectionParameters($connection);
        $connection->connect();
        $connection->forceMasterConnection();

        $dbConnection = $this->getProtectedClassProperty($connection, 'connection');
        $this->assertTrue(is_a($dbConnection, 'Doctrine\DBAL\Connection'));
        $this->assertTrue(is_a($dbConnection, 'Doctrine\DBAL\Connections\MasterSlaveConnection'));

        $this->assertTrue($dbConnection->isConnectedToMaster());
    }

    /**
     * Test case that we force connect to slave.
     */
    public function testForceSlaveWithMasterSlaveAllowed()
    {
        $connection = $this->getMock('OxidEsales\EshopEnterprise\Core\Database\Adapter\Doctrine\Database', array('isMasterSlaveConnection'));
        $connection->expects($this->any())->method('isMasterSlaveConnection')->will($this->returnValue(true));
        $connection = $this->setMasterSlaveConnectionParameters($connection);
        $connection->connect();

        $dbConnection = $this->getProtectedClassProperty($connection, 'connection');
        $this->assertTrue(is_a($dbConnection, 'Doctrine\DBAL\Connection'));
        $this->assertTrue(is_a($dbConnection, 'Doctrine\DBAL\Connections\MasterSlaveConnection'));
        $connection->connect('master');

        $connection->forceSlaveConnection();

        $this->assertFalse($dbConnection->isConnectedToMaster());
    }

    /**
     * Test case that we force connect to slave.
     */
    public function testForceSlaveWithMasterSlaveNotAllowed()
    {
        $connection = $this->getMock('OxidEsales\EshopEnterprise\Core\Database\Adapter\Doctrine\Database', array('isMasterSlaveConnection', 'isConnectionEstablished'));
        $connection->expects($this->any())->method('isMasterSlaveConnection')->will($this->returnValue(false));
        $connection->expects($this->any())->method('isConnectionEstablished')->will($this->returnValue(true));
        $connection = $this->setMasterSlaveConnectionParameters($connection);
        $connection->connect();

        $dbConnection = $this->getProtectedClassProperty($connection, 'connection');
        $this->assertTrue(is_a($dbConnection, 'Doctrine\DBAL\Connection'));
        $this->assertTrue(is_a($dbConnection, 'Doctrine\DBAL\Connections\MasterSlaveConnection'));
        $dbConnection->connect('master');

        $connection->forceSlaveConnection();

        $this->assertTrue($dbConnection->isConnectedToMaster());
    }

    /**
     * Test helper for setting master slave connection parameters.
     *
     * @param $connection
     */
    protected function setMasterSlaveConnectionParameters($connection)
    {
        $this->setProtectedClassProperty($connection, 'connectionParameters', $this->getMasterSlaveParameters());

        return $connection;
    }

    /**
     * Test helper to get master slave configuration array.
     *
     * @return array
     */
    protected function getMasterSlaveParameters()
    {
        $config = $this->getConfig();

        $parameters = array('wrapperClass' => 'Doctrine\DBAL\Connections\MasterSlaveConnection',
                            'driver'       => 'mysqli',
                            'keepSlave'    => true,
                            'master'       => array(
                                'user'     => $config->getConfigParam('dbUser'),
                                'password' => $config->getConfigParam('dbPwd'),
                                'host'     => $config->getConfigParam('dbHost'),
                                'dbname'   => $config->getConfigParam('dbName'),
                                'port'     => 3306
                            ),
                            'slaves'       => array(
                                array(
                                    'user'     => $config->getConfigParam('dbUser'),
                                    'password' => $config->getConfigParam('dbPwd'),
                                    'host'     => $this->getSlaveHost(),
                                    'dbname'   => $config->getConfigParam('dbName'),
                                    'port'     => 3306
                                )
                            ));

        return $parameters;
    }

    /**
     * Get a real slave host if possible.
     */
    protected function getSlaveHost()
    {
        $slaveHosts = $this->getConfig()->getConfigParam('aSlaveHosts');
        $slaveHosts = is_array($slaveHosts) ? $slaveHosts : array();
        $slaveHost = $this->getConfig()->getConfigParam('dbHost');

        foreach ($slaveHosts as $host) {
            if ($host != $slaveHosts) {
                $slaveHost = $host;
            }
        }

        return $slaveHost;
    }

}
