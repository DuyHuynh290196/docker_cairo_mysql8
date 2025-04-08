<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Tests\Unit\Model;

use OxidEsales\Eshop\Core\DatabaseProvider;
use OxidEsales\TestingLibrary\UnitTestCase;
use oxDb;
use oxCategory;
use stdClass;

class CategoryTest extends UnitTestCase
{
    /** @var \OxidEsales\Eshop\Application\Model\Category */
    private $mainCategory = null;

    /** @var \OxidEsales\Eshop\Application\Model\Category */
    private $childCategory = null;

    /**
     * safely reloads test objects
     */
    private function reload()
    {
        $mainCategory = oxNew(\OxidEsales\Eshop\Application\Model\Category::class);
        if (!$mainCategory->load('test')) {
            $this->saveParent();
            $mainCategory->load('test');
        }
        $this->mainCategory = $mainCategory;

        $childCategory = oxNew(\OxidEsales\Eshop\Application\Model\Category::class);
        if (!$childCategory->load('test2')) {
            $this->saveChild();
            $childCategory->load('test');
        }
        $this->childCategory = $childCategory;
    }

    /**
     * initialize parent obj
     */
    private function saveParent()
    {
        $sInsert = "Insert into oxcategories (`OXID`,`OXROOTID`,`OXSHOPID`,`OXLEFT`,`OXRIGHT`,`OXTITLE`,`OXLONGDESC`,`OXLONGDESC_1`,`OXLONGDESC_2`,`OXLONGDESC_3`, `OXACTIVE`, `OXPRICEFROM`, `OXPRICETO`) " .
            "values ('test','test','1','1','4','test','','','','','1','10','50')";
        $this->addToDatabase($sInsert, 'oxcategories');
    }

    /**
     * initialize child obj
     */
    private function saveChild()
    {
        $sInsert = "Insert into oxcategories (`OXID`,`OXROOTID`,`OXSHOPID`,`OXPARENTID`,`OXLEFT`,`OXRIGHT`,`OXTITLE`,`OXLONGDESC`,`OXLONGDESC_1`,`OXLONGDESC_2`,`OXLONGDESC_3`, `OXACTIVE`, `OXPRICEFROM`, `OXPRICETO`) " .
            "values ('test2','test','1','test','2','3','test','','','','','1','10','50')";
        $this->addToDatabase($sInsert, 'oxcategories');
    }

    public function testAssignDeniedByRR()
    {
        $oCategory = $this->getMock(\OxidEsales\Eshop\Application\Model\Category::class, array('canRead', 'isAdmin', 'getConfig'), array(), '', false);
        $oCategory->expects($this->once())->method('canRead')->will($this->returnValue(false));
        $oCategory->expects($this->never())->method('isAdmin');
        $oCategory->expects($this->never())->method('getConfig');

        $this->assertFalse($oCategory->assign(array()));
    }

    public function testInsertDeniedByRR()
    {
        $oCategory = $this->getMock(\OxidEsales\Eshop\Application\Model\Category::class, array('canInsert', 'getId', 'setId'));
        $oCategory->expects($this->once())->method('canInsert')->will($this->returnValue(false));
        $oCategory->expects($this->never())->method('getId');
        $oCategory->expects($this->never())->method('setId');

        $this->assertFalse($oCategory->UNITinsert(array()));
    }

    public function testDeleteDeniedByRR()
    {
        $oCategory = $this->getMock(\OxidEsales\Eshop\Application\Model\Category::class, array('canDelete', 'getId', 'getConfig'), array(), '', false);
        $oCategory->expects($this->once())->method('canDelete')->will($this->returnValue(false));
        $oCategory->expects($this->any())->method('getId')->will($this->returnValue(true));
        $oCategory->expects($this->never())->method('getConfig');

        $this->assertFalse($oCategory->delete(array()));
    }

    public function testUpdateDeniedByRR()
    {
        $oCategory = $this->getMock(\OxidEsales\Eshop\Application\Model\Category::class, array('canUpdate', 'getId'));
        $oCategory->expects($this->once())->method('canUpdate')->will($this->returnValue(false));
        $oCategory->expects($this->never())->method('getId');

        $this->assertFalse($oCategory->UNITupdate(array()));
    }

    public function testCanViewAdmin()
    {
        $oCategory = $this->getMock(\OxidEsales\Eshop\Application\Model\Category::class, array('isAdmin', 'canDo'));
        $oCategory->expects($this->once())->method('isAdmin')->will($this->returnValue(true));
        $oCategory->expects($this->never())->method('canDo');
        $this->assertTrue($oCategory->canView());
    }

