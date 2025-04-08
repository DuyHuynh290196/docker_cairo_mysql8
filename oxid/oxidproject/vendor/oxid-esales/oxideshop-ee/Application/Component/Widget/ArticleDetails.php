<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Component\Widget;

/**
 * @inheritdoc
 */
class ArticleDetails extends \OxidEsales\EshopProfessional\Application\Component\Widget\ArticleDetails
{
    /**
     * Do not cache if user is logged in
     *
     * @var bool
     */
    protected $_blCacheForUser = false;

    /**
     * @inheritdoc
     */
    public function loadVariantInformation()
    {
        if ($this->_aVariantList === null) {
            $this->_aVariantList = parent::loadVariantInformation();

            $this->_aVariantList = $this->_checkVariantAccessRights($this->_aVariantList);
        }

        return $this->_aVariantList;
    }

    /**
     * R&R: sets if variant is buyble
     *
     * @param array $variantList variant list
     *
     * @return null
     * @deprecated underscore prefix violates PSR12, will be renamed to "checkVariantAccessRights" in next major
     */
    protected function _checkVariantAccessRights($variantList) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        if ($this->getRights()) {
            if (is_array($variantList)) {
                foreach ($variantList as $variantId => $variant) {
                    // viewable ?
                    if (!$variant->canView()) {
                        // removing variant
                        unset($variantList[$variantId]);
                        continue;
                    }
                }
            }
        }

        return $variantList;
    }
}
