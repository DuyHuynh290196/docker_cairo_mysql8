<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Controller\Admin;

use oxCategory;

/**
 * Admin article categories mall manager.
 * There is possibility to change category inheritanve.
 * Admin Menu: Manage Products -> Categories -> Mall.
 */
class CategoryMall extends \OxidEsales\Eshop\Application\Controller\Admin\AdminMall
{
    /**
     * DB table used for multiple shops we are going to deal with
     */
    protected $_sMallTable = 'oxcategories';

    /**
     * Class name of object to load
     */
    protected $_sObjectClassName = 'oxcategory';

    /**
     * @var \OxidEsales\Eshop\Application\Model\Category $_oCategory category object
     */
    protected $_oCategory = null;

    /**
     * Loads article category data, passes it to Smarty engine, returns
     * name of template file 'category_mall_nonparent.tpl'.
     *
     * @return string
     */
    public function render()
    {
        $template = parent::render();
        $category = $this->_getSelectedItem();
        $this->_aViewData['edit'] = $category;
        if ($category->oxcategories__oxparentid->value != 'oxrootid') {
            $template = 'category_mall_nonparent.tpl';
        }

        return $template;
    }

    /**
     * Assigns record information in multiple shop field
     */
    public function assignToSubshops()
    {
        $category = $this->_getSelectedItem();
        if (!is_null($category) && $category->oxcategories__oxparentid->value == 'oxrootid') {
            parent::assignToSubshops();
        }
    }

    /**
     * Loads selected item using oxBase
     *
     * @return \OxidEsales\Eshop\Application\Model\Category
     * @deprecated underscore prefix violates PSR12, will be renamed to "getSelectedItem" in next major
     */
    protected function _getSelectedItem() // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        if (is_null($this->_oCategory)) {
            $categoryId = $this->getEditObjectId();
            $this->_oCategory = oxNew(\OxidEsales\Eshop\Application\Model\Category::class);
            $this->_oCategory->load($categoryId);
        }

        return $this->_oCategory;
    }
}
