<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Tests\Unit\Application\Model;

use \oxDb;
use \oxField;
use \oxPrice;

class SimpleVariantTest extends \OxidEsales\TestingLibrary\UnitTestCase
{
    /**
     * Resting if magic getter returns "aSelectlist" value
     *
     * @return null
     */
    public function testAssign()
    {
        $oVariant = $this->getMock(\OxidEsales\Eshop\Application\Model\SimpleVariant::class, array('_setShopValues'));
        $oVariant->expects($this->once())->method('_setShopValues');

        $oVariant->assign(array());
    }

    /**
     * Test set shop values
     *
     * @return null
     */
    public function testSetShopValues()
    {
        $oSubj = oxNew(\OxidEsales\Eshop\Application\Model\SimpleVariant::class);
        $oSubj->setId("_testVar");
        $oSubj->oxarticles__oxprice = new \OxidEsales\Eshop\Core\Field(20, \OxidEsales\Eshop\Core\Field::T_RAW);

        $oParent = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
        $oParent->setId("_testArticle");
        $oParent->oxarticles__oxprice = new \OxidEsales\Eshop\Core\Field(10);
        $oSubj->setParent($oParent);

        \OxidEsales\Eshop\Core\DatabaseProvider::getDB()->execute("insert into oxfield2shop (oxartid, oxprice, oxshopid) values ('_testVar', 25, 1 )");
        $oSubj->oxarticles__oxshopid = new \OxidEsales\Eshop\Core\Field(2, \OxidEsales\Eshop\Core\Field::T_RAW);
        $oSubj->oxarticles__oxprice = new \OxidEsales\Eshop\Core\Field(20, \OxidEsales\Eshop\Core\Field::T_RAW);
        $oSubj->UNITsetShopValues($oSubj);

        \OxidEsales\Eshop\Core\DatabaseProvider::getDB()->execute('delete from oxfield2shop where oxartid = "_testVar"');

        $this->assertEquals(25, $oSubj->oxarticles__oxprice->value);
    }

    /**
     * oxSimpleVariant::getPrice() - Mall price addition Test case.
     *
     * @return null
     */
    public function testGetPrice_MallAddition()
    {
        $oObject = $this->getMock(\OxidEsales\Eshop\Application\Model\SimpleVariant::class, array('_getGroupPrice', 'modifyGroupPrice'));
        $oObject->expects($this->once())->method('_getGroupPrice')->will($this->returnValue(12));
        $oObject->expects($this->once())->method('modifyGroupPrice')->will($this->returnValue(15.17));

        $oCurrPrice = $oObject->getPrice();
        $this->assertTrue($oCurrPrice instanceof \OxidEsales\EshopCommunity\Core\Price, 'Response should be of type oxPrice');
        $this->assertSame(15.17, $oCurrPrice->getBruttoPrice(), 'Incorrect price calculated.');
    }

    /**
     * oxSimpleVariant::modifyGroupPrice() - Test case.
     *
     * @return null
     */
    public function testModifyGroupPrice()
    {
        $this->getConfig()->setConfigParam('iMallPriceAddition', 10.0);
        $this->getConfig()->setConfigParam('blMallInterchangeArticles', true);

        $oProxyObj = oxNew(\OxidEsales\Eshop\Application\Model\SimpleVariant::class);
        $oObj = $this->getMock(get_class($oProxyObj), array('isAdmin'));
        $oObj->expects($this->any())->method('isAdmin')->will($this->returnValue(false));

        $this->getConfig()->setConfigParam('blMallPriceAdditionPercent', false);
        $this->assertSame(18.00, $oObj->modifyGroupPrice(8), 'Absolute price addition failed.');

        $this->getConfig()->setConfigParam('blMallPriceAdditionPercent', true);
        $this->assertSame(8.80, $oObj->modifyGroupPrice(8), 'Percental price addition failed.');
    }
}
