<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Tests\Unit\Core;

use OxidEsales\EshopEnterprise\Core\DbMetaDataHandler;
use \oxDb;
use \oxTestModules;

class DbMetaDataHandlerTest extends \oxUnitTestCase
{
    /**
     * Tear down the fixture.
     *
     * @return null
     */
    protected function tearDown(): void
    {
        $this->cleanUpTable('oxactions');

        //dropping test table
        \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute("DROP TABLE IF EXISTS `testDbMetaDataHandler`");

        parent::tearDown();
    }

    /*
     * Test table
     */
    protected function _createTestTable() // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $sSql = " CREATE TABLE `testDbMetaDataHandler` (
                    `OXID` char(32) NOT NULL,
                    `OXTITLE` varchar(255) NOT NULL,
                    `OXTITLE_1` varchar(255) NOT NULL,
                    `OXLONGDESC` text NOT NULL,
                    `OXLONGDESC_1` text NOT NULL,
                     PRIMARY KEY (`OXID`),
                     KEY `OXTITLE` (`OXTITLE`),
                     KEY `OXTITLE_1` (`OXTITLE_1`),
                     FULLTEXT KEY `OXLONGDESC` (`OXLONGDESC`),
                     FULLTEXT KEY `OXLONGDESC_1` (`OXLONGDESC_1`)
                  ) ENGINE = MyISAM";

        \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute($sSql);
    }

    /**
     * Test getting all db tables list (except views)
     */
    public function testGetAllTables()
    {
        $aTables = \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->getAll("show tables");

        $aTablesList = array();
        foreach ($aTables as $aTableInfo) {
            if (strpos($aTableInfo[0], "oxv_") !== 0) {
                $aTablesList[] = $aTableInfo[0];
            }
        }

        /** @var DbMetaDataHandler $oDbMeta */
        $oDbMeta = oxNew(\OxidEsales\Eshop\Core\DbMetaDataHandler::class);
        $this->assertTrue(count($aTablesList) > 1);
        $this->assertEquals($aTablesList, $oDbMeta->getAllTables());
    }

    /**
     * Test if method call another method which creates sql's with correct params
     */
    public function testAddNewLangToDb()
    {
        $aTables = \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->getAll("show tables");

        $aTablesList = array();
        foreach ($aTables as $aTableInfo) {
            if (strpos($aTableInfo[0], "oxv_") !== 0) {
                $aTablesList[] = $aTableInfo[0];
            }
        }

        /** @var DbMetaDataHandler|\PHPUnit\Framework\MockObject\MockObject $oDbMeta */
        $oDbMeta = $this->getMock('\OxidEsales\EshopEnterprise\Core\DbMetaDataHandler', array('addNewMultilangField'));

        $iIndex = 0;
        foreach ($aTablesList as $sTableName) {
            $oDbMeta->expects($this->at($iIndex++))->method('addNewMultilangField')->with($this->equalTo($sTableName));
        }

        $oDbMeta->addNewLangToDb();
    }

    /*
     * Test if method call views updater
     */
    public function testAddNewLangToDb_upatingViews()
    {
        /** @var DbMetaDataHandler|\PHPUnit\Framework\MockObject\MockObject $oDbMeta */
        $oDbMeta = $this->getMock('\OxidEsales\EshopEnterprise\Core\DbMetaDataHandler', array('addNewMultilangField', 'updateViews'));
        $oDbMeta->expects($this->any())->method('addNewMultilangField');
        $oDbMeta->expects($this->once())->method('updateViews');

        $oDbMeta->addNewLangToDb();
    }

    public function testUpdateViews()
    {
        // saving parameters
        oxTestModules::addFunction("oxshop", "generateViews", "{ \$oConfig = \$this->getConfig(); \$oConfig->setConfigParam( 'testUpdateViewsDebugData', array( \$aA[0], \$aA[1] ) ); return true; }");
        $myConfig = $this->getConfig();

        /** @var DbMetaDataHandler $oDbMeta */
        $oDbMeta = oxNew(\OxidEsales\Eshop\Core\DbMetaDataHandler::class);
        $this->assertTrue($oDbMeta->updateViews());

        $aExpTestData = array(false, array_fill_keys($myConfig->getConfigParam('aMultiShopTables'), false));

        $aTestData = $myConfig->getConfigParam('testUpdateViewsDebugData');
        $this->assertNotNull($aTestData);
        $this->assertEquals($aExpTestData, $aTestData);
    }

    public function testUpdateViewsForOxarticleTable()
    {
        // saving parameters
        oxTestModules::addFunction("oxshop", "generateViews", "{ \$oConfig = \$this->getConfig(); \$oConfig->setConfigParam( 'testUpdateViewsDebugData', array( \$aA[0], \$aA[1] ) ); return true; }");
        $myConfig = $this->getConfig();

        /** @var DbMetaDataHandler $oDbMeta */
        $oDbMeta = oxNew(\OxidEsales\Eshop\Core\DbMetaDataHandler::class);
        $this->assertTrue($oDbMeta->updateViews(array("oxarticles")));

        $aExpTestData = array(false, array("oxarticles" => false));

        $aTestData = $myConfig->getConfigParam('testUpdateViewsDebugData');
        $this->assertNotNull($aTestData);
        $this->assertEquals($aExpTestData, $aTestData);
    }
}
