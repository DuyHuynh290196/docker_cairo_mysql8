<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\EshopCommunity\Internal\Framework\Config\DataObject;

class ShopConfigurationSetting
{
    /* @deprecated will be removed completely in 7.0 */
    const MODULE_CLASS_EXTENSIONS           = 'aModuleExtensions';
    /* @deprecated will be removed completely in 7.0 */
    const MODULE_CLASS_EXTENSIONS_CHAIN     = 'aModules';
    /* @deprecated will be removed completely in 7.0 */
    const MODULE_CONTROLLERS                = 'aModuleControllers';
    /* @deprecated will be removed completely in 7.0 */
    const MODULE_VERSIONS                   = 'aModuleVersions';
    /* @deprecated will be removed completely in 7.0 */
    const MODULE_PATHS                      = 'aModulePaths';
    /* @deprecated will be removed completely in 7.0 */
    const MODULE_TEMPLATES                  = 'aModuleTemplates';
    /* @deprecated will be removed completely in 7.0 */
    const MODULE_SMARTY_PLUGIN_DIRECTORIES  = 'moduleSmartyPluginDirectories';
    /* @deprecated will be removed completely in 7.0 */
    const MODULE_EVENTS                     = 'aModuleEvents';
    /* @deprecated will be removed completely in 7.0 */
    const MODULE_CLASSES_WITHOUT_NAMESPACES = 'aModuleFiles';
    /* @deprecated will be removed completely in 7.0 */
    const ACTIVE_MODULES = 'activeModules';

    /**
     * @var int
     */
    private $shopId;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $type;

    /**
     * @var mixed
     */
    private $value;

    /**
     * @return int
     */
    public function getShopId(): int
    {
        return $this->shopId;
    }

    /**
     * @param int $shopId
     * @return ShopConfigurationSetting
     */
    public function setShopId(int $shopId): ShopConfigurationSetting
    {
        $this->shopId = $shopId;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return ShopConfigurationSetting
     */
    public function setName(string $name): ShopConfigurationSetting
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return ShopConfigurationSetting
     */
    public function setType(string $type): ShopConfigurationSetting
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     * @return ShopConfigurationSetting
     */
    public function setValue($value): ShopConfigurationSetting
    {
        $this->value = $value;
        return $this;
    }
}
