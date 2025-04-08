<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */
namespace OxidEsales\EshopEnterprise\Tests\Unit\Core;

use OxidEsales\EshopEnterprise\Core\AdminRights;
use \DOMDocument;
use \Exception;
use \PHPUnit\Framework\MockObject\MockObject;

class AdminRightsTest extends \oxUnitTestCase
{
    /**
     * Test has rights when no rights are set.
     *
     * @return null
     */
    public function testHasRightsNoRightsSet()
    {
        /** @var AdminRights|MockObject $oRights */
        $oRights = oxNew(\OxidEsales\Eshop\Core\AdminRights::class);
        $this->assertTrue($oRights->hasRights(RIGHT_DELETE, oxNew(\OxidEsales\Eshop\Application\Model\Article::class)));
    }

    /**
     * Test has rights is denied.
     *
     * @return null
     */
    public function testHasRightsDenied()
    {
        /** @var AdminRights|MockObject $oRights */
        $oRights = $this->getMock(\OxidEsales\Eshop\Core\AdminRights::class, array('getObjectRightsIndex'));
        $oRights->expects($this->once())->method('getObjectRightsIndex')->will($this->returnValue(RIGHT_DENY));
        $this->assertFalse($oRights->hasRights(RIGHT_EDIT, oxNew(\OxidEsales\Eshop\Application\Model\Article::class)));
    }

    /**
     * Test has rights is readonly.
     *
     * @return null
     */
    public function testHasRightsReadOnly()
    {
        /** @var AdminRights|MockObject $oRights */
        $oRights = $this->getMock(\OxidEsales\Eshop\Core\AdminRights::class, array('getObjectRightsIndex'));
        $oRights->expects($this->once())->method('getObjectRightsIndex')->will($this->returnValue(RIGHT_EDIT));
        $this->assertTrue($oRights->hasRights(RIGHT_EDIT, oxNew(\OxidEsales\Eshop\Application\Model\Article::class)));
    }

    /**
     * Test has rights is delete.
     *
     * @return null
     */
    public function testHasRightsDelete()
    {
        /** @var AdminRights|MockObject $oRights */
        $oRights = $this->getMock(\OxidEsales\Eshop\Core\AdminRights::class, array('getObjectRightsIndex'));
        $oRights->expects($this->once())->method('getObjectRightsIndex')->will($this->returnValue(RIGHT_DELETE));
        $this->assertTrue($oRights->hasRights(RIGHT_DELETE, oxNew(\OxidEsales\Eshop\Application\Model\Article::class)));

        /** @var AdminRights|MockObject $oRights */
        $oRights = $this->getMock(\OxidEsales\Eshop\Core\AdminRights::class, array('getObjectRightsIndex'));
        $oRights->expects($this->once())->method('getObjectRightsIndex')->will($this->returnValue(RIGHT_DENY));
        $this->assertFalse($oRights->hasRights(RIGHT_DELETE, oxNew(\OxidEsales\Eshop\Application\Model\Article::class)));
    }

    /**
     * Test has rights is insert.
     *
     * @return null
     */
    public function testHasRightsInsert()
    {
        /** @var AdminRights|MockObject $oRights */
        $oRights = $this->getMock(\OxidEsales\Eshop\Core\AdminRights::class, array('getObjectRightsIndex'));
        $oRights->expects($this->once())->method('getObjectRightsIndex')->will($this->returnValue(RIGHT_INSERT));
        $this->assertTrue($oRights->hasRights(RIGHT_INSERT, oxNew(\OxidEsales\Eshop\Application\Model\Article::class)));

        /** @var AdminRights|MockObject $oRights */
        $oRights = $this->getMock(\OxidEsales\Eshop\Core\AdminRights::class, array('getObjectRightsIndex'));
        $oRights->expects($this->once())->method('getObjectRightsIndex')->will($this->returnValue(RIGHT_DENY));
        $this->assertFalse($oRights->hasRights(RIGHT_INSERT, oxNew(\OxidEsales\Eshop\Application\Model\Article::class)));
    }

    /**
     * Test has rights is edit.
     *
     * @return null
     */
    public function testHasRightsEdit()
    {
        /** @var AdminRights|MockObject $oRights */
        $oRights = $this->getMock(\OxidEsales\Eshop\Core\AdminRights::class, array('getObjectRightsIndex'));
        $oRights->expects($this->once())->method('getObjectRightsIndex')->will($this->returnValue(RIGHT_EDIT));
        $this->assertTrue($oRights->hasRights(RIGHT_EDIT, oxNew(\OxidEsales\Eshop\Application\Model\Article::class)));

        /** @var AdminRights|MockObject $oRights */
        $oRights = $this->getMock(\OxidEsales\Eshop\Core\AdminRights::class, array('getObjectRightsIndex'));
        $oRights->expects($this->once())->method('getObjectRightsIndex')->will($this->returnValue(RIGHT_DENY));
        $this->assertFalse($oRights->hasRights(RIGHT_EDIT, oxNew(\OxidEsales\Eshop\Application\Model\Article::class)));
    }

