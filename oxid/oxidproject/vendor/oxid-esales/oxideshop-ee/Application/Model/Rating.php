<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Model;

/**
 * @inheritdoc
 */
class Rating extends \OxidEsales\EshopProfessional\Application\Model\Rating
{
    /**
     * Execute cache dependencies.
     *
     * @deprecated since 2019-02-22 (6.3.0 component version). All calls of the method will be removed soon.
     */
    public function executeDependencyEvent()
    {
    }

    /**
     * Save this Object to database, insert or update as needed.
     *
     * @return bool
     */
    public function save()
    {
        $isSaved = parent::save();
        $this->executeDependencyEvent();

        return $isSaved;
    }

    /**
     * Returns object key used for caching.
     *
     * @return string
     * @deprecated underscore prefix violates PSR12, will be renamed to "getObjectKey" in next major
     */
    protected function _getObjectKey() // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        // @deprecated since v5.3 (2016-06-17); Listmania will be moved to an own module.
        $objectKey = '';
        switch ($this->getObjectType()) {
            case 'oxarticle':
                $objectKey = 'anid';
                break;
            case 'oxrecommlist':
                $objectKey = 'recommid';
                break;
        }

        return $objectKey;
        // END deprecated
    }
}
