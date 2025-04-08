<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Controller\Admin;

use OxidEsales\Eshop\Core\Registry;
use oxField;

/**
 * Admin article main actions manager.
 * There is possibility to change actions description, assign articles to
 * this actions, etc.
 * Admin Menu: Manage Products -> actions -> Main.
 */
class RolesBackendUser extends \OxidEsales\Eshop\Application\Controller\Admin\AdminDetailsController
{
    /**
     * Prepares view data, executes parent::render() and returns name of
     * template "roles_beuser.tpl"
     *
     * @return string
     */
    public function render()
    {
        //allow only mall admin to perform this
        if (!$this->getConfig()->getConfigParam('blAllowSharedEdit')) {
            $this->_aViewData['readonly'] = true;
        }

        parent::render();

        $roleId = $this->getEditObjectId();

        $role = oxNew(\OxidEsales\Eshop\Application\Model\Role::class);
        $role->load($roleId);
        $this->_aViewData["edit"] = $role;

        // all user groups
        $userGroups = oxNew(\OxidEsales\Eshop\Core\Model\ListModel::class);
        $userGroups->init('oxgroups');
        $userGroups->selectString("SELECT * FROM " . getViewName("oxgroups", $this->_iEditLang));

        $rootGroups = oxNew(\OxidEsales\Eshop\Application\Model\Groups::class);
        $rootGroups->oxgroups__oxid = new \OxidEsales\Eshop\Core\Field("");
        $rootGroups->oxgroups__oxtitle = new \OxidEsales\Eshop\Core\Field("-- ");

        // Rebuild list as we need the "no value" entry at the first position.
        $newList = array();
        $newList[] = $rootGroups;

        foreach ($userGroups as $value) {
            $newList[$value->oxgroups__oxid->value] = oxNew(\OxidEsales\Eshop\Application\Model\Groups::class);
            $newList[$value->oxgroups__oxid->value]->oxgroups__oxid = new \OxidEsales\Eshop\Core\Field($value->oxgroups__oxid->value);
            $newList[$value->oxgroups__oxid->value]->oxgroups__oxtitle = new \OxidEsales\Eshop\Core\Field($value->oxgroups__oxtitle->value);
        }

        $userGroups = $newList;
        $this->_aViewData["allgroups2"] = $userGroups;

        $aoc = \OxidEsales\Eshop\Core\Registry::getConfig()->getRequestParameter("aoc");
        if ($aoc == 1) {
            $rolesBackendGroupsAjax = oxNew(\OxidEsales\Eshop\Application\Controller\Admin\RolesBackendGroupsAjax::class);
            $this->_aViewData['oxajax'] = $rolesBackendGroupsAjax->getColumns();

            return "popups/roles_begroups.tpl";
        } elseif ($aoc == 2) {
            $rolesBackendUserAjax = oxNew(\OxidEsales\Eshop\Application\Controller\Admin\RolesBackendUserAjax::class);
            $this->_aViewData['oxajax'] = $rolesBackendUserAjax->getColumns();

            return "popups/roles_beuser.tpl";
        }

        return "roles_beuser.tpl";
    }
}
