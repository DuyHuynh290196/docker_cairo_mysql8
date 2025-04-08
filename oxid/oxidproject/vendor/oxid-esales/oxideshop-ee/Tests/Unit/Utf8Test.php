<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Tests\Unit\Model;

use oxField;
use OxidEsales\EshopEnterprise\Core\Cache\DynamicContent\ContentCache;
use OxidEsales\TestingLibrary\UnitTestCase;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * Tests utf8 related functionality.
 */
class Utf8Test extends UnitTestCase
{
    public function testOxRoleSaveAndLoad()
    {
        $value = 'agentūЛитовfür';

        $fields = array('oxroles__oxtitle');

        $role = oxNew(\OxidEsales\Eshop\Application\Model\Role::class);
        $role->setId('_testRole');
        foreach ($fields as $fieldName) {
            $role->{$fieldName} = new \OxidEsales\Eshop\Core\Field($value);
        }
        $role->save();

        $role = oxNew(\OxidEsales\Eshop\Application\Model\Role::class);
        $role->load('_testRole');

        foreach ($fields as $fieldName) {
            $this->assertTrue(strcmp($role->{$fieldName}->value, $value) === 0, "$fieldName (" . $role->{$fieldName}->value . ")");
        }
    }

    public function testCacheProcessing()
    {
        $contentToProcess = "agentūлитовfür <oxid_dynamic><a href=\"someurl.php?cl=comecl&amp;sid=somesid&amp;something=something\" title=\"agentūлитовfür\"></oxid_dynamic> agentūлитовfür";
        $contentWillGet = "agentūлитовfür # agentūлитовfür";

        /** @var ContentCache|MockObject $cache */
        $cache = $this->getMock(\OxidEsales\Eshop\Core\Cache\DynamicContent\ContentCache::class, array('_processDynContent'));
        $cache->expects($this->once())->method('_processDynContent')->will($this->returnValue('#'));
        $this->assertEquals($contentWillGet, $cache->processCache($contentToProcess));
    }
}
