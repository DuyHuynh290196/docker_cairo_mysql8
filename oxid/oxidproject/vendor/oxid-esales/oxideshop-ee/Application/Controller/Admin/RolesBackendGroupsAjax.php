<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Controller\Admin;

use oxDb;
use oxField;

/**
 * Class manages back-end user groups rights
 */
class RolesBackendGroupsAjax extends \OxidEsales\Eshop\Application\Controller\Admin\ListComponentAjax
{
    /** @var array Columns array. */
    protected $_aColumns = array(
        // field , table,  visible, multilanguage, id
        'container1' => array(
            array('oxtitle', 'oxgroups', 1, 0, 0),
            array('oxid', 'oxgroups', 0, 0, 0),
            array('oxid', 'oxgroups', 0, 0, 1),
        ),
        'container2' => array(
            array('oxtitle', 'oxgroups', 1, 0, 0),
            array('oxid', 'oxgroups', 0, 0, 0),
            array('oxid', 'oxobject2role', 0, 0, 1),
        )
    );

    /**
     * Returns SQL query for data to fetc
     *
     * @return string
     * @deprecated underscore prefix violates PSR12, will be renamed to "getQuery" in next major
     */
    protected function _getQuery() // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        // looking for table/view
        $groupTable = $this->_getViewName('oxgroups');
        $database = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();
        $roleId = $this->getConfig()->getRequestParameter('oxid');
        $syncedRoleId = $this->getConfig()->getRequestParameter('synchoxid');

        // category selected or not ?
        if (!$roleId) {
            $query = " FROM $groupTable WHERE 1 ";
        } else {
            $query =
                " FROM oxobject2role, $groupTable
                WHERE oxobject2role.oxtype = 'oxgroups'
                AND oxobject2role.oxroleid = " . $database->quote($roleId) . "
                AND $groupTable.oxid=oxobject2role.oxobjectid ";
        }

        if ($syncedRoleId && $syncedRoleId != $roleId) {
            $query .=
                " AND $groupTable.oxid not in (
                SELECT oxobject2role.oxobjectid
                FROM oxobject2role
                WHERE oxobject2role.oxtype = 'oxgroups'
                AND oxobject2role.oxroleid = " . $database->quote($syncedRoleId) . " ) ";
        }

        return $query;
    }

    /**
     * Removes User group from R&R
     */
    public function removeGroupFromBeRoles()
    {
        $database = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();
        $groupsToRemove = $this->_getActionIds('oxobject2role.oxid');
        if ($this->getConfig()->getRequestParameter('all')) {
            $query = $this->_addFilter("delete oxobject2role.* " . $this->_getQuery());
            $database->execute($query);
        } elseif ($groupsToRemove && is_array($groupsToRemove)) {
            $groupsToRemoveQuoted = $database->quoteArray($groupsToRemove);
            $query = "DELETE FROM oxobject2role WHERE oxobject2role.oxid IN (" . implode(", ", $groupsToRemoveQuoted) . ") ";
            \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute($query);
        }
    }

    /**
     * Adds User group to R&R
     */
    public function addGroupToBeRoles()
    {
        $chosenCategory = $this->_getActionIds('oxgroups.oxid');
        $roleId = $this->getConfig()->getRequestParameter('synchoxid');
        if ($this->getConfig()->getRequestParameter('all')) {
            $groupViewName = $this->_getViewName('oxgroups');
            $chosenCategory = $this->_getAll($this->_addFilter("select $groupViewName.oxid " . $this->_getQuery()));
        }
        if ($roleId && $roleId != "-1" && is_array($chosenCategory)) {
            foreach ($chosenCategory as $sChosenCat) {
                $objectToRole = oxNew(\OxidEsales\Eshop\Core\Model\BaseModel::class);
                $objectToRole->init("oxobject2role");
                $objectToRole->oxobject2role__oxobjectid = new \OxidEsales\Eshop\Core\Field($sChosenCat);
                $objectToRole->oxobject2role__oxroleid = new \OxidEsales\Eshop\Core\Field($roleId);
                $objectToRole->oxobject2role__oxtype = new \OxidEsales\Eshop\Core\Field("oxgroups");
                $objectToRole->save();
            }
        }
    }
}
