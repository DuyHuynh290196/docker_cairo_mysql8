<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Core;

use OxidEsales\Eshop\Core\Edition\EditionSelector;
use OxidEsales\Eshop\Core\Edition\EditionPathProvider;
use OxidEsales\Eshop\Core\Edition\EditionRootPathProvider;

/**
 * @inheritdoc
 */
class UtilsView extends \OxidEsales\EshopProfessional\Core\UtilsView
{
    /**
     * @inheritdoc
     */
    public function getTemplateDirs()
    {
        $pathSelector = $this->getEnterprisePathSelector();
        $editionTemplatesDirectory = $this->addActiveThemeId($pathSelector->getViewsDirectory());
        $this->setTemplateDir($editionTemplatesDirectory);

        return parent::getTemplateDirs();
    }

    /**
     * @inheritdoc
     */
    protected function getShopSmartyPluginDirectories()
    {
        $editionShopSmartyPluginDirectory = $this->getEnterprisePathSelector()->getSmartyPluginsDirectory();

        return array_merge(
            [$editionShopSmartyPluginDirectory],
            parent::getShopSmartyPluginDirectories()
        );
    }

    /**
     * Create Path provider objects to get path.
     *
     * @return EditionPathProvider
     */
    private function getEnterprisePathSelector()
    {
        return new EditionPathProvider(new EditionRootPathProvider(new EditionSelector(EditionSelector::ENTERPRISE)));
    }
}
