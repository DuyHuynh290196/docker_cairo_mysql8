<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Tests\Unit\Application\Controller\Admin;

use oxField;
use oxTestModules;

/**
 * Unit test class for CategoryMain.
 */
class CategoryMainTest extends \OxidEsales\TestingLibrary\UnitTestCase
{
    /**
     * \OxidEsales\Eshop\Application\Controller\Admin\CategoryMain::_deleteCatPicture() no rights
     */
    public function testDeleteThumbnailNoRights()
    {
        $oItem = $this->getMock("\oxCategory", array("canUpdateField", 'canUpdate', 'isDerived'));
        $oItem->expects($this->once())->method('canUpdateField')->with($this->equalTo('oxthumb'))->will($this->returnValue(true));
        $oItem->expects($this->once())->method('canUpdate')->will($this->returnValue(false));
        $oItem->expects($this->never())->method('isDerived')->will($this->returnValue(false));

        $oItem->oxcategories__oxthumb = new \OxidEsales\Eshop\Core\Field('testThumb.jpg');

        $oPicHandler = $this->getMock(\OxidEsales\Eshop\Core\UtilsPic::class, array('safePictureDelete'));
        $oPicHandler->expects($this->never())->method('safePictureDelete');
        $this->addClassExtension(get_class($oPicHandler), 'oxUtilsPic');

        $oView = $this->getProxyClass('Category_Main');
        $oView->UNITdeleteCatPicture($oItem, 'oxthumb');
        $this->assertEquals('testThumb.jpg', $oItem->oxcategories__oxthumb->value);
    }

    /**
     * \OxidEsales\Eshop\Application\Controller\Admin\CategoryMain::_deleteCatPicture() derived category
     */
    public function testDeleteThumbnailDerived()
    {
        $oItem = $this->getMock("\oxCategory", array("canUpdateField", 'canUpdate', 'isDerived'));
        $oItem->expects($this->once())->method('canUpdateField')->with($this->equalTo('oxthumb'))->will($this->returnValue(true));
        $oItem->expects($this->once())->method('canUpdate')->will($this->returnValue(true));
        $oItem->expects($this->once())->method('isDerived')->will($this->returnValue(true));

        $oItem->oxcategories__oxthumb = new \OxidEsales\Eshop\Core\Field('testThumb.jpg');

        $oPicHandler = $this->getMock(\OxidEsales\Eshop\Core\UtilsPic::class, array('safePictureDelete'));
        $oPicHandler->expects($this->never())->method('safePictureDelete');
        $this->addClassExtension(get_class($oPicHandler), 'oxUtilsPic');

        $oView = $this->getProxyClass('Category_Main');
        $oView->UNITdeleteCatPicture($oItem, 'oxthumb');
        $this->assertEquals('testThumb.jpg', $oItem->oxcategories__oxthumb->value);
    }

    /**
     * \OxidEsales\Eshop\Application\Controller\Admin\CategoryMain::Saveinnlang() test case
     */
    public function testSaveIsDerived()
    {
        $this->setRequestParameter("oxid", "testId");
        oxTestModules::addFunction('oxcategory', 'save', '{ return true; }');
        oxTestModules::addFunction('oxcategory', 'isDerived', '{ return true; }');

        // testing..
        $oView = oxNew(\OxidEsales\Eshop\Application\Controller\Admin\CategoryMain::class);
        $oView->save();

        $this->assertNull($oView->getViewDataElement("updatelist"));
    }

    /**
     * \OxidEsales\Eshop\Application\Controller\Admin\CategoryMain::Saveinnlang() test case
     */
    public function testSaveinnlangIsDerived()
    {
        $this->setRequestParameter("oxid", "testId");
        oxTestModules::addFunction('oxcategory', 'save', '{ return true; }');
        oxTestModules::addFunction('oxcategory', 'isDerived', '{ return true; }');

        // testing..
        $oView = oxNew(\OxidEsales\Eshop\Application\Controller\Admin\CategoryMain::class);
        $oView->saveinnlang();

        $this->assertNull($oView->getViewDataElement("updatelist"));
    }

    /**
     * This is a non wished behavior in contrast to
     *
     * @see ArticleMainTest::testRenderEditorIsDisabledWhenArticleIsDerivedInSubshop
     *
     * The category and all other models should know when its fields are able to updatable.
     * For rights and roles and subshop inheritance.
     */
    public function testRenderEditorIsEnabledWhenCategoryIsDerivedInSubshop()
    {
        /** @var \OxidEsales\Eshop\Application\Model\Article $categoryMock */
        $categoryMock = $this->getMock(\OxidEsales\Eshop\Application\Model\Category::class, ['isDerived']);
        $categoryMock->expects($this->any())->method('isDerived')->will($this->returnValue(true));

        /** @var \OxidEsales\Eshop\Application\Controller\TextEditorHandler $textEditorHandlerMock */
        $textEditorHandlerMock = $this->getMock(
            \OxidEsales\Eshop\Application\Controller\TextEditorHandler::class,
            null
        );

        $categoryMainMock = $this->getMock(
            \OxidEsales\Eshop\Application\Controller\Admin\CategoryMain::class,
            ['createTextEditorHandler', 'createArticle']
        );
        $categoryMainMock->expects($this->any())->method('createTextEditorHandler')->will($this->returnValue($textEditorHandlerMock));
        $categoryMainMock->expects($this->any())->method('createArticle')->will($this->returnValue($categoryMock));

        $categoryMainMock->render();
        $this->assertFalse($textEditorHandlerMock->isTextEditorDisabled());
    }
}
