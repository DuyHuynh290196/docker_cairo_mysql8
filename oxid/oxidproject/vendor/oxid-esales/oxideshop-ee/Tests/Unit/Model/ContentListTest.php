<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Tests\Unit\Model;

use \OxidEsales\TestingLibrary\UnitTestCase;

class ContentListTest extends UnitTestCase
{
    /**
     * Testing category menu with other shop (buglist_327)
     */
    public function testLoadCatMenuesIfOtherShop()
    {
        $this->getConfig()->setShopId(2);

        $contentList = oxNew(\OxidEsales\Eshop\Application\Model\ContentList::class);
        // testing if there is what to test
        $this->assertNull($contentList->LoadCatMenues());
    }
}
