<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Tests\Unit\Model;

use oxDb;
use OxidEsales\TestingLibrary\UnitTestCase;

class SeoEncoderTest extends UnitTestCase
{
    public function testCopyStaticUrlsForOtherThanBaseShop()
    {
        \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute('delete from oxseo where oxshopid != "1" ');

        $oEncoder = oxNew(\OxidEsales\Eshop\Core\SeoEncoder::class);
        $oEncoder->copyStaticUrls('2');

        $seoId = \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->getOne('select oxident from oxseo where oxshopid = "2" and oxlang="1" and oxstdurl="index.php?cl=account_wishlist"');

        // checking if new records are copied
        $this->assertEquals('023abc17c853f9bccc201c5afd549a92', $seoId);
    }
}
