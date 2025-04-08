<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Tests\Integration\Core\Database;

use OxidEsales\Eshop\Core\Database\Adapter\DatabaseInterface;
use OxidEsales\EshopCommunity\Core\Database\Adapter\Doctrine\Database;
use OxidEsales\EshopEnterprise\Core\DatabaseProvider;
use OxidEsales\TestingLibrary\UnitTestCase;
use PHPUnit\Framework\SkippedTestError;

/**
 * @group  database-adapter
 * @group  master-slave
 * @see \Doctrine\DBAL\Connections\MasterSlaveConnection
 */
final class MasterSlaveTest extends UnitTestCase
{
    private DatabaseInterface $masterSlaveConnection;
    private \mysqli $masterConnection;
    private \mysqli $slaveConnection;
    private string $expectedMasterResult;
    private string $selectColumnQuery = 'SELECT column1 FROM `master_slave_table` WHERE id = 1';

    protected function setUp(): void
    {
        parent::setUp();

        $this->skipIfNotConfigured();

        $this->expectedMasterResult = uniqid('some-value-on-master-', true);
        $this->initConnections();
        $this->dropTestTable();
        $this->createTestTable();
        $this->populateTestTable();
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->skipIfNotConfigured();

        $this->dropTestTable();
        $this->closeConnections();
    }

    /**
     * Assure that replication works at all
     */
    public function testBasicMasterSlaveFunctionality(): void
    {
        $actualSlaveResult = $this->readDirectlyFromSlave();
        $actualMasterResult = $this->readDirectlyFromMaster();

        $this->assertSame(
            $this->expectedMasterResult,
            $actualSlaveResult,
            'Slave connection retrieves record inserted into master database'
        );
        $this->assertSame(
            $this->expectedMasterResult,
            $actualMasterResult,
            'Master connection retrieves record inserted into master database'
        );
    }

    /**
     * Test Doctrine behavior:
     * "Use slave if master was never picked before and ONLY if 'getWrappedConnection' or 'executeQuery' is used."
     * Database::getAll uses executeQuery
     * @covers \OxidEsales\EshopCommunity\Core\Database\Adapter\Doctrine\Database::getAll
     */
    public function testMasterSlaveConnectionReadsFromSlaveOnGetAll(): void
    {
        $expectedSlaveResult = '100';

        $this->updateOnSlave($expectedSlaveResult);

        $actualSlaveResult = $this->readDirectlyFromSlave();
        $actualMasterResult = $this->readDirectlyFromMaster();

        $actualMasterSlaveResult = $this->masterSlaveConnection->getAll($this->selectColumnQuery)[0]['column1'];

        $this->assertSame(
            $expectedSlaveResult,
            $actualSlaveResult,
            "Slave connection retrieves modified value $expectedSlaveResult"
        );
        $this->assertSame(
            $this->expectedMasterResult,
            $actualMasterResult,
            "Master connection retrieves unmodified value $expectedSlaveResult"
        );
        $this->assertSame(
            $expectedSlaveResult,
            $actualMasterSlaveResult,
            'Master-Slave connection reads from slave database when using getAll()'
        );
    }

    /**
     * Test Doctrine behavior:
     * "Use slave if master was never picked before and ONLY if 'getWrappedConnection' or 'executeQuery' is used."
     * Database::getCol uses executeQuery
     * @covers \OxidEsales\EshopCommunity\Core\Database\Adapter\Doctrine\Database::getCol
     */
    public function testMasterSlaveConnectionReadsFromSlaveOnGetCol(): void
    {
        $expectedSlaveResult = '100';

        $this->updateOnSlave($expectedSlaveResult);

        $actualSlaveResult = $this->readDirectlyFromSlave();
        $actualMasterResult = $this->readDirectlyFromMaster();

        $actualMasterSlaveResult = $this->masterSlaveConnection->getCol($this->selectColumnQuery)[0];

        $this->assertSame(
            $expectedSlaveResult,
            $actualSlaveResult,
            "Slave connection retrieves modified value $expectedSlaveResult"
        );
        $this->assertSame(
            $this->expectedMasterResult,
            $actualMasterResult,
            "Master connection retrieves unmodified value $expectedSlaveResult"
        );
        $this->assertSame(
            $expectedSlaveResult,
            $actualMasterSlaveResult,
            'Master-Slave connection reads from slave database when using getCol()'
        );
    }

