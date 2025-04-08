<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Controller\Admin;

/**
 * @inheritdoc
 */
class LanguageList extends \OxidEsales\EshopProfessional\Application\Controller\Admin\LanguageList
{
    /**
     * @inheritdoc
     */
    public function deleteEntry()
    {
        $config = $this->getConfig();

        if (!$config->getConfigParam('blAllowSharedEdit')) {
            return;
        }

        parent::deleteEntry();

        $languageData['params'] = $config->getConfigParam('aLanguageParams');

        $languageId = $this->getEditObjectId();
        $baseLanguageId = (int) $languageData['params'][$languageId]['baseId'];

        // resetting all multilanguage DB fields with deleted lang id to default value
        $this->_resetMultiLangDbFields($baseLanguageId);
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
