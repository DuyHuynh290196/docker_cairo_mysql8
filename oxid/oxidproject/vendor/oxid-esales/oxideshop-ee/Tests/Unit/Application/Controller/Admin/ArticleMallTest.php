<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Tests\Unit\Application\Controller\Admin;

/**
 * Test class for ArticleMall.
 */
class ArticleMallTest extends \OxidEsales\TestingLibrary\UnitTestCase
{
    /**
     * Article_Mall::AssignToSubshops() test case
     *
     * @return null
     */
    public function testAssignToSubshops()
    {
        $sOXID = 'xxx';
        $this->setRequestParameter("oxid", $sOXID);


        $oView = $this->getProxyClass("Article_Mall");
        $oView->assignToSubshops();

        $this->assertEquals("oxarticles", $oView->getNonPublicVar("_sMallTable"));
    }

    /**
     * Article_Mall::Render() test case
     *
     * @return null
     */
    public function testRender()
    {
        // testing..
        $oView = oxNew(\OxidEsales\Eshop\Application\Controller\Admin\ArticleMall::class);
        $this->assertEquals('admin_mall.tpl', $oView->render());
    }

    /**
     * test case for bugfix #5474
     */
    public function testAssignToSubshopsUnassignment()
    {
        $sOXID = '1126';
        $this->setRequestParameter("oxid", $sOXID);
        $this->setRequestParameter("allartshops", array(1, 3));

        $oElement2ShopRelations = oxNew(\OxidEsales\Eshop\Core\Element2ShopRelations::class, 'oxarticles');
        $oElement2ShopRelations->setShopIds(array(2, 3, 4));
        $oElement2ShopRelations->addToShop('1126');

        $oDb = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();
        $sQ1 = "replace into oxshops (oxid, oxparentid, oxactive) values (2, 1, 1)";
        $sQ2 = "replace into oxshops (oxid, oxparentid, oxactive) values (3, 1, 1)";
        $sQ3 = "replace into oxshops (oxid, oxparentid, oxactive) values (4, 1, 1)";
        $sQ4 = "replace into oxfield2shop (oxid, oxartid, oxshopid, oxprice) values (222, '1126', 2, 20)";
        $sQ5 = "replace into oxfield2shop (oxid, oxartid, oxshopid, oxprice) values (333, '1126', 3, 30)";
        $sQ6 = "replace into oxfield2shop (oxid, oxartid, oxshopid, oxprice) values (444, '1126', 4, 40)";

        $oDb->execute($sQ1);
        $oDb->execute($sQ2);
        $oDb->execute($sQ3);
        $oDb->execute($sQ4);
        $oDb->execute($sQ5);
        $oDb->execute($sQ6);

        $oConfig = $this->getConfig();
        $aLangParams = $oConfig->getConfigParam('aLanguageParams');
        $aLanguages = $oConfig->getConfigParam('aLanguages');
        $oConfig->saveShopConfVar('aarr', 'aLanguageParams', $aLangParams, 2);
        $oConfig->saveShopConfVar('aarr', 'aLanguages', $aLanguages, 2);
        $oConfig->saveShopConfVar('aarr', 'aLanguageParams', $aLangParams, 3);
        $oConfig->saveShopConfVar('aarr', 'aLanguages', $aLanguages, 3);
        $oConfig->saveShopConfVar('aarr', 'aLanguageParams', $aLangParams, 4);
        $oConfig->saveShopConfVar('aarr', 'aLanguages', $aLanguages, 4);

        $oMetaData = oxNew(\OxidEsales\Eshop\Core\DbMetaDataHandler::class);
        $oMetaData->updateViews();

        $oAdminMall = oxNew(\OxidEsales\Eshop\Application\Controller\Admin\ArticleMall::class);
        $oAdminMall->assignToSubshops();

        $iR2 = $oDb->getOne("select count(*) from oxfield2shop where oxid = '222' ");
        $iR3 = $oDb->getOne("select count(*) from oxfield2shop where oxid = '333' ");
        $iR4 = $oDb->getOne("select count(*) from oxfield2shop where oxid = '444' ");

        //teardown
        $sQ1 = "delete from oxshops where oxid in ('2', '3', '4')";
        $sQ2 = "delete from oxfield2shop where oxid in ('222', '333', '444')";
        $oDb->execute($sQ1);
        $oDb->execute($sQ2);

        $oMetaData = oxNew(\OxidEsales\Eshop\Core\DbMetaDataHandler::class);
        $oMetaData->updateViews();

        $this->assertEquals(0, $iR2);
        $this->assertEquals(1, $iR3);
        $this->assertEquals(0, $iR4);
    }
}
