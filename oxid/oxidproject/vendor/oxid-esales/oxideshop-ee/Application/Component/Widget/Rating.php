<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Component\Widget;

/**
 * @inheritdoc
 */
class Rating extends \OxidEsales\EshopProfessional\Application\Component\Widget\Rating
{
    /**
     * Do not cache if user is logged in
     *
     * @var bool
     */
    protected $_blCacheForUser = false;
}
