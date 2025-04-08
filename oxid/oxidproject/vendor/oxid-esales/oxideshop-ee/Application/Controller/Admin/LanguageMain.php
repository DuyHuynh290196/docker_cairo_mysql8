<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Controller\Admin;

/**
 * @inheritdoc
 */
class LanguageMain extends \OxidEsales\EshopProfessional\Application\Controller\Admin\LanguageMain
{
    /**
     * @inheritdoc
     */
    public function render()
    {
        if (!$this->getConfig()->getConfigParam('blAllowSharedEdit')) {
            $this->_aViewData['readonly'] = true;
        }

        return parent::render();
    }

    /**
     * @inheritdoc
     */
    public function save()
    {
        // Only mall admin  is allowed to perform this action.
        if (!$this->getConfig()->getConfigParam('blAllowSharedEdit')) {
            return;
        }

        return parent::save();
    }
}
