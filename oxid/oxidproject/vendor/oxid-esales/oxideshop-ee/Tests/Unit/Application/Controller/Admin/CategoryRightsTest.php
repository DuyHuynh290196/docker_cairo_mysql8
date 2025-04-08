<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

/**
 * Tests for Category_Rights class
 */
class Unit_Admin_CategoryRightsTest extends OxidTestCase
{
    /**
     * Category_Rights::Render() test case
     */
    public function testRender()
    {
        oxTestModules::addFunction('oxcategory', 'load', '{ $this->blIsDerived = true; }');
        $this->setRequestParameter("oxid", "testId");

        // testing..
        $view = oxNew(\OxidEsales\Eshop\Application\Controller\Admin\CategoryRights::class);
        $templateName = $view->render();

        // testing view data
        $viewData = $view->getViewData();
        $this->assertTrue($viewData["edit"] instanceof \OxidEsales\EshopCommunity\Application\Model\Category);
        $this->assertTrue(isset($viewData["readonly"]));
        $this->assertEquals('category_rights.tpl', $templateName);
    }

}
