<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Component\Widget;

/**
 * @inheritdoc
 */
class Review extends \OxidEsales\EshopProfessional\Application\Component\Widget\Review
{
    /**
     * Do not cache if user is logged in
     *
     * @var bool
     */
    protected $_blCacheForUser = false;
}