    /**
     * Test Doctrine behavior:
     * "Use slave if master was never picked before and ONLY if 'getWrappedConnection' or 'executeQuery' is used."
     * Database::getOne uses executeQuery
     * @covers \OxidEsales\EshopCommunity\Core\Database\Adapter\Doctrine\Database::getOne
     */
    public function testMasterSlaveConnectionReadsFromSlaveOnGetOne(): void
    {
        $expectedSlaveResult = '100';

        $this->updateOnSlave($expectedSlaveResult);

        $actualSlaveResult = $this->readDirectlyFromSlave();
        $actualMasterResult = $this->readDirectlyFromMaster();

        $actualMasterSlaveResult = $this->getOneFromMasterSlave();

        $this->assertSame(
            $expectedSlaveResult,
            $actualSlaveResult,
            "Slave connection retrieves modified value $expectedSlaveResult"
        );
        $this->assertSame(
            $this->expectedMasterResult,
            $actualMasterResult,
            "Master connection retrieves unmodified value $expectedSlaveResult"
        );
        $this->assertSame(
            $expectedSlaveResult,
            $actualMasterSlaveResult,
            'Master-Slave connection reads from slave database when using getOne()'
        );
    }

    /**
     * Test Doctrine behavior:
     * "Use slave if master was never picked before and ONLY if 'getWrappedConnection' or 'executeQuery' is used."
     * Database::getRow uses executeQuery
     * @covers \OxidEsales\EshopCommunity\Core\Database\Adapter\Doctrine\Database::getRow
     */
    public function testMasterSlaveConnectionReadsFromSlaveOnGetRow(): void
    {
        $expectedSlaveResult = '100';

        $this->updateOnSlave($expectedSlaveResult);

        $actualSlaveResult = $this->readDirectlyFromSlave();
        $actualMasterResult = $this->readDirectlyFromMaster();

        $actualMasterSlaveResult = $this->masterSlaveConnection->getRow($this->selectColumnQuery)['column1'];

        $this->assertSame(
            $expectedSlaveResult,
            $actualSlaveResult,
            "Slave connection retrieves modified value $expectedSlaveResult"
        );
        $this->assertSame(
            $this->expectedMasterResult,
            $actualMasterResult,
            "Master connection retrieves unmodified value $expectedSlaveResult"
        );
        $this->assertSame(
            $expectedSlaveResult,
            $actualMasterSlaveResult,
            'Master-Slave connection reads from slave database when using getRow()'
        );
    }

    /**
     * Test Doctrine behavior:
     * "Use slave if master was never picked before and ONLY if 'getWrappedConnection' or 'executeQuery' is used."
     * Database::select uses executeQuery
     * @covers \OxidEsales\EshopCommunity\Core\Database\Adapter\Doctrine\Database::select
     */
    public function testMasterSlaveConnectionReadsFromSlaveOnSelect(): void
    {
        $expectedSlaveResult = '100';

        $this->updateOnSlave($expectedSlaveResult);

        $actualSlaveResult = $this->readDirectlyFromSlave();
        $actualMasterResult = $this->readDirectlyFromMaster();

        $resultSet = $this->masterSlaveConnection->select($this->selectColumnQuery);
        $actualMasterSlaveResult = $resultSet->fields['column1'];

        $this->assertSame(
            $expectedSlaveResult,
            $actualSlaveResult,
            "Slave connection retrieves modified value $expectedSlaveResult"
        );
        $this->assertSame(
            $this->expectedMasterResult,
            $actualMasterResult,
            "Master connection retrieves unmodified value $expectedSlaveResult"
        );
        $this->assertSame(
            $expectedSlaveResult,
            $actualMasterSlaveResult,
            'Master-Slave connection reads from slave database when using select()'
        );
    }

