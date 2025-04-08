<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Tests\Unit\Application\Controller\Admin;

use \OxidEsales\TestingLibrary\UnitTestCase;
use OxidEsales\Eshop\Core\Registry;
use \oxDb;
use \oxTestModules;
use \article_variant;

/**
 * Unit test class for ArticleMain.
 */
class ArticleVariant extends UnitTestCase
{
    /**
     * Initialize the fixture.
     *
     * @return null
     */
    protected function setUp(): void
    {
        parent::setUp();

        Registry::getUtils()->oxResetFileCache();

        $iShopId = $this->getConfig()->getShopId();
        $sTable = 'oxarticles';

        $sSql = "insert into `$sTable` (oxid, oxprice, oxshopid, oxtitle)
                 values ('_testArt', '12', '{$iShopId}', 'testTitle')";
        $this->addToDatabase($sSql, $sTable);
    }

    /**
     * Tear down the fixture.
     *
     * @return null
     */
    protected function tearDown(): void
    {
        \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute("delete from oxartextends where oxid = (select oxid from oxarticles where oxparentid = '_testArt')");
        \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute("delete from oxarticles2shop where oxmapobjectid in (select oxmapid from oxarticles where oxparentid = '_testArt')");
        \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute("delete from oxarticles where oxid = '_testArt' or oxparentid = '_testArt'");

        self::cleanUpTable('oxarticles');
        self::cleanUpTable('oxartextends');

        parent::tearDown();
    }

    /**
     * Test variant saving.
     *
     * FS#1778 FS#2366
     *
     * @return null
     */
    public function testSaveVariantForTestCases()
    {
        oxTestModules::addFunction('oxSuperCfg', 'getRights', '{return false;}');

        $this->setRequestParameter("oxid", '_testArt');

        $aParams = array(
            'oxarticles__oxvarselect' => 'testVar',
            'oxarticles__oxartnum'    => '_testVar',
            'oxarticles__oxprice'     => '12',
            'oxarticles__oxstock'     => '2',
        );

        $oView = new article_variant();
        $oView->savevariant('-1', $aParams);

        $iShopId = $this->getConfig()->getShopId();

        $sSql = "select count(*) from oxarticles2shop where oxshopid = ? and oxmapobjectid =
            (select oxmapid from oxarticles where oxid = '_testArt')";
        $this->assertEquals('1', \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->getOne($sSql, array($iShopId)));

        $sSql = "select count(*) from oxarticles2shop where oxshopid = ? and oxmapobjectid =
            (select oxmapid from oxarticles where oxparentid = '_testArt')";
        $this->assertEquals('1', \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->getOne($sSql, array($iShopId)));
    }
}
