<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Core;

/**
 * @inheritdoc
 */
class Utils extends \OxidEsales\EshopProfessional\Core\Utils
{
    /**
     * @inheritdoc
     */
    public function resetTemplateCache($aTemplates)
    {
        parent::resetTemplateCache($aTemplates);

        //reset output cache
        $oCache = oxNew(\OxidEsales\Eshop\Core\Cache\DynamicContent\ContentCache::class);
        $oCache->reset(false);
    }

    /**
     * @inheritdoc
     */
    public function getEditionCacheFilePrefix()
    {
        return 'ee';
    }
}