    /**
     * Test Doctrine behavior:
     * "Master is picked when 'exec', 'executeUpdate', 'insert', 'delete', 'update', 'createSavepoint',
     * 'releaseSavepoint', 'beginTransaction', 'rollback', 'commit', 'query' or 'prepare' is called."
     * "Use slave if master was never picked before and ONLY if 'getWrappedConnection' or 'executeQuery' is used."
     *
     * Database::getAll uses executeQuery
     * Now picking the master is forced by using 'executeUpdate' and then the master should also be also used for
     * Database::getAll
     *
     * @covers \OxidEsales\EshopCommunity\Core\Database\Adapter\Doctrine\Database::getAll
     */
    public function testMasterSlaveConnectionReadsFromMasterOnGetAll(): void
    {
        $expectedSlaveResult = '100';

        $this->updateOnSlave($expectedSlaveResult);

        $actualSlaveResult = $this->readDirectlyFromSlave();
        $actualMasterResult = $this->readDirectlyFromMaster();

        /** Force picking the master by doing an execute here. Doctrine:executeUpdate is called */
        $this->masterSlaveConnection->execute(
            "INSERT INTO `master_slave_table` (`column1`, `column2`) VALUES ('2', '3')"
        );
        $actualMasterSlaveResult = $this->masterSlaveConnection->getAll($this->selectColumnQuery)[0]['column1'];

        $this->assertSame(
            $expectedSlaveResult,
            $actualSlaveResult,
            "Slave connection retrieves modified value $expectedSlaveResult"
        );
        $this->assertSame(
            $this->expectedMasterResult,
            $actualMasterResult,
            "Master connection retrieves unmodified value $expectedSlaveResult"
        );
        $this->assertSame(
            $this->expectedMasterResult,
            $actualMasterSlaveResult,
            'Master-Slave connection reads from master database when using getAll() after execute()'
        );
    }

    /**
     * Test Doctrine behavior:
     * "Master is picked when 'exec', 'executeUpdate', 'insert', 'delete', 'update', 'createSavepoint',
     * 'releaseSavepoint', 'beginTransaction', 'rollback', 'commit', 'query' or 'prepare' is called."
     * "Use slave if master was never picked before and ONLY if 'getWrappedConnection' or 'executeQuery' is used."
     *
     * Database::getCol uses executeQuery
     * Now picking the master is forced by using 'executeUpdate' and then the master should also be also used for
     * Database::getCol
     *
     * @covers \OxidEsales\EshopCommunity\Core\Database\Adapter\Doctrine\Database::getCol
     */
    public function testMasterSlaveConnectionReadsFromMasterOnGetCol(): void
    {
        $expectedSlaveResult = '100';

        $this->updateOnSlave($expectedSlaveResult);

        $actualSlaveResult = $this->readDirectlyFromSlave();
        $actualMasterResult = $this->readDirectlyFromMaster();

        /** Force picking the master by doing an execute here. Doctrine:executeUpdate is called */
        $this->masterSlaveConnection->execute(
            "INSERT INTO `master_slave_table` (`column1`, `column2`) VALUES ('2', '3')"
        );
        $actualMasterSlaveResult = $this->masterSlaveConnection->getCol($this->selectColumnQuery)[0];

        $this->assertSame(
            $expectedSlaveResult,
            $actualSlaveResult,
            "Slave connection retrieves modified value $expectedSlaveResult"
        );
        $this->assertSame(
            $this->expectedMasterResult,
            $actualMasterResult,
            "Master connection retrieves unmodified value $expectedSlaveResult"
        );
        $this->assertSame(
            $this->expectedMasterResult,
            $actualMasterSlaveResult,
            'Master-Slave connection reads from master database when using getCol() after execute()'
        );
    }

    /**
     * Test Doctrine behavior:
     * "Master is picked when 'exec', 'executeUpdate', 'insert', 'delete', 'update', 'createSavepoint',
     * 'releaseSavepoint', 'beginTransaction', 'rollback', 'commit', 'query' or 'prepare' is called."
     * "Use slave if master was never picked before and ONLY if 'getWrappedConnection' or 'executeQuery' is used."
     *
     * Database::getOne uses executeQuery
     * Now picking the master is forced by using 'executeUpdate' and then the master should also be also used for
     * Database::getOne
     *
     * @covers \OxidEsales\EshopCommunity\Core\Database\Adapter\Doctrine\Database::getOne
     */
    public function testMasterSlaveConnectionReadsFromMasterOnGetOne(): void
    {
        $expectedSlaveResult = '100';

        $this->updateOnSlave($expectedSlaveResult);

        $actualSlaveResult = $this->readDirectlyFromSlave();
        $actualMasterResult = $this->readDirectlyFromMaster();

        /** Force picking the master by doing an execute here. Doctrine:executeUpdate is called */
        $this->masterSlaveConnection->execute(
            "INSERT INTO `master_slave_table` (`column1`, `column2`) VALUES ('2', '3')"
        );
        $actualMasterSlaveResult = $this->getOneFromMasterSlave();

        $this->assertSame(
            $expectedSlaveResult,
            $actualSlaveResult,
            "Slave connection retrieves modified value $expectedSlaveResult"
        );
        $this->assertSame(
            $this->expectedMasterResult,
            $actualMasterResult,
            "Master connection retrieves unmodified value $expectedSlaveResult"
        );
        $this->assertSame(
            $this->expectedMasterResult,
            $actualMasterSlaveResult,
            'Master-Slave connection reads from master database when using getOne() after execute()'
        );
    }

