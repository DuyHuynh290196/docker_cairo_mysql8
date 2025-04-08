<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Controller\Admin;

use OxidEsales\Eshop\Core\Registry;

/**
 * Admin article main actions manager.
 * There is possibility to change actions description, assign articles to
 * this actions, etc.
 * Admin Menu: Manage Products -> actions -> Main.
 */
class RolesFrontendUser extends \OxidEsales\Eshop\Application\Controller\Admin\AdminDetailsController
{
    /**
     * Render.
     *
     * @return string
     */
    public function render()
    {
        parent::render();

        \OxidEsales\Eshop\Core\Registry::getConfig()->getRequestParameter('selgroup');
        $oxId = $this->getEditObjectId();

        $role = oxNew(\OxidEsales\Eshop\Application\Model\Role::class);
        $role->load($oxId);
        $this->_aViewData['edit'] = $role;

        if (\OxidEsales\Eshop\Core\Registry::getConfig()->getRequestParameter('aoc')) {
            $rolesFEGroupsAjax = oxNew(\OxidEsales\Eshop\Application\Controller\Admin\RolesFrontendGroupsAjax::class);
            $this->_aViewData['oxajax'] = $rolesFEGroupsAjax->getColumns();
            return 'popups/roles_fegroups.tpl';
        }

        return 'roles_feuser.tpl';
    }
}
