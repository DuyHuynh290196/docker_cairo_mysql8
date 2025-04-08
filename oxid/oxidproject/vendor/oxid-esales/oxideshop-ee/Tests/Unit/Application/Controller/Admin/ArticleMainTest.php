<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Tests\Unit\Application\Controller\Admin;

/**
 * Unit test class for ArticleMain.
 */
class ArticleMainTest extends \OxidEsales\TestingLibrary\UnitTestCase
{
    /**
     * Setup fixture
     */
    public function setup(): void
    {
        $this->addToDatabase("replace into oxcategories set oxid='_testCategory1', oxshopid='1', oxtitle='_testCategory1'", 'oxcategories');
    }

    public function testCloneArticle()
    {
        \oxTestModules::addFunction('oxarticle', 'load', '{ $this->oxarticles__oxshopid = new \OxidEsales\Eshop\Core\Field(); return true; }');
        \oxTestModules::addFunction('oxarticle', 'save', '{ return true; }');
        $this->setRequestParameter("oxid", "testId");

        $articleMain = $this->getMock(\OxidEsales\Eshop\Application\Controller\Admin\ArticleMain::class, array("copyArticle", "setEditObjectId"));
        $articleMain->expects($this->once())->method('copyArticle')->will($this->returnValue("newTestId"));
        $articleMain->expects($this->once())->method('setEditObjectId')->with($this->equalTo("newTestId"));
        $articleMain->cloneArticle();
    }

    /**
     * Tests that when category is assigned to different shop, it gets proper oxobject2category relations.
     */
    public function testAddToCategoryAndAssignToOtherShopGenerateOneEntry()
    {
        $articleMain = oxNew(\OxidEsales\Eshop\Application\Controller\Admin\ArticleMain::class);
        $articleMain->addToCategory('_testCategory1', '_testArticle1');

        $article = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
        $article->load('_testArticle1');
        $article->assignToShop(2);


        $category = oxNew(\OxidEsales\Eshop\Application\Model\Category::class);
        $category->load('_testCategory1');
        $category->assignToShop(2);

        $count = \OxidEsales\Eshop\Core\DatabaseProvider::getDb(\OxidEsales\Eshop\Core\DatabaseProvider::FETCH_MODE_ASSOC)->getOne("select count(*) from oxobject2category where OXCATNID = '_testCategory1' AND OXOBJECTID = '_testArticle1' and OXSHOPID = 2");
        $this->assertEquals(1, $count);
    }
}
