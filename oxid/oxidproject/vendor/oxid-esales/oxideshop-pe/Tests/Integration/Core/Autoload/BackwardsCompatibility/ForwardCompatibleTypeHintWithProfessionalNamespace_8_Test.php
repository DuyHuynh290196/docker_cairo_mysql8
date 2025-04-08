<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopProfessional\Tests\Integration\Core\Autoload\BackwardsCompatibility;

class ForwardCompatibleTypeHintWithProfessionalNamespace_8_Test extends \PHPUnit\Framework\TestCase
{
    /**
     * Test the backwards compatibility with camel cased type hints
     */
    public function testForwardCompatibleTypeHintWithProfessionalNamespace()
    {
        $object = new \OxidEsales\Eshop\Application\Model\Article();
        /**
         * @param \OxidEsales\EshopProfessional\Application\Model\Article $object
         */
        $functionWithTypeHint = function (\OxidEsales\EshopProfessional\Application\Model\Article $object) {
            /** If the function was called successfully, the test would have passed */
            $this->assertTrue(true);
        };
        /** The function call would produce a catchable fatal error, if the type hint is not correct */
        $functionWithTypeHint($object);
    }
}
