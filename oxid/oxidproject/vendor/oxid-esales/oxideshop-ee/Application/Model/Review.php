<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Model;

/**
 * @inheritdoc
 */
class Review extends \OxidEsales\EshopProfessional\Application\Model\Review
{
    /**
     * Resets reviewable product cache (non admin mode) and
     * executes parent _resetCache method.
     *
     * @param string $reviewId
     * @deprecated underscore prefix violates PSR12, will be renamed to "resetCache" in next major
     */
    protected function _resetCache($reviewId = null) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        // Customer wrote product review, review affects only product
        // details page( "reset by conditions").
        if (
            isset($this->oxreviews__oxtype) &&
            $this->oxreviews__oxtype !== false &&
            $this->oxreviews__oxtype->value === 'oxarticle' &&
            !$this->isAdmin()
        ) {
            $cache = oxNew(\OxidEsales\Eshop\Core\Cache\DynamicContent\ContentCache::class);
            $cache->resetOn(array($this->oxreviews__oxobjectid->value => 'anid'));
        }
        parent::_resetCache($reviewId);
    }

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
     * @return string|bool
     */
    public function save()
    {
        $isSaved = parent::save();
        $this->executeDependencyEvent();

        return $isSaved;
    }

    /**
     * Delete Object from database.
     *
     * @param string $oxId Object ID (default null)
     *
     * @return bool
     */
    public function delete($oxId = null)
    {
        if (!$oxId) {
            $oxId = $this->getId();
        }
        if (!$oxId) {
            return false;
        }
        $isDeleted = parent::delete($oxId);
        $this->executeDependencyEvent();

        return $isDeleted;
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
        switch ($this->getObjectType()) {
            case 'oxarticle':
                return 'anid';
            case 'oxrecommlist':
                return 'recommid';
        }
        // END deprecated
    }
}
