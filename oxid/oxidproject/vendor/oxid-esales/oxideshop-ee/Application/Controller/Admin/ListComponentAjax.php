<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Controller\Admin;

use OxidEsales\Eshop\Core\Registry;

/**
 * @inheritdoc
 */
class ListComponentAjax extends \OxidEsales\EshopProfessional\Application\Controller\Admin\ListComponentAjax
{
    /**
     * @inheritdoc
     * @deprecated underscore prefix violates PSR12, will be renamed to "resetContentCache" in next major
     */
    protected function _resetContentCache() // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        parent::_resetContentCache();

        $cache = oxNew(\OxidEsales\Eshop\Core\Cache\DynamicContent\ContentCache::class);
        $cache->reset();
    }

    /**
     * @inheritdoc
     * @deprecated underscore prefix violates PSR12, will be renamed to "resetCaches" in next major
     */
    protected function _resetCaches() // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        parent::_resetCaches();

        $cache = oxNew(\OxidEsales\Eshop\Core\Cache\DynamicContent\ContentCache::class);
        $cache->reset(false);
    }
}
