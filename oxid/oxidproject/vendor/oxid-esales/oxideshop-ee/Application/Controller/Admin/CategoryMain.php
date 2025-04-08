<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Controller\Admin;

/**
 * @inheritdoc
 */
class CategoryMain extends \OxidEsales\EshopProfessional\Application\Controller\Admin\CategoryMain
{
    /**
     * @inheritdoc
     * @deprecated underscore prefix violates PSR12, will be renamed to "deleteCatPicture" in next major
     */
    protected function _deleteCatPicture($item, $field) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        if (!($item->canUpdateField($field) && $item->canUpdate())) {
            return;
        }

        parent::_deleteCatPicture($item, $field);
    }

    /**
     * @inheritdoc
     */
    protected function updateCategoryOnSave($category, $params)
    {
        $config = $this->getConfig();

        $category = parent::updateCategoryOnSave($category, $params);

        // if it is not parent category then assign it to inhereded shops
        if ($params["oxcategories__oxparentid"] != 'oxrootid') {
            $config->getActiveShop()->setMultiShopInheritCategories(true);
        }

        return $category;
    }
}
