<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Tests\Unit\Model;

use OxidEsales\Eshop\Application\Model\Order;
use OxidEsales\Eshop\Core\Field;
use \OxidEsales\TestingLibrary\UnitTestCase;

class OrderArticleTest extends UnitTestCase
{
    protected $orderArticle;

    /**
     * Initialize the fixture.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $order = oxNew(Order::class);
        $order->setId('_orderArticleId');
        $order->save();

        $this->orderArticle = oxNew(\OxidEsales\Eshop\Application\Model\OrderArticle::class);
        $this->orderArticle->setId('_testOrderArticleId');
        $this->orderArticle->oxorderarticles__oxartid = new Field('_testArticleId', Field::T_RAW);
        $this->orderArticle->oxorderarticles__oxorderid = new Field($order->getId());
        $this->orderArticle->save();
    }

    public function testCheckForVpe()
    {
        $oOrderArticle = oxNew(\OxidEsales\Eshop\Application\Model\OrderArticle::class);
        $this->assertEquals(999, $oOrderArticle->checkForVpe(999));
    }

    /**
     * Testing ERP status getter
     */
    public function testGetStatus()
    {
        $oOrderArticle = oxNew(\OxidEsales\Eshop\Application\Model\OrderArticle::class);
        $oOrderArticle->load('_testOrderArticleId');
        $oOrderArticle = $this->getProxyClass('oxorderarticle');
        $this->assertNull($oOrderArticle->getStatus());
        $this->assertNull($oOrderArticle->getNonPublicVar('_aStatuses'));

        $aParams = array('somekey' => 'somevalue');
        $oOrderArticle->oxorderarticles__oxerpstatus = new Field(serialize($aParams), Field::T_RAW);
        $this->assertEquals($aParams, $oOrderArticle->getStatus());
        $this->assertEquals($aParams, $oOrderArticle->getNonPublicVar('_aStatuses'));
    }

    /*
     * Test correct serializing and loading oxerpstatus values
     */
    public function testSerializingValues()
    {
        $aTestArr = array("te\"st", "test2");
        $sParams = serialize($aTestArr);

        $this->orderArticle->oxorderarticles__oxerpstatus = new Field($sParams, Field::T_RAW);
        $this->orderArticle->save();

        $oOrderArticle = oxNew(\OxidEsales\Eshop\Application\Model\OrderArticle::class);
        $oOrderArticle->load('_testOrderArticleId');

        $this->assertEquals($aTestArr, $oOrderArticle->getStatus());
    }

    /*
     * Test _setFieldData - correctly sets data type to T_RAW to oxpersparam and oxerpstatus fields
     * M #275
     */
    public function test_setFieldData()
    {
        $this->orderArticle->oxorderarticles__oxerpstatus = new Field('" &', Field::T_RAW);
        $this->orderArticle->save();

        $sSQL = "select * from oxorderarticles where oxid = '_testOrderArticleId' ";
        $rs = \OxidEsales\Eshop\Core\DatabaseProvider::getDb(\OxidEsales\Eshop\Core\DatabaseProvider::FETCH_MODE_ASSOC)->select($sSQL);

        $oOrderArticle = oxNew(\OxidEsales\Eshop\Application\Model\OrderArticle::class);
        $oOrderArticle->assign($rs->fields); // field names are in upercase

        $this->assertEquals('" &', $oOrderArticle->oxorderarticles__oxerpstatus->rawValue);
    }
}
