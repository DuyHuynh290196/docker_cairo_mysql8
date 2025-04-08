<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Tests\Unit\Model;

/**
 * This is temporary test which purpose is for CI not to be red.
 */
class ArticleListTest extends \oxUnitTestCase
{
    /**
     * Tear down the fixture.
     *
     * @return null
     */
    protected function tearDown(): void
    {
        $myDB = $this->getDb();
        $myDB->execute('update oxactions set oxactive="1"');
        $myDB->execute('delete from oxaccessoire2article where oxarticlenid="_testArt" ');
        $myDB->execute('delete from oxorderarticles where oxid="_testId" or oxid="_testId2"');
        $myDB->execute('delete from oxrecommlists where oxid like "testlist%" ');
        $myDB->execute('delete from oxobject2list where oxlistid like "testlist%" ');

        $myDB->execute('delete from oxconfig where oxvarname="iTimeToUpdatePrices"');
        $myDB->execute('update oxarticles set oxupdatepricetime="0000-00-00 00:00:00"');

        $this->cleanUpTable('oxorder');
        $this->cleanUpTable('oxarticles');
        $this->cleanUpTable('oxfield2shop');

        parent::tearDown();
    }

    /**
     * Test load action articles ee.
     *
     * @return null
     */
    public function testLoadActionArticlesEE()
    {
        $oTest = $this->getProxyClass('oxArticleList');
        $oTest->loadActionArticles('oxstart');

        $this->assertEquals(2, count($oTest));
        $this->assertTrue($oTest['943ed656e21971fb2f1827facbba9bec'] instanceof \OxidEsales\EshopCommunity\Application\Model\Article);
        $this->assertTrue($oTest['943ed656e21971fb2f1827facbba9bec'] instanceof \OxidEsales\EshopEnterprise\Application\Model\Article);
        $this->assertTrue($oTest['1651'] instanceof \OxidEsales\EshopCommunity\Application\Model\Article);
        $this->assertTrue($oTest['1651'] instanceof \OxidEsales\EshopEnterprise\Application\Model\Article);

        $this->assertEquals(109, $oTest['943ed656e21971fb2f1827facbba9bec']->getPrice()->getBruttoPrice());
        $this->assertEquals("Bierbrauset PROSIT", $oTest['1651']->oxarticles__oxtitle->value);
    }

    /**
     * Test load category articles ee.
     *
     * @return null
     */
    public function testLoadCategoryArticles()
    {
        $sCatId = '30e44ab83b6e585c9.63147165';
        $iExptCount = 4;

        $oTest = $this->getProxyClass('oxArticleList');
        $sCount = $oTest->loadCategoryArticles($sCatId, null);

        $this->assertEquals($iExptCount, count($oTest));
        $this->assertEquals($iExptCount, $sCount);
        $this->assertEquals("Wanduhr SPIDER", $oTest[1354]->oxarticles__oxtitle->value);
        $this->assertEquals(29.9, $oTest[2000]->getPrice()->getBruttoPrice());
    }

    /**
     * Test load category articles with filters ee.
     *
     * FS#1970
     *
     * @return null
     */
    public function testLoadCategoryArticlesWithFiltersEE()
    {
        $sCatId = '30e44ab85808a1f05.26160932';
        $sAttrId = '8a142c3ee0edb75d4.80743302';
        $iExptCount = 4;
        $aSessionFilter = array($sCatId => array('0' => array($sAttrId => 'Zeiger')));

        $oTest = $this->getProxyClass('oxArticleList');
        $sCount = $oTest->loadCategoryArticles($sCatId, $aSessionFilter);

        $this->assertEquals($iExptCount, count($oTest));
        $this->assertEquals($iExptCount, $sCount);
        $this->assertEquals("Wanduhr SPIDER", $oTest[1354]->oxarticles__oxtitle->value);
        $this->assertEquals(29.9, $oTest[2000]->getPrice()->getBruttoPrice());
    }
}
