<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */
namespace OxidEsales\EshopEnterprise\Tests\Unit\Application\Controller;

use oxField;

class ArticleDetailsControllerTest extends \OxidEsales\TestingLibrary\UnitTestCase
{
    /**
     * Test meta keywords generation.
     *
     * @return null
     */
    public function testMetaKeywords()
    {
        $oProduct = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
        $oProduct->load("1849");
        $oProduct->oxarticles__oxsearchkeys->value = 'testValue1 testValue2   testValue3 <br> ';

        //building category tree for category "Bar-eqipment"
        $sCatId = '30e44ab8593023055.23928895';

        $oCategoryTree = oxNew(\OxidEsales\Eshop\Application\Model\CategoryList::class);
        $oCategoryTree->buildTree($sCatId, false, false, false);

        $oDetails = $this->getMock(\OxidEsales\Eshop\Application\Controller\ArticleDetailsController::class, array('getProduct', 'getCategoryTree'));
        $oDetails->expects($this->any())->method('getProduct')->will($this->returnValue($oProduct));
        $oDetails->expects($this->any())->method('getCategoryTree')->will($this->returnValue($oCategoryTree));

        $sKeywords = $oProduct->oxarticles__oxtitle->value;

        //adding breadcrumb
        $sKeywords .= ", Party, Bar-Equipment";

        $oView = oxNew(\OxidEsales\Eshop\Application\Controller\FrontendController::class);
        $sTestKeywords = $oView->UNITprepareMetaKeyword($sKeywords, true) . ", testvalue1, testvalue2, testvalue3";

        $this->assertEquals($sTestKeywords, $oDetails->UNITprepareMetaKeyword(null));
    }

    public function testCanCache()
    {
        $oObj = oxNew(\OxidEsales\Eshop\Application\Controller\ArticleDetailsController::class);
        $this->assertTrue($oObj->canCache());
        $this->setRequestParameter('listtype', 'search');
        $this->assertFalse($oObj->canCache());
        $this->setRequestParameter('listtype', '');
        $this->assertTrue($oObj->canCache());
        $this->setRequestParameter('listtype', 'asd');
        $this->assertTrue($oObj->canCache());
    }

    public function testGetViewId_testHash()
    {
        $view = oxNew(\OxidEsales\Eshop\Application\Controller\ArticleDetailsController::class);

        $baseView = oxNew(\OxidEsales\Eshop\Application\Controller\FrontendController::class);
        $baseViewId = $baseView->getViewId();

        $this->setRequestParameter('anid', 'test_anid');
        $this->setRequestParameter('cnid', 'test_cnid');
        $this->setRequestParameter('listtype', 'search');
        $this->setRequestParameter('searchparam', 'test_sparam');
        $this->setRequestParameter('renderPartial', 'test_render');
        $this->setRequestParameter('varselid', 'test_varselid');
        $filters = array('test_cnid' => array(0 => 'test_filters'));
        $this->setSessionParam('session_attrfilter', $filters);

        $expectedViewId = $baseViewId . '|test_anid|search-test_sparam|test_cnid' . serialize('test_filters') . '|test_render|' . serialize('test_varselid');

        $resultViewId = $view->getViewId();
        $this->assertSame($expectedViewId, $resultViewId);
    }

    public function testGetViewResetId()
    {
        $oBaseView = oxNew(\OxidEsales\Eshop\Application\Controller\FrontendController::class);
        $sBaseViewId = $oBaseView->GetViewResetID();

        $oProduct = $this->getMock(\OxidEsales\Eshop\Application\Model\Article::class, array('getId'));
        $oProduct->expects($this->once())->method('getId')->will($this->returnValue('test_ID'));
        $oProduct->oxarticles__oxparentid = new \OxidEsales\Eshop\Core\Field('test_parentID');

        $oView = $this->getMock($this->getProxyClassName('Details'), array('getProduct'));
        $oView->expects($this->any())->method('getProduct')->will($this->returnValue($oProduct));

        $sExpected = $sBaseViewId . '|anid=test_ID|anid=test_parentID';
        $this->assertSame($sExpected, $oView->getViewResetId());
    }
}


