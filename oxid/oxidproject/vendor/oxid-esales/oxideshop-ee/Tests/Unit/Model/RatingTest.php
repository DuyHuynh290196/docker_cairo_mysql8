<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Tests\Unit\Model;

use \oxField;

class RatingTest extends \oxUnitTestCase
{
    public function testGetObjectIdAndType()
    {
        // inserting few test records
        $rating = oxNew(\OxidEsales\Eshop\Application\Model\Rating::class);
        $rating->setId('id1');
        $rating->oxratings__oxobjectid = new \OxidEsales\Eshop\Core\Field('xx1');
        $rating->oxratings__oxtype = new \OxidEsales\Eshop\Core\Field('oxarticle');
        $rating->oxratings__oxrating = new \OxidEsales\Eshop\Core\Field(1);
        $rating->save();

        $rating = oxNew(\OxidEsales\Eshop\Application\Model\Rating::class);
        $rating->setId('id2');
        $rating->oxratings__oxobjectid = new \OxidEsales\Eshop\Core\Field('xx2');
        $rating->oxratings__oxtype = new \OxidEsales\Eshop\Core\Field('oxrecommlist');
        $rating->oxratings__oxrating = new \OxidEsales\Eshop\Core\Field(2);
        $rating->save();

        $rating = oxNew(\OxidEsales\Eshop\Application\Model\Rating::class);
        $rating->load('id1');
        $this->assertEquals('id1', $rating->getId());
        $this->assertEquals('xx1', $rating->getObjectId());
        $this->assertEquals('oxarticle', $rating->getObjectType());
        $this->assertEquals('anid', $rating->UNITgetObjectKey());

        $rating = oxNew(\OxidEsales\Eshop\Application\Model\Rating::class);
        $rating->load('id2');
        $this->assertEquals('id2', $rating->getId());
        $this->assertEquals('xx2', $rating->getObjectId());
        $this->assertEquals('oxrecommlist', $rating->getObjectType());
        $this->assertEquals('recommid', $rating->UNITgetObjectKey());
    }
}
