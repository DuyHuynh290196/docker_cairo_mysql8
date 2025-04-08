<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */
namespace OxidEsales\EshopEnterprise\Tests\Unit\Application\Model;

use oxActions;
use oxField;

class ActionTest extends \OxidEsales\TestingLibrary\UnitTestCase
{
    /**
     * oxActions::delete() test case
     * Trying to delete denied action by RR (EE only)
     */
    public function testDeleteDeniedByRR()
    {
        $realAction = new \OxidEsales\Eshop\Application\Model\Actions();
        $realAction->oxactions__oxtitle = new \OxidEsales\Eshop\Core\Field("test", \OxidEsales\Eshop\Core\Field::T_RAW);
        $realAction->save();

        $articleId = 'xxx';

        $action = $this->getMock(\OxidEsales\Eshop\Application\Model\Actions::class, array('canDelete'));
        $action->expects($this->once())->method('canDelete')->will($this->returnValue(false));

        $action->load($realAction->getId());
        $action->addArticle($articleId);
        $this->assertFalse($action->delete());
    }
}
