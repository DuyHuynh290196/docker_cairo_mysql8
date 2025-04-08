<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Model;

/**
 * @inheritdoc
 */
class Order extends \OxidEsales\EshopProfessional\Application\Model\Order
{
    /**
     * @inheritdoc
     */
    public function assign($dbRecord)
    {
        if (!$this->canRead()) {
            return false;
        }

        parent::assign($dbRecord);
    }

    /**
     * @inheritdoc
     * @deprecated underscore prefix violates PSR12, will be renamed to "assignUserInformation" in next major
     */
    protected function _setUser($user) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        parent::_setUser($user);
        $this->oxorder__oxbillustidstatus = clone $user->oxuser__oxustidstatus;
    }

    /**
     * @inheritdoc
     */
    public function delete($oxid = null)
    {
        if ($oxid) {
            if (!$this->load($oxid)) {
                // such order does not exist
                return false;
            }
        } elseif (!$oxid) {
            $oxid = $this->getId();
        }

        if (!$this->canDelete($oxid)) {
            return false;
        }

        return parent::delete($oxid);
    }

    /**
     * @inheritdoc
     */
    public function getOrderUser()
    {
        parent::getOrderUser();

        if ($this->_oUser && $this->_isLoaded) {
            $this->_oUser->oxuser__oxustidstatus = clone $this->oxorder__oxbillustidstatus;
        }

        return $this->_oUser;
    }
}
