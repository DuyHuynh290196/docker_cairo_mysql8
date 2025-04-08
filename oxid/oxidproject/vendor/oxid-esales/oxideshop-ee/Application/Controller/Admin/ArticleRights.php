<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Controller\Admin;

use OxidEsales\Eshop\Core\Registry;

/**
 * Admin article categories thumbnail manager.
 * Category thumbnail manager (Previews assigned pictures).
 * Admin Menu: Manage Products -> Categories -> Thumbnail.
 */
class ArticleRights extends \OxidEsales\Eshop\Application\Controller\Admin\AdminDetailsController
{
    /**
     * Loads category object, passes it to Smarty engine and returns name
     * of template file "category_pictures.tpl".
     *
     * @return string
     */
    public function render()
    {
        parent::render();

        $article = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
        $this->_aViewData["edit"] = $article;

        $articleId = $this->getEditObjectId();
        if ($articleId != "-1" && isset($articleId)) {
            // load object
            $article->load($articleId);

            //disable for derived articles
            if ($article->isDerived()) {
                $this->_aViewData['readonly'] = true;
            }
        }

        // 1 - visible articles
        // 2 - buyable articles
        $whatArticlesToDisplay = \OxidEsales\Eshop\Core\Registry::getConfig()->getRequestParameter("aoc");

        if ($whatArticlesToDisplay == 1) {
            $articleRightsVisibleAjax = oxNew(\OxidEsales\Eshop\Application\Controller\Admin\ArticleRightsVisibleAjax::class);
            $this->_aViewData['oxajax'] = $articleRightsVisibleAjax->getColumns();

            return "popups/article_rights_visible.tpl";
        } else if ($whatArticlesToDisplay == 2) {
            $articleRightsBuyableAjax = oxNew(\OxidEsales\Eshop\Application\Controller\Admin\ArticleRightsBuyableAjax::class);
            $this->_aViewData['oxajax'] = $articleRightsBuyableAjax->getColumns();

            return "popups/article_rights_buyable.tpl";
        }

        return "article_rights.tpl";
    }
}
