<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Controller\Admin;

use OxidEsales\Eshop\Core\Registry;
use oxDb;

/**
 * @inheritdoc
 */
class AttributeCategoryAjax extends \OxidEsales\EshopProfessional\Application\Controller\Admin\AttributeCategoryAjax
{
    /**
     * @inheritdoc
     */
    public function removeCatFromAttr()
    {
        $aChosenCat = $this->_getActionIds('oxcategory2attribute.oxid');

        if (\OxidEsales\Eshop\Core\Registry::getConfig()->getRequestParameter('all')) {
            $sSelectSql = "SELECT `oxcategory2attribute`.`oxobjectid` " . $this->_getQuery();
        } else {
            $sChosenCategories = implode(", ", \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->quoteArray($aChosenCat));
            $sSelectSql = "SELECT `oxcategory2attribute`.`oxobjectid` FROM `oxcategory2attribute` " .
                "WHERE `oxcategory2attribute`.`oxid` in (" . $sChosenCategories . ") ";
        }
        $aCategoryIds = \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->getCol($sSelectSql);
        $this->_resetCache($aCategoryIds);

        parent::removeCatFromAttr();
    }

    /**
     * @inheritdoc
     */
    public function addCatToAttr()
    {
        parent::addCatToAttr();

        $aAddCategory = $this->_getActionIds('oxcategories.oxid');
        $this->_resetCache($aAddCategory);
    }

    /**
     * Reset category related cache
     *
     * @param array $aCategoryIds array of category ids
     * @deprecated underscore prefix violates PSR12, will be renamed to "resetCache" in next major
     */
    protected function _resetCache($aCategoryIds) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $oCategory = oxNew(\OxidEsales\Eshop\Application\Model\Category::class);
        $oCategory->executeDependencyEvent($aCategoryIds);
    }
}
