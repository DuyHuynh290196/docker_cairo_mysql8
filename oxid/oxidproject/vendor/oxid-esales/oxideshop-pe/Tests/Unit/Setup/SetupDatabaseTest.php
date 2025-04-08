<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\EshopProfessional\Tests\Unit\Setup;

use OxidEsales\Eshop\Core\Serial;
use OxidEsales\EshopCommunity\Setup\Utilities;
use OxidEsales\EshopProfessional\Setup\Database;
use OxidEsales\EshopProfessional\Setup\Setup;
use PHPUnit\Framework\TestCase;

final class SetupDatabaseTest extends TestCase
{
    private array $loggedQueries = [];

    protected function setUp(): void
    {
        parent::setUp();
        require_once getShopBasePath() . '/Setup/functions.php';
    }

    public function testWriteSerial(): void
    {
        $baseShopId = 1;
        $expectedQueries = [
            "update oxshops set oxserial = 'testSerial' where oxid = '$baseShopId'",
            "delete from oxconfig where oxvarname = 'aSerials'",
            "delete from oxconfig where oxvarname = 'sTagList'",
            "delete from oxconfig where oxvarname = 'IMD'",
            "delete from oxconfig where oxvarname = 'IMA'",
            "delete from oxconfig where oxvarname = 'IMS'",
        ];
        $setup = $this->createConfiguredMock(
            Setup::class,
            ['getShopId' => $baseShopId]
        );
        $serial = $this->createConfiguredMock(
            Serial::class,
            ['getMaxDays' => 1, 'getMaxArticles' => 1, 'getMaxShops' => 1]
        );
        $database = $this->createPartialMock(
            Database::class,
            ['getInstance', 'execSql', 'getConnection']
        );
        $database
            ->method('getInstance')
            ->withConsecutive(
                ['Utilities'],
                ['Setup']
            )
            ->willReturnOnConsecutiveCalls(
                new Utilities(),
                $setup
            );
        $database
            ->method('getConnection')
            ->willReturn($this->getPdoSpy());

        $database->writeSerial($serial, 'testSerial');

        $this->assertEquals($expectedQueries, $this->loggedQueries);
    }

    private function getPdoSpy(): \stdClass
    {
        $pdo = $this->getMockBuilder(\stdClass::class)->setMethods(['prepare', 'exec'])->getMock();
        $pdoPrepareStub = $this->getMockBuilder(\stdClass::class)->setMethods(['execute',])->getMock();
        $pdo
            ->method('prepare')
            ->willReturn(
                $pdoPrepareStub
            );
        $pdo
            ->method('exec')
            ->willReturnCallback(function ($query) {
                $this->loggedQueries[] = $query;
                return 1;
            });

        return $pdo;
    }
}
