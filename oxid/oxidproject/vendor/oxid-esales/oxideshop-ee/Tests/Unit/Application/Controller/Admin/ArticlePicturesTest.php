<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Tests\Unit\Application\Controller\Admin;

class ArticlePicturesTest extends \OxidEsales\TestingLibrary\UnitTestCase
{
    /**
     * Article_Pictures::_deleteMainIcon().
     */
    public function testDeleteMainIconDerived()
    {
        $oArticle = $this->getMock(\OxidEsales\Eshop\Application\Model\Article::class, array("canUpdateField", 'canUpdate', 'isDerived'));
        $oArticle->expects($this->once())->method('canUpdateField')->with($this->equalTo('oxicon'))->will($this->returnValue(true));
        $oArticle->expects($this->once())->method('canUpdate')->will($this->returnValue(true));
        $oArticle->expects($this->once())->method('isDerived')->will($this->returnValue(true));

        $oArticle->oxarticles__oxicon = new \oxField("testIcon.jpg");

        $oPicHandler = $this->getMock(\OxidEsales\Eshop\Core\PictureHandler::class, array("deleteMainIcon"));
        $oPicHandler->expects($this->never())->method('deleteMainIcon');

        \oxTestModules::addModuleObject("oxPictureHandler", $oPicHandler);

        $oArtPic = $this->getProxyClass("Article_Pictures");
        $oArtPic->UNITdeleteMainIcon($oArticle);

        $this->assertEquals("", $oArticle->oxarticles__oxicon->value);
    }

    /**
     * Article_Pictures::_deleteThumbnail().
     */
    public function testDeleteThumbnailDerived()
    {
        $oArticle = $this->getMock(\OxidEsales\Eshop\Application\Model\Article::class, array("canUpdateField", 'canUpdate', 'isDerived'));
        $oArticle->expects($this->once())->method('canUpdateField')->with($this->equalTo('oxthumb'))->will($this->returnValue(true));
        $oArticle->expects($this->once())->method('canUpdate')->will($this->returnValue(true));
        $oArticle->expects($this->once())->method('isDerived')->will($this->returnValue(true));

        $oArticle->oxarticles__oxthumb = new \oxField("testThumb.jpg");

        $oPicHandler = $this->getMock(\OxidEsales\Eshop\Core\PictureHandler::class, array("deleteThumbnail"));
        $oPicHandler->expects($this->never())->method('deleteThumbnail');

        \oxTestModules::addModuleObject("oxPictureHandler", $oPicHandler);

        $oArtPic = $this->getProxyClass("Article_Pictures");
        $oArtPic->UNITdeleteThumbnail($oArticle);

        $this->assertEquals("", $oArticle->oxarticles__oxthumb->value);
    }

    /**
     * Article_Pictures::_resetMasterPicture().
     */
    public function testResetMasterPictureDerived()
    {
        $oArticle = $this->getMock(\OxidEsales\Eshop\Application\Model\Article::class, array("canUpdateField", 'canUpdate', 'isDerived'));
        $oArticle->expects($this->once())->method('canUpdateField')->with($this->equalTo('oxpic2'))->will($this->returnValue(true));
        $oArticle->expects($this->once())->method('canUpdate')->will($this->returnValue(true));
        $oArticle->expects($this->once())->method('isDerived')->will($this->returnValue(true));

        $oArticle->oxarticles__oxpic2 = new \oxField("testPic2.jpg");

        $oPicHandler = $this->getMock(\OxidEsales\Eshop\Core\PictureHandler::class, array("deleteArticleMasterPicture"));
        $oPicHandler->expects($this->never())->method('deleteArticleMasterPicture');

        \oxTestModules::addModuleObject("oxPictureHandler", $oPicHandler);

        $oArtPic = $this->getProxyClass("Article_Pictures");
        $oArtPic->UNITresetMasterPicture($oArticle, 2);

        $this->assertEquals("testPic2.jpg", $oArticle->oxarticles__oxpic2->value);
    }


