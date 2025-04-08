<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Tests\Unit\Model;

use \OxidEsales\TestingLibrary\UnitTestCase;
use OxidEsales\Eshop\Core\Registry;

class CategoryListTest extends UnitTestCase
{
    /**
     * Test get rights&roles sql snippet in admin mode.
     */
    public function testGetSqlRightsSnippetAdminModeNoRightsSnippet()
    {
        $oCatList = $this->getMock(\OxidEsales\Eshop\Application\Model\CategoryList::class, array('isAdmin'));
        $oCatList->expects($this->any())->method('isAdmin')->will($this->returnValue(true));
        $this->assertEquals('', $oCatList->UNITgetSqlRightsSnippet());
    }

    /**
     * Test get rights&roles sql snippet when rr is disabled.
     */
    public function testGetSqlRightsSnippetRROffNoRightsSnippet()
    {
        $oCatList = $this->getMock(\OxidEsales\Eshop\Application\Model\CategoryList::class, array('getRights'));
        $oCatList->expects($this->any())->method('getRights')->will($this->returnValue(null));
        $this->assertEquals('', $oCatList->UNITgetSqlRightsSnippet());
    }

    /**
     * Test get rights&roles sql snippet for regular user.
     */
    public function testGetSqlRightsSnippetRROnNonAdmin()
    {
        $sQ = " and ( ( ";
        $sQ .= "( select oxobjectrights.oxobjectid from oxobjectrights where oxobjectrights.oxobjectid = oxv_oxcategories_1_de.oxid and oxobjectrights.oxaction = 1 limit 1 ) is null ";

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
            $sQ .= "( select oxobjectrights.oxobjectid from oxobjectrights where oxobjectrights.oxobjectid = oxv_oxcategories_1_de.oxid and oxobjectrights.oxaction = 1 and $sSel limit 1 ) is not null ";
        }

        $sQ .= " ) ) ";

        $oRR = $this->getMock(\OxidEsales\Eshop\Application\Model\Rights::class, array('getUserGroupIndex'));
        $oRR->expects($this->any())->method('getUserGroupIndex')->will($this->returnValue($aGroupIdx));

        $oCatList = $this->getMock(\OxidEsales\Eshop\Application\Model\CategoryList::class, array('isAdmin', 'getRights', 'getViewName'));
        $oCatList->expects($this->any())->method('isAdmin')->will($this->returnValue(false));
        $oCatList->expects($this->any())->method('getRights')->will($this->returnValue($oRR));
        $oCatList->expects($this->any())->method('getViewName')->will($this->returnValue('xxx'));
        $this->assertEquals($sQ, $oCatList->UNITgetSqlRightsSnippet());
    }

    /**
     * Test get select fileds for tree.
     */
    public function test_getSqlSelectFieldsForTree()
    {
        $sExpect = 'tablex.oxid as oxid,'
            . ' tablex.oxactive as oxactive,'
            . ' tablex.oxhidden as oxhidden,'
            . ' tablex.oxparentid as oxparentid,'
            . ' tablex.oxdefsort as oxdefsort,'
            . ' tablex.oxdefsortmode as oxdefsortmode,'
            . ' tablex.oxleft as oxleft,'
            . ' tablex.oxright as oxright,'
            . ' tablex.oxrootid as oxrootid,'
            . ' tablex.oxsort as oxsort,'
            . ' tablex.oxtitle as oxtitle,'
            . ' tablex.oxdesc as oxdesc,'
            . ' tablex.oxpricefrom as oxpricefrom,'
            . ' tablex.oxpriceto as oxpriceto,'
            . ' tablex.oxicon as oxicon, tablex.oxextlink as oxextlink,'
            . ' tablex.oxthumb as oxthumb, tablex.oxpromoicon as oxpromoicon,';

        $sExpect .= 'not (tablex.oxactive  and ( ( ( select oxobjectrights.oxobjectid from oxobjectrights where oxobjectrights.oxobjectid = tablex.oxid and oxobjectrights.oxaction = 1 limit 1 ) is null  ) ) ) as oxppremove';
        //
        $oList = $this->getMock(\OxidEsales\Eshop\Application\Model\CategoryList::class, array("getRights"));
        $oList->expects($this->once())->method('getRights')->will($this->returnValue(oxNew(\OxidEsales\Eshop\Application\Model\Rights::class)));

        $this->assertEquals($sExpect, $oList->UNITgetSqlSelectFieldsForTree('tablex'));
    }

    /**
     * Test get select fields for tree in language 1.
     */
    public function test_getSqlSelectFieldsForTree_lang1()
    {
        Registry::getLang()->setBaseLanguage(1);
        $sExpect = 'tablex.oxid as oxid,'
            . ' tablex.oxactive as oxactive,'
            . ' tablex.oxhidden as oxhidden,'
            . ' tablex.oxparentid as oxparentid,'
            . ' tablex.oxdefsort as oxdefsort,'
            . ' tablex.oxdefsortmode as oxdefsortmode,'
            . ' tablex.oxleft as oxleft,'
            . ' tablex.oxright as oxright,'
            . ' tablex.oxrootid as oxrootid,'
            . ' tablex.oxsort as oxsort,'
            . ' tablex.oxtitle as oxtitle,'
            . ' tablex.oxdesc as oxdesc,'
            . ' tablex.oxpricefrom as oxpricefrom,'
            . ' tablex.oxpriceto as oxpriceto,'
            . ' tablex.oxicon as oxicon, tablex.oxextlink as oxextlink,'
            . ' tablex.oxthumb as oxthumb, tablex.oxpromoicon as oxpromoicon,';

        $sExpect .= 'not (tablex.oxactive  and ( ( ( select oxobjectrights.oxobjectid from oxobjectrights where oxobjectrights.oxobjectid = tablex.oxid and oxobjectrights.oxaction = 1 limit 1 ) is null  ) ) ) as oxppremove';

        $oList = $this->getMock(\OxidEsales\Eshop\Application\Model\CategoryList::class, array("getRights"));
        $oList->expects($this->once())->method('getRights')->will($this->returnValue(oxNew(\OxidEsales\Eshop\Application\Model\Rights::class)));

        $this->assertEquals($sExpect, $oList->UNITgetSqlSelectFieldsForTree('tablex'));
    }
}
