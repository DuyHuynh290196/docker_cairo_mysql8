<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Core;

use oxArticle;

/**
 * Class oxArticle2ShopRelations
 * Handles differently shop relation class with oxarticle element.
 * After un/assigning product to shop, un/assigns all variants of this product too.
 *
 * @internal Do not make a module extension for this class.
 */
class Article2ShopRelations extends \OxidEsales\Eshop\Core\Element2ShopRelations
{
    /**
     * Adds article to shop or list of shops.
     * And updates according to it his variants inheritance.
     *
     * @param \OxidEsales\Eshop\Application\Model\Article $oElement element object to add shop id to.
     */
    public function addObjectToShop($oElement)
    {
        parent::addObjectToShop($oElement);

        if ($oElement->getVariantsCount()) {
            $this->updateVariantInheritance($oElement);
        }
    }

    /**
     * Gives an object of item and removes it from shop or list of shops.
     * And updates according to it his variants inheritance.
     *
     * @param \OxidEsales\Eshop\Application\Model\Article $oElement element object to add shop id to.
     */
    public function removeObjectFromShop($oElement)
    {
        parent::removeObjectFromShop($oElement);
        $this->updateVariantInheritance($oElement);
    }

    /**
     * Updates product inheritance information according to parent product inheritance information.
     *
     * @param string $sVariantId Id of given variant.
     * @param string $sParentId  Id of given variants parent product.
     */
    public function updateInheritanceFromParent($sVariantId, $sParentId)
    {
        // clear all previous inheritance information
        $this->removeFromAllShops($sVariantId);
        // copy inheritance information from parent item
        $this->copyInheritance($sParentId, $sVariantId);
    }

    /**
     * Takes variant id list of given article.
     * Adds/removes variants to/from shop according to parent article.
     *
     * @param \OxidEsales\Eshop\Application\Model\Article $oElement oxArticles object to get it's variants.
     */
    public function updateVariantInheritance($oElement)
    {
        $aVariantIds = $oElement->getVariantIds(false);
        $sElementId = $oElement->getId();
        foreach ($aVariantIds as $sId) {
            $this->updateInheritanceFromParent($sId, $sElementId);
        }
    }
}
