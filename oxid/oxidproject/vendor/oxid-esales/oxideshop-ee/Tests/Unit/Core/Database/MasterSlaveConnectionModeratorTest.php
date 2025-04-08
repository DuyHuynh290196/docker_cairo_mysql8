<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Tests\Unit\Core\Database;

use OxidEsales\TestingLibrary\UnitTestCase;

use OxidEsales\Eshop\Core\Serial;
use OxidEsales\Eshop\Core\Database\Adapter\Doctrine\Database;
use OxidEsales\EshopEnterprise\Core\Database\MasterSlaveConnectionModerator;

class MasterSlaveConnectionModeratorTest extends UnitTestCase
{
    public function testModeratorForcesMasterConnectionIfIsMasterSlaveConnectionButLicenseIsWrong()
    {
        $serial = $this->getSerialMock();
        $serial
            ->method('isMasterSlaveLicenseValid')
            ->willReturn(false);

        $database = $this->getDatabaseMockWithMasterSlaveConnection();
        $database
            ->expects($this->once())
            ->method('forceMasterConnection');

        $moderator = oxNew(MasterSlaveConnectionModerator::class,
            $serial,
            $database
        );
        $moderator->moderate();
    }

    public function testModeratorDoesNotForceMasterConnectionIfIsMasterSlaveConnectionAndLicenseIsValid()
    {
        $serial = $this->getSerialMock();
        $serial
            ->method('isMasterSlaveLicenseValid')
            ->willReturn(true);

        $database = $this->getDatabaseMockWithMasterSlaveConnection();
        $database
            ->expects($this->never())
            ->method('forceMasterConnection');

        $moderator = oxNew(MasterSlaveConnectionModerator::class,
            $serial,
            $database
        );
        $moderator->moderate();
    }

    private function getDatabaseMockWithMasterSlaveConnection()
    {
        $database = $this
            ->getMockBuilder(Database::class)
            ->getMock();

        $database
            ->method('isMasterSlaveConnection')
            ->willReturn(true);

        return $database;
    }

    private function getSerialMock()
    {
        return $this
            ->getMockBuilder(Serial::class)
            ->getMock();
    }
}
