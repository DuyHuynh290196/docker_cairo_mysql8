<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Controller;

/**
 * @inheritdoc
 */
class SearchController extends \OxidEsales\EshopProfessional\Application\Controller\SearchController
{
    /**
     * Do not cache if user is logged in
     *
     * @var bool
     */
    protected $_blCacheForUser = false;
}
