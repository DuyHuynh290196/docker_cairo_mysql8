<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Tests\Integration\Model;

use \OxidEsales\TestingLibrary\UnitTestCase;
use OxidEsales\Eshop\Core\Field;
use OxidEsales\Eshop\Core\DatabaseProvider;
use OxidEsales\Eshop\Application\Model\Category;

/**
 *
 * Category caching in cache backend cases:
 *
 * - key generation;
 * - select: first time from db, second from cache;
 * - update;
 * - delete: cache by ident should be empty;
 * - invalidation keys generation;
 *
 */
class CategoryTest extends UnitTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->setConfigParam( 'blCacheActive', true );
        $this->setConfigParam( 'sDefaultCacheConnector', 'oxFileCacheConnector' );
    }

    protected function tearDown(): void
    {
        $this->cleanUpTable('oxcategories');
        $cache = $this->getCacheBackend();
        $cache->flush();

        parent::tearDown();
    }

    /**
     * @return Category
     * @throws \Exception
     */
    private function createCategoryObject(): Category
    {
        $category = oxNew(Category::class);
        $category->setId('_testCategory');
        $category->oxcategories__oxparentid = new Field('oxrootid');
        $category->oxcategories__oxtitle  = new Field('testCategory');
        $category->save();

        return $category;
    }

    public function testGetCacheKey()
    {
        $category = $this->createCategoryObject();
        $this->assertEquals( 'oxCategory__testCategory_1_de', $category->getCacheKey('_testCategory') );
        $this->assertEquals( 'oxCategory__testCategory_1_de', $category->getCacheKey() );
    }

    public function testCategoryLoadFromCache()
    {
        $cache = $this->getCacheBackend();
        $category = $this->createCategoryObject();

        // cache empty
        $this->assertNull($cache->get('oxCategory__testCategory_1_de'));

        // load
        $category->load('_testCategory');
        // cache filed
        $this->assertNotNull($cache->get('oxCategory__testCategory_1_de'));

        // change in directly to db
        DatabaseProvider::getDb()->execute("UPDATE `oxcategories` SET `oxtitle` = 'testCategoryUpdated' WHERE `oxid` = '_testCategory'");

        //testing if it loaded from cache
        $category = oxNew(Category::class);
        $category->load('_testCategory');
        $this->assertEquals( 'testCategory', $category->oxcategories__oxtitle->value );
    }

    public function testCategoryLoadAlwaysFromDbInAdmin()
    {
        $cache = $this->getCacheBackend();
        $category = $this->createCategoryObject();

        // cache empty
        $this->assertNull($cache->get('oxCategory__testCategory_1_de'));

        // load
        $category->load('_testCategory');
        // cache filed
        $this->assertNotNull($cache->get('oxCategory__testCategory_1_de'));

        // change in directly to db
        DatabaseProvider::getDb()->execute("UPDATE `oxcategories` SET `oxtitle` = 'testCategoryUpdated' WHERE `oxid` = '_testCategory'");

        //testing if it loaded from cache
        $category = oxNew(Category::class);
        $category->setAdminMode(true);
        $category->load('_testCategory');
        $this->assertEquals('testCategoryUpdated', $category->oxcategories__oxtitle->value);
    }

    public function testLoadCorrectDataAfterDelete()
    {
        $cache = $this->getCacheBackend();
        $category = $this->createCategoryObject();

        // cache empty
        $this->assertNull($cache->get('oxCategory__testCategory_1_de'));

        // load
        $category->load('_testCategory');

        // cache filed
        $this->assertNotNull($cache->get('oxCategory__testCategory_1_de'));

        // delete
        $category->delete();

        // cache empty
        $this->assertNull($cache->get('oxCategory__testCategory_1_de'));

        //testing if it is deleted
        $category = oxNew(Category::class);
        $this->assertFalse($category->load('_testCategory'));
    }

    public function testLoadCorrectDataAfterUpdate()
    {
        $cache = $this->getCacheBackend();
        $category = $this->createCategoryObject();

        // cache empty
        $this->assertNull($cache->get('oxCategory__testCategory_1_de'));

        // load
        $category->load('_testCategory');

        // cache filed
        $this->assertNotNull($cache->get('oxCategory__testCategory_1_de'));

        // update
        $category->oxcategories__oxtitle = new Field('testCategoryUpdated');
        $category->save();

        // cache empty
        $this->assertNull($cache->get('oxCategory__testCategory_1_de'));

        //testing update
        $category = oxNew(Category::class);
        $category->load('_testCategory');
        $this->assertEquals( 'testCategoryUpdated', $category->oxcategories__oxtitle->value);
    }

    public function testLoadCorrectDataAfterUpdateInAdmin()
    {
        $cache = $this->getCacheBackend();
        $category = $this->createCategoryObject();

        // cache empty
        $this->assertNull($cache->get('oxCategory__testCategory_1_de'));

        // load
        $category->load('_testCategory');

        // cache filed
        $this->assertNotNull($cache->get('oxCategory__testCategory_1_de'));

        // update
        $category->setAdminMode(true);
        $category->oxcategories__oxtitle = new Field('testCategoryUpdated');
        $category->save();

        // cache empty
        $this->assertNull($cache->get('oxCategory__testCategory_1_de'));

        //testing update
        $category = oxNew(Category::class);
        $category->load('_testCategory');
        $this->assertEquals('testCategoryUpdated', $category->oxcategories__oxtitle->value);
    }

    public function testDoNotCreateCacheIfCategoryDoesNotExist(): void
    {
        $cache = $this->getCacheBackend();

        $this->assertNull($cache->get('oxCategory__testCategory_1_de'));

        $category = $this->createCategoryObject();
        $category->load('_testCategory');

        $this->assertNotNull($cache->get('oxCategory__testCategory_1_de'));

        $nonExistentCategory = oxNew(Category::class);
        $nonExistentCategory->load('nonExistent');

        $cacheKey = $nonExistentCategory->getCacheKey('nonExistent');

        $this->assertNull($cache->get($cacheKey));
    }
}
