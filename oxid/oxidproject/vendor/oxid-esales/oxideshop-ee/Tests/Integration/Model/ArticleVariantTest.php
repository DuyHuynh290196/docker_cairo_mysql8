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
class ArticleVariantTest extends \oxUnitTestCase
{
    /**
     * Test setup
     */
    public function setup(): void
    {
        parent::setUp();
        $table = 'oxarticles';
        $sql = "insert into `$table` (oxid, oxprice, oxshopid, oxtitle)
                     values ('_testArt', '12', '1', 'testTitle')";
        $this->addToDatabase($sql, $table);
        $sql = "insert into `$table` (oxid, oxprice, oxshopid, oxtitle, oxparentid)
                     values ('_testArtVariant1', '12', '1', 'testTitle', '_testArt')";
        $this->addToDatabase($sql, $table);
        $sql = "insert into `$table` (oxid, oxprice, oxshopid, oxtitle, oxparentid)
                     values ('_testArtVariant2', '120', '1', 'testTitle2', '_testArt')";
        $this->addToDatabase($sql, $table);
    }

    /**
     * Tries to call Article_Variant::savevariants() from subshop with one variant being change
     */
    public function testSaveVariants2VariantsWithOneChanged()
    {
        $subShop = oxNew(\OxidEsales\Eshop\Application\Model\Shop::class);
        $subShop->setEnableMultilang(true);
        $subShop->oxshops__oxisinherited = new \OxidEsales\Eshop\Core\Field(1);
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
        $article->load("_testArt");
        $article->assignToShop($subShopId);

        $articleVariant = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
        $articleVariant->load('_testArtVariant1');
        $articleVariant->assignToShop($subShopId);

        $articleVariant = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
        $articleVariant->load('_testArtVariant2');
        $articleVariant->assignToShop($subShopId);

        $config->setShopId($subShopId);

        $params['same'] = array('oxarticles__oxprice' => 12, 'oxarticles__oxactive' => 1);
        $params['changed'] = array('oxarticles__oxprice' => 125, 'oxarticles__oxactive' => 1);
        $this->setRequestParameter("editval", array("_testArtVariant1" => $params['same'], "_testArtVariant2" => $params['changed']));
        $this->setRequestParameter("oxid", '_testArt');

        $view = oxNew(\OxidEsales\Eshop\Application\Controller\Admin\ArticleVariant::class);
        $view->savevariants();

        $count = \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->getOne("select count(*) from oxfield2shop where oxartid = '_testArtVariant1'");
        $this->assertEquals(0, $count, "article variant shouldn't have record in field2shop as price was not changed");

        $config->setShopId(1);

        $articleVariant = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
        $articleVariant->load('_testArtVariant1');
        $this->assertEquals(12, $articleVariant->getPrice()->getPrice(), "price of inherited article variant should not change in parent shop");

        $articleVariant = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
        $articleVariant->load('_testArtVariant2');
        $this->assertEquals(120, $articleVariant->getPrice()->getPrice(), "price of inherited article variant should not change in parent shop");

        $config->setShopId($subShopId);

        $articleVariant = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
        $articleVariant->load('_testArtVariant1');
        $this->assertEquals(12, $articleVariant->getPrice()->getPrice(), "price of inherited article variant should not change in subshop shop");

        $articleVariant = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
        $articleVariant->load('_testArtVariant2');
        $this->assertEquals(125, $articleVariant->getPrice()->getPrice(), "price of inherited article variant is incorrect in subshop shop");
    }
}
