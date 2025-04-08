<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopProfessional\Tests\Unit\Core;

use OxidEsales\Eshop\Core\ExpirationEmailBuilder;
use OxidEsales\Eshop\Core\Registry;

class ExpirationEmailBuilderTest extends \oxUnitTestCase
{
    public function providerGetEmail()
    {
        $lang = Registry::getLang();
        return array(
            array(0.5, $lang->translateString('SHOP_LICENSE_ERROR_GRACE_WILL_EXPIRE', null, true)),
            array(1, $lang->translateString('SHOP_LICENSE_ERROR_GRACE_WILL_EXPIRE', null, true)),
            array(7, $lang->translateString('SHOP_LICENSE_ERROR_shop_unlicensed', null, true)),
            array(100, $lang->translateString('SHOP_LICENSE_ERROR_shop_unlicensed', null, true)),
        );
    }

    /**
     * @param $daysTillExpiration
     * @param $expectedBody
     *
     * @dataProvider providerGetEmail
     */
    public function testGetEmailAndCheckIfBodyWasSetCorrectly($daysTillExpiration, $expectedBody)
    {
        $oExpirationEmailBuilder = oxNew(ExpirationEmailBuilder::class);
        $oExpirationEmail = $oExpirationEmailBuilder->build($daysTillExpiration);

        $this->assertStringStartsWith(
            $expectedBody,
            $oExpirationEmail->getBody(),
            'Email content is not that as it should be.'
        );
    }

    /**
     * @param $daysTillExpiration
     *
     * @dataProvider providerGetEmail
     */
    public function testIfEmailBodyEndsWithOriginInformation($daysTillExpiration)
    {
        $this->setConfigParam('sShopURL', 'someTestUrl');
        $lang = Registry::getLang();

        $expirationEmailBuilder = oxNew('oxExpirationEmailBuilder');
        $email = $expirationEmailBuilder->build($daysTillExpiration);

        $expectedEnd = sprintf($lang->translateString(
            'SHOP_EMAIL_ORIGIN_MESSAGE',
            null,
            true
        ), "someTestUrl");

        $this->assertStringEndsWith($expectedEnd, $email->getBody());
    }
}
