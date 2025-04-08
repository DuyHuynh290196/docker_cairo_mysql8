<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Tests\Integration\Model;

use oxDb;
use oxField;
use OxidEsales\Eshop\Core\Registry;


/**
 * oxArticle integration test
 */
class ArticleTest extends \oxUnitTestCase
{
    /**
     * Test setup
     */
    public function setup(): void
    {
        parent::setUp();

        $id = $this->getShopId();
        $this->addToDatabase("Insert into oxarticles (oxid, oxshopid, oxtitle, oxprice) values ('_testid', '{$id}', '_testArticle', '125')", 'oxarticles', array($id, 2, 3, 4, 5));
        $this->addToDatabase("Insert into oxarticles (oxid, oxparentid, oxshopid, oxtitle, oxprice) values ('_testidvariant', '_testid', '{$id}', '_testArticleVariant', '1250')", 'oxarticles');
        $this->addTableForCleanup('oxarticles');
    }

    /**
     * Integration test that checks if after deleting article, maps are deleted from subshops
     */
    public function testDeleteFromMaps()
    {
        $article = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
        $article->load('_testid');

        $mapId = \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->getOne("select oxmapid from oxarticles where oxid = '_testid'");
        $count = (int) \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->getOne("select count(*) from oxarticles2shop where oxmapobjectid={$mapId}");
        $this->assertEquals(5, $count);
        $this->assertEquals('_testid', $article->getId());

        $article->delete('_testid');
        $count = (int) \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->getOne("select count(*) from oxarticles2shop where oxmapobjectid={$mapId}");
        $this->assertEquals(0, $count);

    }

    /**
     * Check whether assigning article to subshop and changing it's price there and then unassigning it
     * leaves oxfield2shop value
     *
     * Test for bug #5461
     */
    public function testInheritedArticlePriceChangeAndUnassignAfterFromShop()
    {
        $subShop = oxNew(\OxidEsales\Eshop\Application\Model\Shop::class);
        $subShop->setEnableMultilang(true);
        $subShop->oxshops__oxisinherited = new \OxidEsales\Eshop\Core\Field(0);
        $subShop->oxshops__oxparentid = new \OxidEsales\Eshop\Core\Field(1);
        $subShop->save();

        $subShopId = $subShop->getId();

        $config = Registry::getConfig();
        $langParams = $config->getConfigParam('aLanguageParams');
        $languages = $config->getConfigParam('aLanguages');
        $config->saveShopConfVar('aarr', 'aLanguageParams', $langParams, $subShopId);
        $config->saveShopConfVar('aarr', 'aLanguages', $languages, $subShopId);
        $subShop->generateViews();

        $article = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
        $article->load("_testid");
        $article->assignToShop($subShopId);

        $config->setShopId($subShopId);

        $article = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
        $article->load("_testid");
        $article->oxarticles__oxprice = new \OxidEsales\Eshop\Core\Field(250);
        $article->save();

        $config->setShopId(1);

        $article = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
        $article->load("_testid");
        $this->assertEquals(125, $article->getPrice()->getPrice(), "price of article is incorrct in parent shop");

        $config->setShopId($subShopId);

        $article = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
        $article->load("_testid");
        $this->assertEquals(250, $article->getPrice()->getPrice(), "price of article is incorrect in subshop");

        $article->unassignFromShop(array($subShopId));
        $count = \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->getOne("SELECT count(*) FROM `oxfield2shop` WHERE `oxartid` = '_testid' AND `oxshopid` = {$subShopId}");
        $this->assertEquals(0, $count, 'after unassigning article from subshop, oxfield2shop values remain');

        $config->setShopId(1);

        $article = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
        $article->load("_testid");
        $this->assertEquals(125, $article->getPrice()->getPrice(), "price of article should not change in parent shop");

        $article->assignToShop($subShopId);
        $count = \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->getOne("SELECT count(*) FROM `oxfield2shop` WHERE `oxartid` = '_testid' AND `oxshopid` = {$subShopId}");
        $this->assertEquals(0, $count, 'oxfield2shop article values should not be present after the first unassignment');

        $config->setShopId($subShopId);

        $article = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
        $article->load("_testid");
        $this->assertEquals(125, $article->getPrice()->getPrice(), "price of article should not change in subshop");
    }

