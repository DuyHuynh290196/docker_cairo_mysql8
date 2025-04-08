<?php
/**
 * This Software is the property of OXID eSales and is protected
 * by copyright law.
 *
 * Any unauthorized use of this software will be prosecuted by
 * civil and criminal law.
 *
 * @link      http://www.oxid-esales.com
 * @copyright (C) OXID eSales AG 2003-2017
 * @version   OXID eSales Visual CMS
 */

namespace OxidEsales\VisualCmsModule\Tests\Unit\Component;

use OxidEsales\VisualCmsModule\Application\Component\BasketComponent;

use OxidEsales\TestingLibrary\UnitTestCase;

/**
 * Class ddVisualEditorOxCmpBasketTest
 */
class BasketComponentTest extends UnitTestCase
{
    /**
     * Test render return when passing various active themes.
     */
    public function testInit()
    {
        $basketComponent = oxNew(BasketComponent::class );
        $basketComponent->init();
        $this->assertContains('oxcid', $basketComponent->aRedirectParams);
        $this->assertContains('oxloadid', $basketComponent->aRedirectParams);
    }
}
