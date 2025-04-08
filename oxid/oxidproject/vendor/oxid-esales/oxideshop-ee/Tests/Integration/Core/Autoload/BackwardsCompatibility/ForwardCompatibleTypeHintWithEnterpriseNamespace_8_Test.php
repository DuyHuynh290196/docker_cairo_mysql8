<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Tests\Integration\Core\Autoload\BackwardsCompatibility;

class ForwardCompatibleTypeHintWithEnterpriseNamespace_8_Test extends \PHPUnit\Framework\TestCase
{
    /**
     * Test the backwards compatibility with camel cased type hints
     */
    public function testForwardCompatibleTypeHintWithEnterpriseNamespace()
    {
        $object = new \OxidEsales\Eshop\Application\Model\Article();
        /**
         * @param \OxidEsales\EshopEnterprise\Application\Model\Article $object
         */
        $functionWithTypeHint = function (\OxidEsales\EshopEnterprise\Application\Model\Article $object) {
            /** If the function was called successfully, the test would have passed */
            $this->assertTrue(true);
        };
        /** The function call would produce a catchable fatal error, if the type hint is not correct */
        $functionWithTypeHint($object);
    }
}
