<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Tests\Integration\Model;

use \OxidEsales\TestingLibrary\UnitTestCase;
use OxidEsales\Eshop\Application\Model\CategoryList;
use OxidEsales\Eshop\Core\Field;
use OxidEsales\Eshop\Core\DatabaseProvider;
use OxidEsales\Eshop\Application\Model\Content;
use OxidEsales\Eshop\Application\Model\Category;

/**
 * Category tree regeneration test cases:
 * - after adding new category;
 * - after deleting category;
 * - after updating category data:
 *   - updating title etc.;
 *   - adding new article to category;
 *   - removing article from category;
 * - on category visibility change ( article stock goes to 0 );
 * - after adding removing content category;
 * - after updating content category;
 *
 */
class CategoryListTest extends UnitTestCase
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

    public function testCategoryTreeLoadAfterAddOrDeleteCategory()
    {
        $categoryTree = oxNew(CategoryList::class);
        $categoryTree->load();

        //checking count of loaded categories
        $categoryCount = $categoryTree->count();

        //adding new category
        $category = $this->createCategory('_testCategory');

        //checking count of loaded categories after adding new category
        $categoryTree->load();
        $this->assertEquals($categoryCount + 1, $categoryTree->count());

        $category->delete();
        //checking count of loaded categories after deleted category
        $categoryTree->load();
        $this->assertEquals($categoryCount, $categoryTree->count());
    }

    public function testCategoryTreeLoadAfterUpdateCategory()
    {
        //adding new category
        $category = $this->createCategory('_testCategory');

        //checking count of loaded categories after adding new category
        $categoryTree = oxNew(CategoryList::class);
        $categoryTree->buildTree(null);
        $this->assertEquals('testCategory', $categoryTree['_testCategory']->oxcategories__oxtitle->value);

        //updating category title
        $category->oxcategories__oxtitle  = new Field('testCategoryEdited');
        $category->save();

        $categoryTree->buildTree(null);
        $this->assertEquals('testCategoryEdited', $categoryTree['_testCategory']->oxcategories__oxtitle->value);
    }

    public function testCategoryTreeLoadAfterAddOrDeleteContentCategory()
    {
        $categoryTree = oxNew(CategoryList::class);

        //adding new category
        $this->createCategory('_testCategory');

        $categoryTree->buildTree(null);
        $this->assertEquals(0, count($categoryTree['_testCategory']->getContentCats()));

        //adding new content category
        $content = oxNew(Content::class);
        $content->setId('_testContent');
        $content->oxcontents__oxtype = new Field( 2 );
        $content->oxcontents__oxtitle = new Field( 'testContentCategory' );
        $content->oxcontents__oxactive = new Field( 1 );
        $content->oxcontents__oxcatid = new Field( '_testCategory' );
        $content->oxcontents__oxsnippet= new Field( 0 );
        $content->save();

        //checking count of loaded categories after adding new category
        $categoryTree->buildTree(null);
        $this->assertEquals(1, count($categoryTree['_testCategory']->getContentCats()));

        $content->delete();
        $categoryTree->buildTree(null);
        $this->assertEquals(0, count($categoryTree['_testCategory']->getContentCats()));

    }

    public function testCategoryTreeLoadAfterUpdateContentCategory()
    {
        $categoryTree = oxNew(CategoryList::class);

        //adding new category
        $this->createCategory('_testCategory');

        //adding new content category
        $content = oxNew(Content::class);
        $content->setId('_testContent');
        $content->oxcontents__oxtype = new Field(2);
        $content->oxcontents__oxtitle = new Field('testContentCategory');
        $content->oxcontents__oxactive = new Field(1);
        $content->oxcontents__oxcatid = new Field('_testCategory');
        $content->oxcontents__oxsnippet= new Field(0);
        $content->save();

        //checking count of loaded categories after adding new category
        $categoryTree->buildTree(null);
        $contCats = $categoryTree['_testCategory']->getContentCats();
        $this->assertEquals('testContentCategory', $contCats[0]->oxcontents__oxtitle->value);

        //update
        $content->oxcontents__oxtitle = new Field('testContentCategoryEdited');
        $content->save();

        $categoryTree->buildTree(null);
        $contCats = $categoryTree['_testCategory']->getContentCats();
        $this->assertEquals('testContentCategoryEdited', $contCats[0]->oxcontents__oxtitle->value);
    }

    public function testCategoryTreeLoadWithCacheBackendOn()
    {
        $categoryId = '_testCategory';
        $cache = $this->getCacheBackend();
        $this->createCategory($categoryId);

        // cache empty
        $this->assertNull($cache->get( 'oxCategoryTree_1_de' ));

        $categoryTree = oxNew(CategoryList::class);
        $categoryTree->load();

        // cache filed
        $this->assertNotNull($cache->get( 'oxCategoryTree_1_de' ));
        $this->assertTrue($categoryTree->offsetExists('_testCategory'));

        // change in directly to db
        DatabaseProvider::getDb()->execute("DELETE from `oxcategories` WHERE `oxid` = '_testCategory'");

        //testing if it loaded from cache
        $categoryTree = oxNew(CategoryList::class);
        $categoryTree->load();
        $this->assertTrue($categoryTree->offsetExists('_testCategory'));
    }

    public function testLoadCategoryTreeAlwaysFromDbInAdmin()
    {
        $categoryId = '_testCategory';
        $cache = $this->getCacheBackend();
        $this->createCategory($categoryId);

        // cache empty
        $this->assertNull($cache->get( 'oxCategoryTree_1_de' ));

        $categoryTree = oxNew(CategoryList::class);
        $categoryTree->load();

        // cache filed
        $this->assertNotNull($cache->get( 'oxCategoryTree_1_de' ));
        $this->assertTrue($categoryTree->offsetExists('_testCategory'));

        // change in directly to db
        DatabaseProvider::getDb()->execute("DELETE from `oxcategories` WHERE `oxid` = '_testCategory'");

        //testing if it loaded from cache
        $categoryTree = oxNew(CategoryList::class);
        $categoryTree->setAdminMode(true);
        $categoryTree->load();
        $this->assertFalse($categoryTree->offsetExists('_testCategory'));
    }

    /**
     * @param string $id
     * @return Category
     * @throws \Exception
     */
    private function createCategory(string $id): Category
    {
        $category = oxNew(Category::class);
        $category->setId($id);
        $category->oxcategories__oxactive = new Field(1);
        $category->oxcategories__oxparentid = new Field('oxrootid');
        $category->oxcategories__oxtitle  = new Field('testCategory');
        $category->save();
        return $category;
    }
}