    /**
     * Article_Pictures::_deleteMainIcon().
     */
    public function testDeleteMainIconNoRights()
    {
        $oArticle = $this->getMock(\OxidEsales\Eshop\Application\Model\Article::class, array("canUpdateField", 'canUpdate'));
        $oArticle->expects($this->once())->method('canUpdateField')->with($this->equalTo('oxicon'))->will($this->returnValue(false));
        $oArticle->expects($this->never())->method('canUpdate')->will($this->returnValue(true));

        $oArticle->oxarticles__oxicon = new \oxField("testIcon.jpg");

        $oPicHandler = $this->getMock(\OxidEsales\Eshop\Core\PictureHandler::class, array("deleteMainIcon"));
        $oPicHandler->expects($this->never())->method('deleteMainIcon');

        \oxTestModules::addModuleObject("oxPictureHandler", $oPicHandler);

        $oArtPic = $this->getProxyClass("Article_Pictures");
        $oArtPic->UNITdeleteMainIcon($oArticle);

        $this->assertEquals("testIcon.jpg", $oArticle->oxarticles__oxicon->value);
    }

    /**
     * Article_Pictures::_deleteThumbnail().
     */
    public function testDeleteThumbnailNoRights()
    {
        $oArticle = $this->getMock(\OxidEsales\Eshop\Application\Model\Article::class, array("canUpdateField", 'canUpdate'));
        $oArticle->expects($this->once())->method('canUpdateField')->with($this->equalTo('oxthumb'))->will($this->returnValue(false));
        $oArticle->expects($this->never())->method('canUpdate')->will($this->returnValue(true));

        $oArticle->oxarticles__oxthumb = new \oxField("testThumb.jpg");

        $oPicHandler = $this->getMock(\OxidEsales\Eshop\Core\PictureHandler::class, array("deleteThumbnail"));
        $oPicHandler->expects($this->never())->method('deleteThumbnail');

        \oxTestModules::addModuleObject("oxPictureHandler", $oPicHandler);

        $oArtPic = $this->getProxyClass("Article_Pictures");
        $oArtPic->UNITdeleteThumbnail($oArticle);

        $this->assertEquals("testThumb.jpg", $oArticle->oxarticles__oxthumb->value);
    }

    /**
     * Article_Pictures::_resetMasterPicture().
     */
    public function testResetMasterPictureNoRights()
    {
        $oArticle = $this->getMock(\OxidEsales\Eshop\Application\Model\Article::class, array("canUpdateField", 'canUpdate'));
        $oArticle->expects($this->once())->method('canUpdateField')->with($this->equalTo('oxpic2'))->will($this->returnValue(false));
        $oArticle->expects($this->never())->method('canUpdate')->will($this->returnValue(true));

        $oArticle->oxarticles__oxpic2 = new \oxField("testPic2.jpg");

        $oPicHandler = $this->getMock(\OxidEsales\Eshop\Core\PictureHandler::class, array("deleteArticleMasterPicture"));
        $oPicHandler->expects($this->never())->method('deleteArticleMasterPicture');

        \oxTestModules::addModuleObject("oxPictureHandler", $oPicHandler);

        $oArtPic = $this->getProxyClass("Article_Pictures");
        $oArtPic->UNITresetMasterPicture($oArticle, 2);

        $this->assertEquals("testPic2.jpg", $oArticle->oxarticles__oxpic2->value);
    }

    /**
     * Article_Pictures::_deleteMainIcon().
     */
    public function testDeleteMainIcon()
    {
        $oArticle = $this->getMock(\OxidEsales\Eshop\Application\Model\Article::class, array("canUpdateField", 'canUpdate', 'isDerived'));
        $oArticle->expects($this->once())->method('canUpdateField')->with($this->equalTo('oxicon'))->will($this->returnValue(true));
        $oArticle->expects($this->once())->method('canUpdate')->will($this->returnValue(true));
        $oArticle->expects($this->once())->method('isDerived')->will($this->returnValue(false));

        $oArticle->oxarticles__oxicon = new \oxField("testIcon.jpg");

        $oPicHandler = $this->getMock(\OxidEsales\Eshop\Core\PictureHandler::class, array("deleteMainIcon"));
        $oPicHandler->expects($this->once())->method('deleteMainIcon');

        \oxTestModules::addModuleObject("oxPictureHandler", $oPicHandler);

        $oArtPic = $this->getProxyClass("Article_Pictures");
        $oArtPic->UNITdeleteMainIcon($oArticle);

        $this->assertEquals("", $oArticle->oxarticles__oxicon->value);
    }

