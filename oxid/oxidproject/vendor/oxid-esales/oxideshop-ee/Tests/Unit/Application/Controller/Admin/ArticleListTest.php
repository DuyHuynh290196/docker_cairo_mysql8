<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Tests\Unit\Application\Controller\Admin;

/**
 * Class ArticleListTest.
 */
class ArticleListTest extends \OxidEsales\TestingLibrary\UnitTestCase
{
    /**
     * Article_List::UnassignEntry() test case.
     */
    public function testUnassignEntry()
    {
        \oxTestModules::addFunction("oxUtilsServer", "getOxCookie", "{return array(1);}");
        \oxTestModules::addFunction("oxUtils", "checkAccessRights", "{return true;}");
        \oxTestModules::addFunction('oxarticle', 'load', '{ return true; }');

        $oView = $this->getMock(\OxidEsales\Eshop\Application\Controller\Admin\ArticleList::class, array("resetContentCache", "_authorize"));
        $oView->expects($this->any())->method('_authorize')->will($this->returnValue(true));
        $oView->expects($this->once())->method('resetContentCache');
        $oView->unassignEntry();
    }
}
