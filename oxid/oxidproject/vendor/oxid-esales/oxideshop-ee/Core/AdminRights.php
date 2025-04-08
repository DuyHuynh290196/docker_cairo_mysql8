<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Core;

define('RIGHT_DELETE', 8);
define('RIGHT_INSERT', 4);
define('RIGHT_EDIT', 2);
define('RIGHT_VIEW', 1);
define('RIGHT_DENY', 0);

use oxDb;
use oxBase;
use DOMDocument;
use DOMXPath;
use DOMElement;
use OxidEsales\EshopEnterprise\Core\Exception\AccessRightException;
use oxView;

/**
 * Manager of Administrators rights.
 */
class AdminRights extends \OxidEsales\Eshop\Core\Base
{
    /**
     * View rights configuration
     *
     * @var array
     */
    protected $_aViewRights = null;

    /**
     * Object rights configuration
     *
     * @var array
     */
    protected $_aObjectRights = null;

    /**
     * Session user group ids array
     *
     * @var array
     */
    protected $_aUserGroupIds = null;

    /**
     * Checks rights on passed field. Return true if checked right allows
     * something or false if denies
     *
     * @param int    $iRight     rights index
     * @param \OxidEsales\Eshop\Core\Model\BaseModel $oObject    object to check its rights
     * @param string $sFieldName name of field to check its rights (optional)
     *
     * @return  bool true, if rights granted, false otherwise
     */
    public function hasRights($iRight, $oObject, $sFieldName = null)
    {
        //default is true as non restrictive mode is preferred by default
        $blHas = true;

        $sCoreTable = strtolower($oObject->getCoreTableName());
        $sFieldName = $sFieldName ? strtolower($sFieldName) : $sCoreTable;

        if (($iIdx = $this->getObjectRightsIndex($sCoreTable, $sFieldName)) !== null) {
            $blHas = (bool) ($iRight & $iIdx);
        }

        return $blHas;
    }

    /**
     * Admin menu tree processor. Filters menu items, enables/disables
     * dynamic menu
     *
     * @param DOMDocument $oTree menu array
     */
    public function processNaviTree($oTree)
    {
        // rights setup ?
        $sViewRights = $this->getViewRights();
        if ($sViewRights !== false) {
            $oXPath = new DOMXPath($oTree);
            $oNodeList = $oXPath->query('//*');
            foreach ($oNodeList as $oNode) {
                /** @var DOMElement $oNode */
                $iRights = $this->getViewRightsIndex($oNode->getAttribute('id'));
                if ($iRights !== null) {
                    if ($iRights < RIGHT_VIEW) {
                        $oNode->parentNode->removeChild($oNode);
                    } else {
                        $oNode->setAttribute('idx', $iRights);
                    }
                } else {
                    $oNode->setAttribute('idx', RIGHT_EDIT);
                }
            }
        }
    }

    /**
     * Returns view rights index
     *
     * @param string $sViewId view id
     *
     * @return int
     */
    public function getViewRightsIndex($sViewId)
    {
        $iRightsIdx = null;
        $aViewRights = $this->getViewRights();
        if ($aViewRights !== false && isset($aViewRights[$sViewId])) {
            $iRightsIdx = $aViewRights[$sViewId];
        }

        return $iRightsIdx;
    }

    /**
     * Returns object field rights index
     *
     * @param string $sTable     object type
     * @param string $sFieldName object field
     *
     * @return int
     */
    public function getObjectRightsIndex($sTable, $sFieldName)
    {
        $iRightsIdx = null;
        $aObjectRights = $this->getObjectRights();
        if ($aObjectRights !== false && isset($aObjectRights[$sTable][$sFieldName])) {
            $iRightsIdx = $aObjectRights[$sTable][$sFieldName];
        }

        return $iRightsIdx;
    }

    /**
     * Returns object rights config
     *
     * @return array
     */
    public function getObjectRights()
    {
        if ($this->_aObjectRights === null) {
            $this->_aObjectRights = false;

            // no rights for no user
            $aIds = $this->_getUserGroupIds();
            if (count($aIds)) {
                // loading field information from XML config
                $this->_aObjectRights = array();
                $aConfig = $this->getObjectConfig();

                // now we have all user info - lets load assigned roles
                $sGroupQ = "select oxobject2role.oxroleid from oxobject2role
                            left join oxroles on oxroles.oxid=oxobject2role.oxroleid
                            where oxroles.oxactive = 1 and oxobject2role.oxobjectid in (" . implode(", ", \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->quoteArray($aIds)) . ")
                            and oxroles.oxshopid = :oxshopid ";

                // creating rights list
                $sQ = "select oxfield2role.oxtype, oxfield2role.oxfieldid, max(oxfield2role.oxidx) as idx
                        from oxfield2role where oxfield2role.oxtype != 'oxview' and
                        oxfield2role.oxroleid in ( $sGroupQ ) group by oxfield2role.oxtype, oxfield2role.oxfieldid ";

                $rs = \OxidEsales\Eshop\Core\DatabaseProvider::getDb(\OxidEsales\Eshop\Core\DatabaseProvider::FETCH_MODE_ASSOC)->select($sQ, [
                    ':oxshopid' => \OxidEsales\Eshop\Core\Registry::getConfig()->getShopId()
                ]);
                if ($rs != false && $rs->count() > 0) {
                    while (!$rs->EOF) {
                        // setting only those which config exist
                        if (isset($aConfig[$rs->fields['oxtype']])) {
                            $this->_aObjectRights[$rs->fields['oxtype']][$rs->fields['oxfieldid']] = $rs->fields['idx'];
                        }
                        $rs->fetchRow();
                    }
                }
            }
        }

        return $this->_aObjectRights;
    }

