<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Tests\Integration\Multishop;

/**
 * Element2ShopRelations integration test
 */
class Element2ShopRelationsTest extends MultishopTestCase
{
    /**
     * Test case directory array
     *
     * @var array
     */
    protected $testCaseDir = array(
        'TestCases/Element2ShopRelations',
    );

    /**
     * @var array
     */
    protected $fixtureTemplate = array(
        'shops'    => array(),
        'articles' => array(),
        'setup'    => array(
            'articles2shop' => array(),
        ),
        'actions'  => array(
            'add_to_shop'                => array(),
            'remove_from_shop'           => array(),
            'remove_from_all_shops'      => array(),
            'copy_inheritance'           => array(),
            'inherit_from_shop'          => array(),
            'remove_inherited_from_shop' => array(),
        ),
        'expected' => array(
            'article_in_shop'     => array(),
            'article_not_in_shop' => array(),
        ),
    );

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

        $this->getConfig()->setShopId(1);

        $setup = $testCase['setup'];

        $shopRelations = oxNew(\OxidEsales\Eshop\Core\Element2ShopRelations::class, null);

        foreach ($setup['articles2shop'] as $articleId => $shopIds) {
            $article = $this->_getArticleById($articleId);

            $shopRelations->setShopIds($shopIds);
            $shopRelations->setItemType($article->getCoreTableName());
            $shopRelations->addToShop($article->getId());
        }
    }

    /**
     * Checks result.
     *
     * @param array $testCase Test cases with expected results.
     */
    protected function _checkResults($testCase) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $expected = $testCase['expected'];

        $shopRelations = oxNew(\OxidEsales\Eshop\Core\Element2ShopRelations::class, null);

        foreach ($expected['article_in_shop'] as $articleId => $expectedShopIds) {
            $article = $this->_getArticleById($articleId);

            $shopRelations->setItemType($article->getCoreTableName());

            $shopIdsFromShopRelations = $shopRelations->getItemAssignedShopIds($article->getId());

            $expectedShopIdsBuf = $expectedShopIds;
            sort($expectedShopIdsBuf);
            sort($shopIdsFromShopRelations);

            $this->assertEquals(
                $expectedShopIdsBuf,
                $shopIdsFromShopRelations,
                "Article {$article->getId()} is expected to be assigned to shops " . implode(', ', $expectedShopIds)
                . " but got " . implode(', ', $shopIdsFromShopRelations)
            );

            foreach ($expectedShopIds as $shopId) {
                $shopRelations->setShopIds($shopId);
                $this->assertTrue(
                    $shopRelations->isInShop($article->getId()),
                    "Article {$article->getId()} is expected to be assigned to shop $shopId."
                );
            }
        }

        foreach ($expected['article_not_in_shop'] as $articleId => $expectedShopIds) {
            $article = $this->_getArticleById($articleId);
            $shopRelations->setItemType($article->getCoreTableName());

            foreach ($expectedShopIds as $shopId) {
                $shopRelations->setShopIds($shopId);
                $this->assertFalse(
                    $shopRelations->isInShop($article->getId()),
                    "Article {$article->getId()} is not expected to be assigned to shop $shopId."
                );
            }
        }
    }

    /**
     * Tears down the fixture.
     */
    protected function tearDown(): void
    {
        $this->_deleteArticles();
        $this->_deleteShops();

        parent::tearDown();
    }


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
     * Tests add item to shop or list of shops.
     *
     * @param array $testCase Test cases with expected results.
     *
     * @dataProvider dpData
     */
    public function testElement2ShopRelations($testCase)
    {
        $this->_setupFixture($testCase);

        // perform actions to test

        $actions = $testCase['actions'];

        $shopRelations = oxNew(\OxidEsales\Eshop\Core\Element2ShopRelations::class, null);
        foreach ($actions['add_to_shop'] as $articleId => $shopIds) {
            $article = $this->_getArticleById($articleId);
            $shopRelations->setItemType($article->getCoreTableName());

            $shopRelations->setShopIds($shopIds);
            $shopRelations->addToShop($article->getId());
        }

        $shopRelations = oxNew(\OxidEsales\Eshop\Core\Element2ShopRelations::class, null);
        foreach ($actions['remove_from_shop'] as $articleId => $shopIds) {
            $article = $this->_getArticleById($articleId);
            $shopRelations->setItemType($article->getCoreTableName());

            $shopRelations->setShopIds($shopIds);
            $shopRelations->removeFromShop($article->getId());
        }

        $shopRelations = oxNew(\OxidEsales\Eshop\Core\Element2ShopRelations::class, null);
        foreach ($actions['remove_from_all_shops'] as $articleId) {
            $article = $this->_getArticleById($articleId);
            $shopRelations->setItemType($article->getCoreTableName());

            $shopRelations->removeFromAllShops($article->getId());
        }

        $shopRelations = oxNew(\OxidEsales\Eshop\Core\Element2ShopRelations::class, null);
        foreach ($actions['copy_inheritance'] as $articleId => $sourceArticleId) {
            $article = $this->_getArticleById($articleId);
            $shopRelations->setItemType($article->getCoreTableName());
            $sourceArticle = $this->_getArticleById($sourceArticleId);

            $shopRelations->copyInheritance(
                $sourceArticle->getId(),
                $article->getId()
            );
        }

        $shopRelations = oxNew(\OxidEsales\Eshop\Core\Element2ShopRelations::class, null);
        foreach ($actions['inherit_from_shop'] as $shopId => $parentShopId) {
            $shopRelations->setShopIds($shopId);
            $shopRelations->setItemType('oxarticles');
            $shopRelations->inheritFromShop($parentShopId);
        }

        $shopRelations = oxNew(\OxidEsales\Eshop\Core\Element2ShopRelations::class, null);
        foreach ($actions['remove_inherited_from_shop'] as $shopId => $parentShopId) {
            $shopRelations->setShopIds($shopId);
            $shopRelations->setItemType('oxarticles');
            $shopRelations->removeInheritedFromShop($parentShopId);
        }

        $this->_checkResults($testCase);
    }

    /**
     * Tests OxShopRelation::isInShop() getter
     */
    public function testInShopDemodata()
    {
        $shopRelations = oxNew(\OxidEsales\Eshop\Core\Element2ShopRelations::class, "oxarticles");
        $shopRelations->setShopIds(1);
        $this->assertFalse($shopRelations->isInShop("nonExistingTestId"));
        $sql = "INSERT INTO oxarticles (oxid, oxtitle) values ('_test123', '_testArticle');";
        $this->addToDatabase($sql, 'oxarticles');
        $this->assertTrue($shopRelations->isInShop("_test123"));
    }

}
