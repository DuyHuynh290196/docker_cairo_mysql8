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

namespace OxidEsales\VisualCmsModule\Tests\Unit\Controller;

use OxidEsales\TestingLibrary\UnitTestCase;

/**
 * Class ddoeVisualCmsContentTest
 */
class ContentControllerTest extends UnitTestCase
{

    /**
     * Returns active theme and expected result
     *
     * @return array
     */
    public function activeThemeProvider()
    {
        return array(
            array(true, false, 'ddoe_roxive_content.tpl'),   //active flow theme
            array(false, true, 'ddoe_azure_content.tpl'),    //active azure theme
            array(false, false, 'page/info/content.tpl'),  //other azure theme
        );
    }

    /**
     * Test render return when passing various active themes.
     *
     * @param $activeRoxive   string
     * @param $activeAzure    string
     * @param $expectedResult string
     *
     * @dataProvider activeThemeProvider
     */
    public function testRenderVariousThemes($activeRoxive, $activeAzure, $expectedResult)
    {
        // set config active theme parameter
        $viewConfig = $this->getMock(\OxidEsales\Eshop\Core\ViewConfig::class, array("isAzureTheme", "isRoxiveTheme"));
        $viewConfig->expects($this->any())->method('isAzureTheme')->will($this->returnValue($activeAzure));
        $viewConfig->expects($this->any())->method('isRoxiveTheme')->will($this->returnValue($activeRoxive));

        $content = $this->getMock(\OxidEsales\Eshop\Application\Controller\ContentController::class, array("getViewConfig"));
        $content->expects($this->any())->method('getViewConfig')->will($this->returnValue($viewConfig));
        $this->assertEquals($expectedResult, $content->render());

    }

}
