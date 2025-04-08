<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Tests\Unit\Application\Controller\Admin;

use oxDb;

/**
 * Test oxAdminList module
 */
class AdminListForAdminListTest extends \oxAdminList
{
    /**
     * force _authorize.
     *
     * @return boolean
     */
    protected function _authorize() // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        return true;
    }
}

/**
 * Test oxLinks module
 */
class LinksIsDerived extends \oxLinks
{
    /**
     * force isDerived.
     *
     * @return boolean
     */
    public function isDerived()
    {
        return true;
    }
}

/**
 * Class AdminListTest
 * @package OxidEsales\EshopEnterprise\Tests\Unit\Application\Controller\Admin
 */
class AdminListTest extends \OxidEsales\TestingLibrary\UnitTestCase
{
    /**
     * Test deleting
     *
     * @return null
     */
    public function testDeleteEntryIsDerivedEE()
    {
        $this->setRequestParameter('oxid', '_testId');

        // preparing test data
        $oLink = oxNew(\OxidEsales\Eshop\Application\Model\Links::class);
        $oLink->setId('_testId');
        $oLink->save();

        $oAdminList = $this->getProxyClass('\OxidEsales\EshopEnterprise\Tests\Unit\Application\Controller\Admin\AdminListForAdminListTest');
        $oAdminList->setNonPublicVar('_sListClass', '\OxidEsales\EshopEnterprise\Tests\Unit\Application\Controller\Admin\LinksIsDerived');
        $this->assertNull($oAdminList->deleteEntry());

        $this->assertEquals('1', \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->getOne('select 1 from oxlinks where oxid = "_testId" '));
    }

    /**
     * Test unassinging item from shop
     *
     * @return null
     */
    public function testUnassignEntry()
    {
        $oLink = oxNew(\OxidEsales\Eshop\Application\Model\Links::class);
        $oLink->setId('_testId');
        $oLink->save();

        $this->setRequestParameter('oxid', '_testId');

        $oAdminList = $this->getProxyClass('\OxidEsales\EshopEnterprise\Tests\Unit\Application\Controller\Admin\AdminListForAdminListTest');
        $oAdminList->setNonPublicVar('_sListClass', 'oxLinks');
        $oAdminList->unassignEntry();
    }

    /**
     * Test building sql where with specified "folder" param for table oxorders
     *  If table is oxorder and folder name not specified, takes first member of
     *  orders folders array
     *
     * @return null
     */
    public function testPrepareWhereQueryWithOrderWhenFolderNotSpecified()
    {
        $this->getConfig()->setConfigParam('aOrderfolder', array('Neu' => 1, 'Old' => 2));
        $this->setRequestParameter('folder', '');

        $aWhere['oxtitle'] = '';
        $oAdminList = $this->getProxyClass('order_list');
        $sResultSql = $oAdminList->UNITprepareWhereQuery($aWhere, '');

        $sSql = " and ( oxorder.oxfolder = 'Neu' ) and oxorder.oxshopid = '1'";
        $this->assertEquals($sSql, $sResultSql);
    }
}