    /**
     * Test has rights is view.
     *
     * @return null
     */
    public function testHasRightsView()
    {
        /** @var AdminRights|MockObject $oRights */
        $oRights = $this->getMock(\OxidEsales\Eshop\Core\AdminRights::class, array('getObjectRightsIndex'));
        $oRights->expects($this->once())->method('getObjectRightsIndex')->will($this->returnValue(RIGHT_VIEW));
        $this->assertTrue($oRights->hasRights(RIGHT_VIEW, oxNew(\OxidEsales\Eshop\Application\Model\Article::class)));

        /** @var AdminRights|MockObject $oRights */
        $oRights = $this->getMock(\OxidEsales\Eshop\Core\AdminRights::class, array('getObjectRightsIndex'));
        $oRights->expects($this->once())->method('getObjectRightsIndex')->will($this->returnValue(RIGHT_DENY));
        $this->assertFalse($oRights->hasRights(RIGHT_VIEW, oxNew(\OxidEsales\Eshop\Application\Model\Article::class)));
    }

    /**
     * Test process navigation tree.
     *
     * @return null
     */
    public function testProcessNaviTree()
    {
        $sInput = '<?xml version="1.0" encoding="UTF-8"?>
               <OX>
                 <OXMENU>
                   <MAINMENU id="mxmainmenu">
                     <SUBMENU id="mxcoresett">
                       <TAB id="tbclshop_main" />
                     </SUBMENU>
                   </MAINMENU>
                   <MAINMENU id="mxshopsett">
                     <SUBMENU id="mxpaymeth">
                       <TAB id="tbclpayment_main" />
                       <TAB id="tbclpayment_country" />
                     </SUBMENU>
                   </MAINMENU>
                 </OXMENU>
               </OX>';

        $sOutput = '<?xml version="1.0" encoding="UTF-8"?>
               <OX idx="2">
                 <OXMENU idx="2">
                   <MAINMENU id="mxmainmenu" idx="1">
                     <SUBMENU id="mxcoresett" idx="1">
                       <TAB id="tbclshop_main"  idx="1" />
                     </SUBMENU>
                   </MAINMENU>
                 </OXMENU>
               </OX>';

        /** @var AdminRights|MockObject $oRights */
        $oRights = $this->getMock(\OxidEsales\Eshop\Core\AdminRights::class, array('getViewRightsIndex', 'getViewRights'));
        $oRights->expects($this->any())->method('getViewRightsIndex')->will($this->onConsecutiveCalls(null, null, RIGHT_VIEW, RIGHT_VIEW, RIGHT_VIEW, RIGHT_DENY, null, null, null));
        $oRights->expects($this->any())->method('getViewRights')->will($this->returnValue(array(1, 2)));

        $oTree = new DOMDocument();
        $oTree->loadXML($sInput);

        $oRights->processNaviTree($oTree);

        $this->assertEquals(str_replace(array("\n", "\r", "\t", " "), "", $sOutput), str_replace(array("\n", "\r", "\t", " "), "", $oTree->saveXML()));
    }

    /**
     * Test get view rights index.
     *
     * @return null
     */
    public function testGetViewRightsIndex()
    {
        $aRights = array('xxx' => 'yyy');

        /** @var AdminRights|MockObject $oRights */
        $oRights = $this->getMock(\OxidEsales\Eshop\Core\AdminRights::class, array('getViewRights'));
        $oRights->expects($this->any())->method('getViewRights')->will($this->returnValue($aRights));

        $this->assertEquals('yyy', $oRights->getViewRightsIndex('xxx'));
    }

    /**
     * Test get object rights index.
     *
     * @return null
     */
    public function testGetObjectRightsIndex()
    {
        $aRights = array('xxx' => array('yyy' => 5));

        /** @var AdminRights|MockObject $oRights */
        $oRights = $this->getMock(\OxidEsales\Eshop\Core\AdminRights::class, array("getObjectRights"));
        $oRights->expects($this->once())->method('getObjectRights')->will($this->returnValue($aRights));
        $this->assertEquals(5, $oRights->getObjectRightsIndex('xxx', 'yyy'));
    }

    /**
     * Test process view.
     *
     * @return null
     */
    public function testProcessView()
    {
        $oView = oxNew(\OxidEsales\Eshop\Core\Controller\BaseController::class);
        /** @var AdminRights|MockObject $oRights */
        $oRights = $this->getMock(\OxidEsales\Eshop\Core\AdminRights::class, array('getViewRightsIndex'));
        $oRights->expects($this->once())->method('getViewRightsIndex')->will($this->returnValue(null));

        $oRights->processView($oView);
    }

    /**
     * Test process view returns readonly.
     *
     * @return null
     */
    public function testProcessViewReadOnly()
    {
        $oView = oxNew(\OxidEsales\Eshop\Core\Controller\BaseController::class);

        /** @var AdminRights|MockObject $oRights */
        $oRights = $this->getMock(\OxidEsales\Eshop\Core\AdminRights::class, array('getViewRightsIndex'));
        $oRights->expects($this->once())->method('getViewRightsIndex')->will($this->returnValue(1));
        $oRights->processView($oView);

        $aView = $oView->getViewData();
        $this->assertTrue(isset($aView['readonly']));
        $this->assertTrue(isset($aView['disablenew']));
    }

