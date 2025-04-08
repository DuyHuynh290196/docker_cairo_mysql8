<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Controller\Admin;

/**
 * @inheritdoc
 **/
class CountryList extends \OxidEsales\EshopProfessional\Application\Controller\Admin\CountryList
{
    /**
     * @inheritdoc
     */
    public function deleteEntry()
    {
        if (!$this->getConfig()->getConfigParam('blAllowSharedEdit')) {
            return;
        }

        return parent::deleteEntry();
    }

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
}
