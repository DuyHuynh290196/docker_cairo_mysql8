<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\EshopCommunity\Internal\Framework\Templating\Loader;

/**
 * Interface TemplateLoaderInterface
 * @deprecated will be removed in v7.0
 * @package OxidEsales\EshopCommunity\Internal\Framework\Templating\Loader
 */
interface TemplateLoaderInterface
{
    /**
     * Check a template exists.
     *
     * @param string $name The name of the template
     *
     * @return bool
     */
    public function exists($name): bool;

    /**
     * Returns the content of the given template.
     *
     * @param string $name The name of the template
     *
     * @return string
     */
    public function getContext($name): string;

    /**
     * Returns the path to the template.
     * @deprecated method will be removed in v7.0
     * @param string $name A template name
     *
     * @return string
     */
    public function getPath($name): string;
}