    /**
     * Check whether assigning article to subshop and changing it's variant price there and then unassigning it
     * leaves oxfield2shop value
     */
    public function testInheritedArticleVariantPriceChangeAndUnassignParentAfterFromShop()
    {
        $subShop = oxNew(\OxidEsales\Eshop\Application\Model\Shop::class);
        $subShop->setEnableMultilang(true);
        $subShop->oxshops__oxisinherited = new \OxidEsales\Eshop\Core\Field(0);
        $subShop->oxshops__oxparentid = new \OxidEsales\Eshop\Core\Field(1);
        $subShop->save();

        $subShopId = $subShop->getId();

        $config = Registry::getConfig();
        $langParams = $config->getConfigParam('aLanguageParams');
        $languages = $config->getConfigParam('aLanguages');
        $config->saveShopConfVar('aarr', 'aLanguageParams', $langParams, $subShopId);
        $config->saveShopConfVar('aarr', 'aLanguages', $languages, $subShopId);
        $subShop->generateViews();

        $article = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
        $article->load("_testid");
        $article->assignToShop($subShopId);

        $articleVariant = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
        $articleVariant->load('_testidvariant');
        $articleVariant->assignToShop($subShopId);

        $config->setShopId($subShopId);

        $articleVariant = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
        $articleVariant->load("_testidvariant");
        $articleVariant->oxarticles__oxprice = new \OxidEsales\Eshop\Core\Field(250);
        $articleVariant->save();

        $config->setShopId(1);

        $article = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
        $article->load("_testid");
        $this->assertEquals(125, $article->getPrice()->getPrice(), "price of article is incorrct in parent shop");

        $articleVariant = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
        $articleVariant->load("_testidvariant");
        $this->assertEquals(1250, $articleVariant->getPrice()->getPrice(), "price of article variant is incorrct in parent shop");

        $config->setShopId($subShopId);

        $article = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
        $article->load("_testid");
        $this->assertEquals(125, $article->getPrice()->getPrice(), "price of article is incorrct in subshop");

        $articleVariant = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
        $articleVariant->load("_testidvariant");
        $this->assertEquals(250, $articleVariant->getPrice()->getPrice(), "price of article variant is incorrct in subshop");

        $article->unassignFromShop(array($subShopId));
        $count = \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->getOne("SELECT count(*) FROM `oxfield2shop` WHERE `oxartid` = '_testidvariant' AND `oxshopid` = {$subShopId}");
        $this->assertEquals(0, $count, 'after unassigning article from subshop, article varnati oxfield2shop values remain');

        $config->setShopId(1);

        $article = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
        $article->load("_testid");
        $this->assertEquals(125, $article->getPrice()->getPrice(), "price of article is incorrct in parent shop");

        $articleVariant = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
        $articleVariant->load("_testidvariant");
        $this->assertEquals(1250, $articleVariant->getPrice()->getPrice(), "price of article variant is incorrct in parent shop");

        $article->assignToShop($subShopId);
        $count = \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->getOne("SELECT count(*) FROM `oxfield2shop` WHERE `oxartid` = '_testidvariant' AND `oxshopid` = {$subShopId}");
        $this->assertEquals(0, $count, 'oxfield2shop article variant values should not be present after the first unassignment');

        $article = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
        $article->load("_testid");
        $this->assertEquals(125, $article->getPrice()->getPrice(), "price of article is incorrct in subshop");

        $articleVariant = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
        $articleVariant->load("_testidvariant");
        $this->assertEquals(1250, $articleVariant->getPrice()->getPrice(), "price of article variant is incorrct in subshop");
    }
}
