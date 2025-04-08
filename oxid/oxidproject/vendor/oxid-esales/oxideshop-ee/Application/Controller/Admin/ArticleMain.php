<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Controller\Admin;

/**
 * @inheritdoc
 */
class ArticleMain extends \OxidEsales\EshopProfessional\Application\Controller\Admin\ArticleMain
{
    /**
     * Copies article (with all parameters) to this shop.
     */
    public function cloneArticle()
    {
        if ($sOldId = $this->getEditObjectId()) {
            $sNewId = $this->copyArticle();

            $sNewArticle = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
            $sNewArticle->load($sNewId);
            $sNewArticle->oxarticles__oxshopid->setValue($this->getConfig()->getShopId());
            $sNewArticle->save();

            $this->setEditObjectId($sNewId);
        }
    }

    /**
     * @inheritdoc
     */
    protected function updateArticle($article, $oxId)
    {
        $article = parent::updateArticle($article, $oxId);
        // Set access field properties to prevent derived articles for editing.
        if ($article->isDerived()) {
            $this->_aViewData["readonly"] = true;
        }

        return $article;
    }

    /**
     * @inheritdoc
     */
    protected function formQueryForCopyingToCategory($newArticleId, $sUid, $sCatId, $sTime)
    {
        $database = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();
        $config = $this->getConfig();
        $shopId = $config->getShopId();
        $sql = "insert into oxobject2category (oxid, oxobjectid, oxcatnid, oxshopid, oxtime) " .
            "VALUES (" . $database->quote($sUid) . ", " . $database->quote($newArticleId) . ", " .
            $database->quote($sCatId) . ", " . $database->quote($shopId) . ", " . $database->quote($sTime) . ") ";

        return $sql;
    }

    /**
     * @inheritdoc
     */
    protected function updateBase($base)
    {
        $base = parent::updateBase($base);
        $config = $this->getConfig();
        $shopId = $config->getShopId();
        $base->oxobject2category__oxshopid = new \oxField($shopId);

        return $base;
    }
}
