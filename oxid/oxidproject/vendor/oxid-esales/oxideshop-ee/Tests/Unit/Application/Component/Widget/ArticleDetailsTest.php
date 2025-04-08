<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */
namespace OxidEsales\EshopEnterprise\Tests\Unit\Application\Component\Widget;

/**
 * Tests for ArticleDetails class
 */
class ArticleDetailsTest extends \oxUnitTestCase
{
    public function testCheckVariantAccessRights_noRights()
    {
        $variant = $this->getMock(\OxidEsales\Eshop\Application\Model\Article::class, array('canView'));
        $variant->expects($this->atLeastOnce())->method('canView')->will($this->returnValue(false));

        $variants = array();
        $variants[] = $variant;

        $view = $this->getMock($this->getProxyClassName('oxwArticleDetails'), array('getRights'));
        $view->expects($this->once())->method('getRights')->will($this->returnValue(true));
        $resp = $view->UNITcheckVariantAccessRights($variants);

        $this->assertSame(array(), $resp);
    }

    public function testCheckVariantAccessRights_withRights()
    {
        $variant = $this->getMock(\OxidEsales\Eshop\Application\Model\Article::class, array('canView'));
        $variant->expects($this->atLeastOnce())->method('canView')->will($this->returnValue(true));

        $variants = array();
        $variants[] = $variant;

        $view = $this->getMock($this->getProxyClassName('oxwArticleDetails'), array('getRights'));
        $view->expects($this->once())->method('getRights')->will($this->returnValue(true));
        $resp = $view->UNITcheckVariantAccessRights($variants);

        $this->assertSame($variants, $resp);
    }
}
