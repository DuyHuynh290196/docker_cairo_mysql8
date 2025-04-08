<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Tests\Unit\Application\Controller\Admin;

use \OxidEsales\TestingLibrary\UnitTestCase;

/**
 * Tests for Attribute_Mall class.
 */
class ContentListTest extends UnitTestCase
{
    /**
     * Content_List::PrepareWhereQuery() test case
     *
     * @return null
     */
    public function testPrepareWhereQueryUserDefinedFolder()
    {
        $this->setRequestParameter("folder", "testFolder");
        $viewName = getviewName("oxcontents");

        // defining parameters
        $view = oxNew(\OxidEsales\Eshop\Application\Controller\Admin\ContentList::class);
        $realQuery = $view->UNITprepareWhereQuery(array(), "");

        $sql = " and {$viewName}.oxfolder = 'testFolder'";
        $sql .= " and {$viewName}.oxshopid = '" . $this->getConfig()->getShopId() . "'";

        $this->assertEquals($sql, $realQuery);
    }
}
