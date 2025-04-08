<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Tests\Unit\Core;

use OxidEsales\Eshop\Core\Registry;

class SessionTest extends \oxUnitTestCase
{
    /**
     * Test if add subshop ID to URL.
     */
    function testSidInAdmin()
    {
        $session = $this->getMock(\OxidEsales\Eshop\Core\Session::class, array('_getCookieSid', 'isAdmin', 'getSessionChallengeToken'));
        $session->expects($this->any())->method('getSessionChallengeToken')->will($this->returnValue('stok'));
        $session->expects($this->any())->method('_getCookieSid')->will($this->returnValue('admin_sid'));
        $session->expects($this->any())->method('isAdmin')->will($this->returnValue(true));
        $session->UNITsetSessionId('testSid');

        $urlPartWithSid = 'stoken=stok&amp;shp=' . $this->getConfig()->getShopId();

        $this->assertEquals($urlPartWithSid, $session->sid());
    }
}
