<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Core;

use OxidEsales\Eshop\Application\Component\Widget\WidgetController;
use OxidEsales\Eshop\Core\Exception\ObjectException;

/**
 * @inheritdoc
 */
class WidgetControl extends \OxidEsales\EshopProfessional\Core\WidgetControl
{
    /**
     * Allow setting env_key cookies for shopcontrol, but not for widget controller.
     * @deprecated in v6.0.0 on 2017-08-23; use $isLayout instead.
     * @var bool
     */
    protected $_blAllowEnvKeySetting = false;

    /** @var bool Distinguishes layout from widget. */
    protected $isLayout = false;

    /**
     * Disallow invalidating cache for for widget controller.
     *
     * @var bool
     */
    protected $_blAllowCacheInvalidating = false;
}
