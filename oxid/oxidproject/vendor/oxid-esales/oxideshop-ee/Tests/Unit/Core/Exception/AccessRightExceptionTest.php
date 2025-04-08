<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */
namespace OxidEsales\EshopEnterprise\Tests\Unit\Core\Exception;

use \PHPUnit\Framework\MockObject\MockObject;

class AccessRightExceptionTest extends \oxUnitTestCase
{
    /**
     * Test set/get object name.
     *
     * @return null
     */
    public function testSetGetObjectName()
    {
        $className = 'className';
        $testObject = oxNew(\OxidEsales\Eshop\Core\Exception\AccessRightException::class);
        $testObject->setObjectName($className);
        $this->assertEquals($className, $testObject->getObjectName());
    }

    /**
     * Test error message.
     *
     * We check on class name and message only - rest is not checked yet
     *
     * @return null
     */
    public function testToString()
    {
        $message = 'Erik was here..';
        $testObject = oxNew(\OxidEsales\Eshop\Core\Exception\AccessRightException::class, $message);
        $this->assertEquals('OxidEsales\Eshop\Core\Exception\AccessRightException', get_class($testObject));

        $className = 'ClassName';
        $testObject->setObjectName($className);
        $stringOut = $testObject->getString();
        $this->assertStringContainsString($message, $stringOut); // Message
        $this->assertStringContainsString('OxidEsales\EshopEnterprise\Core\Exception\AccessRightException', $stringOut); // Exception class name
        $this->assertStringContainsString($className, $stringOut); // Object name
    }

    /**
     * Test get values.
     *
     * @return null
     */
    public function testGetValues()
    {
        $testObject = oxNew(\OxidEsales\Eshop\Core\Exception\AccessRightException::class);
        $className = 'className';
        $testObject->setObjectName($className);
        $result = $testObject->getValues();
        $this->assertArrayHasKey('object', $result);
        $this->assertTrue($className === $result['object']);
    }
}
