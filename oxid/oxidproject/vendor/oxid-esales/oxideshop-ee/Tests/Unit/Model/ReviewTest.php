<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Tests\Unit\Model;

use \oxField;

require_once TEST_LIBRARY_HELPERS_PATH . 'oxCacheHelper.php';

class ReviewTest extends \oxUnitTestCase
{
    public function testResetCacheAdminMode()
    {
        $oReview = $this->getMock(\OxidEsales\Eshop\Application\Model\Review::class, array('_resetCache'));
        $oReview->expects($this->once())->method('_resetCache')->with($this->equalTo('xxx'));
        $oReview->setAdminMode(true);
        $oReview->UNITresetCache('xxx');
    }

    public function testResetCacheNonAdminMode()
    {
        oxAddClassModule('oxCacheHelper', 'oxCache');

        $oReview = oxNew(\OxidEsales\Eshop\Application\Model\Review::class);
        $oReview->setAdminMode(false);
        $oReview->oxreviews__oxtype = new \OxidEsales\Eshop\Core\Field('oxarticle', \OxidEsales\Eshop\Core\Field::T_RAW);
        $oReview->oxreviews__oxobjectid = new \OxidEsales\Eshop\Core\Field('xxx', \OxidEsales\Eshop\Core\Field::T_RAW);
        try {
            $oReview->UNITresetCache();
        } catch (\Exception $oE) {
            $this->assertEquals('a:1:{s:3:"xxx";s:4:"anid";}', $oE->getMessage());

            return;
        }
        $this->fail('error exec. testResetCacheNonAdminMode');
    }

    public function testGetObjectIdAndType()
    {
        // inserting few test records
        $oRev = oxNew(\OxidEsales\Eshop\Application\Model\Review::class);
        $oRev->setId('id1');
        $oRev->oxreviews__oxactive = new \OxidEsales\Eshop\Core\Field(1);
        $oRev->oxreviews__oxobjectid = new \OxidEsales\Eshop\Core\Field('xx1');
        $oRev->oxreviews__oxtype = new \OxidEsales\Eshop\Core\Field('oxarticle');
        $oRev->oxreviews__oxtext = new \OxidEsales\Eshop\Core\Field('revtext');
        $oRev->save();

        $oRev = oxNew(\OxidEsales\Eshop\Application\Model\Review::class);
        $oRev->setId('id2');
        $oRev->oxreviews__oxactive = new \OxidEsales\Eshop\Core\Field(1);
        $oRev->oxreviews__oxobjectid = new \OxidEsales\Eshop\Core\Field('xx2');
        $oRev->oxreviews__oxtype = new \OxidEsales\Eshop\Core\Field('oxrecommlist');
        $oRev->oxreviews__oxtext = new \OxidEsales\Eshop\Core\Field('revtext');
        $oRev->save();

        $oRev = oxNew(\OxidEsales\Eshop\Application\Model\Review::class);
        $oRev->load('id1');
        $this->assertEquals('anid', $oRev->UNITgetObjectKey());

        $oRev = oxNew(\OxidEsales\Eshop\Application\Model\Review::class);
        $oRev->load('id2');

        $this->assertEquals('recommid', $oRev->UNITgetObjectKey());
    }
}
