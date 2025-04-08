<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Core;

use oxDb;

/**
 * @inheritdoc
 */
class Base extends \OxidEsales\EshopProfessional\Core\Base
{
    /**
     * @inheritdoc
     */
    public function setAdminMode($isAdmin)
    {
        // resetting rights object
        self::$_oRights = null;

        parent::setAdminMode($isAdmin);
    }

    /**
     * Rights manager getter
     *
     * @return \OxidEsales\EshopEnterprise\Application\Model\Rights|\OxidEsales\EshopEnterprise\Core\AdminRights
     */
    public function getRights()
    {
        $rightsRolesConfiguration = (int) $this->getConfig()->getConfigParam('blUseRightsRoles');
        if ($rightsRolesConfiguration && self::$_oRights === null) {
            self::$_oRights = false;
            if ($this->isAdmin() && ($rightsRolesConfiguration & 1)) {
                // checking if back-end RR control is on
                self::$_oRights = oxNew(\OxidEsales\Eshop\Core\AdminRights::class);
                self::$_oRights->load();
            } elseif (!$this->isAdmin() && $rightsRolesConfiguration & 2) {
                if (\OxidEsales\Eshop\Core\DatabaseProvider::getDb()->getOne("select 1 from oxobjectrights limit 1")) {
                    // checking if front-end RR control is on
                    self::$_oRights = oxNew(\OxidEsales\Eshop\Application\Model\Rights::class);
                    self::$_oRights->load();
                }
            }
        }

        return self::$_oRights;
    }

    /**
     * Rights manager setter
     *
     * @param \OxidEsales\EshopEnterprise\Application\Model\Rights $rights rights manager object
     */
    public function setRights($rights)
    {
        self::$_oRights = $rights;
    }
}
