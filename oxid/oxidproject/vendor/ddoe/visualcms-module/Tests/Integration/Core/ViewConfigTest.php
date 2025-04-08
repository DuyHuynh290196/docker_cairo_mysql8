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

namespace OxidEsales\VisualCmsModule\Tests\Integration\Core;

use OxidEsales\TestingLibrary\UnitTestCase;
use OxidEsales\Eshop\Core\ViewConfig;
use OxidEsales\Eshop\Application\Model\ArticleList;
use OxidEsales\Eshop\Application\Model\ManufacturerList;

/**
 * Class ddVisualEditorOxViewConfigTest
 */
class ViewConfigTest extends UnitTestCase
{

    /**
     *
     */
    public function testGetNewestArticles()
    {
        /** @var ViewConfig $viewConfig */
        $viewConfig = oxNew(ViewConfig::class );

        $list = $viewConfig->getNewestArticles();
        $this->assertInstanceOf(ArticleList::class, $list);
        $this->assertEquals(2, $list->count());
    }

    /**
     *
     */
    public function testGetTopArticleList()
    {
        /** @var ViewConfig $viewConfig */
        $viewConfig = oxNew( ViewConfig::class );

        $list = $viewConfig->getTopArticleList();
        $this->assertInstanceOf(ArticleList::class, $list);
        $this->assertEquals(1, $list->count());
        $this->assertEquals("1849", $list->current()->getId());
    }

    /**
     *
     */
    public function testGetCategoryArticlesAll()
    {
        /** @var ViewConfig $viewConfig */
        $viewConfig = oxNew(ViewConfig::class );

        $list = $viewConfig->getCategoryArticles('8a142c3e4143562a5.46426637');
        $this->assertInstanceOf(ArticleList::class, $list);
        $this->assertEquals(2, $list->count());
        $this->assertEquals("2024", $list->current()->getId());
    }

    /**
     *
     */
    public function testGetCategoryArticlesWithLimit()
    {
        /** @var ViewConfig $viewConfig */
        $viewConfig = oxNew(ViewConfig::class );

        $list = $viewConfig->getCategoryArticles('8a142c3e4143562a5.46426637', 1);
        $this->assertInstanceOf(ArticleList::class, $list);
        $this->assertEquals(1, $list->count());
        $this->assertEquals("2024", $list->current()->getId());
    }

    /**
     *
     */
    public function testGetManufacturerList()
    {
        /** @var ViewConfig $viewConfig */
        $viewConfig = oxNew(ViewConfig::class );

        $list = $viewConfig->getManufacturerlist();
        $this->assertInstanceOf(ManufacturerList::class, $list);
        $this->assertEquals(2, $list->count());
    }

    /**
     *
     */
    public function testIsAzureThemeTrue()
    {
        /** @var ViewConfig $viewConfig */
        $viewConfig = oxNew(ViewConfig::class );
        //set request parameters
        $this->setConfigParam('sTheme', 'azure');

        $this->assertTrue($viewConfig->isAzureTheme());
    }

    /**
     *
     */
    public function testIsAzureThemeFalse()
    {
        /** @var ViewConfig $viewConfig */
        $viewConfig = oxNew(ViewConfig::class );
        //set request parameters
        $this->setConfigParam('sTheme', 'notazure');

        $this->assertFalse($viewConfig->isAzureTheme());
    }

    /**
     * Returns request parameter and expected result
     *
     * @return array
     */
    public function navFormParamsProvider()
    {
        return array(
            array(null, null, 'testAbc'),   //active flow theme
            array('oxagb', null, 'testAbc<input type="hidden" name="oxcid" value="oxagb" />' . "\n"),    //active azure theme
            array(null, 'oxagb', 'testAbc<input type="hidden" name="oxloadid" value="oxagb" />' . "\n"),  //other azure theme
        );
    }

    /**
     * ddoeVisualCmsOxViewConfig::getNavFormParams() test case
     *
     * @param $oxCid   string
     * @param $oxLoad    string
     * @param $expectedResult string
     *
     * @dataProvider navFormParamsProvider
     */
    public function getNavFormParams($oxCid, $oxLoad, $expectedResult)
    {
        $testText = "testAbc";

        /** @var ViewConfig $viewConfig */
        $viewConfig = oxNew(ViewConfig::class );
        $viewConfig->setViewConfigParam('navformparams', $testText);

        //set request parameters
        $this->setRequestParameter('oxcid', $oxCid);
        $this->setRequestParameter('oxloadid', $oxLoad);

        $this->assertEquals($expectedResult, $viewConfig->getNavFormParams());
    }

}
