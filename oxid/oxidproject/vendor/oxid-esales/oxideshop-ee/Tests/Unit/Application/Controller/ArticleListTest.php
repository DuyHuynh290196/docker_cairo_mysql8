<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */
namespace OxidEsales\EshopEnterprise\Tests\Unit\Application\Controller;

use PHPUnit\Framework\MockObject\MockObject;
use oxTestModules;

class ArticleListTest extends \OxidEsales\TestingLibrary\UnitTestCase
{
    /**
     * Test view id getter.
     *
     * @return null
     */
    public function testGetViewId()
    {
        $this->setRequestParameter('cnid', 'xxx');
        $this->setSessionParam('_artperpage', '100');
        $this->setSessionParam('ldtype', 'grid');
        $this->setSessionParam('session_attrfilter', array('xxx' => array('0' => array('100'))));

        $view = oxNew(\OxidEsales\Eshop\Application\Controller\FrontendController::class);
        $sViewId = $view->getViewId() . '|xxx|' . md5(serialize(array('100'))) . '|999|100|grid';

        $oListView = $this->getMock(\OxidEsales\Eshop\Application\Controller\ArticleListController::class, array('getActPage'));
        $oListView->expects($this->any())->method('getActPage')->will($this->returnValue('999'));
        $this->assertEquals($sViewId, $oListView->getViewId());
    }

    /**
     * Test view id getter when list type is not in session
     */
    public function testGetViewId_ListTypeNotInSession_ReturnsViewIdWithDefaultListTypeIncluded()
    {
        $this->setRequestParameter('cnid', 'xxx');
        $this->setSessionParam('_artperpage', '100');
        $this->setSessionParam('session_attrfilter', array('xxx' => array('0' => array('100'))));

        $oView = oxNew(\OxidEsales\Eshop\Application\Controller\FrontendController::class);
        $sListType = $this->getConfig()->getConfigParam('sDefaultListDisplayType');

        $sViewId = $oView->getViewId() . '|xxx|' . md5(serialize(array('100'))) . '|999|100|' . $sListType;

        $oListView = $this->getMock(\OxidEsales\Eshop\Application\Controller\ArticleListController::class, array('getActPage'));
        $oListView->expects($this->any())->method('getActPage')->will($this->returnValue('999'));
        $this->assertEquals($sViewId, $oListView->getViewId());
    }

    /**
     * Actions_List::_prepareWhereQuery() test case
     *
     * @return null
     */
    public function testPrepareWhereQuery()
    {
        $iTime = time();
        oxTestModules::addFunction('oxUtilsDate', 'getTime', '{ return ' . $iTime . '; }');
        $sTable = getViewName("oxactions");
        $sNow = date('Y-m-d H:i:s', $iTime);

        $iShopId = $this->getConfig()->getShopId();
        $sAddQ = " and ( {$sTable}.oxtype = 0 or ( {$sTable}.oxtype != 0 and {$sTable}.oxshopid = '{$iShopId}') ) ";

        $oView = oxNew(\OxidEsales\Eshop\Application\Controller\Admin\ActionsList::class);

        $sQ = " and $sTable.oxactivefrom < '$sNow' and $sTable.oxactiveto > '$sNow' $sAddQ";
        $this->setRequestParameter('displaytype', 1);
        $this->assertEquals($sQ, $oView->UNITprepareWhereQuery(array(), ""));

        $sQ = " and $sTable.oxactivefrom > '$sNow' $sAddQ";
        $this->setRequestParameter('displaytype', 2);
        $this->assertEquals($sQ, $oView->UNITprepareWhereQuery(array(), ""));

        $sQ = " and $sTable.oxactiveto < '$sNow' and $sTable.oxactiveto != '0000-00-00 00:00:00' $sAddQ";
        $this->setRequestParameter('displaytype', 3);
        $this->assertEquals($sQ, $oView->UNITprepareWhereQuery(array(), ""));
    }
}
