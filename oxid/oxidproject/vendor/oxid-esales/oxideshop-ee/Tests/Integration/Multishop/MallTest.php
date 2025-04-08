<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Tests\Integration\Multishop;

use oxDb;
use oxField;
use OxidEsales\Eshop\Core\Registry;

/**
 * oxShopRelations integration test
 */
class MallTest extends MultishopTestCase
{
    /**
     * @var array
     */
    protected $fixtureTemplate = array(
        'shops'           => array(),
        'articles'        => array(),
        'categories'      => array(),
        'object2category' => array(),
    );

    /**
     * Test case directory array
     *
     * @var array
     */
    protected $testCaseDir = array(
        'TestCases/Mall',
    );

    private $categoryId1 = '_testCat1';
    private $subcategoryId1 = '_testCat2';
    private $subcategoryId3 = '_testCat3';
    private $categoryId2 = '_testCat6';

    private $articleId1 = '_testArticle1';

    private $variantId1 = '_testVariant1';

    /**
     * Data fixture and expected results.
     *
     * @return array
     */
    public function dpData()
    {
        return $this->_getTestCases($this->testCaseDir);
    }

    /**
     * Sets up the fixture.
     *
     * @param array $testCase Test cases with expected results.
     */
    protected function _setupFixture($testCase) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        parent::_setupFixture($testCase);

        foreach ($testCase['articles'] as $data) {
            $this->_createArticle($data);
        }

        foreach ($testCase['categories'] as $data) {
            $this->_createCategory($data);
        }

        foreach ($testCase['object2category'] as $data) {
            $this->_createObject2Category($data);
        }

