<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Tests\Unit\Model;

use OxidEsales\Eshop\Core\DatabaseProvider;
use \OxidEsales\TestingLibrary\UnitTestCase;
use \oxDb;
use \oxField;
use \stdClass;

class Field2ShopTest extends UnitTestCase
{
    /**
     * oxField2Shop::_getMultiShopFields() test case
     */
    public function testGetMultiShopFieldsMultiLanguageOn()
    {
        $aMultishopArticleFields = $this->getConfig()->getConfigParam('aMultishopArticleFields');
        $aMultishopArticleFields[] = "oxtitle";
        $this->setConfigParam('aMultishopArticleFields', $aMultishopArticleFields);

        $oF2S = oxNew(\OxidEsales\Eshop\Application\Model\Field2Shop::class);
        $oF2S->setEnableMultilang(true);

        $this->assertEquals($aMultishopArticleFields, $oF2S->UNITgetMultiShopFields());
    }

    /**
     * oxField2Shop::_getMultiShopFields() test case
     */
    public function testGetMultiShopFieldsMultiLanguageOff()
    {
        $aMultishopArticleFields = $this->getConfig()->getConfigParam('aMultishopArticleFields');
        $aMultishopArticleFields[] = "oxtitle";
        $this->setConfigParam('aMultishopArticleFields', $aMultishopArticleFields);

        $oField1 = new stdClass;
        $oField1->name = 'oxtitle';
        $oField2 = new stdClass;
        $oField2->name = 'oxtitle_1';
        $oField3 = new stdClass;
        $oField3->name = 'oxtitle_2';
        $oField4 = new stdClass;
        $oField4->name = 'oxtitle_3';

        $oConfig = $this->getMock(\OxidEsales\Eshop\Core\Config::class, array("getConfigParam"));
        $oConfig->expects($this->once())->method("getConfigParam")->with($this->equalTo("aMultishopArticleFields"))->will($this->returnValue($aMultishopArticleFields));

        $oF2S = $this->getMock(\OxidEsales\Eshop\Application\Model\Field2Shop::class, array("_getFieldStatus", "getConfig", 'fetchTableFields'), array(), '', false);
        $oF2S->expects($this->any())->method("_getFieldStatus")->will($this->returnValue(true));
        $oF2S->expects($this->once())->method("getConfig")->will($this->returnValue($oConfig));
        $oF2S->expects($this->once())->method('fetchTableFields')->will($this->returnValue([
            (object) array('name' => 'oxtitle'),
            (object) array('name' => 'oxtitle_1'),
            (object) array('name' => 'oxtitle_2'),
            (object) array('name' => 'oxtitle_3')
        ]));
        $oF2S->setEnableMultilang(false);

        $this->assertEquals(array_merge($aMultishopArticleFields, array("oxtitle_1", "oxtitle_2", "oxtitle_3")), $oF2S->UNITgetMultiShopFields());
    }

    /**
     * oxField2Shop::saveProductData() test case
     *
     * @return null
     */
    public function testSaveProductData()
    {
        $aFields = array("oxfield1", "oxfield2", "oxlongdesc");

        $oProduct = $this->getMock(\OxidEsales\Eshop\Application\Model\Article::class, array("getId", "getLongDescription"));
        $oProduct->expects($this->any())->method("getId")->will($this->returnValue("none"));
        $oProduct->expects($this->once())->method("getLongDescription")->will($this->returnValue(new \OxidEsales\Eshop\Core\Field("oxarticles__oxlongdesc")));

        $oProduct->oxarticles__oxfield1 = new \OxidEsales\Eshop\Core\Field("oxarticles__oxfield1");
        $oProduct->oxarticles__oxfield2 = new \OxidEsales\Eshop\Core\Field("oxarticles__oxfield2");

        $oF2S = $this->getMock(\OxidEsales\Eshop\Application\Model\Field2Shop::class, array("setId", "_getMultiShopFields", "save"));
        $oF2S->expects($this->once())->method("setId")->with($this->equalTo(false));
        $oF2S->expects($this->once())->method("save")->will($this->returnValue(true));
        $oF2S->expects($this->once())->method("_getMultiShopFields")->will($this->returnValue($aFields));

        $oF2S->saveProductData($oProduct);

        $this->assertEquals("none", $oF2S->oxfield2shop__oxartid->value);
        $this->assertEquals($this->getConfig()->getShopId(), $oF2S->oxfield2shop__oxshopid->value);

        $this->assertEquals("oxarticles__oxfield1", $oF2S->oxfield2shop__oxfield1->value);
        $this->assertEquals("oxarticles__oxfield2", $oF2S->oxfield2shop__oxfield2->value);
        $this->assertEquals("oxarticles__oxlongdesc", $oF2S->oxfield2shop__oxlongdesc->value);
    }

