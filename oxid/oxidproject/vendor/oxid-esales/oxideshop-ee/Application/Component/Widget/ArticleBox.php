<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Component\Widget;

use OxidEsales\Eshop\Core\Registry;

/**
 * @inheritdoc
 */
class ArticleBox extends \OxidEsales\EshopProfessional\Application\Component\Widget\ArticleBox
{
    /**
     * Do not cache if user is logged in
     *
     * @var bool
     */
    protected $_blCacheForUser = false;
}
