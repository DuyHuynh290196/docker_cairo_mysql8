<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Controller\Admin;

/**
 * @inheritdoc
 **/
class CountryMain extends \OxidEsales\EshopProfessional\Application\Controller\Admin\CountryMain
{
    /**
     * @inheritdoc
     */
    public function render()
    {
        $myConfig = $this->getConfig();

        if (!$myConfig->getConfigParam('blAllowSharedEdit')) {
            $this->_aViewData['readonly'] = true;
        }

        return parent::render();
    }

    /**
     * @inheritdoc
     */
    public function save()
    {
        $myConfig = $this->getConfig();

        //allow malladmin only to perform this action
        if (!$myConfig->getConfigParam('blAllowSharedEdit')) {
            return;
        }

        return parent::save();
    }

    /**
     * @inheritdoc
     */
    public function saveinnlang()
    {
        $myConfig = $this->getConfig();

        //allow malladmin only
        if (!$myConfig->getConfigParam('blAllowSharedEdit')) {
            return;
        }

        return parent::save();
    }
}