    /**
     * Test Doctrine behavior:
     * "Master is picked when 'exec', 'executeUpdate', 'insert', 'delete', 'update', 'createSavepoint',
     * 'releaseSavepoint', 'beginTransaction', 'rollback', 'commit', 'query' or 'prepare' is called."
     * "Use slave if master was never picked before and ONLY if 'getWrappedConnection' or 'executeQuery' is used."
     *
     * Database::getRow uses executeQuery
     * Now picking the master is forced by using 'executeUpdate' and then the master should also be also used for
     * Database::getRow
     *
     * @covers \OxidEsales\EshopCommunity\Core\Database\Adapter\Doctrine\Database::getRow
     */
    public function testMasterSlaveConnectionReadsFromMasterOnGetRow(): void
    {
        $expectedSlaveResult = '100';

        $this->updateOnSlave($expectedSlaveResult);

        $actualSlaveResult = $this->readDirectlyFromSlave();
        $actualMasterResult = $this->readDirectlyFromMaster();

        /** Force picking the master by doing an execute here. Doctrine:executeUpdate is called */
        $this->masterSlaveConnection->execute(
            "INSERT INTO `master_slave_table` (`column1`, `column2`) VALUES ('2', '3')"
        );
        $actualMasterSlaveResult = $this->masterSlaveConnection->getRow($this->selectColumnQuery)['column1'];

        $this->assertSame(
            $expectedSlaveResult,
            $actualSlaveResult,
            "Slave connection retrieves modified value $expectedSlaveResult"
        );
        $this->assertSame(
            $this->expectedMasterResult,
            $actualMasterResult,
            "Master connection retrieves unmodified value $expectedSlaveResult"
        );
        $this->assertSame(
            $this->expectedMasterResult,
            $actualMasterSlaveResult,
            'Master-Slave connection reads from master database when using getRow() after execute()'
        );
    }

    /**
     * Test Doctrine behavior:
     * "Master is picked when 'exec', 'executeUpdate', 'insert', 'delete', 'update', 'createSavepoint',
     * 'releaseSavepoint', 'beginTransaction', 'rollback', 'commit', 'query' or 'prepare' is called."
     * "Use slave if master was never picked before and ONLY if 'getWrappedConnection' or 'executeQuery' is used."
     *
     * Database::select uses executeQuery
     * Now picking the master is forced by using 'executeUpdate' and then the master should also be also used for
     * Database::select
     *
     * @covers \OxidEsales\EshopCommunity\Core\Database\Adapter\Doctrine\Database::select
     */
    public function testMasterSlaveConnectionReadsFromMasterOnSelect(): void
    {
        $expectedSlaveResult = '100';

        $this->updateOnSlave($expectedSlaveResult);

        $actualSlaveResult = $this->readDirectlyFromSlave();
        $actualMasterResult = $this->readDirectlyFromMaster();

        /** Force picking the master by doing an execute here. Doctrine:executeUpdate is called */
        $this->masterSlaveConnection->execute(
            "INSERT INTO `master_slave_table` (`column1`, `column2`) VALUES ('2', '3')"
        );
        $resultSet = $this->masterSlaveConnection->select($this->selectColumnQuery);
        $actualMasterSlaveResult = $resultSet->fields['column1'];

        $this->assertSame(
            $expectedSlaveResult,
            $actualSlaveResult,
            "Slave connection retrieves modified value $expectedSlaveResult"
        );
        $this->assertSame(
            $this->expectedMasterResult,
            $actualMasterResult,
            "Master connection retrieves unmodified value $this->expectedMasterResult"
        );
        $this->assertSame(
            $this->expectedMasterResult,
            $actualMasterSlaveResult,
            'Master-Slave connection reads from master database when using select() after execute()'
        );
    }

