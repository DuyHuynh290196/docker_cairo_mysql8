<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Controller\Admin;

use oxDb;
use oxField;

/**
 * Class manages back-end user rights
 */
class RolesBackendUserAjax extends \OxidEsales\Eshop\Application\Controller\Admin\ListComponentAjax
{
    /** @var array Columns array. */
    protected $_aColumns = array(
        // field , table,  visible, multilanguage, id
        'container1' => array(
            array('oxusername', 'oxuser', 1, 0, 0),
            array('oxlname', 'oxuser', 0, 0, 0),
            array('oxfname', 'oxuser', 0, 0, 0),
            array('oxstreet', 'oxuser', 0, 0, 0),
            array('oxstreetnr', 'oxuser', 0, 0, 0),
            array('oxcity', 'oxuser', 0, 0, 0),
            array('oxzip', 'oxuser', 0, 0, 0),
            array('oxfon', 'oxuser', 0, 0, 0),
            array('oxbirthdate', 'oxuser', 0, 0, 0),
            array('oxid', 'oxuser', 0, 0, 1),
        ),
        'container2' => array(
            array('oxusername', 'oxuser', 1, 0, 0),
            array('oxlname', 'oxuser', 0, 0, 0),
            array('oxfname', 'oxuser', 0, 0, 0),
            array('oxstreet', 'oxuser', 0, 0, 0),
            array('oxstreetnr', 'oxuser', 0, 0, 0),
            array('oxcity', 'oxuser', 0, 0, 0),
            array('oxzip', 'oxuser', 0, 0, 0),
            array('oxfon', 'oxuser', 0, 0, 0),
            array('oxbirthdate', 'oxuser', 0, 0, 0),
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
        $config = $this->getConfig();

        // looking for table/view
        $userViewName = $this->_getViewName('oxuser');
        $database = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();
        $roleId = $config->getRequestParameter('oxid');
        $syncedRoleId = $config->getRequestParameter('synchoxid');

        if (!$roleId) {
            // performance
            $query = " from $userViewName where 1 ";
            if (!$config->getRequestParameter('blMallUsers')) {
                $query .= " and (oxshopid = '" . $config->getShopId() . "' OR oxrights='malladmin')";
            }
        } elseif ($syncedRoleId && $syncedRoleId != $roleId) {
            // selected group ?
            $query = " from oxobject2group inner join $userViewName on $userViewName.oxid = oxobject2group.oxobjectid ";
            $query .= " where oxobject2group.oxgroupsid = " . $database->quote($roleId);
            if (!$config->getRequestParameter('blMallUsers')) {
                $query .= " and $userViewName.oxshopid = '" . $config->getShopId() . "' ";
            }
        } else {
            $query = " from oxobject2role, $userViewName where oxobject2role.oxtype = 'oxuser' and ";
            $query .= " oxobject2role.oxroleid = " . $database->quote($roleId) . " and $userViewName.oxid=oxobject2role.oxobjectid ";
        }

        if ($syncedRoleId && $syncedRoleId != $roleId) {
            // performance
            $query .= " and $userViewName.oxid not in ( select oxobject2role.oxobjectid  from oxobject2role where oxobject2role.oxtype = 'oxuser' and oxobject2role.oxroleid = " . $database->quote($syncedRoleId) . " ) ";
        }

        return $query;
    }

    /**
     * Removes User from R&R
     */
    public function removeUserFromBeroles()
    {
        $removeGroups = $this->_getActionIds('oxobject2role.oxid');
        if ($this->getConfig()->getRequestParameter('all')) {
            $query = $this->_addFilter("delete oxobject2role.* " . $this->_getQuery());
            \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->Execute($query);
        } elseif ($removeGroups && is_array($removeGroups)) {
            $query = "delete from oxobject2role where oxobject2role.oxid in (" . implode(", ", \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->quoteArray($removeGroups)) . ") ";
            \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->Execute($query);
        }
    }

    /**
     * Adds User to R&R
     */
    public function addUserToBeRoles()
    {
        $chosenUsers = $this->_getActionIds('oxuser.oxid');
        $roleId = $this->getConfig()->getRequestParameter('synchoxid');

        if ($this->getConfig()->getRequestParameter('all')) {
            $userViewName = $this->_getViewName('oxuser');
            $chosenUsers = $this->_getAll($this->_addFilter("select $userViewName.oxid " . $this->_getQuery()));
        }
        if ($roleId && $roleId != "-1" && is_array($chosenUsers)) {
            foreach ($chosenUsers as $chosenUser) {
                $objectToRole = oxNew(\OxidEsales\Eshop\Core\Model\BaseModel::class);
                $objectToRole->init("oxobject2role");
                $objectToRole->oxobject2role__oxobjectid = new \OxidEsales\Eshop\Core\Field($chosenUser);
                $objectToRole->oxobject2role__oxroleid = new \OxidEsales\Eshop\Core\Field($roleId);
                $objectToRole->oxobject2role__oxtype = new \OxidEsales\Eshop\Core\Field("oxuser");
                $objectToRole->save();
            }
        }
    }
}
