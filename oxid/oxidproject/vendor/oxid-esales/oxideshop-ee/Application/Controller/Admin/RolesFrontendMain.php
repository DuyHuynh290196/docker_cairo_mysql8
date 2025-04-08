<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Controller\Admin;

use oxDb;
use OxidEsales\Eshop\Core\Registry;
use oxUtilsObject;
use stdClass;

/**
 * Admin article main actions manager.
 * There is possibility to change actions description, assign articles to
 * this actions, etc.
 * Admin Menu: Manage Products -> actions -> Main.
 */
class RolesFrontendMain extends \OxidEsales\Eshop\Application\Controller\Admin\AdminDetailsController
{
    /**
     * Render.
     *
     * @return string
     */
    public function render()
    {
        parent::render();
        $oxId = $this->_aViewData['oxid'] = $this->getEditObjectId();
        if ($oxId != '-1' && $oxId) {
            $database = \OxidEsales\Eshop\Core\DatabaseProvider::getDb(\OxidEsales\Eshop\Core\DatabaseProvider::FETCH_MODE_ASSOC);
            $role = oxNew(\OxidEsales\Eshop\Application\Model\Role::class);
            $role->load($oxId);
            $this->_aViewData['edit'] = $role;

            // Loading default menu/tabs data and building logical structure.
            $subQuery = "SELECT oxfield2role.oxidx FROM oxfield2role
                WHERE oxfield2role.oxfieldid = oxrolefields.oxid
                AND oxfield2role.oxroleid = :oxroleid";

            $query = "select oxrolefields.oxid, oxrolefields.oxname, oxrolefields.oxparam, ($subQuery) as value "
                  . "from oxrolefields ";
            $stringHandler = getStr();

            $fieldsList = array();
            $executionResult = $database->select($query, [
                ':oxroleid' => $oxId
            ]);
            if ($executionResult != false && $executionResult->count() > 0) {
                while (!$executionResult->EOF) {
                    $fieldsList[$executionResult->fields['oxid']] = new stdClass();
                    $fieldsList[$executionResult->fields['oxid']]->id = $executionResult->fields['oxid'];
                    $fieldsList[$executionResult->fields['oxid']]->name = $executionResult->fields['oxname'];
                    $fieldsList[$executionResult->fields['oxid']]->params = $executionResult->fields['oxparam'];
                    $fieldsList[$executionResult->fields['oxid']]->value = $executionResult->fields['value'];

                    // Protecting from deletion special fields.
                    if (!$stringHandler->preg_match('/^ox_/', $fieldsList[$executionResult->fields['oxid']]->id)) {
                        $fieldsList[$executionResult->fields['oxid']]->deletable = true;
                    }
                    $executionResult->fetchRow();
                }
            }

            // Applying defined options.
            $this->_aViewData['shopfnc'] = $fieldsList;
        }

        return 'roles_femain.tpl';
    }

    /**
     * Saving Shop Role parameters.
     */
    public function save()
    {
        parent::save();

        $config = $this->getConfig();

        $rights = \OxidEsales\Eshop\Core\Registry::getConfig()->getRequestParameter('menuright');
        $oxId = $this->getEditObjectId();
        $parameters = \OxidEsales\Eshop\Core\Registry::getConfig()->getRequestParameter('editval');

        // Checkbox handling.
        if (!isset($parameters['oxroles__oxactive'])) {
            $parameters['oxroles__oxactive'] = 0;
        }

        // Saving role data.
        $role = oxNew(\OxidEsales\Eshop\Application\Model\Role::class);
        if ($oxId != '-1') {
            $role->load($oxId);
        } else {
            $parameters['oxroles__oxid'] = null;
            $parameters['oxroles__oxshopid'] = $config->getShopId();
        }

        $role->assign($parameters);
        $role->save();

        $this->setEditObjectId($role->getId());

        // Saving oxfield2role.
        if (is_array($rights) && count($rights)) {
            $database = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();
            // Saving or updating configuration.
            foreach ($rights as $fieldId => $value) {
                // We only interpret it as "exclusively...".
                if (!$value['right']) {
                    $query = "DELETE FROM oxfield2role "
                        . "WHERE oxfieldid=" . $database->quote($fieldId) . " "
                        . "AND oxroleid = " . $database->quote($role->oxroles__oxid->value);
                    $database->execute($query);
                } else {
                    $query = "INSERT INTO oxfield2role (oxfieldid, oxroleid, oxidx)
                                VALUES (:oxfieldid, :oxroleid, :oxidx)
                                ON DUPLICATE KEY UPDATE oxidx = :oxidx";
                    $database->execute($query, [
                        ':oxfieldid' => $fieldId,
                        ':oxroleid' => $role->oxroles__oxid->value,
                        ':oxidx' => $value['right']
                    ]);
                }
            }
        }
    }

    /**
     * Adds field for R&R.
     */
    public function addField()
    {
        $parameter = \OxidEsales\Eshop\Core\Registry::getConfig()->getRequestParameter("oxparam");
        $oxId = $this->getEditObjectId();

        // Saving oxfield2role.
        $role = oxNew(\OxidEsales\Eshop\Application\Model\Role::class);
        if ($parameter && $role->load($oxId)) {
            $stringHandler = getStr();

            // Parsing input.
            $name = $stringHandler->preg_replace('/&.*/', '', $parameter);
            $escapedParameter = '';
            if (strpos($parameter, '&') !== false) {
                $escapedParameter = $stringHandler->preg_replace('/.*&/', '', $parameter);
            }

            $database = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();
            $query = "insert into oxrolefields (oxid, oxname, oxparam) values (:oxid, :oxname, :oxparam)";
            $database->execute($query, [
                ':oxid' => Registry::getUtilsObject()->generateUID(),
                ':oxname' => $name,
                ':oxparam' => $escapedParameter
            ]);
        }
    }

    /**
     * Deletes R&R field.
     */
    public function deleteField()
    {
        // Saving oxfield2role.
        if ($id = \OxidEsales\Eshop\Core\Registry::getConfig()->getRequestParameter('oxparam')) {
            $database = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();
            // Deleting field.
            $query = "delete from oxrolefields where oxid = :oxid";
            $database->execute($query, [
                ':oxid' => $id
            ]);

            // Deleting predefined roles for field.
            $query = "delete from oxfield2role where oxfieldid = :oxfieldid";
            $database->execute($query, [
                ':oxfieldid' => $id
            ]);
        }
    }
}
