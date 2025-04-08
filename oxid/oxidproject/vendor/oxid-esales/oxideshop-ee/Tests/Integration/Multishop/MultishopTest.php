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
 * Multishop type subshop integration test
 */
class MultishopTest extends MultishopTestCase
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
    private $fixtureOneFile = "/TestCases/Multishop/Multishop.php";

    private $categoryOneID = "_testCat1";
    private $subcategoryOneID = "_testCat2";
    private $subcategoryTwoID = "_testCat3";
    private $categoryTwoID = "_testCat4";
    private $categoryThreeID = "_testCat5";
    private $categoryFourID = "_testCat6";

    private $articleIdOne = "_testArticle1";
    private $articleIdTwo = "_testArticle2";
    private $articleThreeID = "_testArticle3";

    private $variantOneID = "_testVariant1";
    private $variantTwoID = "_testVariant2";
    private $variantThreeID = "_testVariant3";

    /**
     * Main setup
     */
    public function setup(): void
    {
        parent::setUp();
        $data = include $this->testDir . $this->fixtureOneFile;
        $data = $this->_updateTemplate($data);
        $this->_setupFixture($data);
    }

    /**
     * Sets up the fixture.
     *
     * @param array $testCase Test cases with expected results.
     */
    protected function _setupFixture($testCase) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        parent::_setupFixture($testCase);
        $this->_updateViews();

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
        $this->_deleteFixture();
        parent::tearDown();
    }

    /* ------------------------------------------------------------------------------------------------------------- */
    /* --------------------------------------- Initial creation of multishop ----------------------------------------*/
    /* ------------------------------------------------------------------------------------------------------------- */

    /**
     * Test case:
     * New subshop of multishop type is created on the fly with oxisinherited config off and no parent.
     * Checking if multishop inherits all inheritable elements except categories from other shops.
     */
    public function testAllInheritableElementsAreInheritedFromAllShopsExceptCategoriesWhenCreatingNewMultiShop()
    {
        $newMultishop = 10;
        $newMultishopData = array(
            'oxid'          => $newMultishop,
            'oxname'        => 'New Multishop',
            'oxparentid'    => 0,
            'oxismultishop' => 1,
        );

        $shop = $this->_createShop($newMultishopData);
        $this->_updateViews();
        $shop->updateInheritance();

        $shopRelations = oxNew(\OxidEsales\Eshop\Core\Element2ShopRelations::class, 'oxarticles');
        $shopRelations->setShopIds($newMultishop);

        $existsMsg = 'exists initially';
        $doesntExistMsg = 'does not exist initially';

        $this->assertTrue($shopRelations->isInShop($this->articleIdOne), "{$this->articleIdOne} {$existsMsg}");
        $this->assertTrue($shopRelations->isInShop($this->articleIdTwo), "{$this->articleIdTwo} {$existsMsg}");
        $this->assertTrue($shopRelations->isInShop($this->articleThreeID), "{$this->articleThreeID} {$existsMsg}");
        $this->assertTrue($shopRelations->isInShop($this->variantOneID), "{$this->variantOneID} {$existsMsg}");
        $this->assertTrue($shopRelations->isInShop($this->variantTwoID), "{$this->variantTwoID} {$existsMsg}");
        $this->assertTrue($shopRelations->isInShop($this->variantThreeID), "{$this->variantThreeID} {$existsMsg}");

        $shopRelations = oxNew(\OxidEsales\Eshop\Core\Element2ShopRelations::class, 'oxcategories');
        $shopRelations->setShopIds($newMultishop);

        $this->assertFalse(
            $shopRelations->isInShop($this->categoryOneID),
            "{$this->categoryOneID} {$doesntExistMsg}"
        );
        $this->assertFalse(
            $shopRelations->isInShop($this->categoryTwoID),
            "{$this->categoryTwoID} {$doesntExistMsg}"
        );
        $this->assertFalse(
            $shopRelations->isInShop($this->categoryThreeID),
            "{$this->categoryThreeID} {$doesntExistMsg}"
        );
        $this->assertFalse(
            $shopRelations->isInShop($this->categoryFourID),
            "{$this->categoryFourID} {$existsMsg}"
        );
        $this->assertFalse(
            $shopRelations->isInShop($this->subcategoryOneID),
            "{$this->subcategoryOneID} {$doesntExistMsg}"
        );
        $this->assertFalse(
            $shopRelations->isInShop($this->subcategoryTwoID),
            "{$this->subcategoryTwoID} {$doesntExistMsg}"
        );
    }

    /**
     * Multishop inherits all inheritable elements except categories from other shops
     */
    public function testAllInheritableElementsAreInheritedFromAllShopsExceptCategories()
    {
        $multishopID = 2;

        // As inheritable element example picking article element
        $shopRelations = oxNew(\OxidEsales\Eshop\Core\Element2ShopRelations::class, 'oxarticles');
        $shopRelations->setShopIds($multishopID);

        $message = "exists initially";
        $this->assertTrue($shopRelations->isInShop($this->articleIdOne), "{$this->articleIdOne} {$message}");
        $this->assertTrue($shopRelations->isInShop($this->articleIdTwo), "{$this->articleIdTwo} {$message}");
        $this->assertTrue($shopRelations->isInShop($this->articleThreeID), "{$this->articleThreeID} {$message}");
        $this->assertTrue($shopRelations->isInShop($this->variantOneID), "{$this->variantOneID} {$message}");
        $this->assertTrue($shopRelations->isInShop($this->variantTwoID), "{$this->variantTwoID} {$message}");
        $this->assertTrue($shopRelations->isInShop($this->variantThreeID), "{$this->variantThreeID} {$message}");

        $shopRelations = oxNew(\OxidEsales\Eshop\Core\Element2ShopRelations::class, 'oxcategories');
        $shopRelations->setShopIds($multishopID);

        $initialMessage = 'does not exist initially';
        $this->assertFalse($shopRelations->isInShop($this->categoryOneID), "{$this->categoryOneID} {$initialMessage}");
        $this->assertFalse(
            $shopRelations->isInShop($this->subcategoryOneID),
            "{$this->categoryOneID} {$initialMessage}"
        );
    }

    /**
     * Categories can be assigned/unassigned all at once to multishop via Shop Core Settings -> Mall Tab
     */
    public function testAllCategoriesAssignmentViaMallTabToMultishopAtOnce()
    {
        $multishopID = 2;

        $category = oxNew(\OxidEsales\Eshop\Application\Model\Category::class);
        $category->load($this->categoryOneID);

        $shopRelations = oxNew(\OxidEsales\Eshop\Core\Element2ShopRelations::class, 'oxcategories');
        $shopRelations->setShopIds($multishopID);

        $initialMessage = "does not exist initially";
        $this->assertFalse($shopRelations->isInShop($this->categoryOneID), "{$this->categoryOneID} {$initialMessage}");
        $this->assertFalse(
            $shopRelations->isInShop($this->subcategoryOneID),
            "{$this->categoryOneID} {$initialMessage}"
        );

        $config = Registry::getConfig();
        $config->setShopId($multishopID);
        $view = oxNew(\OxidEsales\Eshop\Application\Controller\Admin\ShopMall::class);

        Registry::getConfig()->saveShopConfVar('bool', "blMultishopInherit_oxcategories", 1, $multishopID);
        //setRequest parameter for changed value detection
        $this->setRequestParameter("confbools", array("blMultishopInherit_oxcategories" => "false"));
        $view->changeInheritance();

        $assignmentMessage = "exists after assignment";
        $this->assertTrue($shopRelations->isInShop($this->categoryOneID), "{$this->categoryOneID} {$assignmentMessage}");
        $this->assertTrue(
            $shopRelations->isInShop($this->subcategoryOneID),
            "{$this->categoryOneID} {$assignmentMessage}"
        );

        Registry::getConfig()->saveShopConfVar('bool', "blMultishopInherit_oxcategories", 0, $multishopID);
        //setRequest parameter for changed value detection
        $this->setRequestParameter("confbools", array("blMultishopInherit_oxcategories" => "true"));
        $view->changeInheritance();

        $unassignmentMsg = "does not exist after unassignment";
        $this->assertFalse(
            $shopRelations->isInShop($this->categoryOneID),
            "{$this->categoryOneID} {$unassignmentMsg}"
        );
        $this->assertFalse(
            $shopRelations->isInShop($this->subcategoryOneID),
            "{$this->categoryOneID} {$unassignmentMsg}"
        );
    }

    /**
     * Multishop child inherits all inheritable elements except categories
     */
    public function testChildSubshopOfMultishopInheritsAnyElementsFromParentExceptCategories()
    {
        $multishopID = 2;
        $childID = 5;

        $shopRelations = oxNew(\OxidEsales\Eshop\Core\Element2ShopRelations::class, 'oxarticles');

        $shopRelations->setShopIds($multishopID);
        $initialMessage = "exists at parent initially";
        $this->assertTrue($shopRelations->isInShop($this->articleIdOne), "{$this->articleIdOne} {$initialMessage}");
        $this->assertTrue($shopRelations->isInShop($this->articleIdTwo), "{$this->articleIdTwo} {$initialMessage}");
        $this->assertTrue($shopRelations->isInShop($this->variantOneID), "{$this->variantOneID} {$initialMessage}");

        $shopRelations->setShopIds($childID);
        $initialMessage = "does not exist at child initially";
        $this->assertTrue($shopRelations->isInShop($this->articleIdOne), "{$this->articleIdOne} {$initialMessage}");
        $this->assertTrue($shopRelations->isInShop($this->articleIdTwo), "{$this->articleIdTwo} {$initialMessage}");
        $this->assertTrue($shopRelations->isInShop($this->variantOneID), "{$this->variantOneID} {$initialMessage}");

        $shopRelations = oxNew(\OxidEsales\Eshop\Core\Element2ShopRelations::class, 'oxcategories');
        $shopRelations->setShopIds($multishopID);
        $assignmentMessage = "exists at parent after assignment";
        $this->assertTrue($shopRelations->isInShop($this->categoryFourID), "{$this->categoryFourID} {$assignmentMessage}");

        $shopRelations->setShopIds($childID);
        $assignmentMessage = "does not exist at child after assignment to parent";
        $this->assertFalse($shopRelations->isInShop($this->categoryFourID), "{$this->categoryFourID} {$assignmentMessage}");

    }

    /* ------------------------------------------------------------------------------------------------------------- */
    /* ------------------------------ Creating / deleting elements in non-multishops ------------------------------- */
    /* ------------------------------------------------------------------------------------------------------------- */

    /**
     * Multishop by default inherits any newly created inheritable element except categories from any subshop,
     * deletion of that element from subshop is also reflected to multishop.
     */
    public function testNewlyCreatedProductInAnySubshopIsAutoAssignedToMultishopAndDeleted()
    {
        $newArticleId = "_newTestArticle";
        $multishopID = 2;
        $subshopID = 4;

        $shopRelations = oxNew(\OxidEsales\Eshop\Core\Element2ShopRelations::class, 'oxarticles');
        $shopRelations->setShopIds($multishopID);

        $initialMessage = "does not exist initially";
        $this->assertFalse($shopRelations->isInShop($newArticleId), "{$newArticleId} {$initialMessage}");

        $newArticleData = array(
            'oxid'     => $newArticleId,
            'oxshopid' => $subshopID,
        );

        $article = $this->_createArticle($newArticleData);

        $creationMessage = "assigned after creation";
        $this->assertTrue($shopRelations->isInShop($newArticleId), "{$newArticleId} {$creationMessage}");

        $article->delete();

        $unassignMessage = "deleted from subshop";
        $shopRelations->setShopIds($subshopID);
        $this->assertFalse($shopRelations->isInShop($newArticleId), "{$newArticleId} {$unassignMessage}");

        $unassignMessage = "deletion reflected to multishop";
        $shopRelations->setShopIds($multishopID);
        $this->assertFalse($shopRelations->isInShop($newArticleId), "{$newArticleId} {$unassignMessage}");
    }

    /**
     * Multishop inherits any newly created category element if blMultishopInherit_oxcategories config option ON
     * from any subshop, deletion of that category from subshop is also reflected to multishop.
     */
    public function testNewlyCreatedCategoryInAnySubshopIsAssignedToMultishopWithCfgEnabledAndDeleted()
    {
        $newCategoryId = "_newTestCategory";
        $multishopID = 2;
        $subshopID = 4;

        Registry::getConfig()->saveShopConfVar('bool', "blMultishopInherit_oxcategories", 1, $multishopID);

        $shopRelations = oxNew(\OxidEsales\Eshop\Core\Element2ShopRelations::class, 'oxcategories');
        $shopRelations->setShopIds($multishopID);

        $initialMessage = "does not exist initially";
        $this->assertFalse($shopRelations->isInShop($newCategoryId), "{$newCategoryId} {$initialMessage}");

        $newCategoryData = array(
            'oxid'       => $newCategoryId,
            'oxshopid'   => $subshopID,
            'oxrootid'   => $newCategoryId,
            'oxparentid' => 'oxrootid',
            'oxleft'     => 1,
            'oxright'    => 2,
        );

        $category = $this->_createCategory($newCategoryData);

        $creationMessage = "assigned after creation";
        $this->assertTrue($shopRelations->isInShop($newCategoryId), "{$newCategoryId} {$creationMessage}");

        $category->delete();

        $unassignMessage = "deleted from subshop";
        $shopRelations->setShopIds($subshopID);
        $this->assertFalse($shopRelations->isInShop($newCategoryId), "{$newCategoryId} {$unassignMessage}");

        $unassignMessage = "delete reflected to multishop";
        $shopRelations->setShopIds($multishopID);
        $this->assertFalse($shopRelations->isInShop($newCategoryId), "{$newCategoryId} {$unassignMessage}");
    }

    /**
     * Testing case:
     * When new category is assigned to product in parent shop, this assignment should be in subshop too.
     *
     */
    public function testNewCategoryAssignmentToProduct()
    {
        $multishopID = 2;

        $view = oxNew(\OxidEsales\Eshop\Application\Controller\Admin\ShopMall::class);
        Registry::getConfig()->saveShopConfVar('bool', "blMultishopInherit_oxcategories", 1, $multishopID);
        //setRequest parameter for changed value detection
        $this->setRequestParameter("confbools", array("blMultishopInherit_oxcategories" => "false"));

        $view->changeInheritance();

        $category = oxNew(\OxidEsales\Eshop\Application\Model\Category::class);
        $category->load($this->categoryThreeID);

        $shopRelations = oxNew(\OxidEsales\Eshop\Core\Element2ShopRelations::class, 'oxcategories');
        $shopRelations->setShopIds($multishopID);

        $this->assertTrue($shopRelations->isInShop($this->categoryThreeID));
        $this->assertEquals(
            0,
            \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->getOne("select count(*) from oxobject2category where oxcatnid = '{$this->categoryThreeID}' ")
        );
        $object2Category = oxNew(\OxidEsales\Eshop\Application\Model\Object2Category::class);
        $object2Category->oxobject2category__oxshopid = new \OxidEsales\Eshop\Core\Field(1);
        $object2Category->oxobject2category__oxobjectid = new \OxidEsales\Eshop\Core\Field($this->articleThreeID);
        $object2Category->oxobject2category__oxcatnid = new \OxidEsales\Eshop\Core\Field($this->categoryThreeID);
        $object2Category->save();
        $this->assertGreaterThan(
            1,
            \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->getOne("select count(*) from oxobject2category where oxcatnid = '{$this->categoryThreeID}' ")
        );
    }

    /* ------------------------------------------------------------------------------------------------------------- */
    /* ------------------------------ Creating / deleting elements in multishops ----------------------------------- */
    /* ------------------------------------------------------------------------------------------------------------- */

    /**
     * After creating product inside multishop it is inherited to other multishops or child shops and not inherited
     * to not related shops.
     */
    public function testProductInheritanceWhenItIsCreatedInsideMultishop()
    {
        $newArticleId = "_newTestArticle";
        $multishopID = 2;
        $independentSubshop = 4;
        $multishopChildSubshopID = 5;
        $independentMultishopID = 6;

        $newArticleData = array(
            'oxid'     => $newArticleId,
            'oxshopid' => $multishopID,
        );

        $shopRelations = oxNew(\OxidEsales\Eshop\Core\Element2ShopRelations::class, 'oxarticles');

        $shopRelations->setShopIds($multishopID);
        $initialMessage = "does not exist initially in multishop where will be created";
        $this->assertFalse($shopRelations->isInShop($newArticleId), "{$newArticleId} {$initialMessage}");

        $shopRelations->setShopIds($multishopChildSubshopID);
        $initialMessage = "does not exist initially in multishop child subshop";
        $this->assertFalse($shopRelations->isInShop($newArticleId), "{$newArticleId} {$initialMessage}");

        $shopRelations->setShopIds($independentMultishopID);
        $initialMessage = "does not exist initially in another multishop";
        $this->assertFalse($shopRelations->isInShop($newArticleId), "{$newArticleId} {$initialMessage}");

        $shopRelations->setShopIds($independentSubshop);
        $initialMessage = "does not exist initially in independent subshop";
        $this->assertFalse($shopRelations->isInShop($newArticleId), "{$newArticleId} {$initialMessage}");

        $this->_createArticle($newArticleData);

        $shopRelations->setShopIds($multishopID);
        $creationMessage = "exists after creation in multishop where was created";
        $this->assertTrue($shopRelations->isInShop($newArticleId), "{$newArticleId} {$creationMessage}");

        $shopRelations->setShopIds($multishopChildSubshopID);
        $creationMessage = "exists after creation in multishop child subshop";
        $this->assertTrue($shopRelations->isInShop($newArticleId), "{$newArticleId} {$creationMessage}");

        $shopRelations->setShopIds($independentMultishopID);
        $creationMessage = "exists after creation in another multishop";
        $this->assertTrue($shopRelations->isInShop($newArticleId), "{$newArticleId} {$creationMessage}");

        $shopRelations->setShopIds($independentSubshop);
        $creationMessage = "does not exist after creation in independent subshop";
        $this->assertFalse($shopRelations->isInShop($newArticleId), "{$newArticleId} {$creationMessage}");
    }

    /**
     * After creating category inside multishop it is inherited only to other multishops if
     * their blMultishopInherit_oxcategories option is ON.
     */
    public function testCategoryInheritanceWhenItIsCreatedInsideMultishop()
    {
        $multiShopCatId = "_testCat6";
        $newCategoryId = "_newTestCategory";
        $multishopID = 2;
        $independentSubshop = 4;
        $independentMultishopID = 6;

        $newCategoryData = array(
            'oxid'       => $newCategoryId,
            'oxshopid'   => $multishopID,
            'oxrootid'   => $newCategoryId,
            'oxparentid' => 'oxrootid',
            'oxleft'     => 1,
            'oxright'    => 2,
        );

        $shopRelations = oxNew(\OxidEsales\Eshop\Core\Element2ShopRelations::class, 'oxcategories');

        $shopRelations->setShopIds($multishopID);
        $initialMsg = "does not exist initially in multishop where will be created";
        $this->assertFalse($shopRelations->isInShop($newCategoryId), "{$newCategoryId} {$initialMsg}");

        $shopRelations->setShopIds($independentMultishopID);
        $initialMsg = "does not exist initially in another multishop";
        $this->assertFalse($shopRelations->isInShop($multiShopCatId), "{$newCategoryId} {$initialMsg}");

        $shopRelations->setShopIds($independentSubshop);
        $initialMsg = "does not exist initially in independent subshop";
        $this->assertFalse($shopRelations->isInShop($multiShopCatId), "{$newCategoryId} {$initialMsg}");

        Registry::getConfig()->saveShopConfVar('bool', "blMultishopInherit_oxcategories", 1, $independentMultishopID);

        $this->_createCategory($newCategoryData);

        $shopRelations->setShopIds($multishopID);
        $creationMsg = "exists after creation in multishop where was created";
        $this->assertTrue($shopRelations->isInShop($newCategoryId), "{$newCategoryId} {$creationMsg}");

        $shopRelations->setShopIds($independentMultishopID);
        $creationMsg = "exists after creation in another multishop with inheritance config ON";
        $this->assertTrue($shopRelations->isInShop($newCategoryId), "{$newCategoryId} {$creationMsg}");

        $shopRelations->setShopIds($independentSubshop);
        $creationMsg = "does not exist after creation in independent subshop";
        $this->assertFalse($shopRelations->isInShop($newCategoryId), "{$newCategoryId} {$creationMsg}");
    }

    /**
     * After Multishop is deleted all inheritance related data is deleted from mapping table too
     */
    public function testShopDeletedRelatedInheritanceDataIsRemovedToo()
    {
        $multishopID = 2;

        //pretest if subshop specific entry exists
        $this->assertEquals(
            1,
            \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->getOne(
                "select count(*) from oxarticles2shop as t2s inner join oxarticles as a on a.oxmapid = t2s.oxmapobjectid where t2s.oxshopid = '{$multishopID}' and a.OXID = '{$this->articleIdOne}' "
            )
        );

        $shop = oxNew(\OxidEsales\Eshop\Application\Model\Shop::class);
        $shop->delete($multishopID);

        //set different active shop id
        Registry::getConfig()->setShopId(1);

        //test if entries are removed
        $this->assertEquals(
            0,
            \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->getOne(
                "select count(*) from oxarticles2shop as t2s inner join oxarticles as a on a.oxmapid = t2s.oxmapobjectid where t2s.oxshopid = '{$multishopID}' and a.OXID = '{$this->articleIdOne}' "
            )
        );
    }

    /**
     * Changing prices of products in multishop, then in other subshops, does not intersect.
     */
    public function testProductPriceChangingInMultishopDoesNotIntersect()
    {
        $baseshopID = 1;
        $multishopID = 2;
        $baseshopChildID = 3;
        $independentMultishopID = 6;

        $config = Registry::getConfig();

        $article = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
        $article->load($this->articleIdOne);

        $shopRelations = oxNew(\OxidEsales\Eshop\Core\Element2ShopRelations::class, 'oxarticles');

        /* Check that product exists in all shops we need */
        $shopRelations->setShopIds($baseshopID);
        $this->assertTrue(
            $shopRelations->isInShop($this->articleIdOne),
            "{$this->articleIdOne} exists in shop {$baseshopID}"
        );

        $shopRelations->setShopIds($multishopID);
        $this->assertTrue(
            $shopRelations->isInShop($this->articleIdOne),
            "{$this->articleIdOne} exists in shop {$multishopID}"
        );

        $shopRelations->setShopIds($baseshopChildID);
        $this->assertTrue(
            $shopRelations->isInShop($this->articleIdOne),
            "{$this->articleIdOne} exists in shop {$baseshopChildID}"
        );

        $shopRelations->setShopIds($independentMultishopID);
        $this->assertTrue(
            $shopRelations->isInShop($this->articleIdOne),
            "{$this->articleIdOne} exists in shop {$independentMultishopID}"
        );

        /* Set different prices */
        $config->setShopId($baseshopID);
        $article = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
        $article->load($this->articleIdOne);
        $this->assertSame($baseshopID, $config->getActiveShop()->getShopId(), "Active shop is {$baseshopID}");
        $article->oxarticles__oxprice = new \OxidEsales\Eshop\Core\Field(25.00);
        $article->save();
        $this->assertSame(25.00, $article->getPrice()->getBruttoPrice(), "{$this->articleIdOne} price {$baseshopID}");

        $config->setShopId($multishopID);

        $article = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
        $article->load($this->articleIdOne);
        $article->oxarticles__oxprice = new \OxidEsales\Eshop\Core\Field(99.00);
        $article->save();
        $this->assertSame($multishopID, $config->getShopId(), "Active shop is {$multishopID}");
        $this->assertSame(99.00, $article->getPrice()->getBruttoPrice(), "{$this->articleIdOne} price {$multishopID}");

        $config->setShopId($baseshopChildID);

        $article = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
        $article->load($this->articleIdOne);
        $article->oxarticles__oxprice = new \OxidEsales\Eshop\Core\Field(1.00);
        $article->save();
        $this->assertSame($baseshopChildID, $config->getShopId(), "Active shop is {$baseshopChildID}");
        $this->assertSame(
            1.00,
            $article->getPrice()->getBruttoPrice(),
            "{$this->articleIdOne} price {$baseshopChildID}"
        );

        $config->setShopId($independentMultishopID);

        $article = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
        $article->load($this->articleIdOne);
        $article->oxarticles__oxprice = new \OxidEsales\Eshop\Core\Field(399.00);
        $article->save();
        $this->assertSame($independentMultishopID, $config->getShopId(), "Active shop is {$independentMultishopID}");
        $this->assertSame(
            399.00,
            $article->getPrice()->getBruttoPrice(),
            "{$this->articleIdOne} price {$independentMultishopID}"
        );

        /* Recheck the prices */
        $config->setShopId($baseshopID);

        $article = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
        $article->load($this->articleIdOne);

        $this->assertSame(25.00, $article->getPrice()->getBruttoPrice(), "{$this->articleIdOne} price {$baseshopID}");

        $config->setShopId($multishopID);

        $article = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
        $article->load($this->articleIdOne);

        $this->assertSame(99.00, $article->getPrice()->getBruttoPrice(), "{$this->articleIdOne} price {$multishopID}");

        $config->setShopId($baseshopChildID);

        $article = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
        $article->load($this->articleIdOne);

        $this->assertSame(
            1.00,
            $article->getPrice()->getBruttoPrice(),
            "{$this->articleIdOne} price {$baseshopChildID}"
        );

        $config->setShopId($independentMultishopID);

        $article = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
        $article->load($this->articleIdOne);

        $this->assertSame(
            399.00,
            $article->getPrice()->getBruttoPrice(),
            "{$this->articleIdOne} price {$independentMultishopID}"
        );
    }

    /* ------------------------------------------------------------------------------------------------------------- */
    /* ----------------------------------------------- Edge cases -------------------------------------------------- */
    /* ------------------------------------------------------------------------------------------------------------- */

    /**
     * Test case for bug:
     * New subshop of multishop type is created with oxisinherited config OFF and no parent.
     * blMultishopInherit_oxcategories set on.
     * New subshop of multishop type is created with oxisinherited config OFF and no parent.
     * blMultishopInherit_oxcategories is off, but it already inherits all categories
     */
    public function testCreateMultishops()
    {
        $firstMultishop = 100;
        $secondMultishop = 101;

        $config = $this->getConfig();

        $firstMultishopData = array(
            'oxid'          => $firstMultishop,
            'oxname'        => 'Multishop',
            'oxparentid'    => 0,
            'oxismultishop' => 1,
        );

        $this->_createShop($firstMultishopData);

        $config->saveShopConfVar('bool', 'blMultishopInherit_oxcategories', 1, $firstMultishop);

        $this->assertTrue(
            (bool) $config->getShopConfVar('blMultishopInherit_oxcategories', $firstMultishop),
            "Multishop inheritance config is on in shop {$firstMultishop}"
        );

        $secondMultishopData = array(
            'oxid'          => $secondMultishop,
            'oxname'        => 'Multishop',
            'oxparentid'    => 0,
            'oxismultishop' => 1,
        );

        $this->_createShop($secondMultishopData);

        $this->assertFalse(
            (bool) $config->getShopConfVar('blMultishopInherit_oxcategories', $secondMultishop),
            "Multishop inheritance config by default is set to false in shop {$secondMultishop}"
        );
    }
}
