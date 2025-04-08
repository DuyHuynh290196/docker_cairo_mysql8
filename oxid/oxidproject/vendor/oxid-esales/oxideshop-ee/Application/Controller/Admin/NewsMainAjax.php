<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Controller\Admin;

/**
 * @inheritdoc
 * @deprecated 6.5.3 "News" feature will be removed completely
 */
class NewsMainAjax extends \OxidEsales\EshopProfessional\Application\Controller\Admin\NewsMainAjax
{
    /**
     * Adds user group for viewing some news.
     */
    public function addGroupToNews()
    {
        parent::addGroupToNews();

        $newsId = $this->getConfig()->getRequestParameter('synchoxid');
        if ($newsId && $newsId != "-1") {
            $news = oxNew(\OxidEsales\Eshop\Application\Model\News::class);
            $news->executeDependencyEvent();
        }
    }
}
