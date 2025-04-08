<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopProfessional\Core;

use OxidEsales\Eshop\Core\Edition\EditionSelector;
use OxidEsales\Eshop\Core\Edition\EditionPathProvider;
use OxidEsales\Eshop\Core\Edition\EditionRootPathProvider;

/**
 * @inheritdoc
 */
class UtilsView extends \OxidEsales\EshopCommunity\Core\UtilsView
{
    /**
     * @inheritdoc
     */
    public function getTemplateDirs()
    {
        $pathSelector = $this->getProfessionalPathSelector();
        $editionTemplatesDirectory = $this->addActiveThemeId($pathSelector->getViewsDirectory());
        $this->setTemplateDir($editionTemplatesDirectory);

        return parent::getTemplateDirs();
    }

    /**
     * @inheritdoc
     */
    protected function getShopSmartyPluginDirectories()
    {
        $editionShopSmartyPluginDirectory = $this->getProfessionalPathSelector()->getSmartyPluginsDirectory();

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
    protected function getProfessionalPathSelector()
    {
        return new EditionPathProvider(new EditionRootPathProvider(new EditionSelector(EditionSelector::PROFESSIONAL)));
    }
}
