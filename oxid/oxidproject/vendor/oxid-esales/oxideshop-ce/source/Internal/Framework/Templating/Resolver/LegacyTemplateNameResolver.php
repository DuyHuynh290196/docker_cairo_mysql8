<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\EshopCommunity\Internal\Framework\Templating\Resolver;

/**
 * @deprecated class will be removed in v7.0
 */
class LegacyTemplateNameResolver implements TemplateNameResolverInterface
{
    /**
     * @var TemplateNameResolverInterface
     */
    private $resolver;

    /**
     * TemplateNameResolver constructor.
     *
     * @param TemplateNameResolverInterface $resolver
     */
    public function __construct(TemplateNameResolverInterface $resolver)
    {
        $this->resolver = $resolver;
    }

    /**
     * @param string $name
     *
     * @return string
     */
    public function resolve(string $name): string
    {
        return $this->resolver->resolve($this->getFileNameWithoutExtension($name));
    }

    /**
     * @param string $fileName
     *
     * @return string
     */
    private function getFileNameWithoutExtension(string $fileName): string
    {
        $pos = strrpos($fileName, '.tpl');
        if (false !== $pos) {
            $fileName = substr($fileName, 0, $pos);
        }
        return $fileName;
    }
}