    /**
     * Article_Pictures::_deleteThumbnail().
     */
    public function testDeleteThumbnail()
    {
        $oArticle = $this->getMock(\OxidEsales\Eshop\Application\Model\Article::class, array("canUpdateField", 'canUpdate', 'isDerived'));
        $oArticle->expects($this->once())->method('canUpdateField')->with($this->equalTo('oxthumb'))->will($this->returnValue(true));
        $oArticle->expects($this->once())->method('canUpdate')->will($this->returnValue(true));

        $oArticle->oxarticles__oxthumb = new \oxField("testThumb.jpg");

        $oPicHandler = $this->getMock(\OxidEsales\Eshop\Core\PictureHandler::class, array("deleteThumbnail"));
        $oPicHandler->expects($this->once())->method('deleteThumbnail');

        \oxTestModules::addModuleObject("oxPictureHandler", $oPicHandler);

        $oArtPic = $this->getProxyClass("Article_Pictures");
        $oArtPic->UNITdeleteThumbnail($oArticle);

        $this->assertEquals("", $oArticle->oxarticles__oxthumb->value);
    }

    /**
     * Article_Pictures::_resetMasterPicture()
     *
     * @return null
     */
    public function testResetMasterPicture()
    {
        $oArticle = $this->getMock(\OxidEsales\Eshop\Application\Model\Article::class, array("canUpdateField", 'canUpdate', 'isDerived'));
        $oArticle->expects($this->once())->method('canUpdateField')->with($this->equalTo('oxpic2'))->will($this->returnValue(true));
        $oArticle->expects($this->once())->method('canUpdate')->will($this->returnValue(true));
        $oArticle->expects($this->once())->method('isDerived')->will($this->returnValue(false));

        $oArticle->oxarticles__oxpic2 = new \oxField("testPic2.jpg");

        $oPicHandler = $this->getMock(\OxidEsales\Eshop\Core\PictureHandler::class, array("deleteArticleMasterPicture"));
        $oPicHandler->expects($this->once())->method('deleteArticleMasterPicture')->with($this->equalTo($oArticle), $this->equalTo(2), $this->equalTo(false));

        \oxTestModules::addModuleObject("oxPictureHandler", $oPicHandler);

        $oArtPic = $this->getProxyClass("Article_Pictures");
        $oArtPic->UNITresetMasterPicture($oArticle, 2);

        $this->assertEquals("testPic2.jpg", $oArticle->oxarticles__oxpic2->value);
    }

    /**
     * Article_Pictures::_resetMasterPicture() - calling cleanup when field
     * index = 1
     *
     * @return null
     */
    public function testResetMasterPicture_makesCleanupOnFields()
    {
        $oArticle = $this->getMock(\OxidEsales\Eshop\Application\Model\Article::class, array("canUpdateField", 'canUpdate', 'isDerived'));
        $oArticle->expects($this->at(0))->method('canUpdateField')->with($this->equalTo('oxpic2'))->will($this->returnValue(true));
        $oArticle->expects($this->at(1))->method('canUpdate')->will($this->returnValue(true));
        $oArticle->expects($this->at(2))->method('isDerived')->will($this->returnValue(false));
        $oArticle->expects($this->at(3))->method('canUpdateField')->with($this->equalTo('oxpic1'))->will($this->returnValue(true));
        $oArticle->expects($this->at(4))->method('canUpdate')->will($this->returnValue(true));
        $oArticle->expects($this->at(5))->method('isDerived')->will($this->returnValue(false));

        $oArticle->oxarticles__oxpic1 = new \oxField("testPic1.jpg");
        $oArticle->oxarticles__oxpic2 = new \oxField("testPic2.jpg");

        $oPicHandler = $this->getMock(\OxidEsales\Eshop\Core\PictureHandler::class, array("deleteArticleMasterPicture"));
        $oPicHandler->expects($this->exactly(2))->method('deleteArticleMasterPicture');

        \oxTestModules::addModuleObject("oxPictureHandler", $oPicHandler);

        $oArtPic = $this->getMock(\OxidEsales\Eshop\Application\Controller\Admin\ArticlePictures::class, array("_cleanupCustomFields"));
        $oArtPic->expects($this->never())->method('_cleanupCustomFields');
        $oArtPic->UNITresetMasterPicture($oArticle, 2);

        $oArtPic = $this->getMock(\OxidEsales\Eshop\Application\Controller\Admin\ArticlePictures::class, array("_cleanupCustomFields"));
        $oArtPic->expects($this->once())->method('_cleanupCustomFields');
        $oArtPic->UNITresetMasterPicture($oArticle, 1);
    }
}
