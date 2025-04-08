<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Model;

use oxField;
use oxDb;

/**
 * @inheritdoc
 */
class Groups extends \OxidEsales\EshopProfessional\Application\Model\Groups
{
    /**
     * Creating new group ...
     *
     * @return resource
     * @deprecated underscore prefix violates PSR12, will be renamed to "insert" in next major
     */
    protected function _insert() // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        // calculating group RR index
        $this->oxgroups__oxrrid = new \OxidEsales\Eshop\Core\Field(((int) \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->getOne('select max( oxrrid ) from oxgroups') + 1));

        return parent::_insert();
    }

    /**
     * Checks if object can be read
     *
     * @return bool
     */
    public function canRead()
    {
        return true;
    }

    /**
     * Checks if object field can be read/viewed by user
     *
     * @param string $fieldName name of field to check
     *
     * @return bool
     */
    public function canReadField($fieldName)
    {
        return true;
    }

    /**
     * Deletes user group from database. Returns true/false, according to deleting status.
     *
     * @param string $oxid Object ID (default null)
     *
     * @return bool
     */
    public function delete($oxid = null)
    {
        if (!$oxid) {
            $oxid = $this->getId();
        }
        if (!$oxid) {
            return false;
        }

        if (!$this->canDelete($oxid)) {
            return false;
        }

        $rrid = false;
        if (!$this->getId()) {
            $oGroup = oxNew(\OxidEsales\Eshop\Application\Model\Groups::class);
            if ($oGroup->load($oxid)) {
                $rrid = $oGroup->oxgroups__oxrrid->value;
            }
        } else {
            $rrid = $this->oxgroups__oxrrid->value;
        }

        $database = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();

        // deleting R&R info
        $sDelete = "delete from oxobject2role where oxobject2role.oxobjectid = :oxobjectid";
        $database->execute($sDelete, [
            ':oxobjectid' => $oxid
        ]);

        if ($rrid !== false) {
            $offset = (int) ($rrid / 31);
            $bitMap = 1 << ($rrid % 31);

            $sql = "update oxobjectrights set oxgroupidx = oxgroupidx & ~:bitMap where oxoffset = :oxoffset and oxgroupidx & :bitMap";
            $database->execute($sql, [
                ':bitMap' => $bitMap,
                ':oxoffset' => $offset
            ]);

            // removing cleared
            $sql = 'delete from oxobjectrights where oxgroupidx = 0';
            $database->execute($sql);
        }

        $parentResult = parent::delete($oxid);
        return $parentResult;
    }
}