        $this->_updateViews();
    }

    /**
     * Tears down the fixture.
     */
    protected function tearDown(): void
    {
        $this->_deleteFixture();
        parent::tearDown();
    }

    /**
     * Testing case:
     * 1. When parent category is assigned to subshop, subcategories are assigned to subshop too.
     *
     * @dataProvider dpData
     */
    public function testSubcategoryShopAssignment($testCase)
    {
        $this->_setupFixture($testCase);

        $category = oxNew(\OxidEsales\Eshop\Application\Model\Category::class);
        $category->load($this->categoryId1);

        $shopRelations = oxNew(\OxidEsales\Eshop\Core\Element2ShopRelations::class, 'oxcategories');
        $shopRelations->setShopIds(2);

        $this->assertFalse($shopRelations->isInShop($this->categoryId1));
        $this->assertFalse($shopRelations->isInShop($this->subcategoryId1));

        $category->assignToShop(2);

        $this->assertTrue($shopRelations->isInShop($this->categoryId1));
        $this->assertTrue($shopRelations->isInShop($this->subcategoryId1));
    }

    /**
     * Testing case:
     * 2. When parent category is unassigned from subshop, all subcategories are unassigned too.
     *
     * @dataProvider dpData
     */
    public function testSubcategoryShopUnassignment($testCase)
    {
        $this->_setupFixture($testCase);

        $category = oxNew(\OxidEsales\Eshop\Application\Model\Category::class);
        $category->load($this->categoryId1);

        $shopRelations = oxNew(\OxidEsales\Eshop\Core\Element2ShopRelations::class, 'oxcategories');
        $shopRelations->setShopIds(2);

        $this->assertFalse($shopRelations->isInShop($this->categoryId1));
        $this->assertFalse($shopRelations->isInShop($this->subcategoryId1));

        $category->assignToShop(2);

        $this->assertTrue($shopRelations->isInShop($this->categoryId1));
        $this->assertTrue($shopRelations->isInShop($this->subcategoryId1));

        $category->unassignFromShop(2);

        $this->assertFalse($shopRelations->isInShop($this->categoryId1));
        $this->assertFalse($shopRelations->isInShop($this->subcategoryId1));
    }

    /**
     * Testing case:
     * 3. Deleted subcategory from parent shop is deleted from all subshops.
     *
     * @dataProvider dpData
     */
    public function testSubcategoryAssignmentDeletedFromAllShops($testCase)
    {
        $this->_setupFixture($testCase);

        $category = oxNew(\OxidEsales\Eshop\Application\Model\Category::class);
        $category->load($this->categoryId1);

        $subCategory = oxNew(\OxidEsales\Eshop\Application\Model\Category::class);
        $subCategory->load($this->subcategoryId3);

        $shopRelations = oxNew(\OxidEsales\Eshop\Core\Element2ShopRelations::class, 'oxcategories');
        $shopRelations->setShopIds(2);

        $this->assertFalse($shopRelations->isInShop($this->categoryId1));
        $this->assertFalse($shopRelations->isInShop($this->subcategoryId3));

        $category->assignToShop(2);

        $this->assertTrue($shopRelations->isInShop($this->categoryId1));
        $this->assertTrue($shopRelations->isInShop($this->subcategoryId3));

        $subCategory->delete();

        $this->assertTrue($shopRelations->isInShop($this->categoryId1));
        $this->assertFalse($shopRelations->isInShop($this->subcategoryId3));
    }

    /**
     * Testing case:
     * 4. Deleted parent category from subshop is deleted from this and all its subshops.
     *
     * @dataProvider dpData
     */
    public function testCategoryAssignmentDeletedFromAllShops($testCase)
    {
        $this->_setupFixture($testCase);

        $category = oxNew(\OxidEsales\Eshop\Application\Model\Category::class);
        $category->load($this->categoryId2);

        $shopRelations = oxNew(\OxidEsales\Eshop\Core\Element2ShopRelations::class, 'oxcategories');
        $shopRelations->setShopIds(2);

        $this->assertFalse($shopRelations->isInShop($this->categoryId2));

        $category->assignToShop(2);

        $this->assertTrue($shopRelations->isInShop($this->categoryId2));

        $category->delete();

        $this->assertFalse($shopRelations->isInShop($this->categoryId2));
    }

    /**
     * Testing case:
     * When new category is assigned to product in parent shop, this assignment should be in subshop too.
     *
     * @dataProvider dpData
     */
    public function testNewCategoryAssignmentToProduct($testCase)
    {
        $this->_setupFixture($testCase);

        $category = oxNew(\OxidEsales\Eshop\Application\Model\Category::class);
        $category->load($this->categoryId2);

        $shopRelations = oxNew(\OxidEsales\Eshop\Core\Element2ShopRelations::class, 'oxcategories');
        $shopRelations->setShopIds(2);

        $category->assignToShop(2);

        $this->assertTrue($shopRelations->isInShop($this->categoryId2));
        $this->assertEquals(
            0,
            \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->getOne("select count(*) from oxobject2category where oxcatnid = '{$this->categoryId2}' ")
        );
        $object2Category = oxNew(\OxidEsales\Eshop\Application\Model\Object2Category::class);
        $object2Category->oxobject2category__oxshopid = new \OxidEsales\Eshop\Core\Field(1);
        $object2Category->oxobject2category__oxobjectid = new \OxidEsales\Eshop\Core\Field($this->articleId1);
        $object2Category->oxobject2category__oxcatnid = new \OxidEsales\Eshop\Core\Field($this->categoryId2);
        $object2Category->save();
        $this->assertGreaterThan(
            1,
            \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->getOne("select count(*) from oxobject2category where oxcatnid = '{$this->categoryId2}' ")
        );
    }

    /**
     * Testing case:
     * 6. When product is deleted from main shop: remove all relating data for this product is removed from
     * oxobject2category for main shop and all subshops.
     *
     * @dataProvider dpData
     */
    public function testProductRemovedWithCategoryRelations($testCase)
    {
        $this->_setupFixture($testCase);

        $article = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
        $article->load($this->articleId1);

        //pretest if relation exists
        $this->assertGreaterThan(
            1,
            \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->getOne("select count(*) from oxobject2category where oxobjectid = '{$this->articleId1}' ")
        );
        $article->delete();

        //relation is removed
        $this->assertEquals(
            0,
            \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->getOne("select count(*) from oxobject2category where oxobjectid = '{$this->articleId1}' ")
        );
    }

    /**
     * Testing case:
     * 13. If product is inherited to subshop and option "confbools[blMallCustomPrice]" is enabled,
     * then it allows to change prices for product and changed prices are added to oxfield2shop.
     *
     * @dataProvider dpData
     */
    public function testProductCustomSubshopPrices($testCase)
    {
        $this->_setupFixture($testCase);

        $article = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
        $article->load($this->articleId1);
        $article->oxarticles__oxprice = new \OxidEsales\Eshop\Core\Field(10);
        $article->save();

        $config = Registry::getConfig();
        $config->saveShopConfVar("bool", "blMallCustomPrice", "true", 2);

        $config->setShopId(2);
        $article->oxarticles__oxprice = new \OxidEsales\Eshop\Core\Field(20);
        $article->save();

        //load it again and test for main shop
        $config->setShopId(1);
        $article2 = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
        $article2->load($this->articleId1);
        $this->assertEquals(10, $article2->oxarticles__oxprice->value);

        //load product again and test for subshop
        $article3 = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
        $config->setShopId(2);
        $article3->load($this->articleId1);
        $this->assertEquals(20, $article3->oxarticles__oxprice->value);

        //check if entry exists
        $this->assertEquals(
            1,
            \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->getOne(
                "select count(*) from oxfield2shop where oxshopid = 2 and  oxartid = '{$this->articleId1}' "
            )
        );
    }

    /**
     * Testing case:
     * 14. If product is inherited to subshop and option "confbools[blMallCustomPrice]" is enabled and product is
     * unassigned/deleted from subshop, then entries are removed from oxfield2shop.
     *
     * @dataProvider dpData
     */
    public function testProductDeletedCustomSubshopPrices($testCase)
    {
        $this->_setupFixture($testCase);

        $article = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
        $article->load($this->articleId1);
        $article->oxarticles__oxprice = new \OxidEsales\Eshop\Core\Field(10);
        $article->save();

        $config = Registry::getConfig();
        $config->saveShopConfVar("bool", "blMallCustomPrice", "true", 2);
        $config->setShopId(2);
        $article->oxarticles__oxprice = new \OxidEsales\Eshop\Core\Field(20);
        $article->save();

        //pretest if subshop specific entry exists
        $this->assertEquals(
            1,
            \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->getOne(
                "select count(*) from oxfield2shop where oxshopid = 2 and  oxartid = '{$this->articleId1}' "
            )
        );

        $article->delete();

        //test if entries are removed
        $this->assertEquals(
            0,
            \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->getOne(
                "select count(*) from oxfield2shop where oxshopid = 2 and  oxartid = '{$this->articleId1}' "
            )
        );
        $this->assertEquals(
            0,
            \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->getOne(
                "select count(*) from oxfield2shop where oxshopid = 3 and  oxartid = '{$this->articleId1}' "
            )
        );
    }

    /**
     * Testing case:
     * If product is inherited to subshop and this subshop is deleted,
     * then entries are removed from oxarticles2shop too.
     *
     * @dataProvider dpData
     */
    public function testShopDeletedRelatedInheritanceDataIsRemovedToo($testCase)
    {
        $this->_setupFixture($testCase);

        $shopId = 4;

        //pretest if subshop specific entry exists
        $this->assertEquals(
            1,
            \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->getOne(
                "select count(*) from oxarticles2shop as t2s inner join oxarticles as a on a.oxmapid = t2s.oxmapobjectid where t2s.oxshopid = '{$shopId}' and a.OXID = '{$this->articleId1}' "
            )
        );

        $shop = oxNew(\OxidEsales\Eshop\Application\Model\Shop::class);
        $shop->delete($shopId);

        //test if entries are removed
        $this->assertEquals(
            0,
            \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->getOne(
                "select count(*) from oxarticles2shop as t2s inner join oxarticles as a on a.oxmapid = t2s.oxmapobjectid where t2s.oxshopid = '{$shopId}' and a.OXID = '{$this->articleId1}' "
            )
        );
    }

    /**
     * Testing case:
     * You can assign variants to subshop, if even the parent is not.
     *
     * @dataProvider dpData
     */
    public function testVariantAssignmentToSubShops($testCase)
    {
        $this->_setupFixture($testCase);

        $variant = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
        $variant->load($this->variantId1);

        $shopRelations = oxNew(\OxidEsales\Eshop\Core\Element2ShopRelations::class, 'oxarticles');
        $shopRelations->setShopIds(5);

        $this->assertFalse($shopRelations->isInShop($this->articleId1));
        $this->assertFalse($shopRelations->isInShop($this->variantId1));

        $variant->assignToShop(5);

        $this->assertFalse($shopRelations->isInShop($this->articleId1));
        $this->assertTrue($shopRelations->isInShop($this->variantId1));
    }

    /**
     * Testing case:
     * You can assign variants to subshop, if even the parent is not.
     *
     * @dataProvider dpData
     */
    public function testVariantUnassignmentFromSubShops($testCase)
    {
        $this->_setupFixture($testCase);

        $variant = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
        $variant->load($this->variantId1);

        $shopRelations = oxNew(\OxidEsales\Eshop\Core\Element2ShopRelations::class, 'oxarticles');
        $shopRelations->setShopIds(2);

        $this->assertTrue($shopRelations->isInShop($this->articleId1));
        $this->assertTrue($shopRelations->isInShop($this->variantId1));

        $variant->unassignFromShop(2);

        $this->assertTrue($shopRelations->isInShop($this->articleId1));
        $this->assertFalse($shopRelations->isInShop($this->variantId1));
    }

    /**
     * Testing case:
     * You can assign product to subshop, his variants are assigned too.
     *
     * @dataProvider dpData
     */
    public function testProductAndVariantAssignmentToSubShops($testCase)
    {
        $this->_setupFixture($testCase);

        $article = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
        $article->load($this->articleId1);

        $shopRelations = oxNew(\OxidEsales\Eshop\Core\Element2ShopRelations::class, 'oxarticles');
        $shopRelations->setShopIds(5);

        $this->assertFalse($shopRelations->isInShop($this->articleId1));
        $this->assertFalse($shopRelations->isInShop($this->variantId1));

        $article->assignToShop(5);

        $this->assertTrue($shopRelations->isInShop($this->articleId1));
        $this->assertTrue($shopRelations->isInShop($this->variantId1));
    }

    /**
     * Testing case:
     * You can assign product to subshop, its variants are assigned too. Testing specifically inactive variant case.
     *
     * @dataProvider dpData
     */
    public function testProductAndVariantAssignmentToSubShopsInactiveVariant($testCase)
    {
        $this->_setupFixture($testCase);

        $variant = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
        $variant->load($this->variantId1);
        $variant->oxarticles__oxactive = new \OxidEsales\Eshop\Core\Field(0);
        $variant->save();

        $article = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
        $article->load($this->articleId1);

        $shopRelations = oxNew(\OxidEsales\Eshop\Core\Element2ShopRelations::class, 'oxarticles');
        $shopRelations->setShopIds(5);

        $this->assertFalse($shopRelations->isInShop($this->articleId1));
        $this->assertFalse($shopRelations->isInShop($this->variantId1));

        $article->assignToShop(5);

        $this->assertTrue($shopRelations->isInShop($this->articleId1));
        $this->assertTrue($shopRelations->isInShop($this->variantId1));
    }

    /**
     * Testing case:
     * You can unassign product from subshop, its variants are unassigned too.
     *
     * @dataProvider dpData
     */
    public function testProductAndVariantUnassignmentFromSubShops($testCase)
    {
        $this->_setupFixture($testCase);

        $article = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
        $article->load($this->articleId1);

        $shopRelations = oxNew(\OxidEsales\Eshop\Core\Element2ShopRelations::class, 'oxarticles');
        $shopRelations->setShopIds(2);

        $this->assertTrue($shopRelations->isInShop($this->articleId1));
        $this->assertTrue($shopRelations->isInShop($this->variantId1));

        $article->unassignFromShop(2);

        $this->assertFalse($shopRelations->isInShop($this->articleId1));
        $this->assertFalse($shopRelations->isInShop($this->variantId1));
    }

    /**
     * Testing case:
     * New variant is assigned to the same subshop as parent product
     *
     * @dataProvider dpData
     */
    public function testSubshopAssignmentForNewVariantByAutoinheritance($testCase)
    {
        $this->_setupFixture($testCase);

        $article = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
        $article->load($this->articleId1);
        $article->assignToShop(5);

        $newVariant = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
        $newVariant->setId("_testNewVariant");
        $newVariant->oxarticles__oxparentid = new \OxidEsales\Eshop\Core\Field($this->articleId1);
        $newVariant->save();

        $shopRelations = oxNew(\OxidEsales\Eshop\Core\Element2ShopRelations::class, 'oxarticles');
        $shopRelations->setShopIds(5);

        $this->assertTrue($shopRelations->isInShop("_testNewVariant"));

        $newVariant->delete();
    }
}