    public function testCanViewSubcategory()
    {
        $oCategory = $this->getMock(\OxidEsales\Eshop\Application\Model\Category::class, array('isAdmin', 'getRights', 'canDo'));
        $oCategory->expects($this->once())->method('isAdmin')->will($this->returnValue(false));
        $oCategory->expects($this->once())->method('getRights')->will($this->returnValue(true));
        $oCategory->expects($this->any())->method('canDo')->will($this->onConsecutiveCalls(true, false));

        $this->assertFalse($oCategory->canView('test2'));
    }

    public function testCanViewWithoutRR()
    {
        $this->reload();

        $this->assertTrue($this->mainCategory->canView(null));
    }

    public function testRrIsNotViewable()
    {
        $this->reload();

        $rights = $this->getMock(\OxidEsales\Eshop\Application\Model\Rights::class, array('getUserGroupIndex'));
        $rights->expects($this->atLeastOnce())->method('getUserGroupIndex')->will($this->returnValue(null));

        $category = $this->getMock(\OxidEsales\Eshop\Application\Model\Category::class, array('getRights', 'isAdmin', 'fetchCategoryTreeInformation'));
        $category->expects($this->atLeastOnce())->method('fetchCategoryTreeInformation')->will($this->returnValue(false));
        $category->expects($this->atLeastOnce())->method('isAdmin')->will($this->returnValue(false));
        $category->expects($this->atLeastOnce())->method('getRights')->will($this->returnValue($rights));

        $this->assertFalse($category->canView($this->mainCategory->getId()));
    }

    public function testCanViewWithoutCatId_viewAllowed()
    {
        $this->reload();

        $oCategory = $this->getMock(\OxidEsales\Eshop\Application\Model\Category::class, array('getRights', 'isAdmin', 'canDo'));
        $oCategory->expects($this->atLeastOnce())->method('getRights')->will($this->returnValue(new stdClass));
        $oCategory->expects($this->atLeastOnce())->method('isAdmin')->will($this->returnValue(false));
        $oCategory->expects($this->atLeastOnce())->method('canDo')->will($this->returnValue(true));

        $oCategory->load($this->childCategory->getId());
        $this->assertTrue($oCategory->canView());
    }

    public function testCanViewWithoutCatId_viewNotAllowed()
    {
        $this->reload();

        $oCategory = $this->getMock(\OxidEsales\Eshop\Application\Model\Category::class, array('getRights', 'isAdmin', 'canDo'));
        $oCategory->expects($this->atLeastOnce())->method('getRights')->will($this->returnValue(new stdClass));
        $oCategory->expects($this->atLeastOnce())->method('isAdmin')->will($this->returnValue(false));
        $oCategory->expects($this->atLeastOnce())->method('canDo')->will($this->returnValue(false));

        $oCategory->load($this->childCategory->getId());
        $this->assertFalse($oCategory->canView());
    }

    public function testCanViewWithNotExistingCatId()
    {
        $this->reload();

        $rights = $this->getMock(\OxidEsales\Eshop\Application\Model\Rights::class, array('getUserGroupIndex'));
        $rights->expects($this->any())->method('getUserGroupIndex')->will($this->returnValue(null));

        $category = $this->getMock(\OxidEsales\Eshop\Application\Model\Category::class, array('getRights', 'isAdmin', 'canDo'));
        $category->expects($this->atLeastOnce())->method('getRights')->will($this->returnValue($rights));
        $category->expects($this->atLeastOnce())->method('isAdmin')->will($this->returnValue(false));
        $category->expects($this->atLeastOnce())->method('canDo')->will($this->returnValue(false));

        $this->assertFalse($category->canView("test"));
    }

    public function testCanViewWithCatId()
    {
        $this->reload();

        $oRights = $this->getMock(\OxidEsales\Eshop\Application\Model\Rights::class, array('getUserGroupIndex', 'hasObjectRights'));
        $oRights->expects($this->never())->method('getUserGroupIndex');
        $oRights->expects($this->once())->method('hasObjectRights')->will($this->returnValue(true));;

        $oCategory = $this->getMock(\OxidEsales\Eshop\Application\Model\Category::class, array('getRights', 'isAdmin'));
        $oCategory->expects($this->atLeastOnce())->method('isAdmin')->will($this->returnValue(false));
        $oCategory->expects($this->atLeastOnce())->method('getRights')->will($this->returnValue($oRights));

        $this->assertTrue($oCategory->canView('30e44ab82c03c3848.49471214'));
    }