    /**
     * Test Doctrine behavior:
     * "Master is picked when 'exec', 'executeUpdate', 'insert', 'delete', 'update', 'createSavepoint',
     * 'releaseSavepoint', 'beginTransaction', 'rollback', 'commit', 'query' or 'prepare' is called."
     *
     * Database::startTransaction uses beginTransaction
     * @covers \OxidEsales\EshopCommunity\Core\Database\Adapter\Doctrine\Database::startTransaction
     */
    public function testMasterSlaveConnectionReadsFromMasterDuringTransaction(): void
    {
        $expectedSlaveResult = '100';

        $this->updateOnSlave($expectedSlaveResult);

        $actualSlaveResult = $this->readDirectlyFromSlave();
        $actualMasterResult = $this->readDirectlyFromMaster();

        /**
         * Start a transaction and read from the master-slave connection.
         * In this case the modifications on the slave should be invisible
         */
        $this->masterSlaveConnection->startTransaction();
        $actualMasterSlaveResult = $this->getOneFromMasterSlave();
        $this->masterSlaveConnection->commitTransaction();

        $this->assertSame(
            $expectedSlaveResult,
            $actualSlaveResult,
            "Slave connection retrieves modified value $expectedSlaveResult"
        );
        $this->assertSame(
            $this->expectedMasterResult,
            $actualMasterResult,
            "Master connection retrieves unmodified value $this->expectedMasterResult"
        );
        $this->assertSame(
            $this->expectedMasterResult,
            $actualMasterSlaveResult,
            'Master-Slave connection reads from master database after using startTransaction()'
        );
    }

    /**
     * Test Doctrine behavior:
     * "Master is picked when 'exec', 'executeUpdate', 'insert', 'delete', 'update', 'createSavepoint',
     * 'releaseSavepoint', 'beginTransaction', 'rollback', 'commit', 'query' or 'prepare' is called."
     *
     * Database::startTransaction uses beginTransaction
     * @covers \OxidEsales\EshopCommunity\Core\Database\Adapter\Doctrine\Database::commitTransaction
     */
    public function testMasterSlaveConnectionReadsFromMasterAfterCommitTransaction(): void
    {
        $expectedSlaveResult = '100';

        /** Start a transaction, do an update and commit it. */
        $this->masterSlaveConnection->startTransaction();
        $this->masterSlaveConnection->execute(
            "INSERT INTO `master_slave_table` (`column1`, `column2`) VALUES ('2', '3')"
        );
        $this->masterSlaveConnection->commitTransaction();

        $this->updateOnSlave($expectedSlaveResult);

        $actualSlaveResult = $this->readDirectlyFromSlave();
        $actualMasterResult = $this->readDirectlyFromMaster();

        /**
         * Read from the master-slave connection. In this case the modifications on the slave should be invisible
         */
        $actualMasterSlaveResult = $this->getOneFromMasterSlave();

        $this->assertSame(
            $expectedSlaveResult,
            $actualSlaveResult,
            "Slave connection retrieves modified value $expectedSlaveResult"
        );
        $this->assertSame(
            $this->expectedMasterResult,
            $actualMasterResult,
            "Master connection retrieves unmodified value $this->expectedMasterResult"
        );
        $this->assertSame(
            $this->expectedMasterResult,
            $actualMasterSlaveResult,
            'Master-Slave connection reads from master database after using commitTransaction()'
        );
    }

