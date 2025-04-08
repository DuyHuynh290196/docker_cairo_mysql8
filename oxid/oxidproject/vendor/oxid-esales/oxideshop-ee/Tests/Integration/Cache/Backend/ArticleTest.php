<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Tests\Integration\Cache\Backend;

use OxidEsales\Eshop\Application\Model\Article;
use OxidEsales\TestingLibrary\UnitTestCase;
use OxidEsales\Eshop\Core\Field;
use OxidEsales\Eshop\Core\DatabaseProvider;
use OxidEsales\Eshop\Application\Model\ArticleList;

/**
 * Article caching in cache backend cases:
 *
 * - key generation;
 * - select: first time from db, second from cache;
 * - update:
 *   - spec case on stock update;
 *   - parent updates on variant changes;
 *   - reviews and ratings;
 * - delete: cache by identifier should be empty;
 * - invalidation keys generation;
 *
 */
class ArticleTest extends UnitTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->setConfigParam('blCacheActive', true);
        $this->setConfigParam('sDefaultCacheConnector', 'oxFileCacheConnector');
    }

    protected function tearDown(): void
    {
        $this->cleanUpTable('oxarticles');
        $cache = $this->getCacheBackend();
        $cache->flush();

        parent::tearDown();
    }

    public function testGetCacheKey()
    {
        $article = $this->createArticle();
        $this->assertEquals('oxArticle__testArticle_1_de', $article->getCacheKey('_testArticle'));
        $this->assertEquals('oxArticle__testArticle_1_de', $article->getCacheKey());
    }

    public function testIfArticleIsLoadedFromCached()
    {
        $cache = $this->getCacheBackend();
        $article = $this->createArticle();

        // cache empty
        $this->assertNull($cache->get('oxArticle__testArticle_1_de'));

        // load
        $article->load('_testArticle');
        // cache filed
        $this->assertNotNull($cache->get('oxArticle__testArticle_1_de'));

        // change in directly to db
        DatabaseProvider::getDb()->execute("UPDATE `oxarticles` SET `oxtitle` = 'testArticleUpdated' WHERE `oxid` = '_testArticle'");

        //testing if it loaded from cache
        $article = oxNew(Article::class);
        $article->load('_testArticle');
        $this->assertEquals('testArticle', $article->oxarticles__oxtitle->value);
    }

    public function testIfArticleIsLoadedAlwaysFromDbInAdmin()
    {
        $cache = $this->getCacheBackend();
        $article = $this->createArticle();

        // cache empty
        $this->assertNull($cache->get('oxArticle__testArticle_1_de'));

        // load
        $article->load('_testArticle');
        // cache filed
        $this->assertNotNull($cache->get('oxArticle__testArticle_1_de'));

        // change in directly to db
        DatabaseProvider::getDb()->execute("UPDATE `oxarticles` SET `oxtitle` = 'testArticleUpdated' WHERE `oxid` = '_testArticle'");

        //testing if it loaded from cache
        $article = oxNew(Article::class);
        $article->setAdminMode(true);
        $article->load('_testArticle');
        $this->assertEquals('testArticleUpdated', $article->oxarticles__oxtitle->value);
    }

    public function testIfCorrectDataLoadedAfterDelete()
    {
        $cache = $this->getCacheBackend();
        $article = $this->createArticle();

        // cache empty
        $this->assertNull($cache->get('oxArticle__testArticle_1_de'));

        // load
        $article->load('_testArticle');

        // cache filed
        $this->assertNotNull($cache->get('oxArticle__testArticle_1_de'));

        // delete
        $article->delete();

        // cache empty
        $this->assertNull($cache->get('oxArticle__testArticle_1_de'));

        //testing if it is deleted
        $article = oxNew(Article::class);
        $this->assertFalse($article->load('_testArticle'));
    }

    public function testIfCorrectDataLoadedAfterUpdate()
    {
        $cache = $this->getCacheBackend();
        $article = $this->createArticle();

        // cache empty
        $this->assertNull($cache->get('oxArticle__testArticle_1_de'));

        // load
        $article->load('_testArticle');

        // cache filed
        $this->assertNotNull($cache->get('oxArticle__testArticle_1_de'));

        // update
        $article->oxarticles__oxtitle = new Field('testArticleUpdated');
        $article->save();

        // cache empty
        $this->assertNull($cache->get('oxArticle__testArticle_1_de'));

        //testing update
        $article = oxNew(Article::class);
        $article->load('_testArticle');
        $this->assertEquals('testArticleUpdated', $article->oxarticles__oxtitle->value);
    }

    public function testIfCorrectDataLoadedAfterUpdateVariant()
    {
        $cache = $this->getCacheBackend();
        $article = $this->createArticle();
        $variant = $this->createArticleVariant();

        // cache empty
        $this->assertNull($cache->get('oxArticle__testArticle_1_de'));
        $this->assertNull($cache->get('oxArticle__testVariantArticle_1_de'));

        $article->load('_testArticle');
        $article->load('_testVariantArticle');

        // cache filed
        $this->assertNotNull($cache->get('oxArticle__testArticle_1_de'));
        $this->assertNotNull($cache->get('oxArticle__testVariantArticle_1_de'));

        // update variant
        $variant->oxarticles__oxtitle = new Field('testVariantArticleUpdated');
        $variant->save();

        // cache empty
        $this->assertNull($cache->get('oxArticle__testArticle_1_de'));
        $this->assertNull($cache->get('oxArticle__testVariantArticle_1_de'));
    }

    public function testIfCorrectDataLoadedAfterUpdateRating()
    {
        $cache = $this->getCacheBackend();
        $article = $this->createArticle();

        // cache empty
        $this->assertNull($cache->get('oxArticle__testArticle_1_de'));

        // load
        $article->load('_testArticle');

        // cache filed
        $this->assertNotNull($cache->get('oxArticle__testArticle_1_de'));

        // update
        $article->addToRatingAverage(5);

        // cache empty
        $this->assertNull($cache->get('oxArticle__testArticle_1_de'));
    }

    public function testIfCorrectDataLoadedAfterUpdateStock()
    {
        $cache = $this->getCacheBackend();
        $article = $this->createArticle();

        // cache empty
        $this->assertNull($cache->get('oxArticle__testArticle_1_de'));

        // load
        $article->load('_testArticle');

        // cache filed
        $this->assertNotNull($cache->get('oxArticle__testArticle_1_de'));

        // update
        $article->reduceStock(5);

        // cache empty
        $this->assertNull($cache->get('oxArticle__testArticle_1_de'));
    }

    public function testIfCorrectDataLoadedAfterUpdatePrice()
    {
        $cache = $this->getCacheBackend();
        $article = $this->createArticle();

        // cache empty
        $this->assertNull($cache->get('oxArticle__testArticle_1_de'));

        // load
        $article->load('_testArticle');

        // cache filed
        $this->assertNotNull($cache->get('oxArticle__testArticle_1_de'));

        // change directly to db
        DatabaseProvider::getDb()->execute("UPDATE `oxarticles` SET `oxupdateprice` = '600', `oxupdatepricetime` = '2000-01-01 12:00:00' WHERE `oxid` = '_testArticle'");

        // Execute upcoming price update
        $list = oxNew(ArticleList::class);
        $list->updateUpcomingPrices(true);

        // cache empty
        $this->assertNull($cache->get('oxArticle__testArticle_1_de'));
    }

    public function testIfCorrectDataLoadedAfterUpdateInAdmin()
    {
        $cache = $this->getCacheBackend();
        $article = $this->createArticle();

        // cache empty
        $this->assertNull($cache->get('oxArticle__testArticle_1_de'));

        // load
        $article->load('_testArticle');

        // cache filed
        $this->assertNotNull($cache->get('oxArticle__testArticle_1_de'));

        // update
        $article->setAdminMode(true);
        $article->oxarticles__oxtitle = new Field('testArticleUpdated');
        $article->save();

        // cache empty
        $this->assertNull($cache->get('oxArticle__testArticle_1_de'));

        //testing update
        $article = oxNew(Article::class);
        $article->load('_testArticle');
        $this->assertEquals('testArticleUpdated', $article->oxarticles__oxtitle->value);
    }

    /**
     * @return Article
     */
    private function createArticle(): Article
    {
        $article = oxNew(Article::class);
        $article->setId('_testArticle');
        $article->oxarticles__oxprice = new Field(15.5);
        $article->oxarticles__oxshopid = new Field($this->getConfig()->getBaseShopId());
        $article->oxarticles__oxtitle = new Field("testArticle");
        $article->save();

        return $article;
    }

    /**
     * @return Article
     */
    private function createArticleVariant(): Article
    {
        $article = oxNew(Article::class);
        $article->setId('_testVariantArticle');
        $article->oxarticles__oxprice = new Field(15.5);
        $article->oxarticles__oxshopid = new Field($this->getConfig()->getBaseShopId());
        $article->oxarticles__oxtitle = new Field("testVariantArticle");
        $article->oxarticles__oxparentid = new Field('_testArticle');
        $article->save();

        return $article;
    }
}
