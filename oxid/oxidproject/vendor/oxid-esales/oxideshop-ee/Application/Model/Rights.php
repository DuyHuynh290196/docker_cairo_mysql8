<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Model;

use oxDb;
use OxidEsales\Eshop\Core\Registry;

/**
 * User rights manager.
 */
class Rights extends \OxidEsales\Eshop\Core\Base
{
    /**
     * Current user rights array.
     *
     * @var array
     */
    protected $_aRights = null;

    /**
     * Users group index.
     *
     * @var string
     */
    protected $_sUserGroupIndex = null;

    /**
     * Checks rights on passed ident. Returns true, if rights
     * granted, false otherwise.
     *
     * @param string $identifier id
     *
     * @return bool
     */
    public function hasViewRights($identifier)
    {
        // we only keep DENY info
        return !(isset($this->_aRights[$identifier]));
    }

    /**
     * View processor - checks if user has enough rights to view this area
     * or execute some function.
     *
     * @param string $view     name of active view.
     * @param string $function name of function to execute.
     *
     * @throws \OxidEsales\EshopEnterprise\Core\Exception\AccessRightException (should not occur secondary check)
     *
     * @return null
     */
    public function processView($view, $function = null)
    {
        // no restrictions to view ?
        if (!is_array($this->_aRights)) {
            return;
        }

        $className = $view->getClassName();

        // no input, nothing to check
        if (!$function && !$className) {
            return;
        }

        // searching for R&R object
        $rAndRItem = null;
        if ($className && !$this->hasViewRights($className)) {
            $rAndRItem = $this->_aRights[$className];
        } elseif ($function && !$this->hasViewRights($function)) {
            $rAndRItem = $this->_aRights[$function];
            $className = $function;
        } else {
            foreach ($this->_aRights as $item) {
                //
                if (!is_array($item)) {
                    continue;
                }

                if ($className && in_array($className, $item)) {
                    $rAndRItem = $item;
                    break;
                }
                if ($function && in_array($function, $item)) {
                    $rAndRItem = $item;
                    $className = $function;
                    break;
                }
            }
        }

        if ($rAndRItem) {
            // access denied
            $oEx = oxNew(\OxidEsales\Eshop\Core\Exception\AccessRightException::class);
            $oEx->setMessage('EXCEPTION_ACCESSRIGHT_ACCESSDENIED');
            $oEx->setObjectName($className);
            throw $oEx;
        }
    }

    /**
     * Loads user rights config.
     * @deprecated underscore prefix violates PSR12, will be renamed to "loadRights" in next major
     */
    protected function _loadRights() // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $this->_aRights = array();
        $config = $this->getConfig();
        $database = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();
        $params = [
            ':oxshopid' => $config->getShopId()
        ];

        // selecting denied fields
        $query = "SELECT oxrolefields.oxname, oxrolefields.oxparam FROM oxroles
                     LEFT JOIN oxfield2role ON oxroles.oxid = oxfield2role.oxroleid
                     LEFT JOIN oxrolefields ON oxrolefields.oxid = oxfield2role.oxfieldid
                     LEFT JOIN oxobjectrights ON oxobjectrights.oxobjectid = oxroles.oxid
                     WHERE oxroles.oxarea =1 AND oxroles.oxactive =1 AND oxroles.oxshopid = :oxshopid
                     AND oxobjectrights.oxobjectid IS NOT NULL ";

        // should not be loaded user rights
        $groupIndex = $this->getUserGroupIndex();
        if (is_array($groupIndex) && count($groupIndex)) {
            $query .= "AND (
                            SELECT 1 FROM oxroles
                            LEFT JOIN oxfield2role ON oxroles.oxid = oxfield2role.oxroleid
                            LEFT JOIN oxobjectrights ON oxobjectrights.oxobjectid = oxroles.oxid
                            WHERE oxrolefields.oxid = oxfield2role.oxfieldid
                            AND oxroles.oxarea = 1
                            AND oxroles.oxactive = 1
                            AND oxroles.oxshopid = :oxshopid
                            AND oxobjectrights.oxid IS NOT NULL AND ( ";

            $count = 0;
            foreach ($groupIndex as $iOffset => $iBitMap) {
                if ($count) {
                    $query .= " | ";
                }
                $query .= " ( oxobjectrights.oxgroupidx & $iBitMap and oxobjectrights.oxoffset = $iOffset ) ";
                $count++;
            }

            $query .= ") LIMIT 1 ) IS NULL ";
        }
        $query .= "GROUP BY oxrolefields.oxid ";

        // storing all loaded data
        $result = $database->select($query, $params);
        if ($result != false && $result->count() > 0) {
            while (!$result->EOF) {
                // calculating right index
                if ($result->fields[1]) {
                    $this->_aRights[$result->fields[0]] = explode(";", $result->fields[1]);
                } else {
                    $this->_aRights[$result->fields[0]] = 1;
                }

                $result->fetchRow();
            }
        }
    }