    /**
     * Test Doctrine behavior:
     * "Master is picked when 'exec', 'executeUpdate', 'insert', 'delete', 'update', 'createSavepoint',
     * 'releaseSavepoint', 'beginTransaction', 'rollback', 'commit', 'query' or 'prepare' is called."
     *
     * Database::execute uses executeUpdate
     *
     * In this test the slave database is use first for reading.
     * The and update is made and the master database should be used for writing
     *
     * @covers \OxidEsales\EshopCommunity\Core\Database\Adapter\Doctrine\Database::execute
     */
    public function testMasterSlaveConnectionWritesToMasterOnExecute(): void
    {
        $expectedSlaveResult = '100';
        $expectedMasterResultAfterExecute = '2';

        $this->updateOnSlave($expectedSlaveResult);

        $actualSlaveResult = $this->readDirectlyFromSlave();
        $actualMasterResult = $this->readDirectlyFromMaster();

        $resultSet = $this->masterSlaveConnection->select($this->selectColumnQuery);
        $actualMasterSlaveResult = $resultSet->fields['column1'];

        $this->masterSlaveConnection->execute(
            "INSERT INTO `master_slave_table` (`column1`, `column2`) VALUES ('$expectedMasterResultAfterExecute', '3')"
        );

        /** Read directly from master database */
        $resultSet = $this->queryMaster(
            'SELECT column1 FROM `master_slave_table` WHERE id = 2'
        );
        $row = $resultSet->fetch_assoc();
        $actualMasterResultAfterExecute = $row['column1'];

        $this->assertSame(
            $expectedSlaveResult,
            $actualSlaveResult,
            "Slave connection retrieves modified value $expectedSlaveResult"
        );
        $this->assertSame(
            $this->expectedMasterResult,
            $actualMasterResult,
            "Master connection retrieves unmodified value $expectedSlaveResult"
        );
        $this->assertSame(
            $expectedSlaveResult,
            $actualMasterSlaveResult,
            "Master-Slave connection retrieves modified value $expectedSlaveResult from slave database on select"
        );
        $this->assertSame(
            $expectedMasterResultAfterExecute,
            $actualMasterResultAfterExecute,
            "Master-Slave connection writes new value $this->expectedMasterResult to master database using execute"
        );
    }

    /**
     * @covers \OxidEsales\EshopCommunity\Core\Database\Adapter\Doctrine\Database::metaColumns
     */
    public function testMetaColumnsReadsFromMaster(): void
    {
        /** Alter the table schema on the slave bypassing master-slave connection  */
        $this->querySlave(
            'ALTER TABLE `master_slave_table` ADD COLUMN `columnSlave` INT NULL AFTER `column2`;'
        );

        /** Read column meta information for the table. This should happen on the master database */
        $expectedResult = $this->masterSlaveConnection->metaColumns('master_slave_table');

        /** Force pick the master connection and read column meta information again */
        $this->masterSlaveConnection->execute(
            "UPDATE `master_slave_table` SET `column1` = '100' WHERE id = 1"
        );

        $actualResultMaster = $this->masterSlaveConnection->metaColumns('master_slave_table');

        $this->assertEquals($expectedResult, $actualResultMaster, 'metaColumns retrieves data from master connection');
    }

    /**
     * Test, that the initial values on master and on slave is an empty string.
     * @covers \OxidEsales\EshopCommunity\Core\Database\Adapter\Doctrine\Database::addDriverOptions
     */
    public function testSqlModeInitialValueIsEmptyString(): void
    {
        $query = 'show variables like "sql_mode"';

        /**
         * sqlMode is set to an empty string during DatabaseProvider::getDb via Database::addDriverOptions,
         * so we expect the result of the query to be ''
         */
        $expectedResultSqlMode = '';

        /** At this moment the connection is still pointing to the slave database */
        $actualResultSlaveSqlMode = $this->masterSlaveConnection->getRow($query)['Value'];

        /** Force picking the master connection and execute the query again */
        $this->masterSlaveConnection->execute(
            'ALTER TABLE `master_slave_table`	ADD COLUMN `column3` INT NULL AFTER `column2`;'
        );
        $actualResultMasterSqlMode = $this->masterSlaveConnection->getRow($query)['Value'];

        $this->assertSame(
            $expectedResultSqlMode,
            $actualResultSlaveSqlMode,
            'The initial sql_mode on the slave is an empty string'
        );
        $this->assertSame(
            $expectedResultSqlMode,
            $actualResultMasterSqlMode,
            'The sql_mode on the master is an empty string'
        );
    }

