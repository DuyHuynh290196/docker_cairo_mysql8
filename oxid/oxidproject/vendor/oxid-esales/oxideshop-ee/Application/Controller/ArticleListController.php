<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Controller;

use OxidEsales\Eshop\Core\Registry;
use oxUBase;

/**
 * @inheritdoc
 */
class ArticleListController extends \OxidEsales\EshopProfessional\Application\Controller\ArticleListController
{
    /**
     * Do not cache if user is logged in
     *
     * @var bool
     */
    protected $_blCacheForUser = false;

    /**
     * @inheritdoc
     */
    protected function generateViewId()
    {
        $categoryId = \OxidEsales\Eshop\Core\Registry::getConfig()->getRequestParameter('cnid');
        $activePage = $this->getActPage();
        $articlesPerPage = \OxidEsales\Eshop\Core\Registry::getSession()->getVariable('_artperpage');
        $listDisplayType = $this->_getListDisplayType();
        $parentViewId = \OxidEsales\Eshop\Application\Controller\FrontendController::generateViewId();

        //#1998 - filters and caching
        $filter = \OxidEsales\Eshop\Core\Registry::getConfig()->getRequestParameter('attrfilter');
        if (!$filter) {
            $sessionFilter = \OxidEsales\Eshop\Core\Registry::getSession()->getVariable('session_attrfilter');
            $languageId = \OxidEsales\Eshop\Core\Registry::getLang()->getBaseLanguage();
            if (isset($sessionFilter[$categoryId][$languageId])) {
                $filter = $sessionFilter[$categoryId][$languageId];
            }
        }

        $filterAdd = '';
        if (is_array($filter)) {
            $filterAdd = "|" . md5(serialize($filter));
        }

        // shorten it
        $viewId = $parentViewId . '|' . $categoryId . $filterAdd . '|';
        $viewId .= $activePage . '|' . $articlesPerPage . '|' . $listDisplayType;

        return $viewId;
    }

    /**
     * Checks category rights.
     * If category can not be viewed, null is returned instead.
     *
     * @inheritdoc
     *
     * @return null|\oxCategory
     */
    protected function getCategoryToRender()
    {
        $category = parent::getCategoryToRender();

        if ($category && $this->_blIsCat && !$category->canView()) {
            $category = null;
        }

        return $category;
    }
}
