<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Controller\Admin;

/**
 * @inheritdoc
 */
class DynamicExportBaseController extends \OxidEsales\EshopProfessional\Application\Controller\Admin\DynamicExportBaseController
{
    /**
     * @inheritdoc
     */
    protected function updateArticle($article)
    {
        $article = parent::updateArticle($article);
        $shopId = $this->getConfig()->getShopId();
        if ($shopId != 1) {
            $article->appendLink("shp=" . $shopId);
        }

        return $article;
    }
}
