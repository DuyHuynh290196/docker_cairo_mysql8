<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Controller;

use OxidEsales\Eshop\Core\Registry;

/**
 * @inheritdoc
 */
class ArticleDetailsController extends \OxidEsales\EshopProfessional\Application\Controller\ArticleDetailsController
{
    /**
     * Do not cache if user is logged in
     *
     * @var bool
     */
    protected $_blCacheForUser = false;

    /**
     * checks if this view can be cached - i.e. this view is not varying too much so that caching would
     * be ineffective.
     * Note: this method is called before the init()
     *
     * @return bool
     */
    public function canCache()
    {
        $listType = $this->getConfig()->getRequestParameter('listtype');
        if ($listType == 'search') {
            return false;
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function getViewResetId()
    {
        $product = $this->getProduct();

        $viewId = parent::getViewResetId();
        $viewId .= '|anid=' . $product->getId();
        if ($product->oxarticles__oxparentid->value) {
            $viewId .= '|anid=' . $product->oxarticles__oxparentid->value;
        }

        return $viewId;
    }

    /**
     * @inheritdoc
     */
    protected function generateViewId()
    {
        $listType = $this->getConfig()->getRequestParameter('listtype');
        if (!$listType) {
            $listType = 'list';
        } elseif ($listType == 'search') {
            $listType .= '-' . $this->getConfig()->getRequestParameter('searchparam');
        }

        $categoryId = $this->getConfig()->getRequestParameter('cnid');

        //#1998 - filters and caching
        $sessionFilter = \OxidEsales\Eshop\Core\Registry::getSession()->getVariable("session_attrfilter");
        $languageId = \OxidEsales\Eshop\Core\Registry::getLang()->getBaseLanguage();
        $filter = array();
        if ($categoryId && isset($sessionFilter[$categoryId][$languageId])) {
            $filter = $sessionFilter[$categoryId][$languageId];
        }

        $variantSelectionListId = $this->getConfig()->getRequestParameter('varselid');
        $renderPartialParameter = $this->getConfig()->getRequestParameter('renderPartial');

        $viewId = parent::generateViewId() . "{$listType}|{$categoryId}" . serialize($filter) . '|' .
        $renderPartialParameter . '|' . serialize($variantSelectionListId);

        return $viewId;
    }
}