    /**
     * oxField2Shop::setProductData() test case
     *
     * @return null
     */
    public function testSetProductData()
    {
        $aFields = array("oxfield1", "oxfield2", "oxlongdesc");

        $oProduct = $this->getMock(\OxidEsales\Eshop\Application\Model\Article::class, array("getId", "setArticleLongDesc"));
        $oProduct->expects($this->once())->method("getId")->will($this->returnValue("none"));
        $oProduct->expects($this->once())->method("setArticleLongDesc")->with($this->equalTo("oxfield2shop__oxlongdesc"));

        $oProduct->oxarticles__oxfield1 = new \OxidEsales\Eshop\Core\Field("oxarticles__oxfield1");
        $oProduct->oxarticles__oxfield2 = new \OxidEsales\Eshop\Core\Field("oxarticles__oxfield2");

        $oF2S = $this->getMock(\OxidEsales\Eshop\Application\Model\Field2Shop::class, array("load", "_getMultiShopFields"));
        $oF2S->expects($this->once())->method("load")->with($this->equalTo(false))->will($this->returnValue(true));
        $oF2S->expects($this->once())->method("_getMultiShopFields")->will($this->returnValue($aFields));

        $oF2S->oxfield2shop__oxfield1 = new \OxidEsales\Eshop\Core\Field("oxfield2shop__oxfield1");
        $oF2S->oxfield2shop__oxfield2 = new \OxidEsales\Eshop\Core\Field("oxfield2shop__oxfield2");
        $oF2S->oxfield2shop__oxlongdesc = new \OxidEsales\Eshop\Core\Field("oxfield2shop__oxlongdesc");

        $oF2S->setProductData($oProduct);

        $this->assertEquals("oxfield2shop__oxfield1", $oProduct->oxarticles__oxfield1->value);
        $this->assertEquals("oxfield2shop__oxfield2", $oProduct->oxarticles__oxfield2->value);
    }


    public function testCleanMultishopFields()
    {
        $oDB = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();
        $sOXID = 1;

        $oF2S = oxNew(\OxidEsales\Eshop\Application\Model\Field2Shop::class);
        $oDB->Execute("Insert into oxfield2shop (oxid, oxartid, oxshopid, oxprice, oxpricea, oxpriceb, oxpricec) values ('testcase','blafooartid', " . $sOXID . ", 22, 23, 24, 25)");
        $oF2S->cleanMultishopFields($sOXID, 'blafooartid');
        $sResult = $oDB->GetOne("Select * from oxfield2shop where OXARTID = 'blafooartid'");
        $this->assertEquals('', $sResult);
        $oDB->Execute("Insert into oxfield2shop (oxid, oxartid, oxshopid, oxprice, oxpricea, oxpriceb, oxpricec) values ('testcase1','blafooartid1', " . $sOXID . ", 22, 23, 24, 25)");
        $oDB->Execute("Insert into oxfield2shop (oxid, oxartid, oxshopid, oxprice, oxpricea, oxpriceb, oxpricec) values ('testcase2','blafooartid2', " . $sOXID . ", 22, 23, 24, 25)");
        $oF2S->cleanMultishopFields($sOXID);
        $sResult = $oDB->GetOne("Select * from oxfield2shop");
        $this->assertEquals('', $sResult);
    }
}
