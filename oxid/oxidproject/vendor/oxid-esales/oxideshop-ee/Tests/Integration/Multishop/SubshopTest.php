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
class SubshopTest extends MultishopTestCase
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
     * Fixture file
     *
     * @var string
     */
    private $fixtureFile = "/TestCases/Mall/Subshop.php";

    private $categoryId1 = "_testCat1";

    private $articleId1 = "_testArticle1";

    private $variantId1 = "_testVariant1";


    public function setup(): void
    {
        parent::setUp();
        $data = include $this->testDir . $this->fixtureFile;
        $data = $this->_updateTemplate($data);
        $this->_setupFixture($data);

        $this->_updateViews();
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
    }

    /**
     * Tears down the fixture.
     */
    protected function tearDown(): void
    {
        Registry::getConfig()->getActiveShop()->setMultiShopInheritCategories(false);
        $this->_deleteFixture();
        parent::tearDown();
    }

    /**
     * Test case:
     * Existing test object has mapping record
     */
    public function testProductHasDefaultMapping()
    {
        $article = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
        $article->load($this->articleId1);
        $mapId = $article->oxarticles__oxmapid->value;
        $this->assertEquals(5, \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->getOne("select count(*) from oxarticles2shop where oxmapobjectid = '$mapId' "));
    }

    /**
     * Testing case:
     * 5. Inserted subcategory into parent shop is inserted to all inherited subshops.
     */
    public function testSubcategoryInsertedForAllSubshops()
    {
        Registry::getConfig()->getActiveShop()->setMultiShopInheritCategories(true);
        $category = oxNew(\OxidEsales\Eshop\Application\Model\Category::class);
        $category->load($this->categoryId1);

        $shopRelations = oxNew(\OxidEsales\Eshop\Core\Element2ShopRelations::class, 'oxcategories');

        $shopRelations->setShopIds(2);
        $this->assertFalse($shopRelations->isInShop($this->categoryId1));

        $category->assignToShop(2);

        $this->assertTrue($shopRelations->isInShop($this->categoryId1));

        $shopRelations->setShopIds(3);
        $this->assertFalse($shopRelations->isInShop($this->categoryId1));

        $category->assignToShop(3);

        $this->assertTrue($shopRelations->isInShop($this->categoryId1));

        $category = oxNew(\OxidEsales\Eshop\Application\Model\Category::class);
        $category->setId('_newCat1');
        $category->oxcategories__oxparentid = new \OxidEsales\Eshop\Core\Field($this->categoryId1);
        $category->save();

        $shopRelations->setShopIds(2);
        $this->assertTrue($shopRelations->isInShop($this->categoryId1));
        $this->assertTrue($shopRelations->isInShop('_newCat1'));

        $shopRelations->setShopIds(3);
        $this->assertTrue($shopRelations->isInShop($this->categoryId1));
        $this->assertTrue($shopRelations->isInShop('_newCat1'));
    }

    /**
     * Testing case:
     * 5.a. Create subshop(2) with main shop(1) parent and subshop(3) to subshop(2) parent.
     * Assign category from main shop(1) to subshop(2) and create subcategory in main shop.
     * Make sure that subshop(3) inheriting from subshop(2) does not contain this subcategory.
     * If base shop has parent category and this category in mall tab is assigned to subshop1,
     * then after creating subcategory for this category this subcategory is inherited to subshop1
     * (keep in mind that subcategory is handling with parent category).
     * If category is not assigned to any subshop,
     * then subcategory of that category does not have to be assigned to subshops.
     */
    public function testSubCategoryInheritance()
    {
        Registry::getConfig()->getActiveShop()->setMultiShopInheritCategories(true);

        $categoryId = '_newSubCat';
        $category = oxNew(\OxidEsales\Eshop\Application\Model\Category::class);
        $category->load($this->categoryId1);

        $shopRelations = oxNew(\OxidEsales\Eshop\Core\Element2ShopRelations::class, 'oxcategories');

        $shopRelations->setShopIds(2);
        $this->assertFalse(
            $shopRelations->isInShop($this->categoryId1),
            "Parent category must not exist until not assigned to subshop"
        );

        $category->assignToShop(2);

        $this->assertTrue(
            $shopRelations->isInShop($this->categoryId1),
            "Parent category must exist after assignment to subshop"
        );

        $shopRelations->setShopIds(3);
        $this->assertFalse(
            $shopRelations->isInShop($this->categoryId1),
            "Parent category must not exist in subshop which does not inherit it"
        );

        $subCategory = oxNew(\OxidEsales\Eshop\Application\Model\Category::class);
        $subCategory->setId($categoryId);
        $subCategory->oxcategories__oxparentid = new \OxidEsales\Eshop\Core\Field($this->categoryId1);
        $subCategory->save();

        $shopRelations->setShopIds(2);
        $this->assertTrue(
            $shopRelations->isInShop($categoryId),
            "Subcategory must exist in subshop which inherits parent category"
        );

        $shopRelations->setShopIds(3);
        $this->assertFalse(
            $shopRelations->isInShop($categoryId),
            "Subcategory must not exist in subshop which does not inherit parent category"
        );
    }

    /**
     * Testing case:
     * 7. Unassigning article from subshop article is unassigned from its children subshops (when pressing X)
     */
    public function testProductUnassignmentFromAllShops()
    {
        $article = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
        $article->load($this->articleId1);

        $shopRelations = oxNew(\OxidEsales\Eshop\Core\Element2ShopRelations::class, 'oxarticles');

        $shopRelations->setShopIds(2);
        $this->assertTrue($shopRelations->isInShop($this->articleId1));

        $shopRelations->setShopIds(3);
        $this->assertTrue($shopRelations->isInShop($this->articleId1));

        // from admin screen when pressing X to unassign from shop the controller collects inheritance group
        $shop = Registry::getConfig()->getActiveShop();
        $shopIds = $shop->getInheritanceGroup('oxarticles', 2);

        $article->unassignFromShop($shopIds);

        $shopRelations->setShopIds(2);
        $this->assertFalse($shopRelations->isInShop($this->articleId1));

        $shopRelations->setShopIds(3);
        $this->assertFalse($shopRelations->isInShop($this->articleId1));
    }

    /**
     * Testing case:
     * 7. Unassigning article from subshop article is unassigned from its children subshops (when pressing X)
     * Checking also subshops
     */
    public function testProductUnassignmentFromAllSubshops()
    {
        $article = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
        $article->load($this->articleId1);

        $shopRelations = oxNew(\OxidEsales\Eshop\Core\Element2ShopRelations::class, 'oxarticles');

        $shopRelations->setShopIds(2);
        $this->assertTrue($shopRelations->isInShop($this->articleId1));

        $shopRelations->setShopIds(3);
        $this->assertTrue($shopRelations->isInShop($this->articleId1));

        $shop = oxNew(\OxidEsales\Eshop\Application\Model\Shop::class);
        $shopIds = $shop->getInheritanceGroup("oxarticles", 2);
        $article->unassignFromShop($shopIds);

        $shopRelations->setShopIds(2);
        $this->assertFalse($shopRelations->isInShop($this->articleId1));

        $shopRelations->setShopIds(3);
        $this->assertFalse($shopRelations->isInShop($this->articleId1));
    }

    /**
     * Testing case:
     * 8. If product is unassigned from subshop then all variants are unassigned from subshop too
     */
    public function testVariantUnassignmentFromAllShops()
    {
        $article = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
        $article->load($this->articleId1);

        $shopRelations = oxNew(\OxidEsales\Eshop\Core\Element2ShopRelations::class, 'oxarticles');
        $shopRelations->setShopIds(2);
        $this->assertTrue($shopRelations->isInShop($this->variantId1));
        $shopRelations->setShopIds(3);
        $this->assertTrue($shopRelations->isInShop($this->variantId1));

        $article->unassignFromShop(2);

        $shopRelations->setShopIds(2);
        $this->assertFalse($shopRelations->isInShop($this->variantId1));
    }

    /**
     * Testing case:
     * 8. If product is unassigned from subshop then all variants are unassigned from subshop too
     * Checking subshops
     */
    public function testVariantUnassignmentFromAllSubshops()
    {
        $article = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
        $article->load($this->articleId1);

        $shopRelations = oxNew(\OxidEsales\Eshop\Core\Element2ShopRelations::class, 'oxarticles');
        $shopRelations->setShopIds(2);
        $this->assertTrue($shopRelations->isInShop($this->variantId1));
        $shopRelations->setShopIds(3);
        $this->assertTrue($shopRelations->isInShop($this->variantId1));

        $shop = oxNew(\OxidEsales\Eshop\Application\Model\Shop::class);
        $shopIds = $shop->getInheritanceGroup("oxarticles", 2);
        $article->unassignFromShop($shopIds);

        $shopRelations->setShopIds(2);
        $this->assertFalse($shopRelations->isInShop($this->variantId1));
        $shopRelations->setShopIds(3);
        $this->assertFalse($shopRelations->isInShop($this->variantId1));
    }

    /**
     * Testing case:
     * 9. Variants are removed from database for subshops upon removal of parent article in subshop(when pressing X).
     * It works when going through mall tab.
     */
    public function testProductVariantUnassignmentFromAllShops()
    {
        $article = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
        $article->load($this->articleId1);

        $article->delete();

        $shopRelations = oxNew(\OxidEsales\Eshop\Core\Element2ShopRelations::class, 'oxarticles');
        $shopRelations->setShopIds(2);
        $this->assertFalse($shopRelations->isInShop($this->variantId1));
        $shopRelations->setShopIds(3);
        $this->assertFalse($shopRelations->isInShop($this->variantId1));
    }

    /**
     * Testing case:
     * 10. If new variant is created for product in parent shop, then all subshops which inherit that product inherits
     * this variant.
     */
    public function testNewVariantInheritanceForAllShops()
    {
        $variant = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
        $variant->oxarticles__oxparentid = new \OxidEsales\Eshop\Core\Field($this->articleId1);
        $variant->save();

        $shopRelations = oxNew(\OxidEsales\Eshop\Core\Element2ShopRelations::class, 'oxarticles');
        $shopRelations->setShopIds(2);
        $this->assertTrue($shopRelations->isInShop($this->variantId1));
        $shopRelations->setShopIds(3);
        $this->assertTrue($shopRelations->isInShop($this->variantId1));
    }

    /**
     * Testing case:
     * 11. If option "Inherit all products" is enabled and product is created for parent shop,
     * then this product is inherited to all children subshops depending on this option.
     */
    public function testProductAssignmentForAllShops()
    {
        $article = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
        $article->setId('_testArt');
        $article->save();

        $shopRelations = oxNew(\OxidEsales\Eshop\Core\Element2ShopRelations::class, 'oxarticles');
        $shopRelations->setShopIds(2);
        $this->assertTrue($shopRelations->isInShop("_testArt"));
        $shopRelations->setShopIds(3);
        $this->assertTrue($shopRelations->isInShop("_testArt"));
    }

    /**
     * Testing case:
     * 12. If option "Inherit all products" is enabled and product is deleted from parent shop,
     * then this product is deleted from all children subshops depending on this option.
     */
    public function testProductDeleteFromAllShops()
    {
        $article = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
        $article->load($this->articleId1);

        $article->delete();

        $shopRelations = oxNew(\OxidEsales\Eshop\Core\Element2ShopRelations::class, 'oxarticles');
        $shopRelations->setShopIds(2);
        $this->assertFalse($shopRelations->isInShop($this->articleId1));
        $shopRelations->setShopIds(3);
        $this->assertFalse($shopRelations->isInShop($this->articleId1));
    }

    /**
     * Testing case:
     * 15. If option "Inherit all products" is disabled and in mall tab of product of parent shop that product is
     * assigned to 4 subshops, then all 4 subshops inherit this product.
     */
    public function testProductInheritAllForSubshps()
    {
        //disable "Inherit all"
        $config = Registry::getConfig();
        $config->saveShopConfVar("bool", "blMallInherit_oxarticles", "false", 2);
        $config->saveShopConfVar("bool", "blMallInherit_oxarticles", "false", 3);
        $config->saveShopConfVar("bool", "blMallInherit_oxarticles", "false", 4);
        $config->saveShopConfVar("bool", "blMallInherit_oxarticles", "false", 5);

        //update inheirtance
        $subShopIds = array(5, 4, 3, 2);
        foreach ($subShopIds as $subshopId) {
            $shop = oxNew(\OxidEsales\Eshop\Application\Model\Shop::class);
            $shop->load($subshopId);
            $multiShopTables = $config->getConfigParam('aMultiShopTables');
            $shop->setMultiShopTables($multiShopTables);
            $shop->updateInheritance();
        }

        $article = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
        $article->load($this->articleId1);

        $shopRelations = oxNew(\OxidEsales\Eshop\Core\Element2ShopRelations::class, 'oxarticles');
        $shopRelations->setShopIds(2);
        $this->assertFalse($shopRelations->isInShop($this->articleId1));
        $shopRelations->setShopIds(3);
        $this->assertFalse($shopRelations->isInShop($this->articleId1));
        $shopRelations->setShopIds(4);
        $this->assertFalse($shopRelations->isInShop($this->articleId1));
        $shopRelations->setShopIds(5);
        $this->assertFalse($shopRelations->isInShop($this->articleId1));

        $article->assignToShop(2);
        $article->assignToShop(3);
        $article->assignToShop(4);
        $article->assignToShop(5);

        $shopRelations->setShopIds(2);
        $this->assertTrue($shopRelations->isInShop($this->articleId1));
        $shopRelations->setShopIds(3);
        $this->assertTrue($shopRelations->isInShop($this->articleId1));
        $shopRelations->setShopIds(4);
        $this->assertTrue($shopRelations->isInShop($this->articleId1));
        $shopRelations->setShopIds(5);
        $this->assertTrue($shopRelations->isInShop($this->articleId1));
    }

    /**
     * Testing case:
     * 16.  If option "Inherit all products" is disabled and in mall tab of product of parent shop that product
     * is assigned to 4 subshops, then deleting product in parent shop all entries are deleted from all 4 subshops.
     */
    public function testProductRemovedInheritaAllForSubshps()
    {
        $article = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
        $article->load($this->articleId1);
        $mapId = $article->oxarticles__oxmapid->value;

        //pretest entries
        $this->assertGreaterThan(
            1,
            \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->getOne("select count(*) from oxarticles2shop where oxmapobjectid = '$mapId' ")
        );

        $article = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
        $article->load($this->articleId1);

        $article->delete();

        $this->assertEquals(
            0,
            \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->getOne("select count(*) from oxarticles2shop where oxmapobjectid = '$mapId' ")
        );
    }

    /**
     * Testing case:
     * 17. Integration test must be written for this bug https://bugs.oxid-esales.com/view.php?id=5739.
     */
    public function testAllSubshopsDisplayed()
    {
        $shop = oxNew(\OxidEsales\Eshop\Application\Model\Shop::class);
        $shop->load(2);

        $subshopList = $shop->getSubShopList();
        $this->assertEquals(6, count($subshopList));

        $this->assertEquals("Subshop 3", $subshopList[3]->oxshops__oxname->value);
        $this->assertEquals("Subshop 4", $subshopList[4]->oxshops__oxname->value);
        $this->assertEquals("Subshop 8", $subshopList[8]->oxshops__oxname->value);

    }

    /* -----------------------------------------------------------------------------------------------------------*/

    /*
     * Testing cases with preconditions:
     * 3 levels of subshop tree exist in the shop.
     * Assume that structure and naming of these subshops are following:
     * Parent (A) -> Child (B) -> Child of child (C)
     * Option "Inherit all products" is checked in every subshop following:
     * Parent (ENABLED), Child (DISABLED), Child of child (ENABLED)
     * Currently matches #2(A) #5(B) #8(C) shops from main fixture.
     */

    /**
     * Subshop (B - #5) and subshop (C - #8) should not inherit any products/discounts/wrappings and etc.
     * from subshop (A - #2).
     * Checking that: adding, removing elements to shop A the inheritance by the defined options are correct.
     */
    public function testChildSubshopsDoNotInheritAnyElementsFromParentWithPreconditions()
    {
        $article = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
        $article->load($this->articleId1);

        // Unnassigning article from assigned by fixture subshop A
        $article->unassignFromShop(2);
        $article->unassignFromShop(3);
        $article->unassignFromShop(4);

        $shopRelations = oxNew(\OxidEsales\Eshop\Core\Element2ShopRelations::class, 'oxarticles');

        $shopRelations->setShopIds(2);

        $this->assertFalse(
            $shopRelations->isInShop($this->articleId1),
            "Article must not exist in subshop A before assigning"
        );

        $article->assignToShop(2);

        $this->assertTrue(
            $shopRelations->isInShop($this->articleId1),
            "Article must not exist in subshop A after assigning"
        );

        $shopRelations->setShopIds(5);
        $this->assertFalse($shopRelations->isInShop($this->articleId1), "Article must not exist in subshop B");

        $shopRelations->setShopIds(8);
        $this->assertFalse($shopRelations->isInShop($this->articleId1), "Article must not exist in subshop C");
    }

    /**
     * Subshop (C - #8) only inherits products/discounts/wrappings from subshop (B - #5).
     * Adding product only in subshop (B), and this product should be inherited to subshop (C).
     * The functionality was changed. It assigns to these shops, that are marked in the list in admin in mall tab.
     * So if you add to subshop (B) it will not add to subshop (C) automatically.
     */
    public function testChildSubshopOnlyInheritsElementsFromParent()
    {
        $article = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
        $article->load($this->articleId1);

        $article->unassignFromShop(array(5, 8));

        $shopRelations = oxNew(\OxidEsales\Eshop\Core\Element2ShopRelations::class, 'oxarticles');

        $shopRelations->setShopIds(5);

        $this->assertFalse(
            $shopRelations->isInShop($this->articleId1),
            "Article must not exist in subshop B before assigning"
        );

        $shopRelations->setShopIds(8);

        $this->assertFalse(
            $shopRelations->isInShop($this->articleId1),
            "Article must not exist in subshop C before assigning it to subshop B"
        );

        $article->assignToShop(5);

        $shopRelations->setShopIds(5);

        $this->assertTrue(
            $shopRelations->isInShop($this->articleId1),
            "Article must exist in subshop B after assigning"
        );

        $shopRelations->setShopIds(8);

        $this->assertFalse(
            $shopRelations->isInShop($this->articleId1),
            "Article must not exist in subshop C after assigning it to subshop B"
        );
    }

    /**
     * Add product/discount/wrapping exclusively in parent shop (A - #2) and in mall tab assign this
     * product/discount/wrapping to subshop (B - #5).
     * Result - product/discount/wrapping should be shown in subshop (B) even if option "Inherit all products"
     * is DISABLED for subshop (B).
     */
    public function testChildSubshopInheritsElementFromParentWhenInheritOptionIsOffAndElementIsAssignedViaMallTab()
    {
        $subshopAID = 2;
        $subshopBID = 5;

        $article = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
        $article->setId($this->articleId1);
        $article->oxarticles__oxtitle = new \OxidEsales\Eshop\Core\Field("_testArticle1");
        $article->save();

        // Unnassigning article from assigned by fixture subshop A
        $article->unassignFromShop($subshopAID);

        $shopRelations = oxNew(\OxidEsales\Eshop\Core\Element2ShopRelations::class, 'oxarticles');

        $shopRelations->setShopIds($subshopAID);

        $this->assertFalse(
            $shopRelations->isInShop($this->articleId1),
            "Article must not exist in subshop A before assigning"
        );

        $article->assignToShop($subshopAID);

        $this->assertTrue(
            $shopRelations->isInShop($this->articleId1),
            "Article must exist in subshop A after assigning"
        );

        $shopRelations->setShopIds($subshopBID);

        $this->assertFalse(
            $shopRelations->isInShop($this->articleId1),
            "Article must not exist in subshop B before assigning"
        );

        // Emulation of assigning this article to subshop B via mall tab
        $config = Registry::getConfig();
        $config->setShopId($subshopAID);

        $this->setRequestParameter("oxid", $this->articleId1);
        $this->setRequestParameter("allartshops", array(5));

        $view = oxNew(\OxidEsales\Eshop\Application\Controller\Admin\ArticleMall::class);
        $view->assignToSubShops();

        $this->assertTrue(
            $shopRelations->isInShop($this->articleId1),
            "Article must exist in subshop B after assigning"
        );
    }

    /* -----------------------------------------------------------------------------------------------------------*/

    /*
     * Testing cases with preconditions:
     * 1. Create new subshop1 with options :
     * Option - Shop inherits all inheritable items (products, discounts etc) from it's parent shop. Is on
     * Choose parent shop
     *
     * 2. Go to parent shop -> Administer products ->Category and need to create new categories
     * (_testParentCategory(Parent category)
     * -_testCategory1 (child)
     * -_testCategory2 (child))
     *
     * 3. Go to 'Parent Category' and open Mall tab and assign this parent category to created subshop1
     * 4. Go to Administer products-> Product and choose the product '_testArticle1', then go to the tab 'Extended'
     * 5. Click on the button 'Assign categories' , and assign created category tree for the product.
     * 6. then in the parent shop as 'main category' need to set Category1
     * 7. In the subshop for product '_testArticle1' main category need to set Category2
     */

    /**
     * Test Case No.1:
     * 2. In main shop load the product '_testArticle1', getCategory()
     * Main category should be _testCategory1
     *
     * Test Case No.2:
     * 2.In subshop1 load the product '_testArticle1', getCategory()
     * Main category should be _testCategory2
     *
     * @bug #5630 https://bugs.oxid-esales.com/view.php?id=5630
     */
    public function testSetMainCategoryAsSubshop()
    {
        $parentCategory = array(
            'oxid'       => '_testParentCategory',
            'oxrootid'   => '_testParentCategory',
            'oxparentid' => 'oxrootid',
            'oxleft'     => 1,
            'oxright'    => 6,
            'oxshopid'   => 1
        );
        $this->_createCategory($parentCategory);
        $categoryData = array(
            'oxid'       => '_testCategory1',
            'oxrootid'   => '_testParentCategory',
            'oxparentid' => '_testParentCategory',
            'oxleft'     => 2,
            'oxright'    => 3,
            'oxshopid'   => 1
        );
        $this->_createCategory($categoryData);
        $categoryData['oxid'] = '_testCategory2';
        $categoryData['oxleft'] = 4;
        $categoryData['oxright'] = 5;
        $this->_createCategory($categoryData);

        $category = oxNew(\OxidEsales\Eshop\Application\Model\Category::class);
        $category->load('_testParentCategory');
        $category->assignToShop(2);

        $article2CategoryData = array(
            'oxid'       => '_testA2C1',
            'oxshopid'   => 1,
            'oxobjectid' => '_testArticle1',
            'oxcatnid'   => '_testCategory1',
        );
        $this->_createObject2Category($article2CategoryData);
        $article2CategoryData['oxid'] = '_testA2C2';
        $article2CategoryData['oxcatnid'] = '_testCategory2';
        $this->_createObject2Category($article2CategoryData);

        $article2Category = oxNew(\OxidEsales\Eshop\Application\Model\Object2Category::class);
        $article2Category->load('_testA2C1');
        $article2Category->assignToShop(2);

        $article2Category = oxNew(\OxidEsales\Eshop\Application\Model\Object2Category::class);
        $article2Category->load('_testA2C2');
        $article2Category->assignToShop(2);

        $this->setRequestParameter('oxid', '_testArticle1');
        $this->setRequestParameter('defcat', '_testCategory1');
        Registry::getConfig()->setShopId(1);
        $articleExtends = oxNew(\OxidEsales\Eshop\Application\Controller\Admin\ArticleExtendAjax::class);
        $articleExtends->setAsDefault();

        $this->setRequestParameter('defcat', '_testCategory2');
        Registry::getConfig()->setShopId(2);
        $articleExtends = oxNew(\OxidEsales\Eshop\Application\Controller\Admin\ArticleExtendAjax::class);
        $articleExtends->setAsDefault();

        Registry::getConfig()->setShopId(1);
        $article = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
        $article->load('_testArticle1');
        $category = $article->getCategory();
        $this->assertEquals('_testCategory1', $category->getFieldData('oxid'));

        Registry::getConfig()->setShopId(2);
        $article = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
        $article->load('_testArticle1');
        $category = $article->getCategory();
        $this->assertEquals('_testCategory2', $category->getFieldData('oxid'));

    }

    /* -----------------------------------------------------------------------------------------------------------*/

    /*
     * 1. Create subshop but opt out from inheriting articles from parent shop.
     * 2. From parent shop assign any article to subshop.
     * 3. Change inheritance option for any other item group but leave article inheritance opt out and save.
     *
     * Expected: previously assigned article is still assigned to subshop.
     */

    /**
     * Tests that element remains assigned to a sub shop when inheritance option is off and saved without it's value
     * change.
     *
     * From current fixture:
     * - parent shop
     *   - id = 2
     * - sub shop
     *   - id = 5
     *   - parent shop id = 2
     *   - article inheritance = off
     * - article
     *   - id = _testArticle1
     *   - shop id = 1
     *   - assigned to shops = 1
     *     - required - assign to shop 2
     */
    public function testInheritanceOptionIsOffAndNotChangedOnSaveAndElementRemainsAssigned()
    {
        $parentShopId = 2;
        $subShopId = 5;

        $articleId = '_testArticle1';
        $coreTable = 'oxarticles';

        $config = $this->getConfig();

        // ---- configuration for test

        // assign article to parent shop
        $config->setShopId(1);
        $article = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
        $article->load($articleId);
        $article->assignToShop($parentShopId);

        // ---- post configuration checks for test

        // article is assigned to parent shop
        $config->setShopId($parentShopId);
        $article = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
        $article->load($articleId);
        $this->assertTrue($article->isLoaded(), 'article should be assigned to parent shop');

        // article inheritance is off for sub shop
        $config->setShopId($subShopId);
        $this->assertFalse(
            (bool) $config->getShopConfVar("blMallInherit_{$coreTable}"),
            'article inheritance should be off for sub shop'
        );

        // article is not assigned to sub shop
        $config->setShopId($subShopId);
        $article = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
        $article->load($articleId);
        $this->assertFalse($article->isLoaded(), 'article should not be assigned to sub shop');

        // ---- performing test actions

        // assign article to sub shop
        $config->setShopId($parentShopId);
        $article = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
        $article->load($articleId);
        $article->assignToShop($subShopId);

        // article is assigned to sub shop
        $config->setShopId($subShopId);
        $article = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
        $article->load($articleId);
        $this->assertTrue($article->isLoaded(), 'article should be assigned to sub shop');

        // change inheritance option for any other item group but leave article inheritance opt out and save
        $config->setShopId($subShopId);
        $this->getSession()->setVariable('malladmin', true);
        $this->setRequestParameter("oxid", $subShopId);
        $this->setRequestParameter('confbools', array("blMallInherit_{$coreTable}" => 'false'));
        $this->setRequestParameter('confbools', array("blMallInherit_oxattribute" => 'true'));
        $view = oxNew(\OxidEsales\Eshop\Application\Controller\Admin\ShopMall::class);
        $view->changeInheritance();

        // inheritance options are changed
        $config->setShopId($subShopId);
        $this->assertFalse(
            (bool) $config->getShopConfVar("blMallInherit_{$coreTable}"),
            'inheritance options should be off for oxarticles'
        );
        $this->assertTrue(
            (bool) $config->getShopConfVar("blMallInherit_oxattribute"),
            'inheritance options should be on for oxattribute'
        );

        // article remains assigned to sub shop
        $config->setShopId($subShopId);
        $article = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
        $article->load($articleId);
        $this->assertTrue($article->isLoaded(), 'article should remain assigned to sub shop');
    }

    /* -----------------------------------------------------------------------------------------------------------*/
}
