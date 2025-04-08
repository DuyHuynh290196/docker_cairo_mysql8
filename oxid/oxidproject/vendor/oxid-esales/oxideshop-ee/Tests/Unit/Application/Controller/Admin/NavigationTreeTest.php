<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Tests\Unit\Application\Controller\Admin;

use OxidEsales\TestingLibrary\UnitTestCase;
use StdClass;

class NavigationTreeTest extends UnitTestCase
{
    public function testGetDomXml()
    {
        $aTestMethods = array("_getInitialDom", "_checkGroups", "_checkRights", "_cleanEmptyParents", "getRights", 'removeInvisibleMenuNodes');

        $oRights = $this->getMock(\OxidEsales\Eshop\Core\AdminRights::class, array("processNaviTree"));
        $oRights->expects($this->once())->method('processNaviTree');

        $oNavTree = $this->getMock(\OxidEsales\Eshop\Application\Controller\Admin\NavigationTree::class, $aTestMethods);
        $oNavTree->expects($this->once())->method('_getInitialDom')->will($this->returnValue(new stdClass));

        $oNavTree->expects($this->once())->method('getRights')->will($this->returnValue($oRights));

        $oNavTree->getDomXml();
    }
}
