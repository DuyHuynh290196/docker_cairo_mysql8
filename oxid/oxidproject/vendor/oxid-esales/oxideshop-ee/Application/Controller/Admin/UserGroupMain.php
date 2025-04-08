<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Controller\Admin;

/**
 * @inheritdoc
 */
class UserGroupMain extends \OxidEsales\EshopProfessional\Application\Controller\Admin\UserGroupMain
{
    /**
     * @inheritdoc
     */
    public function save()
    {
        //allow malladmin only to perform this action
        if (!$this->getConfig()->getConfigParam('blAllowSharedEdit')) {
            return;
        }

        return parent::save();
    }
}
