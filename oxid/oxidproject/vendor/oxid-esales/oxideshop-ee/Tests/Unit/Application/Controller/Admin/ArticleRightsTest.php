<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */
namespace OxidEsales\EshopEnterprise\Tests\Unit\Application\Controller\Admin;

use OxidEsales\TestingLibrary\UnitTestCase;
use oxArticle;

/**
 * Tests for Article_Rights class
 */
class ArticleRightsTest extends UnitTestCase
{
    /**
     * Article_Rights::Render() test case
     *
     * @return null
     */
    public function testRender()
    {
        $oView = oxNew(\OxidEsales\Eshop\Application\Controller\Admin\ArticleRights::class);
        $sTplName = $oView->render();

        $aViewData = $oView->getViewData();
        $this->assertTrue($aViewData["edit"] instanceof \OxidEsales\EshopCommunity\Application\Model\Article);

        $this->assertEquals('article_rights.tpl', $sTplName);
    }

}
