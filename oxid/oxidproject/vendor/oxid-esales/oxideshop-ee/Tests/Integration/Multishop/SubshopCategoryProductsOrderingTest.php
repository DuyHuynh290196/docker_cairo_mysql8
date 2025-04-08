<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\EshopEnterprise\Tests\Integration\Multishop;

use OxidEsales\Eshop\Application\Controller\Admin\CategoryOrderAjax;
use OxidEsales\Eshop\Application\Model\Article;
use OxidEsales\Eshop\Application\Model\Category;
use OxidEsales\Eshop\Application\Model\Object2Category;
use OxidEsales\Eshop\Application\Model\Shop;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\EshopCommunity\Application\Controller\ArticleListController;
use OxidEsales\EshopEnterprise\Tests\Integration\SubShopTrait;

final class SubshopCategoryProductsOrderingTest extends MultishopTestCase
{
    use SubShopTrait;

    private $mainShopId = 1;
    private $subShopId = 2;
    private $categoryId = 'test_category';
    private $productId1 = 'test_product_1';
    private $productId2 = 'test_product_2';
    private $productId3 = 'test_product_3';

    protected function setUp(): void
    {
        parent::setUp();
        $this->createSubShop($this->subShopId);
        $this->prepareConfigs();
        $this->createCategory();
        $this->createProducts();
        $this->assignProducts();
        $this->generateSubshopViews();
    }

    public function testOrderInAssignedCategoryWillReorderProductsInSubshop(): void
    {
        $this->switchToSubshop();
        $articleList = $this->getArticleListOnFrontend();
        $this->assertSame($this->productId1, reset($articleList)->getId());
        $this->assertSame($this->productId2, next($articleList)->getId());
        $this->assertSame($this->productId3, next($articleList)->getId());

        $this->changeOrderOfCategoryProductsInMainShop();

        $this->switchToSubshop();
        $articleList = $this->getArticleListOnFrontend();
        $this->assertSame($this->productId2, reset($articleList)->getId());
        $this->assertSame($this->productId3, next($articleList)->getId());
        $this->assertSame($this->productId1, next($articleList)->getId());
    }

    private function createCategory(): void
    {
        $category = oxNew(Category::class);
        $category->assign([
            'oxcategories__oxid' => $this->categoryId,
            'oxcategories__oxshopid' => $this->mainShopId,
            'oxcategories__oxparentid' => 'oxrootid',
            'oxcategories__oxleft' => 1,
            'oxcategories__oxright' => 2,
        ]);
        $category->save();
        $category->assignToShop($this->subShopId);
    }

    private function createProducts(): void
    {
        $productData = [
            [
                'oxarticles__oxid' => $this->productId1,
                'oxarticles__oxshopid' => $this->mainShopId,
                'oxarticles__oxparentid' => null,
            ],
            [
                'oxarticles__oxid' => $this->productId2,
                'oxarticles__oxshopid' => $this->mainShopId,
                'oxarticles__oxparentid' => null,
            ],
            [
                'oxarticles__oxid' => $this->productId3,
                'oxarticles__oxshopid' => $this->mainShopId,
                'oxarticles__oxparentid' => null,
            ],
        ];
        foreach ($productData as $productDatum) {
            $product = oxNew(Article::class);
            $product->setAdminMode(true);
            $product->assign($productDatum);
            $product->save();
            $product->assignToShop($this->subShopId);
        }
    }

    private function assignProducts(): void
    {
        $object2CategoryData = [
            [
                'oxobject2category__oxobjectid' => $this->productId1,
                'oxobject2category__oxcatnid' => $this->categoryId,
            ],
            [
                'oxobject2category__oxobjectid' => $this->productId2,
                'oxobject2category__oxcatnid' => $this->categoryId,
            ],
            [
                'oxobject2category__oxobjectid' => $this->productId3,
                'oxobject2category__oxcatnid' => $this->categoryId,
            ],
        ];
        foreach ($object2CategoryData as $object2CategoryDatum) {
            $object2Category = oxNew(Object2Category::class);
            $object2Category->assign($object2CategoryDatum);
            $object2Category->setShopId($this->subShopId);
            $object2Category->save();
        }
    }

    private function generateSubshopViews(): void
    {
        $mallInherit = [];
        foreach (Registry::getConfig()->getConfigParam('aMultiShopTables') as $tableName) {
            $mallInherit[$tableName] = Registry::getConfig()->getShopConfVar(
                "blMallInherit_$tableName",
                $this->mainShopId
            );
        }
        $this->switchToSubshop();
        $shop = oxNew(Shop::class);
        $shop->load($this->subShopId);
        $shop->generateViews(false, $mallInherit);
    }

    private function prepareConfigs(): void
    {
        Registry::getConfig()->setConfigParam('iNrofCatArticles', 3);
        Registry::getConfig()->setConfigParam('blVariantParentBuyable', true);
        Registry::getConfig()->setConfigParam('blUseStock', false);
    }

    private function getArticleListOnFrontend(): array
    {
        $articleListController = oxNew(ArticleListController::class);
        $articleListController->setCategoryId($this->categoryId);
        return $articleListController->getArticleList()->getArray();
    }

    private function switchToSubshop(): void
    {
        Registry::getConfig()->setShopId($this->subShopId);
    }

    private function switchToMainShop(): void
    {
        Registry::getConfig()->setShopId($this->mainShopId);
    }

    private function changeOrderOfCategoryProductsInMainShop(): void
    {
        $this->switchToMainShop();
        $orderAjaxController = oxNew(CategoryOrderAjax::class);
        $_POST['oxid'] = $this->categoryId;
        Registry::getSession()->setVariable(
            'neworder_sess',
            [
                $this->productId2,
                $this->productId3,
                $this->productId1,
            ]
        );
        $orderAjaxController->saveNewOrder();
    }
}
