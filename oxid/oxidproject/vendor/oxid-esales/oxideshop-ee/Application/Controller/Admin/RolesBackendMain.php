<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Controller\Admin;

use oxDb;

/**
 * Admin article main actions manager.
 * There is possibility to change actions description, assign articles to
 * this actions, etc.
 * Admin Menu: Manage Products -> actions -> Main.
 */
class RolesBackendMain extends \OxidEsales\Eshop\Application\Controller\Admin\AdminDetailsController
{
    /**
     * Executes parent method parent::render() and returns name of template
     * file "roles_bemain.tpl".
     *
     * @return string
     */
    public function render()
    {
        if (!$this->getConfig()->getConfigParam('blAllowSharedEdit')) {
            $this->_aViewData['readonly'] = true;
        }

        parent::render();

        $this->_aViewData["oxid"] = $roleId = $this->getEditObjectId();
        if ($roleId != "-1" && $roleId) {
            $database = \OxidEsales\Eshop\Core\DatabaseProvider::getDb(\OxidEsales\Eshop\Core\DatabaseProvider::FETCH_MODE_ASSOC);
            $role = oxNew(\OxidEsales\Eshop\Application\Model\Role::class);
            $role->load($roleId);
            $this->_aViewData["edit"] = $role;

            // creating rights list
            $query = "select oxfield2role.oxfieldid, oxfield2role.oxidx as idx
                from oxfield2role where oxfield2role.oxroleid = :oxroleid and oxtype = :oxtype ";

            $result = $database->select($query, [
                ':oxroleid' => $roleId,
                ':oxtype' => 'oxview'
            ]);
            if ($result != false && $result->count() > 0) {
                while (!$result->EOF) {
                    $fetchedRights[$result->fields['oxfieldid']] = $result->fields['idx'];
                    $result->fetchRow();
                }
            }

            $this->_aViewData['aRights'] = $fetchedRights;
        }

        $this->_aViewData['adminmenu'] = $this->getNavigation()->getDomXml()->documentElement->firstChild->childNodes;

        // Fetching dynamic area rights from user rights def
        if ($rights = $this->getRights()) {
            $dynamicRights['dyn_menu'] = $rights->getViewRightsIndex('dyn_menu');
            $dynamicRights['dyn_about'] = $rights->getViewRightsIndex('dyn_about');
            $dynamicRights['dyn_interface'] = $rights->getViewRightsIndex('dyn_interface');
            $this->_aViewData['aDynRights'] = $dynamicRights;
        }

        return 'roles_bemain.tpl';
    }

    /**
     * Save
     *
     * @return null
     */
    public function save()
    {
        $config = $this->getConfig();
        if (!$config->getConfigParam('blAllowSharedEdit')) {
            return;
        }

        parent::save();

        $rightsId = $this->getEditObjectId();
        $parameters = $config->getRequestParameter('editval');

        // checkbox handling
        if (!isset($parameters['oxroles__oxactive'])) {
            $parameters['oxroles__oxactive'] = 0;
        }

        // saving role data
        $role = oxNew(\OxidEsales\Eshop\Application\Model\Role::class);
        if ($rightsId != '-1') {
            $role->load($rightsId);
        } else {
            $parameters['oxroles__oxid'] = null;
        }

        $role->assign($parameters);
        $role->save();

        $roleId = $role->getId();
        $this->setEditObjectId($roleId);

        $rights = $this->getRights();
        $database = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();
        $fields = $config->getRequestParameter('aFields');
        foreach ($fields as $fieldId => $fieldIndex) {
            $fieldIndex = (int) $fieldIndex;
            // security and optimization
            if ($rights && ($rightsIndex = $rights->getViewRightsIndex($fieldId)) != null) {
                if ($rightsIndex < $fieldIndex) {
                    // skipping if user tries to set rights higher than he has
                    continue;
                }
            }

            $query = "insert into oxfield2role (oxfieldid, oxtype, oxroleid, oxidx)
                       values (:oxfieldid, 'oxview', :oxroleid, :oxidx)
                       on duplicate key update oxidx = :oxidx";
            $database->execute($query, [
                ':oxfieldid' => $fieldId,
                ':oxroleid' => $roleId,
                ':oxidx' => $fieldIndex
            ]);
        }
    }
}