    /**
     * Shop customer R&R processor.
     *
     * @return null
     */
    public function load()
    {
        if (!$this->getConfig()->getSerial()->validateShop()) {
            return false;
        }

        $variableName = $this->_getSessionVariableName();

        // must track if user changed status, for example logged in
        if ($this->_checkStatus()) {
            // loading R&R data from session
            $this->_aRights = \OxidEsales\Eshop\Core\Registry::getSession()->getVariable($variableName);
        }

        // user R&R data is not yet loaded
        if (!is_array($this->_aRights)) {
            // now we have all DENY info
            $this->_loadRights();

            \OxidEsales\Eshop\Core\Registry::getSession()->setVariable($variableName, $this->_aRights);
            \OxidEsales\Eshop\Core\Registry::getSession()->setVariable('oxrrvarname', $variableName);
        }
    }

    /**
     * Calculates and returns session user group index for R&R
     *
     * @return array
     */
    public function getUserGroupIndex()
    {
        if ($this->_sUserGroupIndex == null) {
            if ($user = $this->getUser()) {
                $database = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();
                $query = "select oxgroups.oxrrid from oxgroups left join oxobject2group on oxobject2group.oxgroupsid = oxgroups.oxid
                       where oxobject2group.oxobjectid = :oxobjectid";

                $this->_sUserGroupIndex = array();

                $result = $database->select($query, [
                    ':oxobjectid' => $user->getId()
                ]);

                if ($result != false && $result->count() > 0) {
                    while (!$result->EOF) {
                        // extracting group index
                        $iOffset = (int) ($result->fields[0] / 31);
                        $iBitMap = 1 << ($result->fields[0] % 31);
                        $result->fetchRow();

                        if (!isset($this->_sUserGroupIndex[$iOffset])) {
                            $this->_sUserGroupIndex[$iOffset] = $iBitMap;
                        } else {
                            $this->_sUserGroupIndex[$iOffset] = $this->_sUserGroupIndex[$iOffset] | $iBitMap;
                        }
                    }
                }
            }
        }

        return $this->_sUserGroupIndex;
    }

    /**
     * Returns rights config defined for session user
     *
     * @return array
     */
    public function getViewRights()
    {
        if ($this->_aRights == null) {
            $this->load();
        }

        return $this->_aRights;
    }

    /**
     * Checks is session user id and shop id combination matches loaded roles data.
     * Returns true if matches and false if not
     *
     * @return bool
     * @deprecated underscore prefix violates PSR12, will be renamed to "checkStatus" in next major
     */
    protected function _checkStatus() // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $variableName = $this->_getSessionVariableName();
        $sessionName = \OxidEsales\Eshop\Core\Registry::getSession()->getVariable('oxrrvarname');

        $isOk = true;

        // reloading R&R data
        if ($sessionName != $variableName) {
            \OxidEsales\Eshop\Core\Registry::getSession()->deleteVariable('oxrrvarname');
            \OxidEsales\Eshop\Core\Registry::getSession()->deleteVariable($sessionName);
            $isOk = false;
        }

        return $isOk;
    }

    /**
     * Returns SQL select to check if user rights denies to access object
     *
     * @param string $sOxid   object id
     * @param int    $iAction right id
     *
     * @return string
     */
    public function hasObjectRights($sOxid, $iAction = 1)
    {
        $params = [
            ':oxobjectid' => $sOxid,
            ':oxaction' => $iAction
        ];

        // todo: fix callers of hasObjectRights to not add quotes and use sOxid through the parameter for query
        $query = "select ( ( select 1 from oxobjectrights where oxobjectrights.oxobjectid = $sOxid and oxobjectrights.oxaction = :oxaction limit 1 ) is not null ) ";
        $groupIndex = $this->getUserGroupIndex();
        if (is_array($groupIndex) && count($groupIndex)) {
            $iNr = 0;
            $query .= " xor ( ( select 1 from oxobjectrights where oxobjectrights.oxobjectid = $sOxid and oxobjectrights.oxaction = :oxaction and ( ";
            foreach ($groupIndex as $iOffset => $iBitMap) {
                if ($iNr++) {
                    $query .= " or ";
                }
                $query .= " ( ( oxobjectrights.oxgroupidx & $iBitMap) and oxobjectrights.oxoffset = $iOffset ) ";
            }
            $query .= ") limit 1 ) is not null )";
        }

        return !((bool) \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->getOne($query, $params));
    }

    /**
     * Return variable name used for storage in session
     *
     * @return string
     * @deprecated underscore prefix violates PSR12, will be renamed to "getSessionVariableName" in next major
     */
    protected function _getSessionVariableName() // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $userId = '';
        if ($user = $this->getUser()) {
            $userId = $user->getId();
        }

        return 'oxrr' . $userId . $this->getConfig()->getShopId();
    }
}
