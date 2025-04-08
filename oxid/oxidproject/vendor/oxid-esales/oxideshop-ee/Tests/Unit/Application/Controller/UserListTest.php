<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */
namespace OxidEsales\EshopEnterprise\Tests\Unit\Application\Controller;

use \oxTestModules;

class UserListTest extends \OxidEsales\TestingLibrary\UnitTestCase
{
    /**
     * User_List::BuildSelectString() test case
     *
     * @return null
     */
    public function testBuildSelectString()
    {
        $this->getConfig()->setConfigParam("blMallUsers", true);

        $base = oxNew(\OxidEsales\Eshop\Core\Model\BaseModel::class);
        $base->init("oxarticles");
        $base->setDisableShopCheck(true);
        $query = $base->buildSelectString(null);

        // defining parameters
        $listObject = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);

        // testing..
        $view = oxNew(\OxidEsales\Eshop\Application\Controller\Admin\UserList::class);
        $this->assertEquals($query, $view->UNITbuildSelectString($listObject));
    }

    /**
     * User_List::PrepareWhereQuery() test case
     *
     * @return null
     */
    public function testPrepareWhereQuery()
    {
        $expectedQuery = " and (  oxuser.oxlname testFilter or oxuser.oxlname testFilter  or  oxuser.oxfname testFilter or oxuser.oxfname testFilter )  and oxuser.oxrights != 'malladmin' and oxshopid = '1' ";

        oxTestModules::addFunction('oxUtilsString', 'prepareStrForSearch', '{ return "testUml"; }');

        // defining parameters
        $aWhere['oxuser.oxlname'] = 'testLastName';

        // testing..
        $view = $this->getMock(\OxidEsales\Eshop\Application\Controller\Admin\UserList::class, array("_isSearchValue", "_processFilter", "_buildFilter"));
        $view->expects($this->any())->method('_isSearchValue')->will($this->returnValue(true));
        $view->expects($this->any())->method('_processFilter')->will($this->returnValue("testValue"));
        $view->expects($this->any())->method('_buildFilter')->will($this->returnValue("testFilter"));
        $this->assertEquals($expectedQuery, $view->UNITprepareWhereQuery($aWhere, ''));
    }
}
