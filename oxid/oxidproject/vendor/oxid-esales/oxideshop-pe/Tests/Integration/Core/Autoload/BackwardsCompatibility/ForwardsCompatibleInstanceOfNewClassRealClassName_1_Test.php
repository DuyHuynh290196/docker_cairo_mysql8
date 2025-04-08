<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopProfessional\Tests\Integration\Core\Autoload\BackwardsCompatibility;

class ForwardsCompatibleInstanceOfNewClassRealClassName_1_Test extends \PHPUnit\Framework\TestCase
{
    /**
     * Test the backwards compatibility of class instances created with oxNew and the alias class name
     */
    public function testForwardsCompatibleInstanceOfNewClassRealClassName()
    {
        $this->markTestSkipped(
            'This test will fail on Travis and CI as it MUST run in an own PHP process, which is not possible.'
        );

        $realClassNameCE   = \OxidEsales\EshopCommunity\Application\Model\Article::class;
        $realClassNamePE = \OxidEsales\EshopProfessional\Application\Model\Article::class;
        $unfiedClassName = \OxidEsales\Eshop\Application\Model\Article::class;
        $backwardsCompatibleClassAlias = \oxArticle::class;

        $message = 'Backwards compatible class name - absolute namespace with ::class constant';
        
        $object = new $realClassNamePE();

        $this->assertInstanceOf($backwardsCompatibleClassAlias, $object, $message);
        $this->assertInstanceOf($realClassNameCE, $object, $message);
        $this->assertInstanceOf($realClassNamePE, $object, $message);
        $this->assertInstanceOf($unfiedClassName, $object, $message);
    }
}
