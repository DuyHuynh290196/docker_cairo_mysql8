<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Tests\Integration\Core\Autoload\BackwardsCompatibility;

class ForwardsCompatibleInstanceOfNewClassUnifiedClassName_6_Test extends \PHPUnit\Framework\TestCase
{
    /**
     * Test the backwards compatibility of class instances created with oxNew and the alias class name
     */
    public function testForwardsCompatibleInstanceOfNewClassUnifiedClassName()
    {
        $realClassNameCE   = \OxidEsales\EshopCommunity\Application\Model\Article::class;
        $realClassNamePE = \OxidEsales\EshopProfessional\Application\Model\Article::class;
        $realClassNameEE = \OxidEsales\EshopEnterprise\Application\Model\Article::class;
        $unifiedClassName = \OxidEsales\Eshop\Application\Model\Article::class;
        $backwardsCompatibleClassAlias = 'oxArticle';

        $message = 'Backwards compatible class name - CamelCase string';

        $object = new $unifiedClassName();

        $this->assertInstanceOf($backwardsCompatibleClassAlias, $object, $message);
        $this->assertInstanceOf($realClassNameCE, $object, $message);
        $this->assertInstanceOf($realClassNamePE, $object, $message);
        $this->assertInstanceOf($realClassNameEE, $object, $message);
        $this->assertInstanceOf($unifiedClassName, $object, $message);
    }
}