    /**
     * After master connection was picked, Doctrine would never pick the slave connection. forceSlaveConnection() brings
     * us back to the slave for read accesses.
     *
     * @covers \OxidEsales\EshopEnterprise\Core\Database\Adapter\Doctrine\Database::forceSlaveConnection
     */
    public function testForceSlaveConnectionReadsFromSlaveAfterMasterWasPicked(): void
    {
        $expectedMasterResult = '10';
        $expectedSlaveResult = '100';

        /** A new value for column1 is set on the master slave connection. The master is picked and this value will be replicated */
        $this->masterSlaveConnection->execute(
            "UPDATE `master_slave_table` SET `column1` = '$expectedMasterResult' WHERE id = 1"
        );
        $this->waitTillSlaveIsReady(2, $expectedMasterResult);

        $actualMasterSlaveResultOnMaster = $this->getOneFromMasterSlave();

        $this->updateOnSlave($expectedSlaveResult);

        /** Force the slave connection to be picked */
        $this->masterSlaveConnection->forceSlaveConnection();
        $actualMasterSlaveResultOnSlave = $this->getOneFromMasterSlave();


        $this->assertSame(
            $expectedMasterResult,
            $actualMasterSlaveResultOnMaster,
            'Master-Slave connection reads from master database after update'
        );
        $this->assertSame(
            $expectedSlaveResult,
            $actualMasterSlaveResultOnSlave,
            'Master-Slave connection reads from slave database after forceSlaveConnection()'
        );
    }

    /**
     * After forceSlaveConnection() the slave connection is picked for read accesses. forceMasterConnection() brings us
     * back to the the master for read accesses.
     *
     * @covers \OxidEsales\EshopEnterprise\Core\Database\Adapter\Doctrine\Database::forceSlaveConnection
     * @covers \OxidEsales\EshopEnterprise\Core\Database\Adapter\Doctrine\Database::forceMasterConnection
     */
    public function testForceMasterConnectionReadsFromMasterAfterSlaveWasPicked(): void
    {
        $expectedSlaveResult = '100';

        $this->updateOnSlave($expectedSlaveResult);

        /** Force the slave connection to be picked */
        $this->masterSlaveConnection->forceSlaveConnection();
        $actualMasterSlaveResultOnSlave = $this->getOneFromMasterSlave();

        /** Force the master connection to be picked */
        $this->masterSlaveConnection->forceMasterConnection();
        $actualMasterSlaveResultOnMaster = $this->getOneFromMasterSlave();

        $this->assertSame(
            $expectedSlaveResult,
            $actualMasterSlaveResultOnSlave,
            'Master-Slave connection reads from slave database after forceSlaveConnection()'
        );
        $this->assertSame(
            $this->expectedMasterResult,
            $actualMasterSlaveResultOnMaster,
            'Master-Slave connection reads from master database after forceMasterConnection()'
        );
    }

    /**
     * Right after database bootstrap the slave connection should be picked.
     */
    public function testInitiallyTheSlaveConnectionIsPicked(): void
    {
        $expectedSlaveResult = '100';

        $this->updateOnSlave($expectedSlaveResult);

        $actualMasterSlaveResultOnSlave = $this->getOneFromMasterSlave();

        $this->assertSame(
            $expectedSlaveResult,
            $actualMasterSlaveResultOnSlave,
            'Master-Slave connection reads from slave database after database bootstrap'
        );
    }

    private function initConnections(): void
    {
        /** Set db property of Database instance to null to enforce a fresh connection after closing the connection */
        $this->setProtectedClassProperty(DatabaseProvider::getInstance(), 'db', null);

        $this->masterSlaveConnection = DatabaseProvider::getDb(DatabaseProvider::FETCH_MODE_ASSOC);
        $this->masterConnection = $this->getDatabaseConnection('dbHost');
        $this->slaveConnection = $this->getDatabaseConnection('aSlaveHosts');
    }

    private function closeConnections(): void
    {
        $this->masterSlaveConnection->closeConnection();
        $this->masterConnection->close();
        $this->slaveConnection->close();

        /** Set db property of Database instance to null to enforce a fresh connection after closing the connection */
        $this->setProtectedClassProperty(DatabaseProvider::getInstance(), 'db', null);
    }