    public function testCanViewWithParentCat()
    {
        $this->reload();

        $oRights = $this->getMock(\OxidEsales\Eshop\Application\Model\Rights::class, array('getUserGroupIndex', 'hasObjectRights'));
        $oRights->expects($this->never())->method('getUserGroupIndex');
        $oRights->expects($this->any())->method('hasObjectRights')->will($this->returnValue(true));;

        $oCategory = $this->getMock(\OxidEsales\Eshop\Application\Model\Category::class, array('getRights', 'isAdmin'));
        $oCategory->expects($this->atLeastOnce())->method('isAdmin')->will($this->returnValue(false));
        $oCategory->expects($this->atLeastOnce())->method('getRights')->will($this->returnValue($oRights));

        $this->assertTrue($oCategory->canView('30e44ab85808a1f05.26160932'));
    }

    public function testGetSqlActiveSnippetRROn()
    {
        $sQ = "(  xxx.oxactive = 1  and  xxx.oxhidden = '0'  and ( ( ";
        $sQ .= "( select oxobjectrights.oxobjectid from oxobjectrights where oxobjectrights.oxobjectid = xxx.oxid and oxobjectrights.oxaction = 1 limit 1 ) is null ";

        $aGroupIdx = array(1, 60, 120);
        if (is_array($aGroupIdx) && count($aGroupIdx)) {
            $sSel = "";
            $iCnt = 0;
            foreach ($aGroupIdx as $iOffset => $iBitMap) {
                if ($iCnt) {
                    $sSel .= " | ";
                }
                $sSel .= " ( oxobjectrights.oxgroupidx & $iBitMap and oxobjectrights.oxoffset = $iOffset ) ";
                $iCnt++;
            }

            $sQ .= ") or (";
            $sQ .= "( select oxobjectrights.oxobjectid from oxobjectrights where oxobjectrights.oxobjectid = xxx.oxid and oxobjectrights.oxaction = 1 and $sSel limit 1 ) is not null ";
        }
        $sQ .= " ) ) ";

        $oRights = $this->getMock(\OxidEsales\Eshop\Application\Model\Rights::class, array('getUserGroupIndex'));
        $oRights->expects($this->any())->method('getUserGroupIndex')->will($this->returnValue($aGroupIdx));

        $oCategory = $this->getMock(\OxidEsales\Eshop\Application\Model\Category::class, array('isAdmin', 'getRights', 'getViewName'));
        $oCategory->expects($this->any())->method('isAdmin')->will($this->returnValue(false));
        $oCategory->expects($this->any())->method('getRights')->will($this->returnValue($oRights));
        $oCategory->expects($this->any())->method('getViewName')->will($this->returnValue('xxx'));

        $this->assertEquals(preg_replace('/\W/', '', $sQ), preg_replace('/\W/', '', $oCategory->getSqlActiveSnippet()));
    }

    public function  testUnassignIdNotSet()
    {
        $oBase = oxNew(\OxidEsales\Eshop\Application\Model\Category::class);
        $sResult = $oBase->unassignFromShop(1);
        $this->assertFalse($sResult);
    }

    // #M291: unassigning categories
    public function  testUnassignIfShopNoSet()
    {
        $oBase = oxNew(\OxidEsales\Eshop\Application\Model\Category::class);
        $oBase->setId("test4");
        $sResult = $oBase->unassignFromShop(null);
        $this->assertFalse($sResult);
    }

    public function testAssignViewableRecord()
    {
        $oCategory = $this->getProxyClass("oxcategory");

        $this->assertTrue($oCategory->assignViewableRecord('select * from oxcategories where oxid="test"'));
        $this->assertFalse($oCategory->assignViewableRecord('select * from oxcategories where oxid="test45"'));
    }

    // #M291: unassigning categories
    public function  testUnassignWithSubCat()
    {
        $myDB = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();
        $sInsert = "Insert into oxcategories (`OXID`,`OXROOTID`,`OXSHOPID`,`OXTITLE`,`OXPARENTID`) values ('test4','test4',1,'test','oxrootid')";
        $this->addToDatabase($sInsert, 'oxcategories', array(1, 2));
        $sInsert = "Insert into oxcategories (`OXID`,`OXROOTID`,`OXSHOPID`,`OXTITLE`,`OXPARENTID`) values ('test5','test4',1,'test1','test4')";
        $this->addToDatabase($sInsert, 'oxcategories', array(1, 2));
        $oBase = oxNew(\OxidEsales\Eshop\Application\Model\Category::class);
        $oBase->setId("test4");
        $sQ = 'select count(*) from oxv_oxcategories_1 where oxrootid = "test4" ';
        $this->assertEquals(2, (int) $myDB->getOne($sQ));
        $sResult = $oBase->unassignFromShop(1);
        $this->assertEquals(0, (int) $myDB->getOne($sQ));
        $this->assertTrue($sResult);
    }

}
