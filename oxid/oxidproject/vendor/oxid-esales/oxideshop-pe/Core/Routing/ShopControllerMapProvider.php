<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopProfessional\Core\Routing;

/**
 * @inheritdoc
 *
 * @internal Do not make a module extension for this class.
 */
class ShopControllerMapProvider extends \OxidEsales\EshopCommunity\Core\Routing\ShopControllerMapProvider
{
    private $controllerMap = [
    ];

    /**
     * @inheritdoc
     */
    public function getControllerMap()
    {
        return array_merge(parent::getControllerMap(), $this->controllerMap);
    }
}
