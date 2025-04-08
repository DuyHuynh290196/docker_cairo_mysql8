<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Tests\Unit\Application\Controller\Admin;

use \oxTestModules;

/**
 * Class ActionsMainTest.
 */
class ActionsMainTest extends \OxidEsales\TestingLibrary\UnitTestCase
{
    /**
     * Actions_Main::Save() test case
     *
     * @return null
     */
    public function testSaveDefaultActionWithPermissions()
    {
        oxTestModules::addFunction('oxActions', 'load', '{ return true; }');
        oxTestModules::addFunction('oxActions', 'isDefault', '{ return true; }');

        $this->setRequestParameter("oxid", "-1");
        $this->setConfigParam("blAllowSharedEdit", true);

        $view = oxNew('Actions_Main');
        $view->save();

        $viewData = $view->getViewData();
        $this->assertTrue(isset($viewData["updatelist"]));
        $this->assertEquals(1, $viewData["updatelist"]);
    }

    /**
     * Actions_Main::Save() test case
     *
     * @return null
     */
    public function testSaveDefaultActionWithoutPermissions()
    {
        oxTestModules::addFunction('oxActions', 'load', '{ return true; }');
        oxTestModules::addFunction('oxActions', 'isDefault', '{ return true; }');

        $this->setRequestParameter("oxid", "-1");
        $this->setConfigParam("blAllowSharedEdit", false);

        $view = oxNew('Actions_Main');
        $view->save();

        $viewData = $view->getViewData();

        $this->assertFalse(isset($viewData["updatelist"]));
    }
}
