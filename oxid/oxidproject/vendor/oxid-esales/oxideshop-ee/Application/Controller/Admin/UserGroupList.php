<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Controller\Admin;

/**
 * @inheritdoc
 */
class UserGroupList extends \OxidEsales\EshopProfessional\Application\Controller\Admin\UserGroupList
{
    /**
     * @inheritdoc
     */
    public function render()
    {
        //allow malladmin only to perform this action
        if (!$this->getConfig()->getConfigParam('blAllowSharedEdit')) {
            $this->_aViewData["readonly"] = true;
        }

        return parent::render();
    }
}
