<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Controller;

/**
 * @inheritdoc
 */
class StartController extends \OxidEsales\EshopProfessional\Application\Controller\StartController
{
    /**
     * Do not cache if user is logged in
     *
     * @var bool
     */
    protected $_blCacheForUser = false;
}
