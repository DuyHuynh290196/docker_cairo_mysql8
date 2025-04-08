<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Model;

/**
 * @inheritdoc
 */
class ArticleList extends \OxidEsales\EshopProfessional\Application\Model\ArticleList
{
    /**
     * @inheritdoc
     */
    protected function fetchNextUpdateTime()
    {
        $timeToUpdate = parent::fetchNextUpdateTime();
        $query = $this->getQueryToFetchNextUpdateTime();

        // We force reading from master to prevent issues with slow replications or open transactions (see ESDEV-3804).
        // When not in admin mode, this function is called from inside a transaction.
        // Transaction picks master automatically (see ESDEV-3804 and ESDEV-3822) so forcing master here makes no difference.
        $masterDb = \OxidEsales\Eshop\Core\DatabaseProvider::getMaster();
        // from oxfield2shop also
        if (($iF2STimeToUpdate = $masterDb->getOne(sprintf($query, "`oxfield2shop`")))) {
            $timeToUpdate = (!$timeToUpdate || $timeToUpdate > $iF2STimeToUpdate) ? $iF2STimeToUpdate : $timeToUpdate;
        }

        return $timeToUpdate;
    }

    /**
     * @inheritdoc
     */
    protected function updateOxArticles($currentUpdateTime, $oDb)
    {
        parent::updateOxArticles($currentUpdateTime, $oDb);
        $isUpdated = $oDb->execute(sprintf($this->getQueryToUpdateOxArticle($currentUpdateTime), "`oxfield2shop`"));

        return $isUpdated;
    }

    /**
     * @inheritdoc
     */
    protected function updateArticles($updatedArticleIds)
    {
        parent::updateArticles($updatedArticleIds);
        if (is_array($updatedArticleIds)) {
            // Execute cache dependency invalidation
            foreach ($updatedArticleIds as $articleId) {
                $article = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
                $article->setId($articleId);
                $article->executeDependencyEvent();
            }
        }
    }
}
