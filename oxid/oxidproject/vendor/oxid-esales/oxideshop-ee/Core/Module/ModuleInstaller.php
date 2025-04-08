<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Core\Module;

/**
 * @inheritdoc
 *
 * @deprecated since v6.4.0 (2019-02-14); Use service "OxidEsales\EshopCommunity\Internal\Framework\Module\Setup\Bridge\ModuleActivationBridgeInterface".
 */
class ModuleInstaller extends \OxidEsales\EshopProfessional\Core\Module\ModuleInstaller
{
    /**
     * @inheritdoc
     */
    protected function resetCache()
    {
        parent::resetCache();
        $moduleCache = $this->getModuleCache();
        if ($moduleCache) {
            $moduleCache->executeDependencyEvent();
        }
    }
}
