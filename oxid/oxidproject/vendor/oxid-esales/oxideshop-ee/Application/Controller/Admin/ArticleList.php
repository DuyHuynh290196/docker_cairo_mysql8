<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Controller\Admin;

/**
 * @inheritdoc
 */
class ArticleList extends \OxidEsales\EshopProfessional\Application\Controller\Admin\ArticleList
{
    /**
     * Unassign entry from current shop.
     */
    public function unassignEntry()
    {
        $objectOxid = $this->getEditObjectId();
        $article = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
        if ($article->load($objectOxid)) {
            $this->resetContentCache();
            parent::unassignEntry();
        }
    }
}
