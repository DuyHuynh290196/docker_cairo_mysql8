<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Tests\Unit\Core;

use OxidEsales\TestingLibrary\UnitTestCase;

/**
 * Tests online request.
 *
 * @covers oxOnlineRequest
 */
class OnlineRequestTest extends UnitTestCase
{
    public function testClusterIdTakenFromBaseShopInsteadOfSubShop()
    {
        $config = $this->getConfig();
        $config->saveShopConfVar("str", 'sClusterId', 'generated_unique_cluster_id', 1);

        $this->setShopId(9);
        $config->setConfigParam('sClusterId', '');
        $request = oxNew(\OxidEsales\Eshop\Core\OnlineRequest::class);
        $this->assertSame('generated_unique_cluster_id', $request->clusterId);
    }
}