    /**
     * Test process view returns denied.
     *
     * @return null
     */
    public function testProcessViewDenied()
    {
        $this->expectException('\OxidEsales\EshopEnterprise\Core\Exception\AccessRightException');

        $oView = oxNew(\OxidEsales\Eshop\Core\Controller\BaseController::class);
        /** @var AdminRights|MockObject $oRights */
        $oRights = $this->getMock(\OxidEsales\Eshop\Core\AdminRights::class, array('getViewRightsIndex'));
        $oRights->expects($this->once())->method('getViewRightsIndex')->will($this->returnValue(0));

        $oRights->processView($oView);
    }

    /**
     * Test load view rights.
     *
     * @return null
     */
    public function testLoadViewRights()
    {
        $oUser = oxNew(\OxidEsales\Eshop\Application\Model\User::class);
        $oUser->load('36944b76cc9604c53.04579642'); // management user

        /** @var AdminRights|MockObject $oRights */
        $oRights = $this->getMock(\OxidEsales\Eshop\Core\AdminRights::class, array('getUser'));
        $oRights->expects($this->once())->method('getUser')->will($this->returnValue($oUser));

        $aRights = $oRights->getViewRights();
        $this->assertTrue(isset($aRights['dyn_about']));
        $this->assertTrue(isset($aRights['dyn_interface']));
        $this->assertTrue(isset($aRights['dyn_menu']));
        $this->assertTrue(isset($aRights['mxactions']));
    }

    /**
     * Test load view rights when user is not set.
     *
     * @return null
     */
    public function testLoadViewRightsIfNoUserIsSet()
    {
        /** @var AdminRights|MockObject $oRights */
        $oRights = $this->getMock(\OxidEsales\Eshop\Core\AdminRights::class, array('getUser'));
        $oRights->expects($this->once())->method('getUser')->will($this->returnValue(false));

        $aRights = $oRights->getViewRights();
        $this->assertFalse(isset($aRights['dyn_about']));
        $this->assertFalse(isset($aRights['dyn_interface']));
        $this->assertFalse(isset($aRights['dyn_menu']));
        $this->assertFalse(isset($aRights['mxactions']));
    }

    /**
     * Test get object rights.
     *
     * @return null
     */
    public function testGetObjectRights()
    {
        $this->getConfig()->setConfigParam('sAdminDir', 'admin');

        $oUser = oxNew(\OxidEsales\Eshop\Application\Model\User::class);
        $oUser->load('36944b76cc9604c53.04579642'); // management user

        /** @var AdminRights|MockObject $oRights */
        $oRights = $this->getMock(\OxidEsales\Eshop\Core\AdminRights::class, array('getUser'));
        $oRights->expects($this->once())->method('getUser')->will($this->returnValue($oUser));

        $aRights = $oRights->getObjectRights();

        $this->assertTrue(isset($aRights['oxarticles']));
        $this->assertTrue(isset($aRights['oxcategories']));
    }

    /**
     * Test load object rights when user is not set.
     *
     * @return null
     */
    public function testLoadObjectRightsIfUserNotSet()
    {
        /** @var AdminRights|MockObject $oRights */
        $oRights = $this->getMock(\OxidEsales\Eshop\Core\AdminRights::class, array('getUser'));
        $oRights->expects($this->once())->method('getUser')->will($this->returnValue(null));

        $aRights = $oRights->getObjectRights();
        $this->assertFalse(isset($aRights['oxarticles']));
        $this->assertFalse(isset($aRights['oxcategories']));
    }

    /**
     * @throws Exception
     */
    public function testGetObjectConfig()
    {
        $moduleDir = getShopBasePath() . 'modules/oxtestmodule/';

        $config = $this->getConfig();
        $config->setConfigParam('sAdminDir', 'admin');
        $config->setConfigParam('aModulePaths', array('oxtestmodule' => 'oxtestmodule'));

        // creating file in modules dir for complete simumlation
        $sContent = '<?xml version="1.0" encoding="UTF-8"?><objects><object table="oxtest"><field name="oxtestfield" /></object></objects>';

        try {
            mkdir($moduleDir, 0777, true);
            file_put_contents($moduleDir .'object_rights.xml', $sContent);

            $oRights = oxNew(\OxidEsales\Eshop\Core\AdminRights::class);
            $aRights = $oRights->getObjectConfig();

            $this->assertTrue(isset($aRights['oxarticles']));
            $this->assertTrue(isset($aRights['oxcategories']));
            $this->assertTrue(isset($aRights['oxtest']));
            $this->assertTrue(isset($aRights['oxtest']['oxtestfield']));
        } catch (Exception $e) {
            @unlink($moduleDir . 'object_rights.xml');
            @rmdir($moduleDir);

            throw $e;
        }

        @unlink($moduleDir . 'object_rights.xml');
        @rmdir($moduleDir);
    }

}
