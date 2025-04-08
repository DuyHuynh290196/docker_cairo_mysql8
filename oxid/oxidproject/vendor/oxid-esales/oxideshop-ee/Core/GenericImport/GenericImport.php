<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Core\GenericImport;

use Exception;
use oxDb;
use OxidEsales\Eshop\Core\GenericImport\ImportObject\ImportObject;
use OxidEsales\Eshop\Core\Config;

/**
 * Manager of Administrators rights.
 */
class GenericImport extends \OxidEsales\EshopProfessional\Core\GenericImport\GenericImport
{
    /**
     * Checks if user has sufficient rights.
     *
     * @param ImportObject $importObject  Data type object
     * @param boolean      $isWriteAction Check for write permissions
     *
     * @throws Exception
     */
    protected function checkAccess($importObject, $isWriteAction)
    {
        parent::checkAccess($importObject, $isWriteAction);

        /** @var Config $config */
        $config = \OxidEsales\Eshop\Core\Registry::getConfig();
        static $accessCache;

        // add R&R check for access
        if ($config->blUseRightsRoles) {
            $accessMode = ((bool) $isWriteAction) ? '2' : '1';
            $typeClass = get_class($importObject);

            if (!isset($accessCache[$typeClass][$accessMode])) {
                $database = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();

                //create list of user/group id's
                $userIds = array($database->quote($this->userId));
                $userGroupsQuery = 'SELECT oxgroupsid ' .
                    'FROM oxobject2group ' .
                    //"WHERE oxshopid = '{$this->_iShopID}' ".
                    "WHERE oxshopid = :oxshopid " .
                    "AND oxobjectid = :oxobjectid";

                $result = $database->select($userGroupsQuery, [
                    ':oxshopid' => $config->getShopId(),
                    ':oxobjectid' => $this->userId
                ]);
                if ($result != false && $result->count() > 0) {
                    while (!$result->EOF) {
                        $userIds[] = $database->quote($result->fields[0]);
                        $result->fetchRow();
                    }
                }

                $rightFields = $importObject->getRightFields();
                foreach ($rightFields as $key => $field) {
                    $rightFields[$key] = $database->quote($field);
                }

                //check user rights...
                $query = 'SELECT count(*) ' .
                    'FROM oxfield2role as rr , oxrolefields as rf, oxobject2role as ro, oxroles as rt ' .
                    "WHERE rr.OXIDX < :accessMode " .
                    'AND rr.oxroleid = ro.oxroleid  ' .
                    'AND rt.oxid = ro.oxroleid ' .
                    'AND rt.oxactive = 1 ' .
                    //"AND rt.oxshopid = '{$this->_iShopID}'".
                    "AND rt.oxshopid = :oxshopid " .
                    'AND rf.oxparam IN (' . implode(',', $rightFields) . ') ' .
                    'AND ro.oxobjectid IN (' . implode(',', $userIds) . ') ' .
                    'AND rr.oxfieldid=rf.oxid';

                $accessLevel = $database->getOne($query, [
                    ':accessMode' => $accessMode,
                    ':oxshopid' => $config->getShopId()
                ]);
                $accessCache[$typeClass][$accessMode] = $accessLevel;
            } else {
                $accessLevel = $accessCache[$typeClass][$accessMode];
            }

            if ($accessLevel) {
                throw new Exception(self::ERROR_USER_NO_RIGHTS);
            }
        }
    }
}
