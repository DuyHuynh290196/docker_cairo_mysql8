<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Component;

/**
 * @inheritdoc
 */

class UserComponent extends \OxidEsales\EshopProfessional\Application\Component\UserComponent
{
    /**
     * @inheritdoc
     */
    public function __construct()
    {
        parent::__construct();
        $this->_aAllowedClasses = array_merge($this->_aAllowedClasses, array('mallstart'));
    }


    /**
     * @inheritdoc
     * @deprecated underscore prefix violates PSR12, will be renamed to "afterLogin" in next major
     */
    protected function _afterLogin($user) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $parentResult = parent::_afterLogin($user);

        // resetting rights ..
        $this->setRights(null);

        return $parentResult;
    }

    /**
     * @inheritdoc
     */
    protected function resetPermissions()
    {
        parent::resetPermissions();

        // resetting rights
        $this->setRights(null);
    }

    /**
     * @inheritdoc
     */
    protected function configureUserBeforeCreation($user)
    {
        $user = parent::configureUserBeforeCreation($user);
        $user->setUseMaster();

        return $user;
    }
}
