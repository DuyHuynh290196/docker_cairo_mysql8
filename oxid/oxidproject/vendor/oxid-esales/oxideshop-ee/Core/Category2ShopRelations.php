<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Core;

use oxCategory;

/**
 * Class oxCategory2ShopRelations
 *
 * @internal Do not make a module extension for this class.
 */
class Category2ShopRelations extends \OxidEsales\Eshop\Core\Element2ShopRelations
{
    /**
     * Adds category to shop or list of shops.
     * Also adds category objects and subcategories to list of shops.
     *
     * @param \OxidEsales\Eshop\Application\Model\Category $oCategory Category object to add to shop.
     */
    public function addObjectToShop($oCategory)
    {
        parent::addObjectToShop($oCategory);
        $this->_addCategoryDependenciesToShop($oCategory->getId());
    }

    /**
     * Removes category from shop or list of shops.
     * Also removes category objects and subcategories from list of shops.
     *
     * @param \OxidEsales\Eshop\Application\Model\Category $oCategory Category object to add to shop.
     */
    public function removeObjectFromShop($oCategory)
    {
        parent::removeObjectFromShop($oCategory);
        $this->_removeCategoryDependenciesFromShop($oCategory->getId());
    }

    /**
     * Adds category dependencies to shop.
     *
     * @param string $sCategoryId Category ID.
     */
    private function _addCategoryDependenciesToShop($sCategoryId) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $this->_addCategoryObjectsToShop($sCategoryId);
        $this->_addSubCategoriesToShop($sCategoryId);
    }

    /**
     * Adds category objects to shop.
     *
     * @param string $sCategoryId Category ID.
     */
    private function _addCategoryObjectsToShop($sCategoryId) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $oElement2ShopRelations = oxNew(\OxidEsales\Eshop\Core\Element2ShopRelations::class, 'oxobject2category');
        $oElement2ShopRelations->setShopIds($this->getShopIds());

        $oCategory = oxNew(\OxidEsales\Eshop\Application\Model\Category::class);

        $aCategoryObjectIds = $oCategory->getCategoryObjectIds($sCategoryId);
        foreach ($aCategoryObjectIds as $sCategoryObjectId) {
            $oElement2ShopRelations->addToShop($sCategoryObjectId);
        }
    }

    /**
     * Adds subcategories to shop.
     *
     * @param string $sCategoryId Category ID.
     */
    private function _addSubCategoriesToShop($sCategoryId) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $oCategory = oxNew(\OxidEsales\Eshop\Application\Model\Category::class);

        $aSubCategoryIds = $oCategory->getFieldFromSubCategories('oxid', $sCategoryId);
        foreach ($aSubCategoryIds as $sSubCategoryId) {
            $this->addToShop($sSubCategoryId);
            $this->_addCategoryObjectsToShop($sSubCategoryId);
        }
    }

    /**
     * Removes category dependencies from shop.
     *
     * @param string $sCategoryId Category ID.
     */
    private function _removeCategoryDependenciesFromShop($sCategoryId) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $this->_removeCategoryObjectsFromShop($sCategoryId);
        $this->_removeSubCategoriesFromShop($sCategoryId);
    }

    /**
     * Removes category objects from shop.
     *
     * @param string $sCategoryId Category ID.
     */
    private function _removeCategoryObjectsFromShop($sCategoryId) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $oElement2ShopRelations = oxNew(\OxidEsales\Eshop\Core\Element2ShopRelations::class, 'oxobject2category');
        $oElement2ShopRelations->setShopIds($this->getShopIds());

        $oCategory = oxNew(\OxidEsales\Eshop\Application\Model\Category::class);

        $aCategoryObjectIds = $oCategory->getCategoryObjectIds($sCategoryId);
        foreach ($aCategoryObjectIds as $sCategoryObjectId) {
            $oElement2ShopRelations->removeFromShop($sCategoryObjectId);
        }
    }

    /**
     * Removes subcategories from shop.
     *
     * @param string $sCategoryId Category ID.
     */
    private function _removeSubCategoriesFromShop($sCategoryId) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $oCategory = oxNew(\OxidEsales\Eshop\Application\Model\Category::class);

        $aSubCategoryIds = $oCategory->getFieldFromSubCategories('oxid', $sCategoryId);
        foreach ($aSubCategoryIds as $sSubCategoryId) {
            $this->removeFromShop($sSubCategoryId);
            $this->_removeCategoryObjectsFromShop($sSubCategoryId);
        }
    }
}
