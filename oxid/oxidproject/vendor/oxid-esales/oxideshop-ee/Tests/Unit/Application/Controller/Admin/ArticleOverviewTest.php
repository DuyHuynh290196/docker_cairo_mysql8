<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Tests\Unit\Application\Controller\Admin;

class ArticleOverviewTest extends \OxidEsales\TestingLibrary\UnitTestCase
{
    /**
     * Article_Overview::GetVariantsWhereField() test case.
     */
    public function testGetVariantsWhereField()
    {
        $query = " or oxorderarticles.oxartid='1661-01' or oxorderarticles.oxartid='1661-02'";
        $articleOverview = oxNew(\OxidEsales\Eshop\Application\Controller\Admin\ArticleOverview::class);
        $this->assertEquals($query, $articleOverview->UNITgetVariantsWhereField("1661"));
    }
}
