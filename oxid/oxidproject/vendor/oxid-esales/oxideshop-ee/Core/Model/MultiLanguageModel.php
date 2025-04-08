<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Core\Model;

/**
 * @inheritdoc
 */
class MultiLanguageModel extends \OxidEsales\EshopProfessional\Core\Model\MultiLanguageModel
{
    /**
     * @inheritdoc
     * @deprecated underscore prefix violates PSR12, will be renamed to "getObjectViewName" in next major
     */
    protected function _getObjectViewName($tableName, $shopId = null) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        if (!$this->_blEmployMultilanguage) {
            return parent::_getObjectViewName($tableName, $shopId);
        }

        if ($this->_blForceCoreTableUsage) {
            $shopId = -1;
        }

        return getViewName($tableName, $this->getLanguage(), $shopId);
    }

    /**
     * @inheritdoc
     */
    protected function checkFieldCanBeUpdated($fieldName)
    {
        return $this->canUpdateField($fieldName);
    }
}
