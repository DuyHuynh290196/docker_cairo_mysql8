<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Controller\Admin;

/**
 * @inheritdoc
 */
class ArticleOverview extends \OxidEsales\EshopProfessional\Application\Controller\Admin\ArticleOverview
{
    /** @var string */
    private $articleIdWhereClause = '';

    /**
     * @param string $articleIdWhereClause
     */
    public function setArticleIdWhereClause($articleIdWhereClause)
    {
        $this->articleIdWhereClause = $articleIdWhereClause;
    }

    /**
     * @return string
     */
    protected function getArticleIdWhereClause()
    {
        return $this->articleIdWhereClause;
    }

    /**
     * Get where clause for variants select query.
     *
     * @param string $soxId article parent id
     *
     * @return string
     * @deprecated underscore prefix violates PSR12, will be renamed to "getVariantsWhereField" in next major
     */
    protected function _getVariantsWhereField($soxId) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $database = $this->getDatabase();
        $whereClause = '';

        $results = $database->select("select oxid, oxvarcount from oxarticles where oxparentid = :oxparentid", [
            ':oxparentid' => $soxId
        ]);

        while (!$results->EOF) {
            $whereClause .= ' or oxorderarticles.oxartid=' . $database->quote($results->fields[0]);
            if ($results->fields[1] && $results->fields[1] > 0) {
                $whereClause .= $this->_getVariantsWhereField($results->fields[0]);
            }
            $results->fetchRow();
        }

        return $whereClause;
    }

    /**
     * Set access field properties to prevent derived articles for editing.
     *
     * @param \oxArticle $article
     * @param string     $oxId
     *
     * @return oxArticle
     */
    protected function updateArticle($article, $oxId)
    {
        $article = parent::updateArticle($article, $oxId);
        if ($article->isDerived()) {
            $this->_aViewData["readonly"] = true;
        }

        $articleIdWhereClause = $this->formOxArticleIdWhereQuery($article);
        $this->setArticleIdWhereClause($articleIdWhereClause);

        return $article;
    }

    /**
     * @inheritdoc
     */
    protected function formOrderAmountQuery($soxId)
    {
        $shopId = $this->getConfig()->getShopID();
        $articleIdWhereClause = $this->getArticleIdWhereClause();
        $selectQuery = "select sum(oxamount) from oxorderarticles ";
        $selectQuery .= "where oxordershopid = '{$shopId}' and {$articleIdWhereClause}";

        return $selectQuery;
    }

    /**
     * @inheritdoc
     */
    protected function formSoldOutAmountQuery($soxId)
    {
        $shopId = $this->getConfig()->getShopID();
        $articleIdWhereClause = $this->getArticleIdWhereClause();
        $selectQuery = "select sum(oxorderarticles.oxamount) from  oxorderarticles, oxorder" .
            " where  oxorder.oxshopid = '{$shopId}' and (oxorder.oxpaid>0 or oxorder.oxsenddate > 0)  " .
            "and oxorderarticles.oxstorno != '1' and {$articleIdWhereClause}" .
            " and oxorder.oxid =oxorderarticles.oxorderid";

        return $selectQuery;
    }

    /**
     * @inheritdoc
     */
    protected function formCanceledAmountQuery($soxId)
    {
        $shopId = $this->getConfig()->getShopID();
        $articleIdWhereClause = $this->getArticleIdWhereClause();
        $selectQuery = "select sum(oxamount) from oxorderarticles where oxordershopid = '{$shopId}' " .
            "and  oxstorno = '1' and {$articleIdWhereClause}";

        return $selectQuery;
    }

    /**
     * @param \oxArticle $article
     */
    private function formOxArticleIdWhereQuery($article)
    {
        $oxId = $this->getEditObjectId();
        $database = $this->getDatabase();
        if (!$this->getConfig()->getConfigParam('blVariantParentBuyable')) {
            $variantsCount = $article->oxarticles__oxvarcount->value;
            if ($variantsCount && $variantsCount > 0) {
                $whereClause = "( oxorderarticles.oxartid=" . $database->quote($oxId) . " " .
                    $this->_getVariantsWhereField($oxId) . ' )';
            } else {
                $whereClause = "oxorderarticles.oxartid=" . $database->quote($oxId);
            }
        } else {
            $whereClause = "oxorderarticles.oxartid=" . $database->quote($oxId);
        }

        return $whereClause;
    }
}