    /**
     * View processor - checks if user has enough rights to view this area
     *
     * @param \OxidEsales\Eshop\Core\Controller\BaseController $oView active view object
     *
     * @throws AccessRightException (should not occur secondary check)
     */
    public function processView($oView)
    {
        $iRights = $this->getViewRightsIndex($oView->getViewId());
        if ($iRights !== null) {
            if ($iRights == RIGHT_DENY) {
                $oEx = oxNew(\OxidEsales\Eshop\Core\Exception\AccessRightException::class);
                $oEx->setMessage('EXCEPTION_ACCESSRIGHT_ACCESSDENIED');
                $oEx->setObjectName($oView->getClassName());
                throw $oEx;
            } elseif ($iRights == RIGHT_VIEW) {
                $oView->setFncName(null);

                // limited access rights ?
                $oView->addTplParam('readonly', 1);

                // hide new button
                $oView->addTplParam('disablenew', 1);
            }
        }
    }


    /**
     * Returns view rights config
     *
     * @return array
     */
    public function getViewRights()
    {
        if ($this->_aViewRights === null) {
            $this->_aViewRights = false;

            // no rights for no user
            $aIds = $this->_getUserGroupIds();
            if (count($aIds)) {
                // now we have all user info - lets load assigned roles
                $sGroupQ = "select oxobject2role.oxroleid from oxobject2role
                            left join oxroles on oxroles.oxid=oxobject2role.oxroleid
                            where oxroles.oxactive = 1 and oxobject2role.oxobjectid in (" . implode(",", \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->quoteArray($aIds)) . ")
                            and oxroles.oxshopid = :oxshopid ";

                // creating rights list
                $sQ = "select oxfield2role.oxfieldid, max(oxfield2role.oxidx) as idx
                        from oxfield2role where oxfield2role.oxtype = 'oxview' and
                        oxfield2role.oxroleid in ( $sGroupQ ) group by oxfield2role.oxfieldid ";

                $rs = \OxidEsales\Eshop\Core\DatabaseProvider::getDb(\OxidEsales\Eshop\Core\DatabaseProvider::FETCH_MODE_ASSOC)->select($sQ, [
                    ':oxshopid' => \OxidEsales\Eshop\Core\Registry::getConfig()->getShopId()
                ]);
                if ($rs != false && $rs->count() > 0) {
                    while (!$rs->EOF) {
                        $this->_aViewRights[$rs->fields['oxfieldid']] = $rs->fields['idx'];
                        $rs->fetchRow();
                    }
                }
            }
        }

        return $this->_aViewRights;
    }

    /**
     * Returns session user group ids array
     *
     * @return array
     * @deprecated underscore prefix violates PSR12, will be renamed to "getUserGroupIds" in next major
     */
    protected function _getUserGroupIds() // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        if ($this->_aUserGroupIds === null) {
            // creating a list of user/groups ids
            $this->_aUserGroupIds = array();
            if (($oUser = $this->getUser())) {
                $this->_aUserGroupIds[] = $oUser->getId();
                foreach ($oUser->getUserGroups() as $oGroup) {
                    $this->_aUserGroupIds[] = $oGroup->getId();
                }
            }
        }

        return $this->_aUserGroupIds;
    }

    /**
     * Returns onjects config array
     *
     * @return array
     */
    public function getObjectConfig()
    {
        $aConfig = array();

        $sMenuFile = "/object_rights.xml";
        $sBasePath = getShopBasePath();

        // loading default config
        $this->_loadConfig($sBasePath . 'Application/views/admin' . $sMenuFile, $aConfig);

        // loading module configs
        $oModulelist = oxNew(\OxidEsales\Eshop\Core\Module\ModuleList::class);
        $aActiveModuleInfo = $oModulelist->getActiveModuleInfo();
        if (is_array($aActiveModuleInfo)) {
            $sSourceDir = $sBasePath . 'modules';
            foreach ($aActiveModuleInfo as $sModulePath) {
                $sDir = "$sSourceDir/$sModulePath";
                if (is_dir($sDir)) {
                    $this->_loadConfig("{$sDir}{$sMenuFile}", $aConfig);
                }
            }
        }

        return $aConfig;
    }

    /**
     * Loads config information from XML file which path is passed by param
     *
     * @param string $sConfigPath path to config
     * @param array  &$aConfig    array to store config information
     * @deprecated underscore prefix violates PSR12, will be renamed to "loadConfig" in next major
     */
    protected function _loadConfig($sConfigPath, &$aConfig) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        if (file_exists($sConfigPath)) {
            $oDomFile = new DOMDocument();
            $oDomFile->load($sConfigPath);

            $oXPath = new DOMXPath($oDomFile);
            $oTableList = $oXPath->query('//object');
            foreach ($oTableList as $oTable) {
                /** @var DOMElement $oTable */
                if (($sTable = $oTable->getAttribute('table'))) {
                    $oFieldList = $oXPath->query("//object [@table='$sTable']/field");
                    foreach ($oFieldList as $oField) {
                        /** @var DOMElement $oField */
                        $aConfig[$sTable][$oField->getAttribute('name')] = 0;
                    }
                }
            }
        }
    }

    /**
     * User roles loader
     */
    public function load()
    {
    }
}
