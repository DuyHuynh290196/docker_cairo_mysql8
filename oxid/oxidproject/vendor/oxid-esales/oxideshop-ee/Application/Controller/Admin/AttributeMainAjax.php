<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Controller\Admin;

/**
 * @inheritdoc
 */
class AttributeMainAjax extends \OxidEsales\EshopProfessional\Application\Controller\Admin\AttributeMainAjax
{
    /**
     * @inheritdoc
     */
    public function removeAttrArticle()
    {
        $categoriesIds = $this->_getActionIds('oxobject2attribute.oxid');
        $objectToAttributeView = $this->_getViewName('oxobject2attribute');
        if (\OxidEsales\Eshop\Core\Registry::getConfig()->getRequestParameter('all')) {
            $query = "SELECT $objectToAttributeView.`oxobjectid` " . $this->_getQuery();
        } else {
            $chosenCategories = implode(", ", \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->quoteArray($categoriesIds));
            $query = "SELECT `oxobject2attribute`.`oxobjectid` FROM `oxobject2attribute` " .
                "WHERE `oxobject2attribute`.`oxid` in (" . $chosenCategories . ") ";
        }

        $articleIds = \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->getCol($query);

        foreach ($articleIds as $articleId) {
            $article = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
            $article->setId($articleId);
            $article->executeDependencyEvent();
        }

        parent::removeAttrArticle();
    }

    /**
     * @inheritdoc
     */
    protected function onArticleAddToAttributeList($articleId)
    {
        $article = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
        $article->load($articleId);
        $article->executeDependencyEvent();
    }
}
