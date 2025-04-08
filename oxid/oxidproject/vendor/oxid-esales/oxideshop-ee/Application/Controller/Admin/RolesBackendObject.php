<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Controller\Admin;

use oxDb;
use OxidEsales\Eshop\Core\Registry;

/**
 * Admin article main actions manager.
 * There is possibility to change actions description, assign articles to
 * this actions, etc.
 * Admin Menu: Manage Products -> actions -> Main.
 */
class RolesBackendObject extends \OxidEsales\Eshop\Application\Controller\Admin\AdminDetailsController
{
    /**
     * Loads rights data and sets it to view for setup
     *
     * @return string
     */
    public function render()
    {
        $config = $this->getConfig();
        if (!$config->getConfigParam('blAllowSharedEdit')) {
            $this->_aViewData['readonly'] = true;
        }

        parent::render();

        // Loaded structure Id
        $this->_aViewData["oxid"] = $roleId = $this->getEditObjectId();
        // Loading configuration XMLs
        $adminRights = oxNew(\OxidEsales\Eshop\Core\AdminRights::class);
        $this->_aViewData['objects'] = $adminRights->getObjectConfig();

        if ($roleId && $roleId != "-1") {
            $database = \OxidEsales\Eshop\Core\DatabaseProvider::getDb(\OxidEsales\Eshop\Core\DatabaseProvider::FETCH_MODE_ASSOC);

            $this->_aViewData["edit"] = oxNew(\OxidEsales\Eshop\Application\Model\Role::class);
            $this->_aViewData["edit"]->load($roleId);

            // Creating rights list.
            $query =
                "SELECT oxfield2role.oxtype, oxfield2role.oxfieldid, oxfield2role.oxidx AS idx
                FROM oxfield2role
                WHERE oxfield2role.oxroleid = :oxroleid AND oxtype != 'oxview' ";

            $fetchedRights = array();
            $result = $database->select($query, [
                ':oxroleid' => $roleId
            ]);
            if ($result != false && $result->count() > 0) {
                while (!$result->EOF) {
                    $fetchedRights[$result->fields["oxtype"]][$result->fields["oxfieldid"]] = $result->fields["idx"];
                    $result->fetchRow();
                }
            }

            $this->_aViewData['aRights'] = $fetchedRights;
        }

        // Fetching dyn area rights from user rights def.
        if ($rights = $this->getRights()) {
            $this->_aViewData['aUserRights'] = $rights->getObjectRights();
        }

        return 'roles_beobject.tpl';
    }

    /**
     * Save
     *
     * @return null
     */
    public function save()
    {
        if (!$this->getConfig()->getConfigParam('blAllowSharedEdit')) {
            return;
        }

        parent::save();

        $rightsId = $this->getEditObjectId();
        $parameters = \OxidEsales\Eshop\Core\Registry::getConfig()->getRequestParameter('editval');

        // saving role data
        $role = oxNew(\OxidEsales\Eshop\Application\Model\Role::class);
        if ($rightsId != '-1') {
            $role->load($rightsId);
        } else {
            $parameters['oxroles__oxid'] = null;
        }

        $role->assign($parameters);
        $role->save();

        $this->setEditObjectId($role->getId());

        $rights = $this->getRights();
        $database = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();
        $fields = \OxidEsales\Eshop\Core\Registry::getConfig()->getRequestParameter('aFields');

        foreach ($fields as $type => $objectFields) {
            foreach ($objectFields as $fieldId => $fieldIndex) {
                if (is_array($fieldIndex)) {
                    $right = 0;
                    foreach ($fieldIndex as $value) {
                        $right = $right | $value;
                    }
                    $fieldIndex = $right;
                }

                // security and performance
                if ($rights && ($right = $rights->getObjectRights($fieldId)) != null) {
                    if ($right < $fieldIndex || $right == $fieldIndex) {
                        // skipping if user tries to set rights higher than he has or previous value is the same
                        continue;
                    }
                }

                $fieldIndex = (int) $fieldIndex;
                $query = "insert into oxfield2role (oxfieldid, oxtype, oxroleid, oxidx)
                           values (:oxfieldid, :oxtype, :oxroleid, :oxidx)
                           on duplicate key update oxidx = :oxidx";
                $database->execute($query, [
                    ':oxfieldid' => $fieldId,
                    ':oxtype' => $type,
                    ':oxroleid' => $role->getId(),
                    ':oxidx' => $fieldIndex
                ]);
            }
        }
    }
}
