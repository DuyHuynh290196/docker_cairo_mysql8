<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Model;

use oxBase;
use oxDb;

/**
 * Roles manager.
 */
class Role extends \OxidEsales\Eshop\Core\Model\BaseModel
{
    /**
     * Current class name.
     *
     * @var string
     */
    protected $_sClassName = 'oxroles';

    /**
     * Executes parent constructor, oxroles::init().
     */
    public function __construct()
    {
        parent::__construct();
        if (!$this->getConfig()->getSerial()->validateShop()) {
            return;
        }
        $this->init('oxroles');
    }

    /**
     * Deletes object information from DB, returns true on success.
     *
     * @param string $oxId Object ID (default null).
     *
     * @return bool
     */
    public function delete($oxId = null)
    {
        if (!$oxId) {
            $oxId = $this->getId();
        }
        if (!$oxId) {
            return false;
        }

        if ($isDeleted = parent::delete($oxId)) {
            $database = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();

            // assigned fields
            $database->execute("delete from oxfield2role where oxroleid = :oxroleid", [
                ':oxroleid' => $oxId
            ]);

            // assigned users/groups
            $database->execute("delete from oxobject2role where oxroleid = :oxroleid", [
                ':oxroleid' => $oxId
            ]);

            // assigned users/groups
            $database->execute("delete from oxobjectrights where oxobjectid = :oxobjectid", [
                ':oxobjectid' => $oxId
            ]);
        }

        return $isDeleted;
    }
}
