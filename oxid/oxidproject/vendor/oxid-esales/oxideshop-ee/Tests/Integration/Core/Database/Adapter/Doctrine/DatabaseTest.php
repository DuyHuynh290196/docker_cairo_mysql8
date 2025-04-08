<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Tests\Integration\Core\Database\Adapter\Doctrine;

use PDO;
use OxidEsales\EshopEnterprise\Core\Database\Adapter\Doctrine\Database as DatabaseAdapter;
use OxidEsales\EshopEnterprise\Core\DatabaseProvider;
use OxidEsales\TestingLibrary\UnitTestCase;

/**
 * Class DatabaseTest
 *
 * @covers OxidEsales\EshopEnterprise\Core\Database\Adapter\Doctrine\Database
 */
class DatabaseTest extends UnitTestCase
{
    /**
     * @var DatabaseAdapter The database to test.
     */
    protected $database = null;

    /**
     * Initialize database
     */
    public function setup(): void
    {
        parent::setUp();

        $this->initializeDatabase();
        $this->setProtectedClassProperty($this->database, 'connectionParameters', array());
    }

    /**
     * Empty database table after every test
     */
    public function tearDown(): void
    {
        $this->database->closeConnection();
        parent::tearDown();
    }

    /**
     * Provides an error handler
     *
     * @param integer $errorLevel   Error number as defined in http://php.net/manual/en/errorfunc.constants.php
     * @param string  $errorMessage Error message
     * @param string  $errorFile    Error file
     * @param integer $errorLine    Error line
     * @param array   $errorContext Error context
     */
    public function errorHandler($errorLevel, $errorMessage, $errorFile, $errorLine, $errorContext)
    {
        $this->errors[] = compact(
            "errorLevel",
            "errorMessage",
            "errorFile",
            "errorLine",
            "errorContext"
        );
    }

    /**
     * Create the database, we want to test.
     */
    protected function initializeDatabase()
    {
        $this->database = DatabaseProvider::getDb();
    }

    /*
     * Use Case: All possible parameters for db connection are set
     */
    public function testSetConnectionParametersAllParametersSet()
    {
        $this->setProtectedClassProperty($this->database, 'connectionParameters', []);
        $connectionParametersFromConfigInc = [
            'default' => [
                'databaseHost'     => 'myMasterDatabaseHost',
                'databaseName'     => 'myMasterDatabaseName',
                'databaseUser'     => 'myMasterDatabaseUser',
                'databasePassword' => 'myMasterDatabasePassword',
                'databasePort'     => 'myMasterDatabasePort',
                'databaseDriverOptions' => [
                    'testkey' => 'testvalue',
                ],
            ],
            'slaves'  => [
                [
                    'databaseHost'     => 'mySlave1DatabaseHost',
                    'databasePort'     => 'mySlave1DatabasePort',
                    'databaseName'     => 'mySlave1DatabaseName',
                    'databaseUser'     => 'mySlave1DatabaseUser',
                    'databasePassword' => 'mySlave1DatabasePassword'
                ],
                [
                    'databaseHost'     => 'mySlave2DatabaseHost',
                    'databasePort'     => 'mySlave2DatabasePort',
                    'databaseName'     => 'mySlave2DatabaseName',
                    'databaseUser'     => 'mySlave2DatabaseUser',
                    'databasePassword' => 'mySlave2DatabasePassword'
                ]
            ],
        ];

        $this->database->setConnectionParameters($connectionParametersFromConfigInc);

        $expectedConnectionParameters = [
            'wrapperClass' => 'Doctrine\DBAL\Connections\MasterSlaveConnection',
            'keepSlave'    => true,
            'driver'       => 'pdo_mysql',
            'master'       => [
                'host'     => 'myMasterDatabaseHost',
                'dbname'   => 'myMasterDatabaseName',
                'user'     => 'myMasterDatabaseUser',
                'password' => 'myMasterDatabasePassword',
                'port'     => 'myMasterDatabasePort'
            ],
            'slaves'       => [
                [
                    'host'     => 'mySlave1DatabaseHost',
                    'port'     => 'mySlave1DatabasePort',
                    'dbname'   => 'mySlave1DatabaseName',
                    'user'     => 'mySlave1DatabaseUser',
                    'password' => 'mySlave1DatabasePassword'
                ],
                [
                    'host'     => 'mySlave2DatabaseHost',
                    'port'     => 'mySlave2DatabasePort',
                    'dbname'   => 'mySlave2DatabaseName',
                    'user'     => 'mySlave2DatabaseUser',
                    'password' => 'mySlave2DatabasePassword'
                ]
            ],
            'driverOptions' => [
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET @@SESSION.sql_mode=''",
                'testkey' => 'testvalue',
                PDO::ATTR_STRINGIFY_FETCHES => true,
            ],
        ];

        $this->assertEquals(
            $expectedConnectionParameters,
            $this->getProtectedClassProperty($this->database, 'connectionParameters')
        );
    }

    /*
     * Use Case: All possible parameters for db connection are set
     */
    public function testSetConnectionParametersNoParameters()
    {
        $this->setProtectedClassProperty($this->database, 'connectionParameters', []);
        $connectionParametersFromConfigInc = [];
        $this->database->setConnectionParameters($connectionParametersFromConfigInc);
        $this->assertEquals(
            [],
            $this->getProtectedClassProperty($this->database, 'connectionParameters'),
            "There can be no parameters in the array with no input parameters."
        );
    }
}
