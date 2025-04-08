<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Controller\Admin;

use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Application\Model\Actions;

/**
 * @inheritdoc
 */
class ActionsMain extends \OxidEsales\EshopProfessional\Application\Controller\Admin\ActionsMain
{
    /**
     * Checks access to edit Action.
     *
     * @param Actions $action
     *
     * @return bool
     */
    protected function checkAccessToEditAction(Actions $action)
    {
        $userHasPermissions = $this->userHasPermissionsToEditAction($action);

        if (false === $userHasPermissions) {
            Registry::getUtilsView()->addErrorToDisplay('ACCESS_TO_EDIT_ACTION_FOR_MALL_ADMIN_ONLY');
        }

        return $userHasPermissions;
    }

    /**
     * Returns true if user has permissions to edit Action.
     *
     * @param Actions $action
     *
     * @return bool
     */
    private function userHasPermissionsToEditAction(Actions $action)
    {
        $allowEditDefaultAction = $this
            ->getConfig()
            ->getConfigParam('blAllowSharedEdit');

        return ($action->isDefault() && !$allowEditDefaultAction) ? false : true;
    }
}
