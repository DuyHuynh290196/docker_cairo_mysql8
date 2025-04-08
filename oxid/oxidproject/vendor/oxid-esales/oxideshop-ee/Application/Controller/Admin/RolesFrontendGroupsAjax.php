<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Controller\Admin;

use oxDb;
use OxidEsales\Eshop\Core\Registry;

/**
 * Class manages front-end user groups rights.
 */
class RolesFrontendGroupsAjax extends \OxidEsales\Eshop\Application\Controller\Admin\ListComponentAjax
{
    /** @var array Columns array. */
    protected $_aColumns = array(
        // field , table,  visible, multilanguage, id
        'container1' => array(
            array('oxtitle', 'oxgroups', 1, 0, 0),
            array('oxid', 'oxgroups', 0, 0, 0),
            array('oxrrid', 'oxgroups', 0, 0, 1),
        ),
        'container2' => array(
            array('oxtitle', 'oxgroups', 1, 0, 0),
            array('oxid', 'oxgroups', 0, 0, 0),
            array('oxrrid', 'oxgroups', 0, 0, 1),
        )
    );

    /**
     * Returns SQL query for data to fetch.
     *
     * @return string
     * @deprecated underscore prefix violates PSR12, will be renamed to "getQuery" in next major
     */
    protected function _getQuery() // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        // looking for table/view
        $groupsViewName = $this->_getViewName('oxgroups');
        $database = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();
        $groupId = \OxidEsales\Eshop\Core\Registry::getConfig()->getRequestParameter('oxid');
        $syncedGroupId = \OxidEsales\Eshop\Core\Registry::getConfig()->getRequestParameter('synchoxid');

        $action = 1;
        if (!$groupId) {
            $query = " from $groupsViewName where 1 ";
        } else {
            $query = " from $groupsViewName, oxobjectrights where ";
            $query .= " oxobjectrights.oxobjectid = " . $database->quote($groupId) . " and ";
            $query .= " oxobjectrights.oxoffset = ($groupsViewName.oxrrid div 31) and ";
            $query .= " oxobjectrights.oxgroupidx & (1 << ($groupsViewName.oxrrid mod 31)) and oxobjectrights.oxaction = $action ";
        }

        if ($syncedGroupId && $syncedGroupId != $groupId) {
            $query = " from $groupsViewName left join oxobjectrights on ";
            $query .= " oxobjectrights.oxoffset = ($groupsViewName.oxrrid div 31) and ";
            $query .= " oxobjectrights.oxgroupidx & (1 << ($groupsViewName.oxrrid mod 31)) and oxobjectrights.oxobjectid=" . $database->quote($syncedGroupId) . " and oxobjectrights.oxaction = $action ";
            $query .= " where oxobjectrights.oxobjectid != " . $database->quote($syncedGroupId) . " or (oxobjectid is null) ";
        }

        return $query;
    }

    /**
     * Removes chosen user group (groups) from delivery list.
     */
    public function removeGroupFromFeRoles()
    {
        $chosenGroups = $this->_getActionIds('oxgroups.oxrrid');
        $groupId = \OxidEsales\Eshop\Core\Registry::getConfig()->getRequestParameter('oxid');

        $action = 1;

        // removing all
        if (\OxidEsales\Eshop\Core\Registry::getConfig()->getRequestParameter('all')) {
            $groupViewName = $this->_getViewName('oxgroups');
            $chosenGroups = $this->_getAll($this->_addFilter("select $groupViewName.oxrrid " . $this->_getQuery()));
        }

        if (isset($groupId) && $groupId != "-1" && is_array($chosenGroups) && $chosenGroups) {
            $indexes = array();
            foreach ($chosenGroups as $rightsAndRolesIndex) {
                $offset = (int) ($rightsAndRolesIndex / 31);
                $bitMap = 1 << ($rightsAndRolesIndex % 31);

                // summing indexes
                if (!isset($indexes[$offset])) {
                    $indexes[$offset] = $bitMap;
                } else {
                    $indexes[$offset] = $indexes [$offset] | $bitMap;
                }
            }

            $database = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();
            // iterating through indexes and applying to (sub)categories R&R
            foreach ($indexes as $offset => $index) {
                // processing category
                $query = "update oxobjectrights set oxgroupidx = oxgroupidx & ~:oxindex where oxobjectid = :oxobjectid and oxoffset = :oxoffset and oxaction = :oxaction";
                $database->execute($query, [
                    ':oxindex' => $index,
                    ':oxobjectid' => $groupId,
                    ':oxoffset' => $offset,
                    ':oxaction' => $action
                ]);
            }

            // removing cleared
            $query = "delete from oxobjectrights where oxgroupidx = 0";
            $database->execute($query);
        }
    }

    /**
     * Adds chosen user group (groups) to R&R list.
     */
    public function addGroupToFeroles()
    {
        $chosenCategories = $this->_getActionIds('oxgroups.oxrrid');
        $groupId = \OxidEsales\Eshop\Core\Registry::getConfig()->getRequestParameter('synchoxid');

        $action = 1;
        if (\OxidEsales\Eshop\Core\Registry::getConfig()->getRequestParameter('all')) {
            $groupViewName = $this->_getViewName('oxgroups');
            $chosenCategories = $this->_getAll($this->_addFilter("select $groupViewName.oxrrid " . $this->_getQuery()));
        }

        if (isset($groupId) && $groupId != "-1" && isset($chosenCategories) && $chosenCategories) {
            $indexes = array();
            foreach ($chosenCategories as $rightsAndRolesIndex) {
                $offset = (int) ($rightsAndRolesIndex / 31);
                $bitMap = 1 << ($rightsAndRolesIndex % 31);

                // summing indexes
                if (!isset($indexes[$offset])) {
                    $indexes[$offset] = $bitMap;
                } else {
                    $indexes[$offset] = $indexes [$offset] | $bitMap;
                }
            }

            $utilsObject = \OxidEsales\Eshop\Core\Registry::getUtilsObject();
            $database = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();

            // iterating through indexes and applying to (sub)categories R&R
            foreach ($indexes as $offset => $index) {
                // processing category
                $query = "insert into oxobjectrights (oxid, oxobjectid, oxgroupidx, oxoffset, oxaction)
                            values (:oxid, :oxobjectid, :oxgroupidx, :oxoffset, :oxaction)
                            on duplicate key update oxgroupidx = (oxgroupidx | :oxgroupidx)";
                $database->execute($query, [
                    ':oxid' => $utilsObject->generateUID(),
                    ':oxobjectid' => $groupId,
                    ':oxgroupidx' => $index,
                    ':oxoffset' => $offset,
                    ':oxaction' => $action
                ]);
            }
        }
    }
}
