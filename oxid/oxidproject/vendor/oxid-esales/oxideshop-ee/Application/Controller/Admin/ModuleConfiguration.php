<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Controller\Admin;

/**
 * @inheritdoc
 */
class ModuleConfiguration extends \OxidEsales\EshopProfessional\Application\Controller\Admin\ModuleConfiguration
{
    /**
     * Saves shop configuration variables.
     */
    public function saveConfVars()
    {
        parent::saveConfVars();

        $this->executeDependencyEvent();
    }

    /**
     * Execute dependency event.
     *
     * @deprecated since 2019-02-22 (6.3.0 component version). All calls of the method will be removed soon.
     */
    public function executeDependencyEvent()
    {
    }
}
