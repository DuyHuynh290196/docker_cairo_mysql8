<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Controller\Admin;

use OxidEsales\Eshop\Core\Registry;

/**
 * Admin article categories thumbnail manager.
 * Category thumbnail manager (Previews assigned pictures).
 * Admin Menu: Manage Products -> Categories -> Thumbnail.
 */
class CategoryRights extends \OxidEsales\Eshop\Application\Controller\Admin\AdminDetailsController
{
    /**
     * Loads category object, passes it to Smarty engine and returns name
     * of template file "category_pictures.tpl".
     *
     * @return string
     */
    public function render()
    {
        parent::render();

        $this->_aViewData['edit'] = $category = oxNew(\OxidEsales\Eshop\Application\Model\Category::class);

        $oxId = $this->getEditObjectId();
        if ($oxId != "-1" && isset($oxId)) {
            // load object
            $category->load($oxId);

            //Disable editing for derived items
            if ($category->blIsDerived) {
                $this->_aViewData['readonly'] = true;
            }
        }

        $aoc = \OxidEsales\Eshop\Core\Registry::getConfig()->getRequestParameter("aoc");
        if ($aoc == 1) {
            $oCategoryRightVisibleAjax = oxNew(\OxidEsales\Eshop\Application\Controller\Admin\CategoryRightsVisibleAjax::class);
            $this->_aViewData['oxajax'] = $oCategoryRightVisibleAjax->getColumns();

            return "popups/category_rights_visible.tpl";
        } elseif ($aoc == 2) {
            $categoryRightBuyableAjax = oxNew(\OxidEsales\Eshop\Application\Controller\Admin\CategoryRightsBuyableAjax::class);
            $this->_aViewData['oxajax'] = $categoryRightBuyableAjax->getColumns();

            return "popups/category_rights_buyable.tpl";
        }

        return "category_rights.tpl";
    }
}
