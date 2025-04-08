<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Controller\Admin;

use OxidEsales\Eshop\Core\Registry;
use oxDb;

/**
 * @inheritdoc
 */
class SelectListMainAjax extends \OxidEsales\EshopProfessional\Application\Controller\Admin\SelectListMainAjax
{
    /**
     * @inheritdoc
     */
    public function removeArtFromSel()
    {
        if (\OxidEsales\Eshop\Core\Registry::getConfig()->getRequestParameter('all')) {
            $sSelectSql = "SELECT `oxobject2selectlist`.`oxobjectid` " . $this->_getQuery();
        } else {
            $aChosenArt = $this->_getActionIds('oxobject2selectlist.oxid');
            $sSelectSql = "SELECT `oxobject2selectlist`.`oxobjectid`
                          FROM `oxobject2selectlist`
                          WHERE `oxobject2selectlist`.`oxid`
                          in (" . implode(", ", \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->quoteArray($aChosenArt)) . ") ";
        }

        $aArticleIds = \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->getCol($sSelectSql);

        foreach ($aArticleIds as $sArticleId) {
            $oArticle = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
            $oArticle->setId($sArticleId);
            $oArticle->executeDependencyEvent();
        }
        parent::removeArtFromSel();
    }

    /**
     * @inheritdoc
     */
    protected function onArticleAddToSelectionList($articleId)
    {
        parent::onArticleAddToSelectionList($articleId);
        $article = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
        $article->setId($articleId);
        $article->executeDependencyEvent();
    }
}
