<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Controller\Admin;

/**
 * @inheritdoc
 */
class ArticlePictures extends \OxidEsales\EshopProfessional\Application\Controller\Admin\ArticlePictures
{
    /**
     * @inheritdoc
     */
    protected function updateArticle($article)
    {
        $article = parent::updateArticle($article);

        //set access field properties to prevent derived articles for editing
        if ($article->isDerived()) {
            $this->_aViewData["readonly"] = true;
        }

        return $article;
    }

    /**
     * @inheritdoc
     */
    protected function canResetMasterPicture($article, $masterPictureIndex)
    {
        $isPictureInArticle = parent::canResetMasterPicture($article, $masterPictureIndex);
        return $isPictureInArticle && $article->canUpdateField("oxpic" . $masterPictureIndex) && $article->canUpdate();
    }

    /**
     * @inheritdoc
     */
    protected function canDeleteMainIcon($article)
    {
        $isIconInArticle = parent::canDeleteMainIcon($article);
        return $isIconInArticle && $article->canUpdateField('oxicon') && $article->canUpdate();
    }

    /**
     * @inheritdoc
     */
    protected function canDeleteThumbnail($article)
    {
        $isThumbnailInArticle = parent::canDeleteThumbnail($article);
        return $isThumbnailInArticle && $article->canUpdateField('oxthumb') && $article->canUpdate();
    }
}