    /**
     * @param $configKeyDatabaseHost
     * @return \mysqli
     */
    private function getDatabaseConnection(string $configKeyDatabaseHost): \mysqli
    {
        /** @var string $host Host name or IP address.
         * The config param for $configKeyDatabaseHost might be array in
         * case of a slave connection. In this case the first value of the array is chosen.
         */
        $host = \is_array($this->getConfigParam($configKeyDatabaseHost))
            ? $this->getConfigParam($configKeyDatabaseHost)[0]
            : $this->getConfigParam($configKeyDatabaseHost);

        $mysqli = new \mysqli(
            $host,
            $this->getConfigParam('dbUser'),
            $this->getConfigParam('dbPwd'),
            $this->getConfigParam('dbName'),
            $this->getConfigParam('dbPort') ?: 3306
        );
        if ($mysqli->connect_error) {
            $this->fail("Connect Error ($mysqli->connect_errno) $mysqli->connect_error");
        }
        return $mysqli;
    }

    private function createTestTable(): void
    {
        $query = <<<EOT
                CREATE TABLE `master_slave_table` (
                    `id` INT(11) NOT NULL AUTO_INCREMENT,
                    `column1` VARCHAR(64) NULL DEFAULT NULL,
                    `column2` VARCHAR(64) NULL DEFAULT NULL,
                    PRIMARY KEY (`id`)
                )
                COMMENT='Temporary table to test master slave behaviour'
                COLLATE='latin1_general_ci'
                ENGINE=InnoDB
                ;
EOT;
        $this->queryMaster($query);
    }

    private function dropTestTable(): void
    {
        $this->queryMaster('DROP TABLE IF EXISTS `master_slave_table`');
    }

    private function readDirectlyFromSlave()
    {
        $resultSet = $this->querySlave($this->selectColumnQuery);
        $row = $resultSet->fetch_assoc();
        return $row['column1'];
    }

    private function readDirectlyFromMaster()
    {
        $resultSet = $this->queryMaster($this->selectColumnQuery);
        $row = $resultSet->fetch_assoc();
        return $row['column1'];
    }

    private function populateTestTable(): void
    {
        $this->queryMaster(
            "INSERT INTO `master_slave_table` (`column1`, `column2`) VALUES ('$this->expectedMasterResult', '2')"
        );
        /** Pause to let replication take place */
        $this->waitTillSlaveIsReady(10, $this->expectedMasterResult);
    }

    /** @throws SkippedTestError */
    private function skipIfNotConfigured(): void
    {
        $slaveHosts = $this->getConfig()->getConfigParam('aSlaveHosts');
        $masterHostname = $this->getConfig()->getConfigParam('dbHost');
        if (!\is_array($slaveHosts) || count($slaveHosts) !== 1 || $masterHostname === $slaveHosts[0]) {
            $this->markTestSkipped(
                'This test needs a real master-slave setup with exactly one master and one slave in order to work'
            );
        }
    }

    /**
     * Modify record directly on the slave database, bypassing master-slave replication
     * @param $expectedSlaveResult
     */
    private function updateOnSlave($expectedSlaveResult): void
    {
        $this->querySlave(
            "UPDATE `master_slave_table` SET `column1` = '$expectedSlaveResult' WHERE id = 1"
        );
    }

    /**
     * @return false|string
     */
    private function getOneFromMasterSlave()
    {
        return $this->masterSlaveConnection->getOne($this->selectColumnQuery);
    }

    private function queryMaster(string $query)
    {
        return $this->getResultOrFail($this->masterConnection, $query);
    }

    private function querySlave(string $query)
    {
        return $this->getResultOrFail($this->slaveConnection, $query);
    }

    private function getResultOrFail(\mysqli $connection, string $query)
    {
        $mysqliFailFlag = false;
        $result = $connection->query($query);
        if ($result === $mysqliFailFlag) {
            $this->fail($connection->error);
        }
        return $result;
    }

    private function waitTillSlaveIsReady(int $timeout, string $checkVal): void
    {
        $row = false;
        for ($i = 1; $i <= $timeout; $i++) {
            $row = $this->slaveConnection->query($this->selectColumnQuery);
            if ($row && $row->fetch_object()->column1 === $checkVal) {
                return;
            }
            sleep(1);
        }
        if (!$row && $this->slaveConnection->error) {
            $this->fail($this->slaveConnection->error);
        } else {
            $this->fail("Slave replication didn't occur after $timeout s");
        }
    }
}
