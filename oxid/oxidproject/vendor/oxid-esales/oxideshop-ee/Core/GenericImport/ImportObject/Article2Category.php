<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Core\GenericImport\ImportObject;

/**
 * Import object for Articles assignment to categories.
 */
class Article2Category extends \OxidEsales\EshopProfessional\Core\GenericImport\ImportObject\Article2Category
{
    /**
     * Class constructor.
     */
    public function __construct()
    {
        $this->keyFieldList['OXSHOPID'] = 'OXSHOPID';
    }
}
